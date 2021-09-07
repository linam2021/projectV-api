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
                return $this->sendError($validate->errors());
            }

            $user = Auth::user();
            $path_id = $request->path_id;
            $answer_join = $request->why_do_you_want_to_join;
            $answer_accept_order = $request->why_should_we_accept_you;

            // Verify Event Starts Or End
            $path_start_date = Carbon::yesterday(); // Yesterday Will Replace Time From Event For Starting To Join
            if ($path_start_date >= Carbon::now('UTC')) {
                return $this->sendError('The Event Did Not Start To Join Yet !');
            }

            // Verify Path Is There
            if (!Path::find($request->path_id)) {
                return $this->sendError('This Path Is Not Found');
            }

            // Verify User If He Complete His Profile
            if (!$user->profile_id) {
                return $this->sendError('Sorry, You Can Not Join To A Path. You Need To Complete Your Profile First');
            }

            // Verify User Does Not Have A Path For Waiting Or Accepting
            if (
                $user->userPaths()
                ->whereIn('user_status', [1,2])
                ->count() > 0
            ) {
                return $this->sendError('You Are Already Joined To Path');
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
                return $this->sendError('Sorry, You Need To Complete Your Profile First');
            }
            
            $pathsCount = $user->userPaths->count();
            if ($pathsCount==0) {
                return $this->sendError('You Do Not Have Any Path Now');
            }
            // States Of User (Accept, Wait, Reject, Exclude, Complete)           
            $userLastPath= $user->userPaths->last(); 
            //if ($userLastPath->path_start_date >= Carbon::yesterday()) { // yesterday just temporialy (Suppose to be time to start date event)
                switch ($userLastPath->user_status) {
                    case 1: // Get Path If User Still Waiting || 1 => Waiting from confirm admin
                        $path_id = $userLastPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'A Path Does Not Confirm Yet!');
                    case 2: // Get Path If User Accepted || 2 => Accepted
                        $path_id = $userLastPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'Confirm Successfully You Can Learn Now');
                    case 3: // User Rejected || 3 => Rejected
                        return $this->sendError('Sorry To Say That, You Are Rejected, Try Next Time');
                    case 4: // User Excluded || 4 => Excluded
                        return $this->sendError('Sorry To Say That, You Are Excluded.');
                    case 5: // User Completed || 5 => Completed
                        $path_id = $userLastPath->path_id;
                        $path = Path::find($path_id);
                        return $this->sendResponse($path, 'Congratulations, You Are Completed The Path Successfully');
                    default:
                        return $this->sendError('You Do Not Have Paths');
                }            
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }    
}
