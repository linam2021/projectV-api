<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPathTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('user_path')->insert([
            'user_id'=>4,
            'path_id' =>1,
            'path_start_date' => Carbon::now()->format('Y-m-d'),
            'answer_join'=>'answer_join1',
            'answer_accept_order'=>'answer_accept_order1',
            'repeat_chance_no'=>1,
            'user_status'=>2,
            'score'=> 12,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('user_path')->insert([
            'user_id'=>5,
            'path_id' =>1,
            'path_start_date' => Carbon::now()->format('Y-m-d'),
            'answer_join'=>'answer_join2',
            'answer_accept_order'=>'answer_accept_order2',
            'repeat_chance_no'=>1,
            'user_status'=>2,
            'score'=> 19,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('user_path')->insert([
            'user_id'=>6,
            'path_id' =>1,
            'path_start_date' => Carbon::now()->format('Y-m-d'),
            'answer_join'=>'answer_join3',
            'answer_accept_order'=>'answer_accept_order3',
            'repeat_chance_no'=>1,
            'user_status'=>2,
            'score'=> 16,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
