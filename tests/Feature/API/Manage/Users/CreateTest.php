<?php

namespace Tests\Feature\API\Manage\Users;

use App\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_should_create_a_new_user()
    {
        $data = [
            'is_admin' => true,
            'name' => 'John Doe',
            'email' => 'john@mail.com',
            'password' => '12345678',
        ];

        $response = $this->postJson(route('api.manage.users.store'), $data, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $user = User::query()->where('email', $data['email'])->first();

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'data' => [
                    'id' => $user->id,
                    'is_admin' => $user->is_admin,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
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

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $this->postJson(route('api.manage.users.store'), [], [
            'Authorization' => 'Bearer invalid-token',
        ])
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_should_return_403_if_the_user_is_not_an_admin()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson(route('api.manage.users.store', $user), [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response
            ->assertForbidden()
            ->assertJsonFragment([
                'message' => 'This action is unauthorized.',
            ]);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_name_should_be_required()
    {
        $this->postJson(route('api.manage.users.store'), [], [
            'Authorization' => "Bearer {$this->token}"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name' => __('validation.required', ['attribute' => 'name'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_required()
    {
        $this->postJson(route('api.manage.users.store'), [], [
            'Authorization' => "Bearer {$this->token}"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.required', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_valid()
    {
        $this->postJson(route('api.manage.users.store'), [
            'email' => 'invalid-email',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.email', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_unique()
    {
        User::factory()->create(['email' => 'test@email.com']);

        $this->postJson(route('api.manage.users.store'), [
            'email' => 'test@email.com',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.unique', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_password_should_be_required()
    {
        $this->postJson(route('api.manage.users.store'), [], [
            'Authorization' => "Bearer {$this->token}"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password' => __('validation.required', ['attribute' => 'password'])]);

        $this->assertDatabaseCount('users', 1);
    }
}
