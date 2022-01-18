<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Users\CreateCommentRequest;
use App\Http\Requests\Api\v1\Users\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use function auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCommentRequest $request
     * @return JsonResponse
     */
    public function store(CreateCommentRequest $request): JsonResponse
    {
        // Check Parent_id is acceptable
        // check if parent_Id exist comment1.post_id == comment2.post_id
        if (! $request->isSafeParentId())
            return response()->error("The comment parent can't be from another post", Response::HTTP_BAD_REQUEST);

        $validData = $request->all();

        DB::transaction(function () use ($validData) {
            $comment = auth()->user()->comments()->create($validData);

            // Update posts.comment_count
            $post = $comment->post()->firstOrFail();
            $post->update([
                'comments_count' => ++$post->comments_count
            ]);
        });


        return response()->success('Comment Created Successfully', Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->all());

        return response()->success('Comment Updated Successfully', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment)
    {
        // Authorization
        if (Gate::denies('delete-comment', $comment)) {
            return response()->error('403 Forbidden', Response::HTTP_FORBIDDEN);
        }

        DB::transaction(function () use ($comment) {
            // Update posts.comment_count
            $post = $comment->post()->firstOrFail();
            $post->update([
                'comments_count' => --$post->comments_count
            ]);

            $comment->delete();
        });

        return response()->success('Comment Deleted Successfully', Response::HTTP_OK);
    }
}
