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
        $now = Carbon::now();
        $now2 = Carbon::now();
        DB::table('events')->insert([
            'event_name' => 'database path start day',
            'event_type' => 'path start',
            'event_data'=>$now->toDateTimeString(),
            'path_id' => 1,
            'created_at' => $now->toDateTimeString(),
            'updated_at' => $now->toDateTimeString()
        ]);
        DB::table('events')->insert([
            'event_name' => 'database path exam day',
            'event_type' => 'exam day',
            'event_data'=>$now->addDays(12)->toDateTimeString(),
            'path_id' => 1,
            'created_at' => $now->toDateTimeString(),
            'updated_at' => $now->toDateTimeString()
        ]);
        DB::table('events')->insert([
            'event_name' => 'flutter path start day',
            'event_type' => 'path start',
            'event_data'=>$now2->toDateTimeString(),
            'path_id' => 2,
            'created_at' => $now2->toDateTimeString(),
            'updated_at' => $now2->toDateTimeString()
        ]);
        DB::table('events')->insert([
            'event_name' => 'flutter path exam day',
            'event_type' => 'exam day',
            'event_data'=>$now2->addDays(23)->toDateTimeString(),
            'path_id' => 2,
            'created_at' => $now2->toDateTimeString(),
            'updated_at' => $now2->toDateTimeString()
        ]);
    }
}
