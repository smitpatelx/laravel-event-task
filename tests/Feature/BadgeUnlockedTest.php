<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use App\Events\BadgeUnlocked;
use App\Listeners\ListenBadgeUnlocked;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class BadgeUnlockedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private object $badges;
    private object $badges_offset;
    private string $badge_name;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::create([
            'name' => 'test user',
            'email' => 'test@cmail.com',
            'password' => 'testpassword',
        ]);

        $sorted_badges_a = Badge::orderBy('no_of_achievement', 'asc');

        $this->badges_offset = $sorted_badges_a->offset(1)->limit(4)->get();
        $this->badges = $sorted_badges_a->get();
        $this->badge_name = $sorted_badges_a->first()->name;
    }

    /**
     * Assert badge unlocked
     */
    private function assert_badge_unlocked(
        string $badge_name,
        User $user,
    ): void
    {
        $badge = Badge::where('name', $badge_name)->first();
        $user = User::find($user->id)->first();

        $this->assertEquals($badge->id, $user->badge->id);
    }

    /**
     * Fire event AchievementUnlocked
     */
    public function fire_event_badge_unlocked(
        string $badge_name,
        User $user,
    ): void
    {
        Event::fake();

        $event = new BadgeUnlocked($badge_name, $user);
        $listener = new ListenBadgeUnlocked();

        // Trigger event
        $listener->handle($event);

        // Assert assert_badge_unlocked
        $this->assert_badge_unlocked($badge_name, $user);
    }

    /**
     * Test badge unlocked listener
     */
    public function test_badge_unlocked(): void
    {
        // Fire event for all badges except the last one
        foreach ($this->badges_offset as $badge) {
            $this->fire_event_badge_unlocked(
                $badge->name,
                $this->user,
            );
        }

        $this->fire_event_badge_unlocked(
            $this->badge_name,
            $this->user,
        );
    }

    /**
     * Test badge unlocked listener
     * Fire duplicate event
     * Expected: No error, as we are just replacing the badge_id
     */
    public function test_badge_unlocked_duplicate(): void
    {
        // Fire event for all badges except the last one
        foreach ($this->badges as $badge) {
            $this->fire_event_badge_unlocked(
                $badge->name,
                $this->user,
            );
        }

        Event::fake();

        $event = new BadgeUnlocked($this->badge_name, $this->user);
        $listener = new ListenBadgeUnlocked();

        // Trigger event
        $listener->handle($event);

        $this->assert_badge_unlocked($this->badge_name, $this->user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
