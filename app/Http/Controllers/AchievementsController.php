<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        // Get all achievements that are unlocked
        $achievements = $user->achievements()->get()->pluck('id')->toArray();
        $unlocked_achievements = $user->achievements()->get()->pluck('name')->toArray();
        // Get all achievements that are not unlocked
        $next_achievements = Achievement::whereNotIn('id', $achievements)->orderBy('level', 'desc')->get()->pluck('name')->toArray();

        // Get the current badge
        $badge = $user->badge_id;
        if ($badge == NULL) {
            $badge = Badge::first();
        } else {
            $badge = Badge::find($badge);
        }
        $badge_name = $badge->name;

        // Next badge
        $next_badge = Badge::where('no_of_achievement', '>', $badge->no_of_achievement)->first();
        // Next badge name
        if ($next_badge == NULL) {
            $next_badge_name = '';
        } else {
            $next_badge_name = $next_badge->name;
        }

        // Remaining to unlock next badge
        if ($next_badge == NULL) {
            $remaing_to_unlock_next_badge = 0;
        } else {
            $remaing_to_unlock_next_badge = $next_badge->no_of_achievement - $badge->no_of_achievement;
        }

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_achievements,
            'current_badge' => $badge_name,
            'next_badge' => $next_badge_name,
            'remaing_to_unlock_next_badge' => $remaing_to_unlock_next_badge,
        ]);
    }
}
