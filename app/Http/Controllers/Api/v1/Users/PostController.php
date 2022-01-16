<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Post as PostResponse;
use App\Http\Resources\v1\PostCollection;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PostCollection
     */
    public function index()
    {
        $posts = Post::paginate(15);
        return new PostCollection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResponse
     */
    public function show(Post $post)
    {
        return new PostResponse($post->load('comments'));
    }
}
