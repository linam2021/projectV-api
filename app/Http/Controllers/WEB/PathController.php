<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Path;
use App\Models\Course;
use App\Models\UserPath;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PathController extends Controller
{
    // Show all withTrashed Paths
    public function allwithTrashed()
    {
        try 
        {
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
            return view('layouts.path.index')->with(['paths'=>$paths, 'trashedPaths'=>$trashedPaths]);
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }
    }

    public function loadPathImage($name)
    {
        $filename = $name;
        
        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::disk('google')->listContents($dir, $recursive));    
        // Get file details...
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!    
       
        // Stream the file to the browser...
        $readStream = Storage::disk('google')->getDriver()->readStream($file['path']);
    
        return response()->stream(function () use ($readStream) {
            fpassthru($readStream);
        }, 200, [
            'Content-Type' => $file['mimetype'],
            //'Content-disposition' => 'attachment; filename="'.$filename.'"', // force download?
        ]);
    }

    // Show Create Page
    public function create()
    {
        try
        {
            //Get paths from QuestionBank API
            $questionBankPaths = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://gamequestionbank.herokuapp.com/api/showAllPaths', ['api_key' => '52w32pG0971iQS33PKHfgPuSurfn0EcO'])->json();
            return view('layouts.path.create')->with(['questionBankPaths'=>$questionBankPaths]);
        } catch (\Throwable $th) {            
            return redirect()->intended('paths/create')->with(['questionBankPaths'=>$questionBankPaths])->with('error',$th->getMessage()) ; 
        }
    }

    // Store A Path
    public function store(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all(), [
                'idBankPath' => 'required',
                'path_image' =>'required'
            ]);
            if ($validate->fails()) {
                return redirect()->intended('paths/create')->with('error', $validate->errors());
            }
            //Get paths from QuestionBank API
            $questionBankPaths = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://gamequestionbank.herokuapp.com/api/showAllPaths', ['api_key' => '52w32pG0971iQS33PKHfgPuSurfn0EcO'])->json();
            for($i=0; $i<count($questionBankPaths['data']);$i++)
            if ($questionBankPaths['data'][$i]['id']==$request->idBankPath)
            {
                $path_name=$questionBankPaths['data'][$i]['path_name'];
                break;
            }  
            $frequentPath =Path::where('path_name',$path_name)->first();
            if(is_null($frequentPath))
            {
                $newImageName=time() . '-' . $request->path_image->getClientOriginalName();
                $request->path_image->move(public_path("/"),$newImageName);
                $filename = $newImageName;   
                $filePath = public_path($filename);
                $fileData = File::get($filePath);
                //Storage::disk('google')->put($filename, $fileData);
                Path::create([
                    'path_name' => $path_name,
                    'questionbank_path_id' => $request->idBankPath,
                    'path_image_name' =>$newImageName,
                ]);
                return redirect()->intended('paths/create')->with('success', 'تم إضافة المسار بنجاح');
            }                   
            else
                return redirect()->intended('paths/create')->with(['questionBankPaths'=>$questionBankPaths])->with('error','تم إضافة هذا المسار سابقاً') ; 
        
        } catch (\Throwable $th) { 
            dd($th->getMessage());  
           // return redirect()->intended('paths/create')->with(['questionBankPaths'=>$questionBankPaths])->with('error',$th->getMessage()) ; 
        }
    }

    // Show opened Paths 
    public function openedPaths()
    {
        try 
        {
            $paths = Path::orderBy('id')->where('current_stage','>',0)->get();
            foreach ($paths as $p)
            {
                $course=Course::where('path_id',$p->id)->where('stage',$p->current_stage)->first();
                $p->course_name=$course->course_name;
                $p->course_start_date= $course->course_start_date;
                $p->course_end_date= Carbon::createFromFormat('Y-m-d H:i', $course->course_start_date.' 00:00')->addDays($course->course_duration)->format('Y-m-d');
                $exam=Exam::where('course_id',$course->id)->orderByDesc('id')->first();
                $p->exam_type=$exam->exam_type;
                $p->exam_start_date=Carbon::createFromFormat('Y-m-d H:i:s', $exam->exam_start_date)->format('Y-m-d');
                if (( Carbon::parse($p->exam_start_date)->format('Y-m-d') <=  Carbon::parse(Carbon::now()->addDays(1))->format('Y-m-d')) && ($course->exam_repeated ==0))
                
                $p->has_repeat_exam = 1;//Carbon::parse($p->exam_start_date)->format('Y-m-d').'<='. Carbon::parse(Carbon::now()->addDays(1))->format('Y-m-d');
            }
            return view('layouts.path.trackpaths')->with(['paths'=>$paths]);
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }
    }

    public function startRegisterInPath($id) 
    {
        try
        {
            $path=DB::table('paths')
                    ->select('paths.id','paths.path_name')
                    ->where('paths.id',$id)
                    ->whereNull('deleted_at')
                    ->first();
            return view('layouts.path.startRegister')->with(['path'=>$path]);   
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }   
    }

    public function setStartRegisterPath(Request $request, $id)
    {
        try
        {
            $this->validate($request, [
                'startDate' => 'required'
            ]);
            if ( Carbon::parse($request->startDate)->format('Y-m-d') <=  Carbon::parse(Carbon::now())->format('Y-m-d'))
                return  redirect()->back()->with(['error' => 'يجب أن يكون تاريخ البدء بالمسار بعد التاريخ الحالي']);
            Path::where('id',$id)
                    ->update(['path_start_date' => Carbon::parse($request->startDate)->format('Y-m-d') 
                             ]); 
            Course::where('path_id',$id)
                   ->where('stage',1)
                   ->update(['course_start_date' => Carbon::parse($request->startDate)->format('Y-m-d') 
                            ]);                  
            return redirect()->intended('/allpaths')->with('success', 'تم تحديد موعد البدء بالمسار بنجاح');  
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }   
    }

    public function finishRegister($id)
    {
        try
        {
            Path::where('id',$id)
                    ->update(['current_stage' =>-3 
                             ]); 
            return redirect()->intended('/allpaths')->with('success', 'تم إنهاء التسجيل على المسار بنجاح');  
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }
    }

    public function examPreparation($id)
    {
        try
        {
            $path=Path::where('id',$id)->first(); 
            $course= course::where('path_id',$id)
                            ->where('stage',$path->current_stage)
                            ->first();
            return view('layouts.exam.addExam')->with(['course_id'=>$id]);  
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }
    }

    public function startPath($id)
    {
        try
        {
            $path=Path::where('id',$id)->first();
            if (Carbon::parse($path->path_start_date)->format('Y-m-d') !=  Carbon::parse(Carbon::now())->format('Y-m-d'))
                return  redirect()->back()->with(['error' => 'يجب أن يكون تاريخ اليوم مطابقاً لتاريخ البدء بالمسار']);
            Path::where('id',$id)
                    ->update(['current_stage' =>1
                             ]); 
            return redirect()->intended('/allpaths')->with('success', 'تم البدء بالمسار بنجاح');  
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }
    }

    public function applicantsUsers($id) 
    {
        try
        {          
            $user_path_count= DB::table('paths')
                        ->join('user_path','paths.id', '=','user_path.path_id')
                        ->select('paths.id')
                        ->where('paths.id',$id)
                        ->where('paths.current_stage','-2')
                        ->where('user_path.user_status','1')
                        ->whereNull('paths.deleted_at')
                        ->count();
            $path=Path::where('id',$id)->first();            
            return view('layouts.path.applicantsUsers')->with(['path'=>$path, 'user_path_count'=>$user_path_count]);   
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }   
    }

    public function acceptUsers(Request $request, $id, $count)
    {
        try
        {            
            $input = $request->all();
            $validator = Validator::make($input,[
                'acceptUsersCount'=>'required'
            ]);
            if( $validator->fails()) 
                return redirect()->back()->withErrors($validator);
            else if ($count<=0) 
                return redirect()->back()->with('error','لا يوجد منضمين إلى المسار');   
            else if($input['acceptUsersCount']<=0)
                return redirect()->back()->with('error','يجب أن يكون العدد الأعظمي للمقبولين أكبر من الصفر ');
            else if($input['acceptUsersCount']>$count)
                return redirect()->back()->with('error','يجب أن يكون العدد الأعظمي للمقبولين في المسار أصغر من أو يساوي '.$count);
            else
            {
                $userpath=DB::table('user_path')
                    ->select(DB::raw('user_id, (LENGTH(answer_join) + LENGTH(answer_accept_order)) as user_order'))
                    ->where('user_status','1')
                    ->where('path_id',$id)
                    ->orderbyDESC('user_order')
                    ->orderBy('created_at')
                    ->get();                 
                $acceptedUsersEmails=[]; 
                for($i=0; $i<$input['acceptUsersCount']; $i++)
                {
                    $user_id=$userpath[$i]->user_id;
                    UserPath::where('user_id',$user_id)->update(['user_status' => 2]);
                    $user=User::where('id',$user_id)->first();
                    $email=$user->email;
                    $acceptedUsersEmails[$i]=$email;
                }
                Mail::send('Mails.acceptEmail',[], function ($message) use ($acceptedUsersEmails) {
                        $message->bcc($acceptedUsersEmails);
                        $message->subject('Accept Email');
                }); 
                $rejectedUsersEmails=[]; 
                $j=0;          
                for($i=$input['acceptUsersCount']; $i<$count; $i++)
                {
                    $user_id=$userpath[$i]->user_id;
                    UserPath::where('user_id',$user_id)->update(['user_status' => 3]);
                    $user=User::where('id',$user_id)->first();
                    $email=$user->email;
                    $rejectedUsersEmails[$j]=$email;
                    $j=$j+1;
                }
                Mail::send('Mails.rejectEmail',[], function ($message) use ($rejectedUsersEmails) {
                    $message->bcc($rejectedUsersEmails);
                    $message->subject('Reject Email');
                }); 
                $path=Path::where('id',$id)->update(['current_stage' => -1]);
                return redirect()->intended('/allpaths')->with('success', 'تم قبول العدد المطلوب بالمسار بنجاح');      
            }
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }   
    }

    // Show Active Paths
    public function index()
    {
        $paths = Path::latest()->get();

        return view('paths.index', compact('paths'));
    }

    // Show Trashed Paths
    public function trashed()
    {
        $paths = Path::onlyTrashed()->get();

        return view('paths.trashed', compact('paths'));
    }

    // Current Users Except Exclude
    public function currentUsersPath($id)
    {
        $path = Path::find($id);
        if (!$path) {
            return redirect()->route('paths.index')->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)->where('user_status', 2)->orderBy('score', 'DESC')->paginate(10);

        return view('paths.users', compact('usersPath'))->with('name', $path->path_name);
    }

    // All Users Except Exclude
    public function allUsersPath($id)
    {
        $path = Path::find($id);
        if (!$path) {
            return redirect()->route('paths.index')->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)->whereIn('user_status', [2, 4])->paginate(10);

        return view('paths.users', compact('usersPath'))->with('name', $path->path_name);
    }

    // Current Exclude Users
    public function currentExcludeUser($id)
    {
        $path = Path::find($id);
        if (!$path) {
            return redirect()->route('paths.index')->with('message', 'هذا المسار غير موجود');
        }

        // get event start date
        $event = $path->events->last();
        if (!$event) return redirect()->route('paths.index')->with('message', 'لا يوجد تاريخ لبدأ المسار بعد');
        $date_start = $event->event_data;

        $usersPath = UserPath::where('path_id', $path->id)
            ->where('user_status', 3)
            ->where('created_at', '>=', $date_start)
            ->paginate(10);

        return view('paths.users', compact('usersPath'))->with('name', $path->path_name);
    }

    // Exlude Users
    public function allExcludeUser($id)
    {
        $path = Path::find($id);
        if (!$path) {
            return redirect()->route('paths.index')->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)
            ->where('user_status', 3)->paginate(10);

        return view('paths.users', compact('usersPath'))->with('name', $path->path_name);
    }

    // Soft Delete A Path
    public function destroy($id)
    {
        try 
        {
            $path = Path::where('id',$id)->where('current_stage',0)->whereNull('deleted_at')->get()->first();
            if (empty($path)) 
                return redirect()->intended('/allpaths')->with('error', 'لا يمكن حذف المسار إلا بعد إنهائه');
            $path->delete();
            return redirect()->intended('/allpaths')->with('success', 'تم حذف المسار بنجاح');
        } catch (\Throwable $th) {   
            return redirect()->intended('/allpaths')->with('error',$th->getMessage()) ; 
        }
    }

    // Restore A Path From Sofe Delete
    public function restore($id)
    {
        $path = Path::withTrashed()->where('id', $id)->first();
        if (!$path) {
            return redirect()->route('paths.index')->with('message', 'هذا المسار غير موجود');
        }

        $path->restore();

        return redirect()->route('paths.index')->with('message', 'تم إستعادة المسار بنجاح');
    }
}
