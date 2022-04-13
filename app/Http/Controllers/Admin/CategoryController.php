<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Symfony\Component\HttpFoundation\Response;
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

    // public function show(Request $request)
    // {
    //     $category = Category::find($id);

    //     if (!$category) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Sorry, Category not found.'
    //         ], 400);
    //     }

    //     return $category;
    // }

    public function edit(Category $category)
    {
        //
    }


    public function update(CreateCategoryRequest  $request, $id)
    {
        try {
            //Validate data
            $category = Category::find($id);

            //Request is valid, update Category
            $data = [
                'name' => $request->name,
                'status' => (int)1,
                'is_trending' => (int)1,
            ];

            if (!empty($request->file('icon')))
                $data['icon']  = singleFile($request->file('icon'), 'icon/');

            foreach ($data as $key => $res) {
                $category->$key = $res;
            }
            //Category updated, return success response
            if ($category->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully',
                    'data' => $category
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Category not Updated!',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ], Response::HTTP_OK);
    }
}
