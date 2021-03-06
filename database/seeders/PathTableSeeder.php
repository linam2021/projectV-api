<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PathTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('paths')->insert([
            'path_name' => 'Database',
            'current_stage' => 0,
            'questionbank_path_id'=>100,
            'path_image_name'=>'database&api.jpg',
            'path_start_date'=>Carbon::parse(Carbon::now()->addDays(7))->format('Y-m-d'),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('paths')->insert([
            'path_name' => 'Flutter',
            'current_stage' => 0,
            'questionbank_path_id'=>200,
            'path_start_date'=>Carbon::parse(Carbon::now()->addDays(3))->format('Y-m-d'),
            'path_image_name'=>'flutter.jpg',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('paths')->insert([
            'path_name' => 'design',
            'current_stage' => 0,
            'questionbank_path_id'=>300,
            'path_image_name'=>'Design.jpg',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('paths')->insert([
            'path_name' => 'Web',
            'current_stage' => 0,
            'questionbank_path_id'=>400,
            'path_image_name'=>'web.jpg',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
