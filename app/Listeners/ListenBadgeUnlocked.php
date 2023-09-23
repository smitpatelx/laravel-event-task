<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use App\Models\User;

class ListenBadgeUnlocked
{
    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event): void
    {
        // Store badge unlocked for user
        $badge = Badge::where('name', $event->badge_name)->first();

        if ($badge !== NULL) {
            $user = User::find($event->user->id)->first();
            $user->badge()->associate($badge);
            $user->save();
        }
    }
}
