<?php

namespace Database\Seeders;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        Message::insert([
            ['title' => 'registration accepted',
              'body' =>'Your registration has been accepted for database-api path',
              'admin_id'=>3,
             'created_at' => $now, 'updated_at' => $now],
             ['title' => 'registration refused',
             'body' =>'Your registration has been refused for database-api path try later',
             'admin_id'=>3,
            'created_at' => $now, 'updated_at' => $now],
        ]);
        Message::insert([
            ['title' => 'registration accepted',
              'body' =>'Your registration has been accepted for database-api path',
              'admin_id'=>3,
             'created_at' => $now, 'updated_at' => $now],
             ['title' => 'registration refused',
             'body' =>'Your registration has been refused for database-api path try later',
             'admin_id'=>3,
            'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
