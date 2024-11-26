<?php

namespace Tests\Feature\API\Manage\Users;

use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_should_update_a_user()
    {
        $data = [
            'is_admin' => true,
            'name' => 'John Doe',
            'email' => 'john@mail.com',
            'password' => '12345678',
        ];

        $user = User::factory()->create();

        $response = $this->putJson(route('api.manage.users.update', $user), $data, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => $data['is_admin'],
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $user = User::factory()->create();

        $response = $this->putJson(route('api.manage.users.update', $user), [], [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_should_return_404_if_user_does_not_exist()
    {
        $response = $this->putJson(route('api.manage.users.update', 0), [
            'name' => 'John Doe',
            'email' => 'john@mail.com',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertNotFound()
            ->assertJsonFragment([
                'message' => 'Not found',
            ]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_name_should_be_required()
    {
        $user = User::factory()->create();

        $response = $this->putJson(route('api.manage.users.update', $user), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name' => __('validation.required', ['attribute' => 'name'])]);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_email_should_be_required()
    {
        $user = User::factory()->create();

        $response = $this->putJson(route('api.manage.users.update', $user), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.required', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_email_should_be_unique()
    {
        $user = User::factory()->create([
            'email' => 'test2@email.com',
        ]);

        User::factory()->create(['email' => 'test@email.com']);

        $response = $this->putJson(route('api.manage.users.update', $user), [
            'email' => 'test@email.com',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.unique', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 3);
    }

    public function test_should_return_403_if_the_user_is_not_an_admin()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->putJson(route('api.manage.users.update', $user), [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response
            ->assertForbidden()
            ->assertJsonFragment([
                'message' => 'This action is unauthorized.',
            ]);

        $this->assertDatabaseCount('users', 2);
    }
}
