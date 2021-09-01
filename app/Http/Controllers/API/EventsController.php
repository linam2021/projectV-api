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

class EventsController extends BaseController
{
  public function showAllEvents(){
     try{
        $event=Event::get();
        if(!$event){
            return $this ->sendError(['message' =>'There are no event yet'], 404);
        }else{
            return $this ->sendResponse ($event,['message' => 'Event is retrieved successfully']);
        }
    }catch (\Exception $exception){
        return $this->sendError(['message' => $exception->getMessage()], 404);
    }
  }
}
