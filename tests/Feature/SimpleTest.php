<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $name = "John Doe";
    private $email = "xyz@testmail.com";
    private $password = "secret2023@";

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }

    /**
     * A basic test example.
     */
    public function test_simple_test_to_check_user_creation(): void
    {
        // Compare name and email from database
        $this->assertEquals($this->name, $this->user->name);
        $this->assertEquals($this->email, $this->user->email);
        // Compare plaintext password with hash from database
        $this->assertTrue(Hash::check($this->password, $this->user->password));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
