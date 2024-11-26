<?php

namespace Tests\Feature\API\Manage\Users;

use App\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    public function test_should_delete_a_user_by_id()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('api.manage.users.destroy', $user), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'User deleted successfully',
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('api.manage.users.destroy', $user), [], [
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
        $response = $this->deleteJson(route('api.manage.users.destroy', 0), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertNotFound()
            ->assertJsonFragment([
                'message' => 'Not found',
            ]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_should_return_403_if_the_user_is_not_an_admin()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->deleteJson(route('api.manage.users.destroy', $user), [], [
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
