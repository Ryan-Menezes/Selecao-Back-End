<?php

namespace Tests\Feature\API\Manage\Profile;

use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_should_return_the_current_user_authenticated()
    {
        $response = $this->getJson(route('api.manage.profile.me'), [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'data' => [
                    'id' => $this->user->id,
                    'is_admin' => $this->user->is_admin,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'created_at' => $this->user->created_at,
                    'updated_at' => $this->user->updated_at,
                ],
            ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $response = $this->getJson(route('api.manage.profile.me'), [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);
    }
}
