<?php

namespace Tests\Feature\API\Manage\Comments;

use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_should_return_all_comments()
    {
        $response = $this->getJson(route('api.manage.comments.index'), [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $response = $this->getJson(route('api.manage.comments.index'), [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);
    }
}
