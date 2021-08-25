<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends BaseController
{
    //display notifications for a user
    public function notifications(){
        try {
            $user=Auth::user();
            $notifications=$user->notifications;
            //verify if list is empty
            if($notifications->isEmpty()){
                return $this->sendError('Your notifications list is empty!');
            }
            return $this->sendResponse($notifications,'notifications retrieved successfully!');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    //get notification by id
    public function getNotifiationById($id){
        try {
            $notification= Notification::find($id);
        if(is_null($notification)){
            return $this->sendError('notification not found!');
        }
        return $this->sendResponse($notification,'notification was retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    //user want to stop receiving notfications
    public function closeNotifications(Request $request){
        try {
            $user = Auth::user();
            //verify if notifications button already closed
            if( $user->accept_notification==0){
                return $this->sendError('notification already closed');
            }else{
                $user->accept_notification=0;
                $user->save();
                return $this->sendResponse($user,'notification closed successfully.');
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    //user want to allow recieving notifications
    public function openNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            //verify if notifications button already opened
            if( $user->accept_notification==1){
                return $this->sendError('notifications already opened');
            }else{
                $user->accept_notification=1;
                $user->save();
                return $this->sendResponse($user,'notifications opened successfully.');
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     //mark notification as read
     public function markNotificationAsRead(Request $request,$id){
        try {
            //get current user
            $user=Auth::user();
            //get notification of user
            $notification=NotificationUser::where('user_id',$user->id)->where('notification_id',$id)->first();
            if(is_null($notification))
                return $this->sendError('notification not found');
            NotificationUser::where('user_id',$user->id)->where('notification_id',$id)->update(array('read'=>1));
            //get object after update
            $updatedNotification=NotificationUser::where('user_id',$user->id)->where('notification_id',$id)->first();
            return $this->sendResponse($updatedNotification,'notification marked as read successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    //delete a notification for user
    public function deleteNotification(Request $request,$id){
        try {
            $user_id=Auth::id();
            $notification=NotificationUser::where('user_id',$user_id)->where('notification_id',$id)->first();
            //if notification exist
            if($notification){
                DB::table('notification_user')->where('user_id',$user_id)->where('notification_id',$id)->delete();
                    return $this->sendResponse($notification,'notification deleted successfully.');
                }else{
                return $this->sendError('this notification not founded');
                }
            }
        catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function clearNotifications(Request $request){
        try {
        $user_id=Auth::id();
        $notifications=NotificationUser::where('user_id',$user_id)->get();
        if($notifications->isEmpty())
            return $this->sendError('this notification not founded');
        DB::table('notification_user')->where('user_id',$user_id)->delete();
            return $this->sendResponse($notifications,'all notifications deleted successfully.');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage());
        }
    }

}
