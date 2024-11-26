<?php

namespace Tests\Feature\API\Manage\Comments;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_should_update_a_comment()
    {
        $data = [
            'content' => 'Test',
        ];

        $comment = Comment::factory()->create();

        $response = $this->putJson(route('api.manage.comments.update', $comment), $data, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk();

        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => $data['content'],
        ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $comment = Comment::factory()->create();

        $response = $this->putJson(route('api.manage.comments.update', $comment), [], [
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
        $response = $this->putJson(route('api.manage.comments.update', 0), [
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

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_content_should_be_required()
    {
        $comment = Comment::factory()->create();

        $response = $this->putJson(route('api.manage.comments.update', $comment), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['content' => __('validation.required', ['attribute' => 'content'])]);

        $this->assertDatabaseCount('comments', 1);
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

        $response = $this->putJson(route('api.manage.comments.update', $comment), [], [
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
