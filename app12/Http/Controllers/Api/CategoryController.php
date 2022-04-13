<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\validation\Api\CreateCategoryRequest;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->categories()
            ->get();
    }


    public function create()
    {
        //
    }


    public function store(CreateCategoryRequest $request)
    {
        try {

            //Request is valid, create new Category
            $category = new Category();
            $data = [
                'name' => $request->name,
                'user_id' => $this->user->id,
                'status' => (int)1,
                'is_trending' => (int)1,
            ];

            if (!empty($request->file('icon')))
                $data['icon']  = singleFile($request->file('icon'), 'icon/');

            foreach ($data as $key => $res) {
                $category->$key = $res;
            }

            if ($category->save()) {
                //Category created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully',
                    'data' => $category
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Category not Created!',
            ], 400);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Category not found.'
            ], 400);
        }

        return $category;
    }

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
