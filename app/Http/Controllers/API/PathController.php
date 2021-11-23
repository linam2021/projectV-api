<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Path;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PathController extends BaseController
{
    /**
     * Retrived Paths For Users
     */
    public function index()
    {
        try {
            $paths = Path::orderBy('path_name', 'ASC')->get();
            if ($paths->count() > 0) 
                return $this->sendResponse($paths, 'Paths are retrived Successfully');

            return $this->sendError('We do not have paths yet!');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function showComingPaths()
    {
        try {
            $paths = Path::whereNotNull('path_start_date')->where('current_stage',0) ->orderBy('path_start_date', 'ASC')->get();
            if ($paths->count() > 0) 
            {
            $response = [
                'success' => true,
                'open'=>true,
                'data' => $paths,
                'message' => 'Coming paths are retrived Successfully'
            ];
            return response()->json($response);
            }
            else if ($paths->count() == 0) 
            {
                $response = [
                    'success' => true,
                    'open'=>false,
                    'data' => $paths,
                    'message' => 'We do not have Coming paths yet!'
                ];
                return response()->json($response);
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
