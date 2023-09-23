<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;

class ListenLessonWatched
{
    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        // Increase watched count
        $user1 = User::find($event->user->id)->first();
        $user1->lessons()->attach($event->lesson->id, ['watched' => 1]);

        $lesson_achievement_type = AchievementType::where('name', 'lesson')->first();
        $all_achievements = Achievement::where('achievement_type_id', $lesson_achievement_type->id)->orderBy('level', 'desc')->get();

        $unlocked_achievement = NULL;
        foreach ($all_achievements as $achievement) {
            if ($achievement->level >= $event->user->watched) {
                $unlocked_achievement = $achievement;
            }
        }

        // Dispatch AchievementUnlocked event
        if ($unlocked_achievement) {
            Event::dispatch(new AchievementUnlocked(
                $unlocked_achievement->name,
                $event->user,
            ));
        }
    }
}
