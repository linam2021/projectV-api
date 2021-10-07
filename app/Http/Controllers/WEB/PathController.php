<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Path;
use App\Models\Course;
use App\Models\UserPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            $this->validate($request, [
                'idBankPath' => 'required'
            ]);
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
                Path::create([
                    'path_name' => $path_name,
                    'questionbank_path_id' => $request->idBankPath,
                ]);
                return redirect()->intended('paths/create')->with('success', 'تم إضافة المسار بنجاح');
            }                   
            else
                return redirect()->intended('paths/create')->with(['questionBankPaths'=>$questionBankPaths])->with('error','تم إضافة هذا المسار سابقاً') ; 
        
        } catch (\Throwable $th) {   
            return redirect()->intended('paths/create')->with(['questionBankPaths'=>$questionBankPaths])->with('error',$th->getMessage()) ; 
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
                    ->update(['current_stage' =>-1 
                             ]); 
            return redirect()->intended('/allpaths')->with('success', 'تم إنهاء التسجيل على المسار بنجاح');  
        }catch (\Throwable $th) { 
            dd($th->getMessage())  ;         
        }
    }

    public function startPath($id)
    {
        try
        {
            $path=Path::where('id',$id)->first();
            if ( Carbon::parse($path->path_start_date)->format('Y-m-d') !=  Carbon::parse(Carbon::now())->format('Y-m-d'))
                return  redirect()->back()->with(['error' => 'يجب أن يكون تاريخ اليوم مطابقاً لتاريخ البدء بالمسار']);
            Path::where('id',$id)
                    ->update(['current_stage' =>1
                             ]); 
            return redirect()->intended('/allpaths')->with('success', 'تم البدء بالمسار بنجاح');  
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
