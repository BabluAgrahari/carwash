<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\Service;
use App\Models\Admin\UserOtp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function getServices()
    {
        try {
            $lists = Service::get();
             
             if($lists->isEmpty())
                  return response(['status' => true, 'message' =>"no record found."]);

            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'          =>$list->_id,
             'name'         =>$list->name,
             'title'        =>$list->title,
             'icon'         =>asset('icon/'.$list->icon),
             'created'      =>$list->dFormat($list->created),
             ];
             }

            return response(['status' => true, 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


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
                return response()->json(['error' => $validator->messages()], 422);
            }
            $usersDetails = User::select('phone_no', $credentials['phone_no'])->first();
            if (empty($usersDetails)) {
                $user = User::create([
                    'phone_no'     => $credentials['phone_no'],
                ]);
            }
            $success = $this->send_otp($credentials['phone_no']);
            if ($success) {
                $post['otp']     = $success['otp'];
                $post['message'] = $success['msg'];
                return response(['status' => true, 'data' => $post]);
            } else {
                return response(['status' => 'error', 'message' => 'Otp not sended!']);
            }
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function send_otp($data)
    {
        $message = array();
        $usersDetails = User::where('phone_no', $data['phone_no'])->first();

        if (!empty($usersDetails)) {

            $data['otp'] = rand(111111, 999999);
            $data['otpDateTime'] = time();
            $data['countryCode'] = 91;
            // SEND OTP-q
            // $apiKey = urlencode('');
            // $messages = "Your verification otp is " . $data['otp'];
            // $sender = urlencode('');
            // $mobilenumbers = "+" . $data['countryCode'] . $data['phone_no'];
            // $url  = "https://api.textlocal.in/send/";

            // $message1 = urlencode($messages);
            // $postdata = array('apikey' => $apiKey, 'numbers' => $mobilenumbers, "sender" => $sender, "message" => $message1);

            // $ch = curl_init();
            // if (!$ch) {
            //     die("Couldn't initialize a cURL handle");
            // }
            // $ret = curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            // $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $curlresponse = curl_exec($ch); // execute

            // if (curl_errno($ch))
            //     echo 'curl error : ' . curl_error($ch);
            // if (empty($ret)) {
            //     // some kind of an error happened
            //     die(curl_error($ch));
            //     curl_close($ch); // close cURL handler
            // } else {
            //     $info = curl_getinfo($ch);
            //     curl_close($ch);
            // }

            $User_otp = UserOtp::Select('_id')->where('phone_no', $data['phone_no'])->first();

            $d['otp']           = $data['otp'];
            $d['otp_date_time'] = $data['otp_date_time'];
            $d['phone_no']      = $data['phone_no'];

            if (!empty($User_otp)) {
                $User_otp->otp           = $data['otp'];
                $User_otp->otp_date_time = $data['otp_date_time'];

                array_push($message, "OTP resent successfully");
            } else {
                $User_otp = new UserOtp();
                $User_otp->otp           = $data['otp'];
                $User_otp->otp_date_time = $data['otp_date_time'];
                $User_otp->phone_no      = $data['phone_no'];
                $User_otp->user_id       = $usersDetails->_id;

                array_push($message, "OTP sent successfully");
            }

            $message1 = implode(" ", $message);
            if ($User_otp->save()) {
                $success['otp'] = $d['otp'];
                $success['msg'] = $message1;
                return $success;
            } else {
                return FALSE;
            }
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
            return response()->json(['error' => $validator->messages()], 422);
        }
        $usersDetails = UserOtp::where('phone_no', $credentials['phone_no'])->where('otp', $credentials['otp'])->first();;
        if (empty($usersDetails)) {
            return response(['status' => 'error', 'message' => 'Invalid phone number or OTP']);
        }
        $success = $this->verify_otp($credentials);
        if ($success == 'otp_expired') {
            return response(['status' => 'error', 'message' => 'Otp Expired!']);
        } elseif ($success) {
            return response(['status' => 'success', 'message' => 'OTP verified successfully!']);
        }
    }

    public function verify_otp($data)
    {
        $otp = UserOtp::where('phone_no', $data['phone_no'])->where('otp', $data['otp'])->first();

        if (!empty($otp)) {
            $currentTime = time();
            $otpTime = $otp->otp_date_time;
            $otpDiff = ($currentTime - $otpTime) / 60;
            if ($otpDiff > 30) {
                return 'otp_expired';
            } else {
                $id = $otp->_id;
                return $id;
            }
        } else {
            return FALSE;
        }
    }
}
