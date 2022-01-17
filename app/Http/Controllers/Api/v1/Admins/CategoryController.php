<?php

namespace App\Http\Controllers\Api\v1\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Admins\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? 0
        ]);

        return response()->success('Category Created Successfully', Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        //Avoid becoming your own father
        $request->safeParentId($category);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? 0
        ]);

        return response()->success('Category Updated Successfully', Response::HTTP_OK);
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

        return response()->success('Category Deleted Successfully', Response::HTTP_OK);
    }
}
