<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAchievementsWebTest extends TestCase
{
    use RefreshDatabase;

    private $data_to_compare;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

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
}
