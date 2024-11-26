<?php

namespace Tests\Feature\API\Manage\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_should_create_a_new_user()
    {
        $data = [
            'is_admin' => true,
            'name' => 'John Doe',
            'email' => 'john@mail.com',
            'password' => '12345678',
        ];

        $response = $this->postJson(route('api.manage.auth.register'), $data);

        $user = User::query()->where('email', $data['email'])->first();
        $token = $response->json('token');

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'token' => $token,
            ]);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => $user->is_admin,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    public function test_name_should_be_required()
    {
        $this->postJson(route('api.manage.auth.register'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name' => __('validation.required', ['attribute' => 'name'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_required()
    {
        $this->postJson(route('api.manage.auth.register'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.required', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_valid()
    {
        $this->postJson(route('api.manage.auth.register'), [
            'email' => 'invalid-email',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.email', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_unique()
    {
        User::factory()->create(['email' => 'test@email.com']);

        $this->postJson(route('api.manage.auth.register'), [
            'email' => 'test@email.com',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.unique', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_password_should_be_required()
    {
        $this->postJson(route('api.manage.auth.register'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password' => __('validation.required', ['attribute' => 'password'])]);

        $this->assertDatabaseCount('users', 1);
    }
}
