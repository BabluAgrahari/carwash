<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ShopOwner;
use App\Http\Requests\validation\Admin\CreateShop;
use App\Http\Requests\validation\Admin\UpdateShop;
use Exception;
use Illuminate\Support\Facades\Auth;

class shopOwnerController extends Controller
{
    public function index()
    {
        try {
            $lists = ShopOwner::get();
            if($lists->isEmpty())
                  return response(['status' =>'error', 'message' =>"no found any record."]);


            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'          =>$list->_id,
             'name'         =>$list->name,
             'email'        =>$list->email,
             'phone_no'     =>$list->phone_no,
             'address'      =>$list->address,   
             'status'       =>$list->isActive($list->status),
             'created'      =>$list->dFormat($list->created),
             'updated'      =>$list->dFormat($list->updated)
             ];
             }

            return response(['status' =>'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function store(CreateShop $request)
    {
        try {
            $shopOwner = new ShopOwner();
            $shopOwner->user_id  = Auth::user()->_id;
            $shopOwner->name     = $request->name;
            $shopOwner->email    = $request->email;
            $shopOwner->phone_no = $request->phone_no;
            $shopOwner->address  = $request->address;
            $shopOwner->status   = $request->status;

            if ($shopOwner->save())
                return response(['status' => 'success', 'message' => 'Shop Owner created successfully!']);

            return response(['status' => 'error', 'message' => 'Shop Owner not created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $list = ShopOwner::find($id);
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function update(UpdateShop  $request, $id)
    {
        try {
            $shopOwner = ShopOwner::find($id);
            $shopOwner->name      = $request->name;
            $shopOwner->email     = $request->email;
            $shopOwner->phone_no  = $request->phone_no;
            $shopOwner->address   = $request->address;
            $shopOwner->status    = $request->status;

            if ($shopOwner->save())
                return response(['status' => 'success', 'message' => 'Shop Owner updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Shop Owner not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy(ShopOwner $ShopOwner)
    {
        if ($ShopOwner->delete())
            return response(['status' => 'success', 'message' => 'Shop Owner deleted Successfully!']);

        return response(['status' => 'error', 'message' => 'Shop Owner not deleted!']);
    }
}
