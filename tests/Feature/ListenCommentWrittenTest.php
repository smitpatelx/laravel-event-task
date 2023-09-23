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

class ListenCommentWrittenTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Comment $comment;

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
     * A basic test example.
     */
    public function test_listen_comment_written(): void
    {
        Event::fake();

        $event = new CommentWritten($this->comment);
        $listener = new ListenCommentWritten();

        // Trigger event
        $listener->handle($event);

        Event::assertDispatched(AchievementUnlocked::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
