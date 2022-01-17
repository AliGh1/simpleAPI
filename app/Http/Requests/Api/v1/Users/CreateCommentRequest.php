<?php

namespace App\Http\Requests\Api\v1\Users;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string',
            'parent_id' => 'nullable|int|exists:comments,id',
            'post_id' => 'required|int|exists:posts,id',
        ];
    }

    // Check Parent_id is acceptable
    // check if parent_Id exist comment1.post_id == comment2.post_id
    public function isSafeParentId(): bool
    {
        if(isset($this['parent_id'])){
            if($this['post_id'] != Comment::find($this['parent_id'])->post_id) {
                return false;
            }
        }
        return true;
    }
}
