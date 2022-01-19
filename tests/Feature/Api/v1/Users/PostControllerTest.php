<?php

namespace Tests\Feature\Api\v1\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    /**
     * User Can see lists of posts.
     *
     * @return void
     */
    public function test_user_can_see_lists_of_posts()
    {
        $this->seed();

        $response = $this->get('/api/v1/posts');

        $response->assertJsonFragment([
            'status' => 'success',
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    "title",
                    'image',
                    'body',
                    'likes_count',
                    'comments_count',
                    'created_at',
                    'updated_at',
                    'author' => [
                        'id',
                        'name'
                    ]
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active'
                    ]
                ],
                'path',
                'per_page',
                'to',
                'total'
            ],
            'status',
        ]);

        $response->assertOk();
    }

    /**
     * User Can see a post.
     *
     * @return void
     */
    public function test_user_can_see_a_post()
    {
        $this->seed();

        $response = $this->get('/api/v1/posts/1');

        $response->assertJsonFragment([
            'status' => 'success',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'title',
                'image',
                'body',
                'likes_count',
                'comments_count',
                'created_at',
                'updated_at',
                'author' => [
                    'id',
                    'name'
                ],
                'categories' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ],
            'status',
        ]);

        $response->assertOk();
    }
}
