<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\App\AppController;
use App\Models\Admin\Service;
use App\Models\Admin\UserOtp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends AppController
{

    public function sendOtp(Request $request)
    {

        try {
            $credentials = $request->only('phone_no');
            //valid credential
            $validator = Validator::make($credentials, [
                'phone_no' => 'required|numeric|digits:10',
            ]);
            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['success' => 'error', 'message' => $validator->messages()]);
            }

            $success = $this->send_otp($credentials);
            if ($success) {
                $post['otp']     = $success['otp'];
                $post['message'] = $success['msg'];
                return response(['status' => 'success', 'data' => $post]);
            } else {
                return response(['status' => 'error', 'message' => 'Otp not sent!']);
            }
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }



    public function send_otp($data)
    {

        $message = array();
        $data['otp'] = rand(1111, 9999);
        $data['otp_date_time'] = time();
        $data['countryCode'] = 91;

        $user = User::Select('_id')->where('phone_no', $data['phone_no'])->first();

        $d['otp']           = $data['otp'];
        $d['otp_date_time'] = $data['otp_date_time'];
        $d['phone_no']      = $data['phone_no'];

        if (!empty($user)) {
            $user->otp           = $data['otp'];
            $user->otp_date_time = $data['otp_date_time'];
            array_push($message, "OTP sent successfully");
        } else {
            $user = new User();
            $user->otp           = $data['otp'];
            $user->otp_date_time = $data['otp_date_time'];
            $user->phone_no      = $data['phone_no'];
            $user->token         =  Str::random(60).time();
            array_push($message, "OTP sent successfully");
        }
        $message1 = implode(" ", $message);
        if ($user->save()) {
            $success['otp'] = $d['otp'];
            $success['msg'] = $message1;
            return $success;
        } else {
            return FALSE;
        }
    }

    public function verifyOtp(Request $request)
    {
        $credentials = $request->only('phone_no', 'otp');
        //valid credential
        $validator = Validator::make($credentials, [
            'phone_no' => 'required|numeric|digits:10',
            'otp'      => 'required',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()]);
        }

        $token = $this->verify_otp($credentials);
        if ($token == 'otp_expired') {
            return response(['status' => 'error', 'message' => 'Otp Expired!']);
        } elseif (!$token) {
            return response(['status' => 'error', 'message' => 'Invaliad OTP!']);
        } elseif ($token) {
            return response(['status' => 'success', 'message' => 'OTP verified successfully!','token'=>$token]);
        }
    }

    public function verify_otp($data)
    {
        $otp = User::where('phone_no', $data['phone_no'])->where('otp', (int)$data['otp'])->first();
        if (!empty($otp)) {
            $currentTime = time();
            $otpTime = $otp->otp_date_time;
            $otpDiff = ($currentTime - $otpTime) / 60;
            if ($otpDiff > 30) {
                return 'otp_expired';
            } else {
                $token = $otp->token;
                return $token;
            }
        } else {
            return FALSE;
        }
    }


    public function resendOtp(Request $request)
    {

        try {
            $credentials = $request->only('phone_no');
            //valid credential
            $validator = Validator::make($credentials, [
                'phone_no' => 'required|numeric|digits:10',
            ]);
            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['success' => 'error', 'message' => $validator->messages()]);
            }

            $success = $this->send_otp($credentials);
            if ($success) {
                $post['otp']     = $success['otp'];
                $post['message'] = $success['msg'];
                return response(['status' => 'success', 'data' => $post]);
            } else {
                return response(['status' => 'error', 'message' => 'Otp not sent!']);
            }
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function logoOut(){


    }
}
