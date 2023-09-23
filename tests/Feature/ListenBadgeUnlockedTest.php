<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\BadgeUnlocked;
use App\Listeners\ListenBadgeUnlocked;
use App\Models\Badge;

class ListenBadgeUnlockedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
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

        $badge = Badge::orderBy('no_of_achievement', 'asc')->first();
        $this->badge_name = $badge->name;
    }

    /**
     * A basic test example.
     */
    public function test_listen_badge_unlocked(): void
    {
        $event = new BadgeUnlocked($this->badge_name, $this->user);
        $listener = new ListenBadgeUnlocked();

        // Check if user has badge
        $user_badge = User::find($this->user->id)->first()->badge;
        $this->assertEquals(NULL, $user_badge);

        // Trigger event
        $listener->handle($event);

        // Check if user has badge after event
        $user_badge = User::find($this->user->id)->first()->badge->name;
        $this->assertEquals($this->badge_name, $user_badge);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
