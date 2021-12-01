<?php

    namespace App\Http\Controllers;
    
    use App\Models\User;
    use App\Models\StoresModel;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    // use Response;
    use Illuminate\Support\Str;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class UserProfileController extends Controller
    {
    
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','registerAdmin']]);
        }
    
       
        
         /**
         * Get user details.
         *
         * @param  Request  $request
         * @return Response
         */	 	
        public function profile() 
        {
          
            $user = auth()->user();
            return response()->json(['user'=>$user], 201);
        }
   
}