<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Course;
use App\Models\Path;
use Illuminate\Support\Collection;


class CourseController extends Controller
{
    public function index($id){
        try {                  
                $courses=DB::table('courses')
                       ->select('courses.id','courses.course_name','courses.course_link','courses.course_duration','courses.stage')
                       ->where('courses.path_id',$id)
                       ->orderBy('courses.stage')
                       ->get();                               
                $path=DB::table('paths')
                       ->select('paths.path_name')
                       ->where('paths.id',$id)
                       ->whereNull('deleted_at')
                       ->first();  
                return view('layouts.course.index')->with(['courses'=>$courses,'path'=>$path]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    // Show Create Page
    public function create($id)
    {
        try
        {
            $path=DB::table('paths')
                       ->select('paths.path_name','paths.id','paths.questionbank_path_id')
                       ->where('paths.questionbank_path_id',$id)
                       ->whereNull('deleted_at')
                       ->get()->first();  
            //Get courses from QuestionBank API
            $questionBankPathCourses = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://gamequestionbank.herokuapp.com/api/showPathCourses', ['api_key' => '52w32pG0971iQS33PKHfgPuSurfn0EcO','path_id'=>$id])->json();
            return view('layouts.course.create')->with(['path' => $path,'questionBankPathCourses' => $questionBankPathCourses]);
        } catch (\Throwable $th) { 
            dd($th->getMessage());        
        }
    }

    // Store A Course
    public function store(Request $request, $id,$qbid)
    {
        try 
        {
            $i=0;
            $input = $request->all();
            $path=DB::table('paths')
                ->select('paths.path_name','paths.id','paths.questionbank_path_id')
                ->where('paths.questionbank_path_id',$qbid)
                ->whereNull('deleted_at')
                ->get()->first();
            $questionBankPathCourses = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://gamequestionbank.herokuapp.com/api/showPathCourses', ['api_key' => '52w32pG0971iQS33PKHfgPuSurfn0EcO','path_id'=>$qbid])->json();
        
        $isCourseSelected=0;
        $collection = collect([]);
        for($i=0; $i<count($questionBankPathCourses['data']); $i++)
        if ($input['course_name'.(string)$i]!= null)
        {
            $isCourseSelected++;
            $collection->push($input['stage'.(string)$i]);        
        }
        //dd($collection);
        $uniqueCollection=$collection->unique();
        $count=$collection->count();
        $max=$uniqueCollection->max();
        if($isCourseSelected==0)
        {
            return redirect()->intended('courses/create/'.$qbid)->with(['path' => $path,'questionBankPathCourses' => $questionBankPathCourses,'error'=>'يجب اختيار المراحل لإضافتها إلى المسار']);
        }
        else if($count != $max) 
        {
            return redirect()->intended('courses/create/'.$qbid)->with(['path' => $path,'questionBankPathCourses' => $questionBankPathCourses,'error'=>'يجب وضع أرقام تسلسلية صحيحة للمراحل في المسار']);
        }
        else {
            for ($i=0; $i<count($questionBankPathCourses['data']); $i++)
            if ($input['course_name'.(string)$i]!= null)
            {
                Course::create([
                        'path_id'=>$id,
                        'course_name'=>$input['course_name'.(string)$i],
                        'course_link'=>$input['course_link'.(string)$i],
                        'course_duration'=>$input['course_duration'.(string)$i],
                        'stage'=>$input['stage'.(string)$i],
                        'questionbank_course_id'=>$input['qBid'.(string)$i]
                    ]);
            }  
            $paths = Path::orderBy('id')->get();
            foreach ($paths as $p)
            {
                $courseCount=Course::where('path_id',$p->id)->count();
                if ($courseCount != 0)
                    $p->hasCourses=true;   
            }
            $trashedPaths = Path::onlyTrashed()->get();
            foreach ($trashedPaths as $p)
            {
                $courseCount=Course::where('path_id',$p->id)->count();
                if ($courseCount != 0)
                    $p->hasCourses=true;   
            }
            return  redirect()->intended('allpaths')->with(['paths'=>$paths, 'trashedPaths'=>$trashedPaths,'success'=>'تم إضافة المراحل إلى المسار بنجاح']);
        }
        }catch (\Throwable $th) { 
            dd($th->getMessage()) ;  
        } 
    }
}
