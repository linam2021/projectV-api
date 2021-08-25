<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('users')->insert([
            'email' => 'email1@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
	        'is_admin'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
         DB::table('users')->insert([
            'email' => 'email2@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
	        'is_admin'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
         DB::table('users')->insert([
            'email' => 'email3@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
	        'is_admin'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
