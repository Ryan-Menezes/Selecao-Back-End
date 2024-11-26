<?php

namespace Tests\Feature\API\Manage\Comments;

use App\Models\Comment;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_should_create_a_new_comment()
    {
        $data = [
            'content' => 'Test',
        ];

        $response = $this->postJson(route('api.manage.comments.store'), $data, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $comment = Comment::query()->first();

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'data' => [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ],
            ]);

        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'user_id' => $comment->user_id,
            'content' => $comment->content,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
        ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $this->postJson(route('api.manage.comments.store'), [], [
            'Authorization' => 'Bearer invalid-token',
        ])
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_content_should_be_required()
    {
        $this->postJson(route('api.manage.comments.store'), [], [
            'Authorization' => "Bearer {$this->token}"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['content' => __('validation.required', ['attribute' => 'content'])]);

        $this->assertDatabaseCount('comments', 0);
    }
}
