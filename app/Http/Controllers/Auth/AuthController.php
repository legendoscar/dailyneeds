<?php

    namespace App\Http\Controllers\Auth;
    
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

    class AuthController extends Controller
    {
    
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','registerUser']]);
        }
    
        /**
         * Store a new user.
         *
         * @param  Request  $request
         * @return Response
         */
        public function registerUser(Request $request)
        {
            //validate incoming request 
            $this->validate($request, [
                'fname' => 'required|bail|string',
                'lname' => 'required|bail|string',
                'email' => 'required|bail|email|unique:users',
                'phone' => 'required|bail|numeric|unique:users',
                'password' => 'required|bail|min:6|confirmed',
                'profile_picture' => 'file'
            ]);
    
            try 
            {
                $user = new User;
                $user->fname= $request->input('fname');
                $user->lname= $request->input('lname');
                $user->email= $request->input('email');
                $user->phone= $request->input('phone');
                $user->referral_program_id= 1;

                if($request->getRequestUri() == '/api/auth/register/admin') {
                    $user->user_role = 1;
                    $type = 'Admin';
                }elseif($request->getRequestUri() == '/api/auth/register/customer') {
                    $user->user_role = 2;
                    $type = 'Customer';
                }
               else{
                    $user->user_role = 3;
                    $type = 'Driver';
               }
                

                $user->password = Hash::make($request->input('password'));
                
                $user->save();
    
                $this->login($request);
                return response()->json( [
                            'data' => $user, 
                            'action' => 'create', 
                            'msg' => $type . ' account created successfully.',
                             
                ], 201);
    
            } 
            catch (\Exception $e) 
            {
                return response()->json( [
                           'action' => 'create', 
                           'err' => $e->getMessage(),
                           'msg' => $type . ' account creation failed'
                ], 409);
            }
        }
        
         /**
         * Get a JWT via given credentials.
         *
         * @param  Request  $request
         * @return Response
         */	 
        public function login(Request $request)
        {
              //validate incoming request 
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $credentials = $request->only(['email', 'password']);
    
            try{
            if (! $token = auth()->attempt($credentials)) {			
                return response()->json(['message' => 'Unauthorized. Invalid credentials'], 401);
            }

        }catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        };
            return  response()->json([
                
                'tokenData' => $this->respondWithToken($token),
                'userData' => auth()->user()                    
            ]);
        }
        
         /**
         * Get user details.
         *
         * @param  Request  $request
         * @return Response
         */	 	
        public function me()
        {
            // return Auth::user();
        //     try {
        //     return response()->json(auth()->userOrFail());
        //     // return response()->json(auth()->user());

        // } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
        //     return response()->json([
        //         'err' => $e->getMessage(),
                
                
        //     ]);
            $user = auth()->user();
            return response()->json(['user'=>$user], 201);
        }
        
        /**
         * Store a new user.
         *
         * @param  Request  $request
         * @return Response
         */
        public function registerStore(Request $request)
        {
            //validate incoming request 
            $this->validate($request, [
                'fname' => 'required|bail|string',
                'lname' => 'required|bail|string',
                'email' => 'required|bail|email|unique:users',
                'phone' => 'required|bail|numeric|unique:users',
                'password' => 'required|bail|min:6|confirmed',
            ]);
    
            try 
            {
                $user = new StoresModel;
                $user->fname= $request->input('fname');
                $user->lname= $request->input('lname');
                $user->email= $request->input('email');
                $user->phone= $request->input('phone');
                $user->referral_program_id= 1;

                if($request->getRequestUri() == '/api/auth/register/admin') {
                    $user->user_role = 1;
                    $type = 'Admin';
                }elseif($request->getRequestUri() == '/api/auth/register/customer') {
                    $user->user_role = 2;
                    $type = 'Customer';
                }
               else{
                    $user->user_role = 3;
                    $type = 'Driver';
               }
                

                $user->password = Hash::make($request->input('password'));
                
                $user->save();
    
                return response()->json( [
                            'data' => $user, 
                            'action' => 'create', 
                            'msg' => $type . ' account created successfully.'
                ], 201);
    
            } 
            catch (\Exception $e) 
            {
                return response()->json( [
                           'action' => 'create', 
                           'err' => $e->getMessage(),
                           'msg' => $type . ' account creation failed'
                ], 409);
            }
        }


     /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    
}