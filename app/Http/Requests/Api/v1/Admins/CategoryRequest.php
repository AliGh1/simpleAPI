<?php

namespace App\Http\Requests\Api\v1\Admins;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|min:3|string'
        ];
    }


    /**
     * Avoid becoming your own father
     *
     * @param $category
     * @return void
     */
    public function safeParentId($category){
        if ($this->parent_id == $category->id)
            $this->parent_id = 0;
    }
}
