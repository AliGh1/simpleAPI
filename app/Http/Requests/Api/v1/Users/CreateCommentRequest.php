<?php

namespace App\Http\Requests\Api\v1\Users;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'parent_id' => 'nullable|int|exists:comments,id',
            'post_id' => 'required|int|exists:posts,id',
        ];
    }

    /**
     * Check Parent_id is acceptable
     * Check if parent_Id exist comment1.post_id == comment2.post_id
     *
     * @return bool
     */
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
