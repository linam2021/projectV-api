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
            $messages = Message::orderByDESC('id')->get();
            return view('Messages.index')->with('messages', $messages);
        } catch (\Throwable $th) {
            return view('Messages.index')->with(['error' => $th->getMessage()]);
        }
    }

    //create a messages
    public function create()
    {
        //get only poeded paths
        $paths = Path::where('current_stage', 1)->get();
        //dd($paths);
        return view('Messages.create')->with('paths', $paths);
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'title' =>  'required|string',
                'body' =>  'required',
                'path_name' => 'required',
                'category' => 'required'
            ]);
            if ($validate->fails())
                return redirect()->back()->withInput()->with('error', $validate->errors());
            //get admin id
            $admin_id = User::find(Auth::id());
            $message = new Message();
            $message->title = $request->title;
            $message->body = $request->body;
            $message->admin_id = $admin_id;

            //get path
            $path = Path::where('id', $request->path_name)->first();
            $usersPath = UserPath::where('path_id', $path->id);
            switch ($request->category) {
                case 'accepted':
                    $usersPath = $usersPath->where('user_status', 2)->get();
                    break;
                case 'rejeted':
                    $usersPath = $usersPath->where('user_status', 3)->get();
                    break;
                case 'succeded':
                    $usersPath = $usersPath->where('user_status', 5)->get();
                    break;
                case 'excluded':
                    $usersPath = $usersPath->where('user_status', 4)->get();
                    break;
                default:
                    return $this->sendError('يجب ان تختار فئة محددة');
            }
            //dd();
            if ($usersPath->count() <= 0) {
                return redirect()->back()->with('error', 'لا يوجد اي طالب في هذه الفئة');
            } else {
                $admin_id->adminMessages()->save($message);
                //save in message_user table
                $users = [];
                foreach ($usersPath as $item) {
                    $users[] = MessageUser::create([
                        'user_id' => $item->user_id,
                        'message_id' => $message->id,
                    ]);
                }
                return redirect()->intended('Messages')->with('success', 'تم إرسال الرسالة بنجاح');
            }
        } catch (\Throwable $th) {
            return redirect()->intended('Messages')->with(['error' => $th->getMessage()]);
        }
    }

    public function showMessage($id)
    {
        try {
            $message = Message::find($id);
            //dd($message->users()->first()->pivot->read);
            if (is_null($message))
                return  redirect()->back()->with(['error' => 'message not found']);
            //get all users recieved the message
            $users = $message->users()->get();
            //dd($users);
            return view('Messages.showMessage')->with('users', $users);
        } catch (\Throwable $th) {
            return view('Messages.index')->with(['error' => $th->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $message = Message::find($id);
            if (is_null($message))
                return  redirect()->back()->with(['error' => 'message not found']);
            $message->delete();
            return redirect()->back()->with('success', 'تم حذف الرسالة بنجاح');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }
}
