<?php

namespace Tests\Feature;

use App\Listeners\ListenCommentWritten;
use App\Events\CommentWritten;
use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\AchievementType;

class ListenCommentWrittenTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Comment $comment;

    private function getFirstCommentAchievement() {
        $achievement_type = AchievementType::where('name', '=', 'comment')->first();
        return Achievement::where('achievement_type_id', '=', $achievement_type->id)->orderBy('level', 'asc')->first();
    }

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::create([
            'name' => 'test user',
            'email' => 'test@cmail.com',
            'password' => 'testpassword',
        ]);

        $this->comment = Comment::create([
            'body' => 'test comment',
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Fire event AchievementUnlocked
     */
    public function test_listen_comment_written(): void
    {
        Event::fake();

        $event = new CommentWritten($this->comment);
        $listener = new ListenCommentWritten();

        // Trigger event
        $listener->handle($event);

        $achievement_name_to_check = $this->getFirstCommentAchievement()->name;

        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($achievement_name_to_check) {
            return $e->achievement_name === $achievement_name_to_check
                && $e->user->id === $this->user->id;
        });
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
