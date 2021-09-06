<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Http;


class QuestionBankController extends BaseController
{
    public function getAllQuestionBankPaths(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'api_key' => 'required',
            ]);
            if ( $validator ->fails())
                return $this->sendError('Validate Error',$validator->errors());
            else{
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/showAllPaths', ['api_key' => $request->api_key])->json();
                return $response;
            }
        }catch (\Exception $exception){
            return $this->sendError(['message' => $exception->getMessage()], 404);
        }
    }

    public function showQuestionBankPathCourses(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'api_key' => 'required',
                'path_id' =>'required',
            ]);
            if ( $validator ->fails())
                return $this->sendError('Validate Error',$validator->errors());
            else{
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/showPathCourses', ['api_key' => $request->api_key,'path_id'=>$request->path_id])->json();
                return $response;
            }
        }catch (\Exception $exception){
            return $this->sendError(['message' => $exception->getMessage()], 404);
        }
    }

    public function getExamQuestions(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'api_key' => 'required',
                'course_id' =>'required',
                'question_count' =>'required',
            ]);
            if ( $validator ->fails()){
                return $this->sendError('Validate Error',$validator->errors());
            }else{
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('http://gamequestionbank.herokuapp.com/api/getRandomQuestions', ['api_key' => $request->api_key,'course_id'=>$request->course_id,'question_count'=>$request->question_count])->json();
                return $response;
            }
        }catch (\Exception $exception){
            return $this->sendError(['message' => $exception->getMessage()], 404);
        }
    }
}