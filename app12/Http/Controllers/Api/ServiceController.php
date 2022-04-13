<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Service;
use App\Models\Category;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Storage;
class ServiceController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        //
    }

    
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        try {
            //Validate data
            $data = $request->only('category', 'vehicle_brand', 'vehicle_model', 'service_type', 'service_tittle', 'sort_description', 'description', 'multiple_images', 'video', 'time_duration', 'service_charge', 'discount', 'gst_charges');
            $validator = Validator::make($data, [
                'category'          => 'required',
                'vehicle_brand'     => 'required',
                'vehicle_model'     => 'required',
                'service_type'      => 'required|string',
                'service_tittle'    => 'required|string|max:255',
                'sort_description'  => 'required|string|max:255',
                'description'       => 'required|string|max:500',
                'time_duration'     => 'required',
               // 'video'             => 'required|file|mimetypes:video/mp4',
                'multiple_images'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'service_charge'    => 'required|numeric',
                'discount'          => 'required|string',
                'gst_charges'       => 'required|string',
            ]);
           
            $category = Category::where('user_id', $this->user->id)->where('_id', $request->category)->first();

            if (empty($category)) {
                return response()->json(['error' => 'Invalid Category.'], 200);
            }
            $vehicleBrand = VehicleBrand::where('user_id', $this->user->id)->where('_id', $request->vehicle_brand)->first();

            if (empty($vehicleBrand)) {
                return response()->json(['error' => 'Invalid Vehicle Brand.'], 200);
            }
            $vehicleModel = VehicleModel::where('user_id', $this->user->id)->where('_id', $request->vehicle_model)->first();

            if (empty($vehicleModel)) {
                return response()->json(['error' => 'Invalid Vehicle Model.'], 200);
            }
            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
           
            //Request is valid, create new Service
            $service = new Service();
            $data = [
                'user_id'            => $this->user->id,
                'category'           => $request->category,
                'vehicle_brand'      => $request->vehicle_brand,
                'vehicle_model'      => $request->vehicle_model,
                'service_type'       => $request->service_type,
                'service_tittle'     => $request->service_tittle,
                'sort_description'   => $request->sort_description,
                'description'        => $request->description,
                'time_duration'      => $request->time_duration,
                'service_charge'     => $request->service_charge,
                'discount'           => $request->discount,
                'gst_charges'        => $request->gst_charges,
                'status'             => (int)1,
            ];

            if (!empty($request->hasFile('multiple_images')))
            $data['multiple_images'] = json_encode(multipleFile($request->file('multiple_images'), 'images'));
            
           
            if (!empty($request->hasFile('video'))) {
                $path = $request->file('video')->store('videos', ['disk' =>      'my_files']);
                $data['video'] = $path;
            }
            foreach ($data as $key => $res) {
                $service->$key = $res;
            }

            if ($service->save()) {
                //Service created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Service Created successfully',
                    'data' => $service
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Service not Created!',
            ], 400);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

 
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
