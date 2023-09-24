<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class ListenAchievementUnlocked
{
    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): ?Badge
    {
        // Store achievement unlocked for user
        $achievement = Achievement::where('name', $event->achievement_name)->first();
        $user = User::find($event->user->id)->first();

        if ($achievement == NULL || $user == NULL) {
            return NULL;
        }

        // Check if user already has this achievement
        if (!$user->achievements()->where('achievement.id', $achievement->id)->exists()) {
            $user->achievements()->attach($achievement);

            // Current badge
            $current_badge = Badge::where(
                'no_of_achievement',
                '>=',
                $user->achievements()->count()
            )->orderBy('no_of_achievement', 'asc')->first();


            if (!is_null($current_badge)) {
                // Dispatch event for badge
                Event::dispatch(new BadgeUnlocked(
                    $current_badge->name,
                    $event->user,
                ));
            }

            return $current_badge;
        }

        return NULL;
    }
}
