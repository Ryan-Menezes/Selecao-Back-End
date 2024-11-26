<?php

namespace Tests\Feature\API\Manage\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_should_return_200_if_login_is_valid()
    {
        $user = User::factory()->create([
            'email' => 'test@email.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson(route('api.manage.auth.login'), [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function test_should_return_error_401_if_login_is_invalid()
    {
        $email = 'test@email.com';
        $password = 'invalid';

        $response = $this->postJson(route('api.manage.auth.login'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_email_should_be_required()
    {
        $this->postJson(route('api.manage.auth.login'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.required', ['attribute' => 'email'])]);
    }

    public function test_password_should_be_required()
    {
        $this->postJson(route('api.manage.auth.login'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password' => __('validation.required', ['attribute' => 'password'])]);
    }
}
