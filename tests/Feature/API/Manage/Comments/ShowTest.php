<?php

namespace Tests\Feature\API\Manage\Comments;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function test_should_return_a_comment_by_id()
    {
        $comment = Comment::factory()->create();

        $response = $this->getJson(route('api.manage.comments.show', $comment), [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'data' => [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ],
            ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $comment = Comment::factory()->create();

        $response = $this->getJson(route('api.manage.comments.show', $comment), [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_should_return_404_if_comment_does_not_exist()
    {
        $response = $this->getJson(route('api.manage.comments.show', 0), [
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

        $comment = Comment::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->getJson(route('api.manage.comments.show', $comment), [
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
