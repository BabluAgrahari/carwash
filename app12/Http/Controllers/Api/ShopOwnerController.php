<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\ShopOwner;
use Illuminate\Http\Request;
use App\Http\Requests\validation\Api\CreateShopRequest;
use App\Http\Requests\validation\Api\UpdateShopRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class shopOwnerController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->shopOwners()
            ->get();
    }


    public function create()
    {
        //
    }


    public function store(CreateShopRequest $request)
    {
        try {

            //Request is valid, create new Shop Owner
            $shopOwner = new ShopOwner();
            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
                'phone_no' => $request->phone_no,
                'address'  => $request->address,
                'user_id'  => $this->user->id,
                'status'   => (int)1,
            ];

            foreach ($data as $key => $res) {
                $shopOwner->$key = $res;
            }

            if ($shopOwner->save()) {
                //Shop Owner created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Shop Owner created successfully',
                    'data' => $shopOwner
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Shop Owner not Created!',
            ], 400);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $shopOwner = ShopOwner::find($id);

        if (!$shopOwner) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Shop Owner not found.'
            ], 400);
        }

        return $shopOwner;
    }

    public function edit(ShopOwner $shopOwner)
    {
        //
    }


    public function update(UpdateShopRequest  $request, $id)
    {
        try {
            //Validate data
            $shopOwner = ShopOwner::find($id);

            //Request is valid, update Shop Owner
            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
                'phone_no' => $request->phone_no,
                'address'  => $request->address,
            ];

            foreach ($data as $key => $res) {
                $shopOwner->$key = $res;
            }
            //Shop Owner updated, return success response
            if ($shopOwner->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shop Owner updated successfully',
                    'data' => $shopOwner
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Shop Owner not Updated!',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function destroy(ShopOwner $ShopOwner)
    {
        $ShopOwner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shop Owner deleted successfully'
        ], Response::HTTP_OK);
    }
}
