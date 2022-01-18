<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Resources\v1\PostCollection;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index(): CategoryCollection
    {
        $categories = Category::with('children')
            ->where('parent_id', 0)->paginate(15);

        return new CategoryCollection($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return PostCollection
     */
    public function show(Category $category): PostCollection
    {
        $posts = $category->posts()->paginate(15);
        return New PostCollection($posts);
    }

}
