<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller as Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPath;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserEventsController extends BaseController
{
    public function getDateTime() 
    {
        try{
            $dateTime=Carbon::now()->addHour();
            return $this ->sendResponse($dateTime,'DateTime is retrieved successfully');
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function showUserEvents(){
        try{
            $user = Auth::user();
            //get user path
            $user_path = UserPath::where('user_id',$user->id)->where('user_status',2)->first();
            if(is_null($user_path))
                return $this->sendError('You do not have access to any path');
            //get get events
            $path=$user->paths->where('id',$user_path->path_id)->first();

            $event=$path->events->where('path_id',$user_path->path_id)->all();
            if(!$event){
                return $this ->sendError('There are no event yet');
            }else{
                return $this ->sendResponse($event,'Event is retrieved successfully');
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }
}
