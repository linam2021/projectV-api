<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\UserExam;
use Illuminate\Http\Request;

class UserExamController extends Controller
{
   //show Users marks forn an exam

    public function ExamUsersMarks($idExam)
    {
        $exam = Exam::find($idExam);
        if(!$exam)
            return  redirect()->back()->with(['error' => 'exam not found']);
        //get users marks
        $users_exam= UserExam::where('exam_id',$exam->id)->orderBy('exam_result','DESC')->get();
        return view('layouts.exam.examUsersMark')->with('users_exam',$users_exam);
    }
    //get mark of one users
    public function UserExamMark($userId)
    {
        $user_exam= UserExam::where('user_id',$userId)->get();
        return view('layouts.exam.UserExamMark')->with('user_exam',$user_exam);
    }


}
