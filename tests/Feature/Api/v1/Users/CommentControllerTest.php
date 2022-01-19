<?php

namespace Tests\Feature\Api\v1\Users;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    /**
     * User can create a comment.
     *
     * @return void
     */
    public function test_user_can_create_a_comment()
    {
        $this->seed();

        $user = User::factory()->create();

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        $attributes  = [
            'body' => $this->faker->text(50),
            'post_id' => 1,
        ];

        // create without parent_id
        $response = $this->postJson('/api/v1/comments', $attributes, [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Comment Created Successfully'
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('comments', array_merge($attributes, [
            'parent_id' => 0,
            'user_id' => $user->id
        ]));

        // create with parent_id
        $response = $this->postJson('/api/v1/comments', array_merge($attributes, ['parent_id' => 1]), [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Comment Created Successfully'
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('comments', array_merge($attributes, [
            'parent_id' => 1,
            'user_id' => $user->id
        ]));
    }


    /**
     * User can Update a comment.
     *
     * @return void
     */
    public function test_user_can_update_a_comment()
    {
        $this->seed();

        $user = User::factory()->create();

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        $attributes  = [
            'body' => $this->faker->text(50),
        ];

        $this->postJson('/api/v1/comments', array_merge($attributes, ['post_id' => 1]), [
            'Authorization' => "Bearer $token"
        ]);

        $response = $this->patchJson('/api/v1/comments/1', $attributes);

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Comment Updated Successfully'
        ]);

        $response->assertOK();

        $this->assertDatabaseHas('comments', array_merge($attributes, [
            'user_id' => $user->id,
            'post_id' => 1
        ]));
    }

    /**
     * User can Delete a comment.
     *
     * @return void
     */
    public function test_user_can_delete_a_comment()
    {
        $this->seed();

        $user = User::factory()->create();

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        $attributes  = [
            'body' => $this->faker->text(50),
            'post_id' => 1,
        ];

        $this->postJson('/api/v1/comments', $attributes, [
            'Authorization' => "Bearer $token"
        ]);

        $response = $this->deleteJson('/api/v1/comments/1');

        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Comment Deleted Successfully'
        ]);

        $response->assertOK();

        $this->assertDatabaseMissing('comments', array_merge($attributes, [
            'user_id' => $user->id,
            'post_id' => 1
        ]));
    }
}
