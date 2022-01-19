<?php

namespace Tests\Feature\Api\v1\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    /**
     * User Can like a Likable.
     *
     * @return void
     */
    public function test_user_can_like_a_likeable()
    {
        $this->seed();

        $user = User::factory()->create();

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        $attributes  = [
            'likeable_id' => 1,
            'likeable_type' => 'App\Models\Post',
        ];

        $response = $this->postJson('/api/v1/like', $attributes, [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Post liked Successfully'
        ]);

        $response->assertOK();

        $this->assertDatabaseHas('likes', array_merge($attributes, [
            'user_id' => $user->id
        ]));
    }

    /**
     * User Can unlike a Likable.
     *
     * @return void
     */
    public function test_user_can_unlike_a_likeable()
    {
        $this->seed();

        $user = User::factory()->create();

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        $attributes  = [
            'likeable_id' => 1,
            'likeable_type' => 'App\Models\Post',
        ];

        $this->postJson('/api/v1/like', $attributes, [
            'Authorization' => "Bearer $token"
        ]);

        $response = $this->postJson('/api/v1/like', $attributes, [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Post unliked Successfully'
        ]);

        $response->assertOK();

        $this->assertDatabaseMissing('likes', array_merge($attributes, [
            'user_id' => $user->id
        ]));
    }
}
