<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Path;
use App\Models\Exam;
use App\Models\Question;
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
    
    public function addExam(Request $request, $id){
        try { 
            $input = $request->all();
            $validate = Validator::make($request->all(), [
                'exam_type' => 'required',
                'exam_start_date' =>'required',
                'sucess_mark' =>'required',
                'maximum_mark' =>'required'
            ]);
            if ($validate->fails()) 
                return redirect()->back()->withInput()->with('error', $validate->errors());
            
            $courseP = Path::where('id',$id)->orderByDesc('id')->first();
            if ($courseP->current_stage == -3)
                $course= Course::where('path_id',$id)->where('stage',1)->first(); 
            // else
            // $course= Course::where('path_id',$id)->where('stage',1)->first();
            
            if ($courseP->current_stage != -3)
                return redirect()->back()->withInput()->with('error', "تم إعداد الامتحان لهذه المرحلة سابقاً");
                
            $coursefinishDate=Carbon::createFromFormat('Y-m-d H:i', $course->course_start_date.' 00:00')->addDays($course->course_duration);
            $inputexamDate = Carbon::createFromFormat('m/d/Y', $input['exam_start_date']);
            $inputexamDate=Carbon::createFromFormat('Y-m-d H:i', $inputexamDate->format('Y-m-d'). '00:00');
            
            if ( Carbon::parse($input['exam_start_date'])->format('Y-m-d') <=  Carbon::parse(Carbon::now())->format('Y-m-d'))
                return  redirect()->back()->withInput()->with(['error' => 'يجب أن يكون تاريخ الامتحان بعد التاريخ الحالي']);

            if ($inputexamDate <= $coursefinishDate)
                return  redirect()->back()->withInput()->with(['error' => 'يجب أن يكون تاريخ الامتحان بعد تاريخ '. $coursefinishDate->format('d-m-Y')]); 
            
            if($input['sucess_mark']>=$input['maximum_mark'])
                return redirect()->back()->withInput()->with('error', "يجب أن تكون درجة النجاح أصغر من الدرجة العظمى");

            if($input['exam_type']=='theoretical')   
            {
                $questionBankCount = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/getQuestionsCount', ['api_key' => '52w32pG0971iQS33PKHfgPuSurfn0EcO','course_id' => $course->questionbank_course_id])->json();

                if($questionBankCount['data']['questionCount'] < $input['questions_count'])
                    return redirect()->back()->withInput()->with('error', "يجب أن يكون عدد الأسئلة الأساسية أصغر أو يساوي ".$questionBankCount['data']['questionCount']);
                
                if ($input['maximum_mark'] != $input['questions_count']*2)
                    return redirect()->back()->withInput()->with('error', "يجب أن تكون الدرجة العظمى ".($input['questions_count']*2));
               
                if ($input['exam_duration'] <15)
                    return redirect()->back()->withInput()->with('error', "يجب أن تكون مدة الامتحان 15 دقيقة على الأقل");
                
                $exam =Exam::create([
                                'exam_type' => 'theoretical',
                                'course_id' => $course->id,
                                'exam_start_date' => $inputexamDate,
                                'exam_duration'=> $input['exam_duration'],
                                'questions_count' => $input['questions_count'],
                                'sucess_mark' => $input['sucess_mark'],
                                'maximum_mark' => $input['maximum_mark'] 
                                ]);
                
               
                Path::where('id',$id)->update(['current_stage' =>-2]); 

                // add Questions               
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/getRandomQuestions', ['api_key' => '52w32pG0971iQS33PKHfgPuSurfn0EcO','course_id'=>$course->id,'question_count'=>$input['questions_count']])->json();
                for($i=0; $i<count($response['data']);$i++)
                {
                    $addPQues=Question::create([
                        'question_text'=>$response['data'][$i]['question_text'],
                        'question_image_url'=>$response['data'][$i]['question_image'],
                        'answer_a'=>$response['data'][$i]['answer_a'],
                        'answer_b'=>$response['data'][$i]['answer_b'],
                        'answer_c'=>$response['data'][$i]['answer_c'],
                        'correct_answer'=>$response['data'][$i]['correct_answer'], 
                        'primary_question_id'=>$response['data'][$i]['primary_question_id'],                       
                        'exam_id'=>$exam->id
                    ]);

                    $addSubQues=Question::create([
                        'question_text'=>$response['data'][$i]['sub_question']['question_text'],
                        'question_image_url'=>$response['data'][$i]['sub_question']['question_image'],
                        'answer_a'=>$response['data'][$i]['sub_question']['answer_a'],
                        'answer_b'=>$response['data'][$i]['sub_question']['answer_b'],
                        'answer_c'=>$response['data'][$i]['sub_question']['answer_c'],
                        'correct_answer'=>$response['data'][$i]['sub_question']['correct_answer'],
                        'primary_question_id'=>$addPQues->id,
                        'exam_id'=>$exam->id
                    ]);
                }
                return redirect()->intended('/allpaths')->with('success', 'تم إعداد امتحان المرحلة بنجاح');
            }
            else
            {
                $exam =Exam::create([
                    'exam_type' => $input['exam_type'],
                    'course_id' => $course->id,
                    'exam_start_date' => $inputexamDate,
                    'exam_duration'=> $input['exam_duration'],
                    'questions_count' => $input['questions_count'],
                    'sucess_mark' => $input['sucess_mark'],
                    'maximum_mark' => $input['maximum_mark'] 
                    ]);
                Path::where('id',$id)->update(['current_stage' =>-2]);     
                return redirect()->intended('/allpaths')->with('success', 'تم إعداد امتحان المرحلة بنجاح');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
     
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
