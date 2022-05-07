<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            $token =  Str::substr($header, 7);
            $user = User::where('token', $token)->first();
            if (!empty($user) && $user->token == $token) {
                $userData = ['_id' => $user->_id, 'phone_no' => $user->phone_no, 'otp' => $user->otp];
                session(['AppUser' => $userData]);
                 return $next($request);
            } else {
                return response()->json(['status' => FALSE, 'message' => 'Invalid token!']);
            }
        } else {
            return response()->json(['status' => FALSE, 'message' => 'Token is Required!']);
        }
    }
}
