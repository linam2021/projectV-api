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
            'course_name' => 'course11',
            'course_link' => 'course11 link',
	        'stage'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('courses')->insert([
            'path_id' => 1,
            'course_name' => 'course12',
            'course_link' => 'course12 link',
	        'stage'=>2,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('courses')->insert([
            'path_id' => 1,
            'course_name' => 'course13',
            'course_link' => 'course13 link',
	        'stage'=>3,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('courses')->insert([
            'path_id' => 2,
            'course_name' => 'course21',
            'course_link' => 'course21 link',
	        'stage'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
