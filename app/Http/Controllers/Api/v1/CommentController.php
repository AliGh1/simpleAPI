<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'body' => 'required|string',
            'parent_id' => 'nullable|int|exists:comments,id',
            'post_id' => 'required|int|exists:posts,id',
        ]);

        // Check Parent_id is acceptable
        // check if parent_Id exist comment1.post_id == comment2.post_id
        if(isset($validData['parent_id'])){
            if($validData['post_id'] != Comment::find($validData['parent_id'])->post_id){
                return Response::json([
                    'message' => "The comment parent can't be from another post",
                    'status' => 'error'
                ], \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
            }
        }

        auth()->user()->comments()->create($validData);
        $post = Post::find($validData['post_id']);
        $post->update([
            'comments_count' => ++$post->comments_count
        ]);

        return Response::json([
            'message' => 'Comment Created Successfully',
            'status' => 'success'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $validData = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($validData);

        return Response::json([
            'message' => 'Comment Updated Successfully',
            'status' => 'success'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $post = Post::find($comment->post_id);
        $post->update([
            'comments_count' => --$post->comments_count
        ]);

        $comment->delete();

        return Response::json([
            'message' => 'Comment Deleted Successfully',
            'status' => 'success'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }
}
