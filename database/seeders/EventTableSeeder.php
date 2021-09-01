<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('events')->insert([
            'user_id' => 4,
            'event_name' => 'start of Database path',
            'event_data' => $now
        ]);
        DB::table('events')->insert([
            'user_id' => 4,
            'event_name' => 'start of Database path (stage 1)',
            'event_data' => $now
        ]);
        DB::table('events')->insert([
            'user_id' => 5,
            'event_name' => 'start of Flutter path',
            'event_data' => $now
        ]);
    }
}
