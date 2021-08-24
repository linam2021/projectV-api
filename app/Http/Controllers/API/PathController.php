<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Path;
use App\Models\UserPath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PathController extends BaseController
{
    /**
     * Retrived Paths For Users
     */
    public function index()
    {
        $paths = Path::orderBy('path_name', 'ASC')->get();
        if ($paths->count() > 0) {
            return $this->sendResponse($paths, 'Retrived Paths Successfully');
        }

        return $this->sendError('error', 'We do not have paths yet!');
    }

    /**
     * Store A Path Of User
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'path_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validate Error', $validate->errors(), 400);
        }

        // Verify Path Is There
        if (!Path::find($request->path_id)) {
            return $this->sendError('Not Found', 'This Path Is Not Found', 404);
        }

        // Verify User Does Not Have A Path For Waiting Or Accepting
        $user = Auth::user();
        if (
            $user->userPaths()
            ->where('user_status', 0)
            ->orWhere('user_status', 1)
            ->count() > 0
        ) {
            return $this->sendError('Error', 'You Are Already Joined To Path', 400);
        }

        // Path Start Date Depends Event For Start
        $path_start_date = Carbon::now('UTC'); // Temporaily

        UserPath::create([
            'user_id' => $user->id,
            'path_id' => $request->path_id,
            'path_start_date' => $path_start_date,
            'user_status' => 0, // 0 => waiting for confirm from admin
        ]);

        return $this->sendResponse([], 'You Are Join To Path Successfully, Please Wait For Confirm From Admin');
    }

    /**
     * Show Current Path For User
     */
    public function show()
    {
        $user = Auth::user();
        // Get Path If User Accepted 1 => Accept
        $path_accept = $user->userPaths->where('user_status', 1)->first();
        if ($path_accept) {
            $path_id = $path_accept->path_id;
            $path = Path::find($path_id);
            return $this->sendResponse($path, 'Confirm Successfully You Can Learn Now', 200);
        }

        // Get Path If User Still Waiting 0 => Waiting
        $path_wait = $user->userPaths->where('user_status', 0)->first();
        if ($path_wait) {
            $path_id = $path_wait->path_id;
            $path = Path::find($path_id);
            return $this->sendResponse($path, 'A Path Does Not Confirm Yet!', 200);
        }

        // User Rejected 2 => Rejected
        $path_refuse = $user->userPaths->where('user_status', 2)->first();
        if ($path_refuse) {
            $path_id = $path_refuse->path_id;
            $path = Path::find($path_id);
            return $this->sendError($path, 'Sorry To Say That, You Are Rejected, Try Next Time', 400);
        }

        return $this->sendError('Not Found', 'You Do Not Have Paths', 404);
    }
}
