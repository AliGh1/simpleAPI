<?php

namespace App\Http\Controllers\Api\v1\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Admins\CreatePostRequest;
use App\Http\Requests\Api\v1\Admins\UpdatePostRequest;
use App\Http\Resources\v1\PostCollection;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        // Admin posts
        $posts = auth()->user()->posts()->with('categories')->paginate(15);
        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    public function store(CreatePostRequest $request): JsonResponse
    {
        $validData = $request->all();

        //Upload and Resize Image
        $validData = Post::uploadImage($request, $validData,600,400);

        DB::transaction(function () use ($validData) {
            $post = auth()->user()->posts()->create($validData);
            $post->categories()->sync($validData['categories']);
        });

        return response()->success('Post Created Successfully', Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $validData = $request->all();

        // Check if there are new image
        if(isset($validData['image'])){

            // Delete outdated Image
            if(\File::exists(public_path($post->image)))
                \File::delete(public_path($post->image));

            //Upload and Resize Image
            $validData = Post::uploadImage($request,$validData,600,400);
        }
        DB::transaction(function () use ($post, $validData) {
            $post->update($validData);

            if(isset($validData['categories'])){
                // check if there are categories
                $post->categories()->sync($validData['categories']);
            }
        });


        return response()->success('Post Updated Successfully', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        // Authorization
        if (Gate::denies('delete-post', $post)) {
            return response()->error('403 Forbidden', Response::HTTP_FORBIDDEN);
        }

        if(\File::exists(public_path($post->image)))
            \File::delete(public_path($post->image));

        $post->delete();

        return response()->success('Post Deleted Successfully', Response::HTTP_OK);
    }
}
