<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\ListenAchievementUnlocked;
use App\Models\Achievement;

class AchievementUnlockedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private object $achievements;
    private object $achievements_offset;
    private string $achievement_name;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::create([
            'name' => 'test user',
            'email' => 'test@cmail.com',
            'password' => 'testpassword',
        ]);

        $this->achievements_offset = Achievement::orderBy('level', 'desc')->offset(1)->limit(8)->get();
        $this->achievements = Achievement::orderBy('level', 'desc')->get();
        $this->achievement_name = Achievement::orderBy('level', 'desc')->first()->name;
    }

    /**
     * Fire event AchievementUnlocked
     */
    public function fire_event_achievement_unlocked(
        string $achievement_name,
        User $user,
    ): void
    {
        Event::fake();

        $event = new AchievementUnlocked($achievement_name, $user);
        $listener = new ListenAchievementUnlocked();

        // Trigger event
        $listener->handle($event);

        Event::assertDispatched(BadgeUnlocked::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /**
     * Test achievement unlocked listener
     */
    public function test_achievement_unlocked(): void
    {
        // Fire event for all achievements except the last one
        foreach ($this->achievements_offset as $achievement) {
            $this->fire_event_achievement_unlocked(
                $achievement->name,
                $this->user,
            );
        }

        Event::fake();

        $event = new AchievementUnlocked($this->achievement_name, $this->user);
        $listener = new ListenAchievementUnlocked();

        // Trigger event
        $listener->handle($event);

        // Check if event BadgeUnlocked is dispatched
        Event::assertDispatched(BadgeUnlocked::class, function ($e) {
            return $e->user->id === $this->user->id &&
                $e->badge_name === 'Master';
        });
    }

    /**
     * Test achievement unlocked listener
     * Expected: Duplicate Key Error
     */
    public function test_achievement_unlocked_duplicate(): void
    {
        // Fire event for all achievements except the last one
        foreach ($this->achievements as $achievement) {
            $this->fire_event_achievement_unlocked(
                $achievement->name,
                $this->user,
            );
        }

        Event::fake();

        $event = new AchievementUnlocked($this->achievement_name, $this->user);
        $listener = new ListenAchievementUnlocked();

        // Trigger event
        $listener->handle($event);

        // Check if event BadgeUnlocked is dispatched, must not
        Event::assertNotDispatched(BadgeUnlocked::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
