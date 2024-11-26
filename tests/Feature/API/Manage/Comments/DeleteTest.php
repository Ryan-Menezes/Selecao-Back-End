<?php

namespace Tests\Feature\API\Manage\Comments;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    public function test_should_delete_a_comment_by_id()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson(route('api.manage.comments.destroy', $comment), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Comment deleted successfully',
            ]);

        $this->assertDatabaseCount('comments', 0);
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson(route('api.manage.comments.destroy', $comment), [], [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);

        $this->assertDatabaseCount('comments', 1);
    }

    public function test_should_return_404_if_comment_does_not_exist()
    {
        $response = $this->deleteJson(route('api.manage.comments.destroy', 0), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertNotFound()
            ->assertJsonFragment([
                'message' => 'Not found',
            ]);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_should_return_403_if_the_user_is_not_an_admin()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $comment = Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->deleteJson(route('api.manage.comments.destroy', $comment), [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response
            ->assertForbidden()
            ->assertJsonFragment([
                'message' => 'This action is unauthorized.',
            ]);

        $this->assertDatabaseCount('comments', 1);
    }
}
