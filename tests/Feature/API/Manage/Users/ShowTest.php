<?php

namespace Tests\Feature\API\Manage\Users;

use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function test_should_return_a_user_by_id()
    {
        $user = User::factory()->create();

        $response = $this->getJson(route('api.manage.users.show', $user), [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
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
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $user = User::factory()->create();

        $response = $this->getJson(route('api.manage.users.show', $user), [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_should_return_404_if_user_does_not_exist()
    {
        $response = $this->getJson(route('api.manage.users.show', 0), [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertNotFound()
            ->assertJsonFragment([
                'message' => 'Not found',
            ]);
    }

    public function test_should_return_403_if_the_user_is_not_an_admin()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->getJson(route('api.manage.users.show', $user), [
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
