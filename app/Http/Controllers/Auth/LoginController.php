<?php

  

namespace App\Http\Controllers\Auth;

   
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;

   

class LoginController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Login Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles authenticating users for the application and

    | redirecting them to your home screen. The controller uses a trait

    | to conveniently provide its functionality to your applications.

    |

    */

    public function username(){
        return 'email';
    }
  

    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        // $this->middleware('guest:admin')->except('logout');
        // $this->middleware('guest:customer')->except('logout');

        $this->middleware('auth:api', ['except' => ['adminLogin','custLogin']]);
    }

    public function adminLogin(Request $request)
    {

        // return 33;
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return response()->json(['status' => 'admin login success'], 200);
        }else{
            return response()->json(['status' => 'admin login fail'], 401);
        }
    }


    public function custLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return response()->json(['status' => 'customer login success'], 200);
        }else{
            return response()->json(['status' => 'customer login fail'], 401);
        }
    }
  
    
}    