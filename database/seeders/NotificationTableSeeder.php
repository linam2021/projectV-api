<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        $messages=Message::all();
        Notification::insert([
            ['title' => 'you have a new message',
              'body' =>'your registration have been accepted check your messages box ',
              'message_id' =>$messages->random()->id,
             'created_at' => $now, 'updated_at' => $now],
             ['title' => 'send push notification',
             'body' =>'notification content',
             'message_id' =>$messages->random()->id,
            'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
