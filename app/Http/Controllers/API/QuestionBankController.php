<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Http;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;


class QuestionBankController extends BaseController
{
    public function getAllQuestionBankPaths(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'api_key' => 'required',
            ]);
            if ($validator ->fails())
                return $this->sendError($validator->errors());
            else{
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/showAllPaths', ['api_key' => $request->api_key])->json();
                return $response;
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function showQuestionBankPathCourses(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'api_key' => 'required',
                'path_id' =>'required',
            ]);
            if ($validator ->fails())
                return $this->sendError($validator->errors());
            else{
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/showPathCourses', ['api_key' => $request->api_key,'path_id'=>$request->path_id])->json();
                return $response;
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function addExamQuestions(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'api_key' => 'required',
                'course_id' =>'required',
                'question_count' =>'required',
                'exam_id'=>'required'
            ]);
            if ($validator ->fails()){
                return $this->sendError($validator->errors());
            }else{
                $user=Auth::user();
                if($user->is_admin !=1)
                    return $this->sendError('You must be admin to add questions');
                $isPreAddedExam= Question::where('exam_id',$request->exam_id)->first();
                if ($isPreAddedExam)
                    return $this->sendError('Exam Questions are added previously');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/getRandomQuestions', ['api_key' => $request->api_key,'course_id'=>$request->course_id,'question_count'=>$request->question_count])->json();
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
                        'exam_id'=>$request->exam_id
                    ]);

                    $addSubQues=Question::create([
                        'question_text'=>$response['data'][$i]['sub_question']['question_text'],
                        'question_image_url'=>$response['data'][$i]['sub_question']['question_image'],
                        'answer_a'=>$response['data'][$i]['sub_question']['answer_a'],
                        'answer_b'=>$response['data'][$i]['sub_question']['answer_b'],
                        'answer_c'=>$response['data'][$i]['sub_question']['answer_c'],
                        'correct_answer'=>$response['data'][$i]['sub_question']['correct_answer'],
                        'primary_question_id'=>$addPQues->id,
                        'exam_id'=>$request->exam_id
                    ]);
                }
                return $this->sendResponse('Success','Question is added successfully');
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }

    public function getUserExamQuestions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'exam_id'=>'required'
            ]);
            if ($validator ->fails()){
                return $this->sendError($validator->errors());
            }else {
                $Exam= Question::where('exam_id',$request->exam_id)->first();
                if (!($Exam))
                    return $this->sendError('This exam is not found');
                $questions=Question::where('primary_question_id',null)->where('exam_id',$request->exam_id)->inRandomOrder()->get();
                foreach($questions as $ques)  
                    $ques= $ques->sub_question;
                return $this->sendResponse($questions,'Questions are retrieved successfully');
            }
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage());
        }
    }
}