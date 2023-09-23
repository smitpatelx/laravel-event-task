<?php

namespace Tests\Feature;

use App\Listeners\ListenLessonWatched;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Lesson;

class ListenLessonWatchedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Lesson $lesson;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::create([
            'name' => 'test user',
            'email' => 'test@cmail.com',
            'password' => 'testpassword',
        ]);

        $this->lesson = Lesson::create([
            'title' => 'lesson 1',
        ]);
    }

    /**
     * A basic test example.
     */
    public function test_listen_comment_written(): void
    {
        Event::fake();

        $event = new LessonWatched($this->lesson, $this->user);
        $listener = new ListenLessonWatched();

        // Trigger event
        $listener->handle($event);

        Event::assertDispatched(AchievementUnlocked::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
