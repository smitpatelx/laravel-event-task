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

class ListenAchievementUnlockedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
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

        $achievement = Achievement::orderBy('level', 'desc')->first();
        $this->achievement_name = $achievement->name;
    }

    /**
     * A basic test example.
     */
    public function test_listen_achievement_unlocked(): void
    {
        Event::fake();

        $event = new AchievementUnlocked($this->achievement_name, $this->user);
        $listener = new ListenAchievementUnlocked();

        // Trigger event
        $listener->handle($event);

        Event::assertDispatched(BadgeUnlocked::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
