<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends BaseController
{
    /**
     * Show Current Course For User
     *
     * @return \Illuminate\Http\Response
     */
    public function currnetUserCourse()
    {
        try {
            $user = Auth::user();
            // Verify If User Has Path Now
            // 2 => Accepted From Admin
            $path_now = $user->userPaths->where('user_status', 2)->first();
            if (!$path_now) {
                return $this->sendError('error', 'You Do Not Have Any Course Now', 404);
            }
            // Get Path
            $path = $user->paths->where('id', $path_now->path_id)->first();
            // Waiting others people for making events table which uses for filter date begining a path
            //
            // Get Course Depends On A Path And A Current Stage
            $course = $path->courses->where('stage', $path->current_stage)->first();
            return $this->sendResponse($course, 'Retrived Current Course successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
