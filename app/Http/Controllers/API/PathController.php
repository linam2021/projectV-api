<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Path;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PathController extends BaseController
{
    /**
     * Retrived Paths For Users
     */
    public function index()
    {
        $paths = Path::orderBy('path_name', 'ASC')->get();
        if ($paths->count() > 0) {
            return $this->sendResponse($paths, 'Retrived Paths Successfully', 200);
        }

        return $this->sendError('error', 'We do not have paths yet!', 404);
    }

    /**
     * Show Current Path For User
     */
    public function show()
    {
        $user = Auth::user();
        // If User Does Not Complete His Profile
        if (!$user->profile_id) {
            return $this->sendError('Not Complete', 'Sorry, You Need To Complete Your Profile First', 400);
        }

        // States Of User (Accept, Wait, Reject, Exclude, Complete)
        foreach ($user->userPaths as $userPath) {
            if ($userPath->path_start_date >= Carbon::yesterday()) { // yesterday just temporialy (Suppose to be time to start date event)
                switch ($userPath->user_status) {
                    case 1: // Get Path If User Still Waiting || 1 => Waiting from confirm admin
                        $path_id = $userPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'A Path Does Not Confirm Yet!', 200);
                    case 2: // Get Path If User Accepted || 2 => Accepted
                        $path_id = $userPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'Confirm Successfully You Can Learn Now', 200);
                    case 3: // User Rejected || 3 => Rejected
                        return $this->sendError('Rejected', 'Sorry To Say That, You Are Rejected, Try Next Time', 400);
                    case 4: // User Excluded || 4 => Excluded
                        return $this->sendError('Excluded', 'Sorry To Say That, You Are Excluded.', 400);
                    case 5: // User Completed || 5 => Completed
                        $path_id = $userPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'Congratulations, You Are Completed The Path Successfully', 200);
                    default:
                        return $this->sendError('Not Found', 'You Do Not Have Paths', 404);
                }
            }
        }

        return $this->sendError('Not Start', 'The Event Did Not Start To Learn Yet!', 400);
    }
}
