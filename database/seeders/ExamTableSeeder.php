<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('exams')->insert([
            'course_id' => 1,
            'exam_type'=>'theoretical',
            'exam_start_date' => $now,
            'exam_duration' => Carbon::parse('00:45:00')->format('H:i:s'),
            'questions_count'=>10,
	        'sucess_mark'=>10,
            'maximum_mark'=>20,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('exams')->insert([
            'course_id' => 2,
            'exam_type'=>'theoretical',
            'exam_start_date' => $now,
            'exam_duration' => Carbon::parse('00:30:00')->format('H:i:s'),
            'questions_count'=>6,
	        'sucess_mark'=>6,
            'maximum_mark'=>12,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('exams')->insert([
            'course_id' => 3,
            'exam_type'=>'theoretical',
            'exam_start_date' => $now,
            'exam_duration' => Carbon::parse('00:35:00')->format('H:i:s'),
            'questions_count'=>5,
	        'sucess_mark'=>5,
            'maximum_mark'=>10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('exams')->insert([
            'course_id' => 4,
            'exam_type'=>'theoretical',
            'exam_start_date' => $now,
            'exam_duration' => Carbon::parse('00:40:00')->format('H:i:s'),
            'questions_count'=>7,
	        'sucess_mark'=>7,
            'maximum_mark'=>14,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
