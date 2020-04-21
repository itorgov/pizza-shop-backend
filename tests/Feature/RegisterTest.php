<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function validParams(array $overrides = [])
    {
        return array_merge([
            'name' => 'John',
            'email' => 'john@example.com',
            'phone' => '+79991112233',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ], $overrides);
    }

    /** @test */
    public function guests_can_register_with_valid_data()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'phone' => '+79991112233',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'John',
                'email' => 'john@example.com',
                'phone' => '+79991112233',
            ],
        ]);
        /** @var User $user */
        $user = User::query()->first();
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function guests_can_register_with_valid_data_without_phone()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'John',
                'email' => 'john@example.com',
                'phone' => null,
            ],
        ]);
        /** @var User $user */
        $user = User::query()->first();
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function guests_can_login_after_registration()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', $this->validParams([
            'email' => 'john@example.com',
            'password' => 'secret',
        ]));

        $response->assertStatus(201);
        /** @var User $user */
        $user = User::query()->first();
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });

        Auth::logout();
        $this->assertGuest();

        $response = $this->postJson('/auth/login', [
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function guests_cannot_register_without_name()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function guests_cannot_register_without_email()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', $this->validParams([
            'email' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function guests_cannot_register_with_invalid_email()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', $this->validParams([
            'email' => 'invalid-email',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function guests_cannot_register_with_non_unique_email()
    {
        Event::fake();
        factory(User::class)->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->postJson('/auth/register', $this->validParams([
            'email' => 'john@example.com',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function guests_cannot_register_with_non_unique_phone()
    {
        Event::fake();
        factory(User::class)->create([
            'phone' => '+79991112233',
        ]);

        $response = $this->postJson('/auth/register', $this->validParams([
            'phone' => '+79991112233',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'phone',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function guests_cannot_register_without_password_confirmation()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', $this->validParams([
            'password_confirmation' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function guests_cannot_register_with_invalid_password_confirmation()
    {
        Event::fake();

        $response = $this->postJson('/auth/register', $this->validParams([
            'password' => 'secret',
            'password_confirmation' => 'not-secret',
        ]));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password',
            ],
        ]);
        $this->assertGuest();
        Event::assertNotDispatched(Registered::class);
    }

    /** @test */
    public function authenticated_user_cannot_register()
    {
        Event::fake();
        $user = factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        Auth::login($user);

        $response = $this->postJson('/auth/register', $this->validParams());

        $response->assertStatus(403);
        $this->assertAuthenticatedAs($user);
        Event::assertNotDispatched(Registered::class);
    }
}
