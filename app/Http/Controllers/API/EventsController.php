<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller as Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventsController extends BaseController
{
    public function getDateTime() 
    {
        try{
            $dateTime=Carbon::now("Europe/Berlin");
            return $this ->sendResponse($dateTime,'DateTime is retrieved successfully');
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function showAllEvents(){
        try{
            $event=Event::get();
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
