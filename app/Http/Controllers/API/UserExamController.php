<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Course;
use App\Models\Exam;
use App\Models\UserExam;
use App\Models\UserPath;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserExamController extends BaseController
{
    public function examInfo(){
        try {
            $user = Auth::user();
            //get user path
            $user_path = UserPath::where('user_id',$user->id)->where('user_status',2)->first();
            if(is_null($user_path))
                return $this->sendError('You do not have access to any path');
            //get get path
            $path=$user->paths->where('id',$user_path->path_id)->first();
            //get exam
            $course =$path->courses->where('stage',$path->current_stage)->first();
            $exam= $course->exams->last();
            $exam_start_date=Carbon::parse($exam->exam_start_date)->toDateString();
            $date_now= Carbon::now("Europe/Berlin")->toDateString();
            //get exam link
            if($date_now == $exam_start_date){
                $exam_link= true;
            }else{
                $exam_link= false;
            }
            return $this->sendResponse([
                                    'exam_id' => $exam->id,
                                    'exam_duration' =>$exam->exam_duration,
                                    'exam_link'  => $exam_link
                                     ],'Exam Info is retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function addUserExamResult(Request $request){
        try {
            $validator = Validator::make($request->all(),[
            'exam_result' => 'required',
            'is_well_prepared' =>'required|in:yes,no ',
            'is_easy_exam' => 'required|in:easy,hard'
            ]);
            if ( $validator ->fails())
                return $this->sendError($validator->errors());
            //get current user
            $user = Auth::user();
            //check if user has permission
            $user_path = UserPath::where('user_id',$user->id)
                                    ->where('user_status',2)
                                    ->where('repeat_chance_no','!=',0)->first();
            if(is_null($user_path))
                return $this->sendError('You are not allowed');
            //get user exam result
            $user_result_mark= $request->exam_result; 

            $path=$user->paths->where('id',$user_path->path_id)->first();
            $course= Course::where('path_id',$user_path->path_id)->where('stage',$path->current_stage)->first();
            //get last exam of current stage course
            $exam= Exam::where('course_id',$course->id)->get()->last();
            $isPreAddedUserExam= UserExam::where('user_id',$user->id)
                                         ->where('path_id',$user_path->path_id)
                                         ->where('path_start_date',$user_path->path_start_date)
                                         ->where('exam_id',$exam->id)->first();                         
            if ($isPreAddedUserExam)
                return $this->sendError('Exam mark is added previously');            
            //store user exam informations
            $user_exam = new UserExam();
            $user_exam->user_id = $user->id;
            $user_exam->path_id = $user_path->path_id;
            $user_exam->path_start_date = $user_path->path_start_date;
            $user_exam->exam_id=$exam->id;
            $user_exam->user_exam_date = Carbon::now("Europe/Berlin")->toDateString();
            $user_exam->is_well_prepared= $request->is_well_prepared;
            $user_exam->is_easy_exam = $request->is_easy_exam;
            $user_exam->exam_result= $user_result_mark;
            $user_exam->save();
            //update user score in user_score table
            UserPath::where('user_id',$user->id)
                                    ->where('user_status',2)
                                    ->where('repeat_chance_no','!=',0)
                                    ->update(['score' => $user_path->score + $user_result_mark,
                                             ]);
            //compare user_mark with success_mark
            $sucess_mark= $exam->sucess_mark;
            $hasRepeatChance = ($user_path->repeat_chance_no>0);
            if(($user_result_mark < $sucess_mark) && $hasRepeatChance ){
                $user_path->update([
                    'repeat_chance_no' => $user_path->repeat_chance_no -1,
                ]);
                return $this->sendError('you failed, try again after 24h');
            } else if(($user_result_mark < $sucess_mark) && $hasRepeatChance )
                return $this->sendError('You rre excluded, Game Over');
            else
                return $this->sendResponse('Success','You have succeeded');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function examDate(){
        try {
            $user = Auth::user();
            //get user path
            $user_path = UserPath::where('user_id',$user->id)->where('user_status',2)->first();
            if(is_null($user_path))
                return $this->sendError('You do not have access to any path');
            $path=$user->paths->where('id',$user_path->path_id)->first();
            $course= Course::where('path_id',$path->id)->where('stage',$path->current_stage)->first();
            if(is_null($course))
                return $this->sendError('Course is not found');
            //get last exam of current stage course
            $exam_date=Carbon::parse($course->exams->last()->exam_start_date)->toDateString();
            return $this->sendResponse($exam_date,'exam date is retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
