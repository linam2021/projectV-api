<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Path;
use App\Models\UserPath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;

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
            if (!$user->first_name) {
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
            if (!$user->first_name) 
                return $this->sendError('Sorry, You Need To Complete Your Profile First');
            
            $pathsCount = $user->userPaths->count();
            if ($pathsCount==0) 
                return $this->sendError('You Do Not Have Any Path Now');

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
                        return $this->sendError('You Do Not Have Any Path Now');
                }            
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }  
    
    public function showUserPathInfo()
    {
        try {
            $user = Auth::user();
           
            $pathsCount = $user->userPaths->count();
            if ($pathsCount==0) 
                return $this->sendError('You Do Not Have Any Path Now');
            //get user path
            $user_path = UserPath::where('user_id',$user->id)->where('user_status',2)->first();
            $path=Path::where('id',$user_path->path_id)->first();
            if(is_null($user_path))
                return $this->sendError('You do not have access to any path');
            $userId=Auth::id();
            $rank = DB::table('user_path')->where('path_start_date',$path->path_start_date)->whereNotNull('path_start_date')->where('user_status',2)->orderByDesc('score')->orderBy('user_id')->get();
            $position = $rank->search(function ($cha) use ($userId) {
                return $cha->user_id == $userId;
            });    

            $progressiveCount =UserPath::where('path_id',$user_path->path_id)->where('user_status',2)->count();

            $excludedHeroesCount =UserPath::where('path_id',$user_path->path_id)->where('user_status',4)->count();

            if ($path->current_stage==1)
                $score =0;
            else {
                $currentPathTotalScore =DB::table('courses')
                                ->join('exams','courses.id', '=','exams.course_id')
                                ->where('courses.path_id',$user_path->path_id)
                                ->whereBetween('courses.id', [1, $path->current_stage-1])
                                ->sum('exams.maximum_mark');
                $score=round($user_path->score*100/$currentPathTotalScore);
            }                   
            return $this->sendResponse([
                                'user_id' =>$user->id,
                                'first_name' => $user->first_name,
                                'father_name' => $user->father_name,
                                'last_name' => $user->last_name,
                                'gender' =>$user->gender,
                                'rank' => $position + 1,
                                'score' =>$score,
                                'repeat_chance_no' => $user_path->repeat_chance_no,
                                'current_stage'=>$path->current_stage,
                                'progressiveCount' => $progressiveCount,
                                'ExcludedCount' => $excludedHeroesCount
            ], 'User Path Info is retrieved successfully');            
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function showUserPathLeaderboard()
    {
        try{
            $user = Auth::user();
            //get user path
            $user_path = UserPath::where('user_id',$user->id)->where('user_status',2)->first();
            $path=Path::where('id',$user_path->path_id)->first();
            if(is_null($user_path))
                return $this->sendError('You do not have access to any path');
            $currentPathTotalScore =DB::table('courses')
                                ->join('exams','courses.id', '=','exams.course_id')
                                ->where('courses.path_id',$user_path->path_id)
                                ->whereBetween('courses.id', [1, $path->current_stage-1])
                                ->sum('exams.maximum_mark');
            
            $UserPathLeaderboard= DB::table('user_path')
                                    ->join('users','user_path.user_id', '=','users.id')
                                    ->select('users.id','users.first_name','users.last_name', 'users.gender', 'user_path.path_id','user_path.score')
                                    ->where('user_status',2)
                                    ->orderByDesc('score')
                                    ->orderBy('users.id')->take(300)->get();
            foreach($UserPathLeaderboard as $userP)
            {    
                if ($path->current_stage==1) 
                    $userP->score =0;
                else
                    $userP->score =round($userP->score*100/$currentPathTotalScore);
            }
            if(!$user_path){
                return $this ->sendError('There is no users in leader board');
            }else{
                return $this ->sendResponse($UserPathLeaderboard,'Leader board is retrieved successfully');
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function acceptUser(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if($validator->fails())
                return $this->sendError($validator->errors());
            $user = Auth::user();
            if($user->is_admin !=1)
                return $this->sendError('You must be admin to accept user');

            $user_path = UserPath::where('user_id',$request->user_id)->orderByDesc('created_at')->first();
            if(is_null($user_path))
                return $this->sendError('This user does not join to any path');
            else if($user_path->user_status==2)
                return $this->sendError('This user is joined to path previously');
            else if($user_path->user_status==1)    
            {
                UserPath::where('user_id',$request->user_id)
                ->orderByDesc('created_at')
                ->first()
                ->update(['user_status' => 2,
                         ]);
                return $this ->sendResponse('Success','The user is joined to path');         
            }    
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function rejectUser(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if($validator->fails())
                return $this->sendError($validator->errors());
            $user = Auth::user();
            if($user->is_admin !=1)
                return $this->sendError('You must be admin to accept user');

            $user_path = UserPath::where('user_id',$request->user_id)->orderByDesc('created_at')->first();
            if(is_null($user_path))
                return $this->sendError('This user does not join to any path');
            else if($user_path->user_status==2)
                return $this->sendError('This user is joined to path previously');
            else if($user_path->user_status==1)    
            {
                UserPath::where('user_id',$request->user_id)
                ->orderByDesc('created_at')
                ->first()
                ->update(['user_status' => 3,
                         ]);
                return $this ->sendResponse('Success','The user is rejected from path');        
            }    
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }
}
