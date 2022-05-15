<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validation\Admin\Category\Create;
use App\Models\Admin\Category;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $lists = Category::desc()->get();

           if($lists->isEmpty())
                  return response(['status' =>'error', 'message' =>"no found any record."]);


            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'          =>$list->_id,
             'user_id'      =>$list->user_id,
             'name'         =>$list->name,
             'status'       =>$list->isActive($list->status),
             'icon'         =>(!empty($list->icon))?asset('icon/'.$list->icon):'',
             'created'      =>$list->dFormat($list->created),
             'updated'      =>$list->dFormat($list->updated)
             ];
             }

            return response(['status' =>'success', 'data' => $records]);

        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(Create $request)
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
            $record = [
              'name'   =>$list->name,
              'status' =>$list->status,
              'icon'   =>(!empty($list->icon))?asset('icon/'.$list->icon):'',
              'created'=>$list->dFormat($list->created),
              'updated'=>$list->dFormat($list->updated)
            ];
            return response(['status' => true, 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Create $request, $id)
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
