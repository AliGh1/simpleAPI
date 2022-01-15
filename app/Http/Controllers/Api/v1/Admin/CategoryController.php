<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Resources\v1\PostCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\v1\Category as CategoryResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $categories = Category::paginate(15);
        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if($request->parent_id) {
            $request->validate([
                'parent_id' => 'exists:categories,id'
            ]);
        }

        $request->validate([
            'name' => 'required|min:3|string'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? 0
        ]);

        return Response::json([
            'message' => 'Category Created Successfully',
            'status' => 'success'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return PostCollection
     */
    public function show(Category $category)
    {
        $posts = $category->posts()->paginate(15);
        return New PostCollection($posts);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        //check parent_id
        if($request->parent_id) {
            $request->validate([
                'parent_id' => 'exists:categories,id'
            ]);
        }

        //Avoid becoming your own father
        if ($request->parent_id == $category->id)
            $request->parent_id = 0;

        $request->validate([
            'name' => 'required|min:3|string'
        ]);

        $category = $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? 0
        ]);

        return Response::json([
            'message' => 'Category Updated Successfully',
            'status' => 'success'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return Response::json([
            'message' => 'Category Deleted Successfully',
            'status' => 'success'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }
}