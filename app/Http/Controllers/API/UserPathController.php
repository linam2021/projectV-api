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
        try {
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
                ->whereIn('user_status', [1,2])
                ->count() > 0
            ) {
                return $this->sendError('Error', 'You Are Already Joined To Path', 400);
            }

            $userpath=UserPath::create([
                'user_id' => $user->id,
                'path_id' => $path_id,
                'path_start_date' => $path_start_date,
                'answer_join' => $answer_join,
                'answer_accept_order' => $answer_accept_order,
                'user_status' => 1, // 1 => waiting for confirm from admin
            ]);

            return $this->sendResponse($userpath, 'You Are Join To Path Successfully, Please Wait For Confirm From Admin');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Show Current Path For User
     */
    public function show()
    {
        try {
            $user = Auth::user();
            // If User Does Not Complete His Profile
            if (!$user->profile_id) {
                return $this->sendError('Not Complete', 'Sorry, You Need To Complete Your Profile First', 400);
            }
            
            $pathsCount = $user->userPaths->count();
            if ($pathsCount==0) {
                return $this->sendError('error', 'You Do Not Have Any Path Now', 404);
            }
            // States Of User (Accept, Wait, Reject, Exclude, Complete)           
            $userLastPath= $user->userPaths->last(); 
            //if ($userLastPath->path_start_date >= Carbon::yesterday()) { // yesterday just temporialy (Suppose to be time to start date event)
                switch ($userLastPath->user_status) {
                    case 1: // Get Path If User Still Waiting || 1 => Waiting from confirm admin
                        $path_id = $userLastPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'A Path Does Not Confirm Yet!', 200);
                    case 2: // Get Path If User Accepted || 2 => Accepted
                        $path_id = $userLastPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'Confirm Successfully You Can Learn Now', 200);
                    case 3: // User Rejected || 3 => Rejected
                        return $this->sendError('Rejected', 'Sorry To Say That, You Are Rejected, Try Next Time', 400);
                    case 4: // User Excluded || 4 => Excluded
                        return $this->sendError('Excluded', 'Sorry To Say That, You Are Excluded.', 400);
                    case 5: // User Completed || 5 => Completed
                        $path_id = $userLastPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'Congratulations, You Are Completed The Path Successfully', 200);
                    default:
                        return $this->sendError('Not Found', 'You Do Not Have Paths', 404);
                }            
            // }
            // return $this->sendError('Not Start', 'The Event Did Not Start To Learn Yet!', 400);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }    
}
