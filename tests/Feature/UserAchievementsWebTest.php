<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\ListenAchievementUnlocked;
use App\Listeners\ListenBadgeUnlocked;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserAchievementsWebTest extends TestCase
{
    use RefreshDatabase;

    private $data_to_compare;
    private object $achievements;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->achievements = Achievement::orderBy('level', 'desc')->get();

        $this->data_to_compare = [
            'unlocked_achievements' => [],
            'next_available_achievements' => [
                '50 Lessons Watched',
                '25 Lessons Watched',
                '20 Comments Written',
                '10 Lessons Watched',
                '10 Comments Written',
                '5 Lessons Watched',
                '5 Comments Written',
                '3 Comments Written',
                'First Lesson Watched',
                'First Comment Written'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 4,
        ];
    }

    /**
     * Test the response from /users/{user}/achievements api route.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson($this->data_to_compare);
        $response->assertJsonIsObject();
        $response->assertStatus(200);
    }

    /**
     * Fire event AchievementUnlocked
     */
    public function fire_event_achievement_unlocked(
        string $achievement_name,
        User $user
    ): void
    {
        Event::fake();

        // Unlock achievements
        $event = new AchievementUnlocked($achievement_name, $user);
        $listener = new ListenAchievementUnlocked();

        // Trigger event
        $current_badge = $listener->handle($event);

        Event::assertDispatched(BadgeUnlocked::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        // Unlock badge if current_badge is not NULL
        if (is_null($current_badge)) {
            return;
        }

        Event::fake();

        $event = new BadgeUnlocked($current_badge->name, $user);
        $listener = new ListenBadgeUnlocked();

        // Trigger event
        $listener->handle($event);
    }

    /**
     * Test achievement unlocked listener after unlocking all achievements
     */
    public function test_achievement_response(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'xyz@cmail.com',
            'password' => 'testpassword',
        ]);

        foreach ($this->achievements as $achievement) {
            $this->fire_event_achievement_unlocked($achievement->name, $user);
        }

        $response = $this->get("/users/{$user->id}/achievements");

        // All achievements are unlocked
        $new_compare_data = [
            'unlocked_achievements' => [
                '50 Lessons Watched',
                '25 Lessons Watched',
                '20 Comments Written',
                '10 Lessons Watched',
                '10 Comments Written',
                '5 Lessons Watched',
                '5 Comments Written',
                '3 Comments Written',
                'First Lesson Watched',
                'First Comment Written'
            ],
            'next_available_achievements' => [],
            'current_badge' => 'Master',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0,
        ];

        $response->assertJson($new_compare_data);
        $response->assertJsonIsObject();
        $response->assertStatus(200);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
