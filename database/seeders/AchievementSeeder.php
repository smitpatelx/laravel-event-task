<?php
namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder {
    public function run()
    {
        $at_lesson = AchievementType::create([
            'name' => 'lesson',
        ]);
        $at_comment = AchievementType::create([
            'name' => 'comment',
        ]);

        Achievement::create([
            'name' => 'First Lesson Watched',
            'achievement_type_id' => $at_lesson->id,
            'level' => 1,
        ]);
        Achievement::create([
            'name' => '5 Lessons Watched',
            'achievement_type_id' => $at_lesson->id,
            'level' => 5,
        ]);
        Achievement::create([
            'name' => '10 Lessons Watched',
            'achievement_type_id' => $at_lesson->id,
            'level' => 10,
        ]);
        Achievement::create([
            'name' => '25 Lessons Watched',
            'achievement_type_id' => $at_lesson->id,
            'level' => 25,
        ]);
        Achievement::create([
            'name' => '50 Lessons Watched',
            'achievement_type_id' => $at_lesson->id,
            'level' => 50,
        ]);

        Achievement::create([
            'name' => 'First Comment Written',
            'achievement_type_id' => $at_comment->id,
            'level' => 1,
        ]);
        Achievement::create([
            'name' => '3 Comments Written',
            'achievement_type_id' => $at_comment->id,
            'level' => 3,
        ]);
        Achievement::create([
            'name' => '5 Comments Written',
            'achievement_type_id' => $at_comment->id,
            'level' => 5,
        ]);
        Achievement::create([
            'name' => '10 Comments Written',
            'achievement_type_id' => $at_comment->id,
            'level' => 10,
        ]);
        Achievement::create([
            'name' => '20 Comments Written',
            'achievement_type_id' => $at_comment->id,
            'level' => 20,
        ]);

        Badge::create([
            'name' => 'Beginner',
            'no_of_achievement' => 0,
        ]);
        Badge::create([
            'name' => 'Intermediate',
            'no_of_achievement' => 4,
        ]);
        Badge::create([
            'name' => 'Advanced',
            'no_of_achievement' => 8,
        ]);
        Badge::create([
            'name' => 'Master',
            'no_of_achievement' => 10,
        ]);
    }
}
