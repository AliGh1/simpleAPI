<?php

namespace App\Http\Controllers\Api\v1\Admins;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\PostCollection;
use App\Models\Post;
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
    public function index()
    {
        // Admin posts
        $posts = auth()->user()->posts()->paginate(15);
        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'title' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'body' => 'required|string',
            'categories' => 'required',
        ]);

        //Upload and Resize Image
        $validData = Post::uploadImage($request,$validData,600,400);

        DB::transaction(function () use ($validData) {
            $post = auth()->user()->posts()->create($validData);
            $post->categories()->sync($validData['categories']);
        });

        return response()->success('Post Created Successfully', Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        // Authorization
        if (Gate::denies('update-post', $post)) {
            return response()->error('403 Forbidden', Response::HTTP_FORBIDDEN);
        }

        $validData = $request->validate([
            'title' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'body' => 'nullable|string',
            'categories' => 'nullable',
        ]);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
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
