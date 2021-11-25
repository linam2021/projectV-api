<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Path;
use App\Models\UserPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    // USERS APPLICANTS
    public function usersApplicants($id)
    {
        $path = Path::find($id);

        if (!$path) {
            redirect()->back()->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)
            ->where('path_start_date', '>=', $path->path_start_date)
            ->where('user_status', 1)->latest()->paginate(100);

        return view('statistics.usersapplicants', compact('usersPath'))->with('path_name', $path->path_name);
    }

    // USERS CONTINUE
    public function usersContinue($id)
    {
        $path = Path::find($id);

        if (!$path) {
            redirect()->back()->with('message', 'هذا المسار غير موجود');
        }

        // 2 is meaning user is accepted
        $usersPath = UserPath::where('path_id', $path->id)->where('user_status', 2)->paginate(100);

        return view('statistics.userscontinue', compact('usersPath'))->with('path_name', $path->path_name);
    }

    // LEADERBOARD
    public function leaderboard($id)
    {
        $path = Path::find($id);

        if (!$path) {
            redirect()->back()->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)
            ->where('path_start_date', '>=', $path->path_start_date)
            ->whereIn('user_status', [2, 3])->orderBy('score', 'DESC')->paginate(100);

        return view('statistics.path_leaderboard', compact('usersPath'))->with('path_name', $path->path_name);
    }

    // USERS EXCLUDE
    public function usersExcludes($id)
    {
        $path = Path::find($id);

        if (!$path) {
            redirect()->back()->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)
            ->where('path_start_date', '>=', $path->path_start_date)
            ->where('user_status', 3)->latest()->paginate(100);

        return view('statistics.usersexcludes', compact('usersPath'))->with('path_name', $path->path_name);
    }

    // USERS ANWSERS
    public function usersAnwsers($id)
    {
        $path = Path::find($id);

        if (!$path) {
            redirect()->back()->with('message', 'هذا المسار غير موجود');
        }

        $usersPath = UserPath::where('path_id', $path->id)
            ->where('path_start_date', '>=', $path->path_start_date)->latest()->paginate(100);

        return view('statistics.usersanwser', compact('usersPath'))->with('path_name', $path->path_name);
    }
}
