<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ExamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function showExams(){
        try {
                $now = Carbon::now();
                //get recent exams
                $exams=DB::table('exams')
                       ->join('courses','exams.course_id', '=','courses.id')
                       ->select('exams.id','exams.exam_type','courses.course_name',
                        DB::raw('DATE(exams.exam_start_date) AS exam_start_date'),
                        'exams.exam_duration','exams.questions_count','exams.sucess_mark','exams.maximum_mark')
                        // ->where('exam_start_date','>', $now)
                ->get();
                return view('layouts.exam.showExams')->with(['exams'=>$exams]);
            } catch (\Throwable $th) {
                //dd($th->getMessage());
            }
    }
    //create an exam
    public function create(){
        $courses = Course::all();
        return view('layouts.exam.addExam')->with('courses',$courses);
    }

    //add exam
    public function addExam(Request $request){
        $user=User::where('id',Auth::id())->where('is_admin',1)->get();
        if($user->isEmpty())
            return redirect()->back()->with(['error' => 'you do not have Permission']);
        //validator
        $this->validate($request,[
            'exam_type'          => 'required|in:theoretical,practical,practicalTheoretical',
            'course_id'          => 'required',
            'exam_start_date'    => 'required',
            'exam_duration'      => 'required',
            'questions_count'    => 'required',
            'maximum_mark'       => 'required',
            'sucess_mark'        => 'required',

        ]);
        //get course
        $course = Course::find($request->course_id);
        if(!$course)
            return  redirect()->back()->with(['error' => 'course not found']);
        else{
            //verify questions_count in QuestionBank api
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://gamequestionbank.herokuapp.com/api/getRandomQuestions', ['api_key' => $request->api_key,
                                                                                      'course_id'=>$course->questionbank_course_id,
                                                                                      'question_count'=>$request->questions_count])->json();
            if($response['data']->isEmpty())
                return  redirect()->back()->with(['error' => 'course not found']);
            if($request->exam_type == 'theoretical'){
                if( ($request->questions_count *2) <= ($request->sucess_mark))
                return  redirect()->back()->with(['error' => 'success mark must be less than total number of questions  ']);
            }
            //specify maximum mark
            $maximum_mark = ($request->exam_type == 'theoretical')? ($request->questions_count *2): ($request->maximum_mark);
            $exam=Exam::create([
                'exam_type'          =>$request->exam_type ,
                'course_id'          => $request->course_id ,
                'exam_start_date'    => $request->exam_start_date ,
                'exam_duration'      =>$request->exam_duration ,
                'questions_count'    => $request->questions_count,
                'sucess_mark'        =>$request->sucess_mark ,
                'maximum_mark'       => $maximum_mark ,
            ]);
            return redirect()->back()->with('success','the exam was stored successfully') ;
        }

    }

    //edit an exam
    public function edit(){
        return view('layouts.exam.editExam');
    }
    //update an exam
    public function update(Request $request,$idExam){
        $this->validate($request,[
            'exam_start_date' => 'required'
        ]);
        $exam= Exam::find($idExam);
        if($exam->exam_type !='practicalTheoretical')
            return redirect()->back()->with(['error' => 'you an only update practicalTheoretical exam']);
        $exam_start_date=Carbon::parse($exam->exam_start_date)->toDateString();
        if($exam_start_date == $request->exam_start_date){
            return redirect()->back()->with(['error' => 'today is date of this exam you can not update it now']);
        }
        $exam->exam_start_date = $request->exam_start_date;
        $exam->save();
        return redirect()->back()->with(['success' => 'exam was updated successfully']);
    }


}
