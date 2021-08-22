<?php

namespace Database\Seeders;

use App\Models\MessageUser;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessageUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();

        MessageUser::insert([
            ['user_id' => 1,
              'message_id' =>1,
              'read'=>0,
             'created_at' => $now, 'updated_at' => $now],
             ['user_id' => 1,
              'message_id' =>2,
              'read'=>0,
             'created_at' => $now, 'updated_at' => $now],

        ]);
    }
}
