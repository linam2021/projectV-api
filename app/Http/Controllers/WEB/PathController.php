<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Path;
use App\Models\UserPath;
use Illuminate\Http\Request;

class PathController extends Controller
{
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

    // Show Create Page
    public function create()
    {
        // Wait To Get API's Questions And Send it
        return view('paths.create');
    }

    // Store A Path
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'idBankPath' => 'required'
        ]);

        Path::create([
            'path_name' => $request->name,
            'questionbank_path_id' => $request->idBankPath,
        ]);

        return redirect()->route('paths.index')->with('message', 'تم إنشاء المسار بنجاح');
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
        $path = Path::find($id);
        if (!$path) {
            return redirect()->route('paths.index')->with('message', 'هذا المسار غير موجود');
        }

        $path->delete();

        return redirect()->route('paths.index')->with('messsage', 'تم حذف المسار بنجاح');
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
