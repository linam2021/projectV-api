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
            'current_stage' => 2,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('paths')->insert([
            'path_name' => 'Flutter',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('paths')->insert([
            'path_name' => 'Design',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('paths')->insert([
            'path_name' => 'Web',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}