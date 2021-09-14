<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Message;
use App\Models\MessageUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends BaseController
{
    //show messages for a user
    public function messages(){
        try {
            // get the current user authenticated
            $user=Auth::user();
            //get user messages
            $messages = $user->messages;
            //verify if list is empty
            if ($messages->isEmpty())
                return $this->sendError('Your messages list is empty!');
            return $this->sendResponse($messages,'messages retrieved successfully!');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    //show content of message
    public function getMessageById($id){
        try {
            $user=Auth::user();
            $messageUser=MessageUser::where('user_id',$user->id)->where('message_id',$id)->first();
            if(is_null($messageUser))
                return $this->sendError('you are not allowed ');
            $message = Message::where('id',$id)->first();
            if(is_null($message))
                return $this->sendError('message not found');
            return $this->sendResponse($message,'message was retrieved successfully');
            } catch (\Throwable $th) {
                return $this->sendError($th->getMessage());
            }
    }
    //mark message as read
    public function markMessageAsRead($id){
        try {
            //get current user
            $user=Auth::user();
            //get message of user
            $message=MessageUser::where('user_id',$user->id)->where('message_id',$id)->first();
            if (is_null($message))
                return $this->sendError('message not found');
            MessageUser::where('user_id',$user->id)->where('message_id',$id)->update(array('read'=>1));
            //get object after update
            $updatedMessage=MessageUser::where('user_id',$user->id)->where('message_id',$id)->first();
            return $this->sendResponse($updatedMessage,'message marked as read successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     //delete a notification for user
     public function deleteMessage($id){
        try {
            $user_id=Auth::id();
            $message=MessageUser::where('user_id',$user_id)->where('message_id',$id)->first();
            if ($message){
                DB::table('message_user')->where('user_id',$user_id)->where('message_id',$id)->delete();
                    return $this->sendResponse($message,'message deleted successfully.');
                }else
                return $this->sendError('this message not founded');
            }
        catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
