<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\validation\Api\CreateVehicleBrandRequest;
use Illuminate\Support\Facades\Validator;

class VehicleBrandController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->vehicleBrands()
            ->get();
    }


    public function create()
    {
        //
    }


    public function store(CreateVehicleBrandRequest $request)
    {
        try {
           
            //Request is valid, create new vehicle Brand
            $vehicleBrand = new VehicleBrand();
            $data = [
                'name' => $request->name,
                'user_id' => $this->user->id,
                'status' => (int)1,
            ];

            if (!empty($request->file('icon')))
                $data['icon']  = singleFile($request->file('icon'), 'icon/');

            foreach ($data as $key => $res) {
                $vehicleBrand->$key = $res;
            }

            if ($vehicleBrand->save()) {
                //vehicle Brand created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle Brand created successfully',
                    'data' => $vehicleBrand
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Vehicle Brand not Created!',
            ], 400);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $vehicleBrand = VehicleBrand::find($id);

        if (!$vehicleBrand) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Vehicle Brand not found.'
            ], 400);
        }

        return $vehicleBrand;
    }

    public function edit(VehicleBrand $vehicleBrand)
    {
        //
    }


    public function update(CreateVehicleBrandRequest  $request, $id)
    {
        try {
            //Validate data
            $vehicleBrand = VehicleBrand::find($id);

            //Request is valid, update vehicle Brand
            $data = [
                'name' => $request->name,
                'status' => (int)1,
            ];

            if (!empty($request->file('icon')))
                $data['icon']  = singleFile($request->file('icon'), 'icon/');

            foreach ($data as $key => $res) {
                $vehicleBrand->$key = $res;
            }
            //Vehicle Brand updated, return success response
            if ($vehicleBrand->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle Brand updated successfully',
                    'data' => $vehicleBrand
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Vehicle Brand not Updated!',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function destroy(VehicleBrand $vehicleBrand)
    {
        $vehicleBrand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle Brand deleted successfully'
        ], Response::HTTP_OK);
    }
}
