<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Imports\PraticalResultsImport;
use App\Models\Path;
use App\Models\UserPath;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PraticalResultsController extends Controller
{
    public function showUpload()
    {
        $paths = Path::all();
        return view('exams.praticalresults', compact('paths'));
    }

    public function uploadResults(Request $request)
    {
        $this->validate($request, [
            'path_id' => 'required',
            'sheet' => 'required|mimes:xlsx,csv,tsv,ods,xls,slk,xml',
        ]);

        $data = Excel::toCollection(new PraticalResultsImport, $request->file('sheet'))->first();

        $res = array();
        foreach ($data as $result) {
            // FIRST COLUMN IS PATH ID
            $path_id = $result[0];
            // SECOND COLUMN IS STUDENT ID
            $student_id = $result[1];
            // THIRD COLUMN IS STUDENT'S RESULT
            $res_std = $result[2];

            // PATH MUST NOT NULL AND PATH IS SAME PATH IN DATABASE
            if (is_null($path_id) || $path_id !== (int)$request->path_id) continue;

            // STUDENT ID MUST NOT NULL
            if (is_null($student_id)) continue;

            // CONFIRM THIS STUDENT HAS THIS PATH
            $path_user = UserPath::where('user_id', $student_id)
                ->where('path_id', $path_id)
                ->where('user_status', 2)->first();

            if (!$path_user) continue;

            // IF STUDENT'S RESULT IS NULL
            if (is_null($res_std)) $res_std = 0;





            $res[] = [$path_id, $student_id, $res_std];
        }

        dd($res);
    }
}
