<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Path;
use App\Models\UserPath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserPathController extends BaseController
{
    /**
     * Store A Path Of User
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'path_id' => 'required|integer',
            'why_do_you_want_to_join' => 'required|string',
            'why_should_we_accept_you' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validate Error', $validate->errors(), 400);
        }

        $user = Auth::user();
        $path_id = $request->path_id;
        $answer_join = $request->why_do_you_want_to_join;
        $answer_accept_order = $request->why_should_we_accept_you;

        // Verify Event Starts Or End
        $path_start_date = Carbon::yesterday(); // Yesterday Will Replace Time From Event For Starting To Join
        if ($path_start_date >= Carbon::now('UTC')) {
            return $this->sendError('Not Start', 'The Event Did Not Start To Join Yet !', 400);
        }

        // Verify Path Is There
        if (!Path::find($request->path_id)) {
            return $this->sendError('Not Found', 'This Path Is Not Found', 404);
        }

        // Verify User If He Complete His Profile
        if (!$user->profile_id) {
            return $this->sendError('Not Complete', 'Sorry, You Can Not Join To A Path. You Need To Complete Your Profile First', 400);
        }

        // Verify User Does Not Have A Path For Waiting Or Accepting
        if (
            $user->userPaths()
            ->where('user_status', 1)
            ->orWhere('user_status', 2)
            ->count() > 0
        ) {
            return $this->sendError('Error', 'You Are Already Joined To Path', 400);
        }

        UserPath::create([
            'user_id' => $user->id,
            'path_id' => $path_id,
            'path_start_date' => $path_start_date,
            'answer_join' => $answer_join,
            'answer_accept_order' => $answer_accept_order,
            'user_status' => 1, // 1 => waiting for confirm from admin
        ]);

        return $this->sendResponse([], 'You Are Join To Path Successfully, Please Wait For Confirm From Admin');
    }
}
