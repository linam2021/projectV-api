<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\MessageUser;
use App\Models\User;
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
        $users=User::where('is_admin',0)->get();
        $messages=Message::all();
        MessageUser::insert([
            ['user_id' => $users->random()->id,
              'message_id' =>$messages->random()->id,
              'read'=>0,
             'created_at' => $now, 'updated_at' => $now],
             ['user_id' => $users->random()->id,
             'message_id' =>$messages->random()->id,
              'read'=>0,
             'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
