<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetailerMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            // if user is not admin take him to his dashboard
            if (Auth::user()->isAdmin()) {

                return redirect(url('admin/dashboard'));
            }

            // allow admin to proceed with request
            else if (Auth::user()->isRetailer()) {

                //check otp retailer otp verify or not
                if (empty(Auth::user()->verify_otp) || !Auth::user()->verify_otp)
                    return redirect(url('otp-sent'));

                return $next($request);
            } else if (Auth::user()->isEmployee()) {
                return redirect(url('employee/dashboard'));
            } else {

                return redirect(url('/'));
            }
        }

        //abort(404);  // for other user throw 404 error
        return redirect('/');
    }
}
