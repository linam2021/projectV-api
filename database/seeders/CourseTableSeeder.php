<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('courses')->insert([
            'path_id' => 1,
            'course_name' => 'DB1',
            'course_link' => 'course11 link',
            'course_duration'=>4,
	        'stage'=>1,
            'questionbank_course_id'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('courses')->insert([
            'path_id' => 1,
            'course_name' => 'DB2',
            'course_link' => 'course12 link',
            'course_duration'=>12,
	        'stage'=>2,
            'questionbank_course_id'=>2,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('courses')->insert([
            'path_id' => 1,
            'course_name' => 'DB3',
            'course_link' => 'course13 link',
            'course_duration'=>14,
	        'stage'=>3,
            'questionbank_course_id'=>3,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('courses')->insert([
            'path_id' => 2,
            'course_name' => 'flutter1',
            'course_link' => 'course21 link',
            'course_duration'=>19,
            'questionbank_course_id'=>4,
	        'stage'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
