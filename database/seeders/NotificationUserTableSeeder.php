<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationUserTableSeeder extends Seeder
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
        $notifications=Notification::all();
        NotificationUser::insert([
            ['user_id' => $users->random()->id,
              'notification_id' =>$notifications->random()->id,
              'read'=>0,
             'created_at' => $now, 'updated_at' => $now],
             ['user_id' => $users->random()->id,
             'notification_id' =>$notifications->random()->id,
              'read'=>0,
             'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
