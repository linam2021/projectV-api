<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageUser;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //get notifications send by admin
    public function index()
    {
        //get current admin
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return view('PushNotifications.index')->with(['error' => 'you do not have permission']);
        //get messages ids for admin
        $messages_id=Message::where('admin_id',$user->id)->pluck('id')->get();
        //get notifications belong to admin messages

        $notifications=Notification::whereIn('message_id',$messages_id)->get();
        if($notifications->isEmpty())
            return  redirect()->back()->with(['error' => 'you do not have any notification']);
        return view('PushNotifications.index')->with('notifications',$notifications);
    }

    public function create()
    {
        return view('PushNotifications.create');
    }

    public function sendPushNotification(Request $request)
    {

        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return redirect()->back()->with(['error' => 'you do not have Permission']);
        //validator
        $this->validate($request,[
            'title' =>  'required|string',
            'body' =>  'required'
        ]);
    //store notification in database
    $push_notification=new Notification();
    $push_notification->title=$request->input('title');
    $push_notification->body=$request->input('body');
    $push_notification->message_id=$request->message_id;
    $push_notification->save();

    //get users id whose recieved the message
    $users_message_ids = $request->users->pluck('id')->get();

    $users_notification=User::whereIn('id',$users_message_ids)->where('accept_notification',1)->whereNotNull('device_token')->get();

    //get tokens users
    $firebaseToken =$users_notification->pluck('device_token')->all();
    //dd($firebaseToken);

    //add server api key from firebase
    $SERVER_API_KEY = "AAAAVCYn75s:APA91bHwJJgmeXdm5eJPSPt21xCbyoYRORkmLn-1PZ73oPUVK48a4heJpWC696bIAxzUlp6va46X_rqQdGN2qcrjDwGtO9qDtzh66GMDdcugjAIa05EOdkV82ms9oZzbjMWEgYNi8cuv";
    /**
     * send notification with firebase cloud messaging
     */
    if($request->has('image')){
        $data = [
            //all tokens of device users
            "registration_ids"=>$firebaseToken,

            /*
                to send notifiation to one user use "to" instead of "registration_ids"
                */
            //"to" =>"wkUtnTH-7ztwP92PFef:APA91bF2Ji4yUxfCsgs_Ng0B5lwEsmqhJQd6IB_AZNSwbL6oYC8VViwbVHryt9pk8VyB1Sz3fItjC9X2Qxkz5__OT9Lain-vAh7zuSt8V6UZyMROb1FVQnDgs8FCTGO1Kv7FXvSb2l4k",
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    }else{
        $data = [
            //all tokens of device users
            "registration_ids"=>$firebaseToken,
            /*
                to send notifiation to one user use "to" instead of "registration_ids"
                */
            //"to" =>"wkUtnTH-7ztwP92PFef:APA91bF2Ji4yUxfCsgs_Ng0B5lwEsmqhJQd6IB_AZNSwbL6oYC8VViwbVHryt9pk8VyB1Sz3fItjC9X2Qxkz5__OT9Lain-vAh7zuSt8V6UZyMROb1FVQnDgs8FCTGO1Kv7FXvSb2l4k",
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    if(!$response)
        trigger_error(curl_error($ch));

        //verify if sucess response ==1
    $sucessResponse = json_decode($response);
    if($sucessResponse->success==1){
        //if notification sended sucessfully then keep users id with notifiation id
            $listUsers=[];
            foreach ($users_notification as $item) {
                $listUsers[] = $item['id'];
            }
            $push_notification->users()->syncWithoutDetaching($listUsers);
            dd($response);
            return redirect()->back()->with('success','notification successfully sended') ;
    }else{
        return redirect()->back()->with('error',$response) ;
    }

    }
    //show a notification with users
    public function showNotification($id)
    {
        $notification= notification::find($id);
        if(is_null($notification))
            return  redirect()->back()->with(['error' => 'notification not found']);
        //get all users recieved the notification
        $users=$notification->users();
        return view('PushNotifications.show')->with('notification',$notification)->with('users',$users);

    }


}
