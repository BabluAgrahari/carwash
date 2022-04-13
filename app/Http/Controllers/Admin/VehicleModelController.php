<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\validation\Api\CreateVehicleModelRequest;
use Illuminate\Support\Facades\Validator;

class VehicleModelController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->vehicleModels()
            ->get();
    }


    public function create()
    {
        //
    }


    public function store(CreateVehicleModelRequest $request)
    {
        try {
        
            //Request is valid, create new vehicle model
            $vehicleModel = new VehicleModel();
            $data = [
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'user_id' => $this->user->id,
            ];

            foreach ($data as $key => $res) {
                $vehicleModel->$key = $res;
            }

            if ($vehicleModel->save()) {
                //vehicle Model created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle Model created successfully',
                    'data' => $vehicleModel
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
        $vehicleModel = VehicleModel::find($id);

        if (!$vehicleModel) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Vehicle Model not found.'
            ], 400);
        }

        return $vehicleModel;
    }

    public function edit(VehicleModel $vehicleModel)
    {
        //
    }


    public function update(CreateVehicleModelRequest  $request, $id)
    {
        try {
            //Validate data
            $vehicleModel = VehicleModel::find($id);

            //Request is valid, update vehicle Model
            $data = [
                'name' => $request->name,
                'brand_id' => $request->brand_id
            ];

            foreach ($data as $key => $res) {
                $vehicleModel->$key = $res;
            }
            //Vehicle Model updated, return success response
            if ($vehicleModel->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle Model updated successfully',
                    'data' => $vehicleModel
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


    public function destroy(VehicleModel $vehicleModel)
    {
        $vehicleModel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle Model deleted successfully'
        ], Response::HTTP_OK);
    }
}
