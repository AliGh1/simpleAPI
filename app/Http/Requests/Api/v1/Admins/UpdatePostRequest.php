<?php

namespace App\Http\Requests\Api\v1\Admins;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('update-post', $this->route('post'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'body' => 'nullable|string',
            'categories' => 'nullable',
        ];
    }
}
