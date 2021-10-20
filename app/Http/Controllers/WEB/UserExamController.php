<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\UserExam;
use App\Models\UserPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //show merks of one user
    public function showUserMarks(Request $request,$userId)
    {
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return redirect()->back()->with(['error' => 'you do not have Permission']);

        //get path of user
        $this->validate($request,[
            'path_id' => 'required'
        ]);
        //verify if user exists in a specified path
        $user_path = UserPath::where('user_id',$userId)
                                ->where('path_id',$request->path_id)
                                ->where('user_status',2)->first();
        if(is_null($user_path))
            return redirect()->back()->with(['error' => 'this user not exist in this path ']);
        $userMarks= UserExam::where('user_id',$userId)
                            ->where('path_id',$request->path_id)
                            ->where('path_start_date',$user_path->path_start_date)
                            ->get();

        if($userMarks->isEmpty())
            return redirect()->back()->with(['error' => 'this user did not pass any exam yet ']);
        return view('exams.showUserMarks')->with(['examMarks',$userMarks]);
    }

    //show marks of users in specified exam and path
    public function showUsersMarksInExam(Request $request,$examId)
    {
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return redirect()->back()->with(['error' => 'you do not have Permission']);

        //get path of user
        $this->validate($request,[
            'path_id' => 'required'
        ]);
        //get users exists in current path
        $user_path= UserPath::where('path_id',$request->path_id)->where('user_status',2)->get();
        //get users marks in this exam
        $usersMarks= UserExam::where('exam_id',$examId)
                                ->where('path_id',$request->path_id)
                                ->where('path_start_date',$user_path->path_start_date)
                                ->get();
        if($usersMarks->isEmpty())
            return redirect()->back()->with(['error' => 'there is any user passed this exam ']);
        return view('exams.showUsersMarks')->with(['UsersExamMarks',$usersMarks]);
    }

    //get users succeeded in an exam
    public function usersSucceededInExam(Request $request,$examId)
    {
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return redirect()->back()->with(['error' => 'you do not have Permission']);

        //get path of user
        $this->validate($request,[
            'path_id' => 'required'
        ]);
        //get users in this  path
        $user_path = UserPath::where('path_id',$request->path_id)
                                ->where('user_status',2)->get();
        //get exam success mark
        $exam=Exam::where('id',$examId)->get();
        if($exam->isEmpty())
            return redirect()->back()->with(['error' => 'this exam not found']);
        $users_succeeded = UserExam::where('exam_id',$exam->id)
                                    ->where('path_id',$request->path_id)
                                    ->where('path_start_date',$user_path->path_start_date)
                                    ->where('exam_result','>=',$exam->success_mark)
                                    ->get();

        return view('exams.usersSucceeded')->with(['usersSucceeded',$users_succeeded]);
    }


    public function usersFailedInExam(Request $request,$examId)
    {
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return redirect()->back()->with(['error' => 'you do not have Permission']);

        //get path of user
        $this->validate($request,[
            'path_id' => 'required'
        ]);
        //get users in this  path
        $user_path = UserPath::where('path_id',$request->path_id)
                                ->where('user_status',2)->get();
        //get exam success mark
        $exam=Exam::where('id',$examId)->get();
        if($exam->isEmpty())
            return redirect()->back()->with(['error' => 'this exam not found']);
        $users_succeeded = UserExam::where('exam_id',$exam->id)
                                    ->where('path_id',$request->path_id)
                                    ->where('path_start_date',$user_path->path_start_date)
                                    ->where('exam_result','<',$exam->success_mark)
                                    ->get();

        return view('exams.usersSucceeded')->with(['usersSucceeded',$users_succeeded]);
    }


    public function averageMarks(Request $request, $id)
    {
        //
    }

}
