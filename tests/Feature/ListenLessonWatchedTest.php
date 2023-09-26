<?php

namespace Tests\Feature;

use App\Listeners\ListenLessonWatched;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\Lesson;

class ListenLessonWatchedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Lesson $lesson;

    private function getFirstLessonAchievement() {
        $achievement_type = AchievementType::where('name', '=', 'lesson')->first();
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

        $this->lesson = Lesson::create([
            'title' => 'lesson 1',
        ]);
    }

    /**
     * Fire event AchievementUnlocked
     */
    public function test_listen_comment_written(): void
    {
        Event::fake();

        $event = new LessonWatched($this->lesson, $this->user);
        $listener = new ListenLessonWatched();

        // Trigger event
        $listener->handle($event);

        $achievement_name_to_check = $this->getFirstLessonAchievement()->name;

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
