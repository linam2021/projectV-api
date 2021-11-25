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
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
 
    //show all messages send by admin
    public function index()
    {
        try {
            //get messages for admin
            $messages=Message::orderByDESC('id')->get();
            $admins= User::where('is_admin',1)->get();
            return view('Messages.index')->with('messages',$messages)->with('admins',$admins);
        } catch (\Throwable $th) {
            return view('Messages.index')->with(['error' =>$th->getMessage()]);
        }
    }

    //create a messages
    public function create()
    {
        //get paths
        $paths =Path::all();
        //dd($paths);
        return view('Messages.create')->with('paths',$paths);
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'title' =>  'required|string',
                'body' =>  'required',
                'path_name'=> 'required'
            ]);
            if ($validate->fails()) 
                return redirect()->back()->withInput()->with('error', $validate->errors());

            $message = new Message();
            $message->title = $request->title;
            $message->body = $request->body;
            $message->admin_id = Auth::id();
            $message->save();
            //get path
            $path= Path::where('id',$request->path_name)->first();
            //get users for current path
            $usersPath = UserPath::where('path_id',$path->id)->where('user_status',2)->get();
            //save in message_user table
            $users=[];
            foreach($usersPath as $item){
                $users[]=MessageUser::create([
                    'user_id' => $item->user_id,
                    'message_id' => $message->id,
                ]);
            }
            // if($request->input('sendNotification',true)){                
            //     return view('PushNotifications.create')->with('message',$message)->with('users',$users);
            // } 
            return redirect()->intended('Messages')->with('success', 'تم إرسال الرسالة بنجاح');           
        } catch (\Throwable $th) {
            return redirect()->intended('Messages')->with(['error' =>$th->getMessage()]);
        }

    }

    public function showMessage($id)
    {
        try {
            $message= Message::find($id);
            if(is_null($message))
                return  redirect()->back()->with(['error' => 'message not found']);
            //get all users recieved the message
            $users=MessageUser::where('message_id',$message->id)->get();

            return view('Messages.showMessage')->with('users',$users);
        } catch (\Throwable $th) {
            return view('Messages.index')->with(['error' =>$th->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try
        {
            $message=Message::find($id);
            if (is_null($message))
            return  redirect()->back()->with(['error' => 'message not found']);
            $message->delete();
            return redirect()->back()->with('success','تم حذف الرسالة بنجاح');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' =>$th->getMessage()]);
        }
    }
}
