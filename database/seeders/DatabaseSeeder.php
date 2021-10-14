<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);
        $this->call(PathTableSeeder::class);
        $this->call(CourseTableSeeder::class);
        $this->call(UserPathTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(ExamTableSeeder::class);
        $this->call(MessagesTableSeeder::class);
        $this->call(MessageUserTableSeeder::class);
        $this->call(NotificationTableSeeder::class);
        $this->call(NotificationUserTableSeeder::class);
    }
}
