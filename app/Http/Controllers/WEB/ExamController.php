<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
}
