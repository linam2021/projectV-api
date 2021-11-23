<?php

namespace App\Http\Controllers\WEB;

use App\Exports\PraticalResultsExport;
use App\Http\Controllers\Controller;
use App\Imports\PraticalResultsImport;
use App\Models\Course;
use App\Models\Event;
use App\Models\Exam;
use App\Models\Path;
use App\Models\UserExam;
use App\Models\UserPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Facades\Excel;

class PraticalResultsController extends Controller
{
    // SHOW UPLOAD PAGE
    public function showUpload()
    {
        $paths = Path::all();
        return view('exams.praticalresults', compact('paths'));
    }

    // ADD PRATICAL RESULTS TO DATABASE
    public function uploadResults(Request $request)
    {
        $this->validate($request, [
            'path_id' => 'required',
            'sheet' => 'required|mimes:xlsx,csv,tsv,ods,xls,slk,xml',
        ]);

        $data = Excel::toCollection(new PraticalResultsImport, $request->file('sheet'))->first();

        // GET LAST PRATICAL EXAM
        $path = Path::find($request->path_id);
        if (!$path) {
            return redirect()->back()->with('message', 'هذا المسار غير موجود');
        }
        $current_stage = $path->current_stage;
        if ($current_stage == 0) {
            return redirect()->back()->with('message', 'هذا المسار لم يبدأ بعد');
        }
        $course = Course::where('path_id', $request->path_id)->where('stage', $current_stage)->first();
        $exam = Exam::where('course_id', $course->id)
            ->whereIn('exam_type', ['practical', 'practicalTheoretical'])->orderBy('created_at', 'DESC')->first();

        $res_rejected = array();
        foreach ($data as $result) {
            // FIRST COLUMN IS PATH ID
            $path_id = $result[0];
            // SECOND COLUMN IS STUDENT ID
            $student_id = $result[1];
            // THIRD COLUMN IS STUDENT'S RESULT
            $res_std = $result[2];

            // PATH MUST NOT NULL AND PATH IS SAME PATH IN DATABASE
            if (is_null($path_id) || $path_id !== (int)$request->path_id) {
                $res_rejected[] = [$path_id, $student_id, $res_std, 'هذا المسار غير مطابق أو غير موجود'];
                continue;
            }

            // STUDENT ID MUST NOT NULL
            if (is_null($student_id)) {
                $res_rejected[] = [$path_id, $student_id, $res_std, 'هذا المستخدم غير موجود'];
                continue;
            }

            // CONFIRM THIS STUDENT HAS THIS PATH
            $path_user = UserPath::where('user_id', $student_id)
                ->where('path_id', $path_id)
                ->where('user_status', 2)->first();

            if (!$path_user) {
                $res_rejected[] = [$path_id, $student_id, $res_std, 'هذا المستخدم لا ينتمي إلى هذا المسار'];
                continue;
            }

            // IF STUDENT'S RESULT IS NULL
            if (is_null($res_std)) {
                $res_rejected[] = [$path_id, $student_id, $res_std, 'هذا المستخدم ليس لديه علامة'];
                continue;
            }

            // CREATE USER EXAM
            $user_exam = UserExam::create([
                'user_id' => $path_user->user_id,
                'path_id' => $path_id,
                'path_start_date' => $path_user->path_start_date,
                'exam_id' => $exam->id,
                'user_exam_date' => $exam->exam_start_date,
                'exam_result' => $res_std,
                'is_well_prepared' => 'yes',
                'is_easy_exam' => 'easy',
            ]);
        }

        if (count($res_rejected) > 0) {
            return Excel::download(new PraticalResultsExport($res_rejected), 'rejected_users.xlsx');
        }

        return redirect()->back()->with('message', 'تم إدخال جميع البيانات بنجاح');
    }
}
