<?php

namespace App\Http\Requests\Api\v1\Admins;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'title' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'body' => 'required|string',
            'categories' => 'required',
        ];
    }
}
