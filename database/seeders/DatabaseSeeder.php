<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Database\Seeders\AchievementSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        (new AchievementSeeder())->run();

        $lessons = Lesson::factory()
            ->count(20)
            ->create();
    }
}
