<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageUser;
use App\Models\Path;
use App\Models\User;
use App\Models\UserPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //show all messages send by admin
    public function index()
    {
        //get cureent user
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return view('Messages.index')->with(['error' => 'you do not have permission']);
        //get messages for admin
        $messages=Message::where('admin_id',$user->id)->get();
        return view('Messages.index')->with('messages',$messages);

    }

    //create a messages
    public function create()
    {
        //get paths
        $paths =Path::all();
        return view('Messages.create')->with('paths',$paths);
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request,[
            'title' =>  'required|string',
            'body' =>  'required',
            'path_name'=> 'required'
        ]);

        $message = Message::create([
            'user_id' =>  Auth::id(),
            'title' =>  $request->title,
            'body' =>   $request->body,
        ]);
        //get path
        $path= Path::where('path_name',$request->path_name)->first();
        //get users for current path
        $users = UserPath::where('path_id',$path->id)->where('user_status',2)->get();
        //save in message_user table
        foreach($users as $user){
            $mesage_user=MessageUser::create([
                'message_id' => $message->id,
                'user_id'    => $user->id,
            ]);
        }
        if($request->input('sendNotification',true)){
            return view('PushNotifications.create')->with('message',$message)->with('users',$users);
        }
        return redirect()->back()->with('success','the message was sent successfully') ;
    }

    public function showMessage($id)
    {
        $message= Message::find($id);
        if(is_null($message))
            return  redirect()->back()->with(['error' => 'message not found']);
        //get all users recieved the message
        $users=$message->users();
        return view('Messages.showUsersMessage')->with('users',$users);

    }


}
