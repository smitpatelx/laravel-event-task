<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Support\Facades\Event;

class ListenAchievementUnlocked
{
    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        // Store achievement unlocked for user
        $achievement = Achievement::where('name', $event->achievement_name)->first();
        $event->user->achievements()->attach($achievement->id);

        // Current badge
        $current_badge = Badge::where('no_of_achievement', '>=', $event->user->achievements->count())->first();

        if ($current_badge !== NULL && $event->user->badge_id !== $current_badge->id) {
            // Dispatch event for badge
            Event::dispatch(new BadgeUnlocked(
                $current_badge->name,
                $event->user,
            ));
        }
    }
}
