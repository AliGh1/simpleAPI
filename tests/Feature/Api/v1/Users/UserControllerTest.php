<?php

namespace Tests\Feature\Api\v1\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * Test User Can Register Successfully.
     *
     * @return void
     */
    public function test_user_register_successfuly()
    {
        $attributes  = [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/register', $attributes);

        $response->assertJsonFragment([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'status' => 'success',
            'message' => 'You have successfully registered'
        ]);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'token'
            ],
            'status',
            'message'
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => $attributes['name'],
            'email' => $attributes['email']
        ]);
    }

    /**
     * Test User Logged Successfully.
     *
     * @return void
     */
    public function test_user_login_successfuly()
    {
        $user = User::factory()->create();

        $attributes  = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/login', $attributes);

        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
            'status' => 'success',
            'message' => 'You have logged in successfully'
        ]);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'token'
            ],
            'status',
            'message'
        ]);

        $response->assertOk();
    }

    public function test_user_can_change_password_successfuly()
    {
        $user = User::factory()->create();

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        $attributes  = [
            'new_password' => '12345678',
            'password' => 'password',
        ];

        $response = $this->patchJson('/api/v1/change-password', $attributes, [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
            'status' => 'success',
            'message' => 'Password changed successfully'
        ]);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'token'
            ],
            'status',
            'message'
        ]);

        $response->assertOk();
    }
}
