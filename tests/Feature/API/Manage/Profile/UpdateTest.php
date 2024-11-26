<?php

namespace Tests\Feature\API\Manage\Profile;

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

        $response = $this->putJson(route('api.manage.profile.update'), $data, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'is_admin' => $data['is_admin'],
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_should_return_401_if_bearer_token_is_invalid()
    {
        $response = $this->putJson(route('api.manage.profile.update'), [], [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_name_should_be_required()
    {
        $response = $this->putJson(route('api.manage.profile.update'), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name' => __('validation.required', ['attribute' => 'name'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_required()
    {
        $response = $this->putJson(route('api.manage.profile.update'), [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.required', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_email_should_be_unique()
    {
        User::factory()->create(['email' => 'test@email.com']);

        $response = $this->putJson(route('api.manage.profile.update'), [
            'email' => 'test@email.com',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => __('validation.unique', ['attribute' => 'email'])]);

        $this->assertDatabaseCount('users', 2);
    }
}
