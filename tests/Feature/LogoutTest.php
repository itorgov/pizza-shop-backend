<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = factory(User::class)->create();
        Auth::login($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->postJson('/auth/logout');

        $response->assertStatus(204);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_logout()
    {
        factory(User::class)->create();
        $this->assertGuest();

        $response = $this->postJson('/auth/logout');

        $response->assertStatus(401);
        $this->assertGuest();
    }
}
