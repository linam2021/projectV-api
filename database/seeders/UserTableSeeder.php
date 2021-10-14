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
            'email' => 'admin1@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_verified'=>1,
	        'is_admin'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
         DB::table('users')->insert([
            'email' => 'admin2@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_verified'=>1,
	        'is_admin'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
         DB::table('users')->insert([
            'email' => 'admin3@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_verified'=>1,
	        'is_admin'=>1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('users')->insert([
            'email' => 'user1@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_verified'=>1,
	        'is_admin'=>0,
            'first_name'=>'Ahmad', 
            'father_name'=>'m',
            'last_name'=>'Ali', 
            'telegram'=>'tel1',
            'phone'=>'1234567', 
            'country'=>'xyz',
            'gender'=>'male',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('users')->insert([
            'email' => 'user2@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_verified'=>1,
	        'is_admin'=>0,
            'first_name'=>'Mona', 
            'father_name'=>'s',
            'last_name'=>'Samer', 
            'telegram'=>'tel2',
            'phone'=>'12345678', 
            'country'=>'xyz',
            'gender'=>'female',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('users')->insert([
            'email' => 'user3@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_verified'=>1,
	        'is_admin'=>0,
            'first_name'=>'Rana', 
            'father_name'=>'H',
            'last_name'=>'Maher', 
            'telegram'=>'tel3',
            'phone'=>'12345689', 
            'country'=>'xyz',
            'gender'=>'female',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
