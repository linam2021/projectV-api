<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
    public function index()
    {
         try {    
              $userCount=DB::table('users')->where('is_admin',0)->count();
              $adminCount=DB::table('users')->where('is_admin',1)->count();
              $openPathCount=DB::table('paths')->where('current_stage','<>',0)->count();
              $pathsProgress = DB::table('paths')
              ->join('courses','paths.id', '=','courses.path_id')
                       ->select(DB::raw('(current_stage-1)*100/count(*) as course_stage_count, path_name'))
                       ->where('current_stage','<>',0)
                       ->groupBy('path_name','current_stage')
                       ->orderby('course_stage_count')
                       ->get();              
              return view('layouts.home')->with(['userCount'=>$userCount, 'adminCount'=> $adminCount, 'openPathCount'=>$openPathCount, 'pathsProgress'=>$pathsProgress]);
             } catch (\Throwable $th) {
               dd($th->getMessage());
             }
    }
}
