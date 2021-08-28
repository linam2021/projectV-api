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
                return $this->sendResponse($paths, 'Retrived Paths Successfully', 200);

            return $this->sendError('error', 'We do not have paths yet!', 404);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
