<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Request\Validation\Admin\LoginRequest;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// use Tymon\JWTAuth\JWTAuth;
class LoginController extends Controller
{
    public function store(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'status' => false,
                'message' => 'Could not create token.',
            ], 500);
        }
        //Token created, return with success response and jwt token
        return $this->createNewToken($token);
    }


    protected function createNewToken($token)
    {
        $user['name']  = auth()->user()->name;
        $user['email'] = auth()->user()->email;
        $user['role']  = auth()->user()->role;

        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }


    public function register(Request $request)
    {
       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->role  = 'admin';
       $user->password = Hash::make($request->password);
       $user->save();

        return response()->json([
            'status' => true,
            'message' => 'register.',
        ], 200);
    }


    public function create(array $data)
    {
        return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      =>  'admin',
            'password'  => Hash::make($data['password'])
        ]);
    }
}
