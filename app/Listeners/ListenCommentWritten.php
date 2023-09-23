<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\AchievementType;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;
use App\Models\Comment;

class ListenCommentWritten
{
    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $comment_achievement_type = AchievementType::where('name', 'comment')->first();
        $all_achievements = Achievement::where('achievement_type_id', $comment_achievement_type->id)->orderBy('level', 'desc')->get();

        $comment_count = Comment::where('user_id', $event->comment->user->id)->count();

        $unlocked_achievement = NULL;
        foreach ($all_achievements as $achievement) {
            if ($achievement->level >= $comment_count) {
                $unlocked_achievement = $achievement;
            }
        }

        // Dispatch AchievementUnlocked event
        if ($unlocked_achievement) {
            Event::dispatch(new AchievementUnlocked(
                $unlocked_achievement->name,
                $event->comment->user,
            ));
        }
    }
}
