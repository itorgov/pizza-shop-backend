<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_login_with_valid_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'email' => 'john@example.com',
            ],
        ]);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function guest_can_login_with_valid_credentials_and_remember_it()
    {
        $user = factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password',
            'remember' => '1',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'email' => 'john@example.com',
            ],
        ]);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function guest_cannot_login_without_email()
    {
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_login_with_invalid_email()
    {
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_login_without_password()
    {
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password',
            ],
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_login_with_wrong_email()
    {
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'mike@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_login_with_wrong_password()
    {
        factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_login_with_phone_instead_email()
    {
        factory(User::class)->create([
            'email' => 'john@example.com',
            'phone' => '+79991112233',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $response = $this->postJson('/auth/login', [
            'phone' => '+79991112233',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_cannot_login()
    {
        $userA = factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        factory(User::class)->create([
            'email' => 'mike@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        Auth::login($userA);

        $response = $this->postJson('/auth/login', [
            'email' => 'mike@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
        $this->assertAuthenticatedAs($userA);
    }
}
