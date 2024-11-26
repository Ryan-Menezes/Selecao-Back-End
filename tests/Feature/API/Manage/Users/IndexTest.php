<?php

namespace Tests\Feature\API\Manage\Users;

use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_should_return_all_users()
    {
        $response = $this->getJson(route('api.manage.users.index'), [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $response = $this->getJson(route('api.manage.users.index'), [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_should_return_403_if_the_user_is_not_an_admin()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->getJson(route('api.manage.users.index'), [
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
