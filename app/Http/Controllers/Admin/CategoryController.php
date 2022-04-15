<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Http\Requests\Validation\Admin\CreateCategory;
use Exception;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $list = Category::get();
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(CreateCategory $request)
    {
        try {
            $category = new Category();
            $category->user_id = Auth::user()->_id;
            $category->name = $request->name;
            $category->status = $request->status;

            if (!empty($request->file('icon')))
                $category->icon  = singleFile($request->file('icon'), 'icon/');

            if ($category->save())
                return response(['status' => 'success', 'message' => 'Category created Successfully!']);

            return response(['status' => 'error', 'message' => 'Category not created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        try {
            $list = Category::find($id);
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(CreateCategory  $request, $id)
    {
        try {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->status = $request->status;

            if (!empty($request->file('icon')))
                $category->icon  = singleFile($request->file('icon'), 'icon/');

            if ($category->save())
                return response(['status' => 'success', 'message' => 'Category updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Category not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function destroy(Category $category)
    {
        if($category->delete())
        return response(['status' => 'success', 'message' => 'Category deleted Successfully!']);

         return response(['status' => 'error', 'message' => 'Category not deleted!']);
    }
}
