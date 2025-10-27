<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_form_displays_register_view(): void
    {
        $response = $this->get(route('register.form'));

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    /** @test */
    public function register_method_creates_user_and_redirects(): void
    {
        $this->withoutMiddleware(); // ігноруємо CSRF

        $userData = [
            'username' => 'TestUser',
            'phone' => '380991234567',
        ];

        $response = $this->post(route('register.submit'), $userData);

        $user = User::first();

        $this->assertNotNull($user);
        $this->assertEquals('TestUser', $user->username);
        $this->assertEquals('380991234567', $user->phone);
        $this->assertTrue($user->active);
        $this->assertTrue($user->expires_at->isFuture());
        $this->assertNotEmpty($user->token);

        $response->assertRedirect(route('lucky.page', ['token' => $user->token]));
    }

    /** @test */
    public function register_method_returns_validation_error_for_missing_fields(): void
    {
        $this->withoutMiddleware();

        $userData = [
            'username' => '',
            'phone' => '380991234567',
        ];

        $response = $this->from(route('register.form'))
            ->post(route('register.submit'), $userData);

        $response->assertRedirect(route('register.form'));
        $response->assertSessionHasErrors(['username']);

        $this->assertDatabaseCount('users', 0);
    }
}
