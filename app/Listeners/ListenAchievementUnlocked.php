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
    public function handle(AchievementUnlocked $event): void
    {
        // Store achievement unlocked for user
        $achievement = Achievement::where('name', $event->achievement_name)->first();
        $user = User::find($event->user->id)->first();
        $user->achievements()->attach($achievement->id);

        // Current badge
        $current_badge = Badge::where('no_of_achievement', '>=', $user->achievements->count())->first();

        if ($current_badge !== NULL) {
            // Dispatch event for badge
            Event::dispatch(new BadgeUnlocked(
                $current_badge->name,
                $event->user,
            ));
        }
    }
}
