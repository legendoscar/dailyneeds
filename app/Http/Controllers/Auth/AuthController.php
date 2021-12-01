<?php

    namespace App\Http\Controllers\Auth;
    
    use App\Models\User;
    // use App\Models\StoresModel;  
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class AuthController extends Controller
    {
        protected $guard = 'users';

        
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','registerUser', 'registerStore']]);
            $this->middleware('storeCanCreate', ['only' => ['registerStore']]);
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
         * Store a new store.
         *
         * @param  Request  $request
         * @return Response
         */
        public function registerStore(Request $request)
        {
            // return 33;
            //validate incoming request 
            $this->validate($request, [
                'store_name' => 'required|bail|string|unique:stores,store_name',
                'store_cat_id' => 'required|bail|integer|exists:categories,id',
                'store_location_id' => 'bail|integer|exists:locations,id',
                'store_email' => 'required|bail|email|unique:users,email|unique:stores,store_email',
                'store_phone' => 'required|bail|numeric|unique:users,phone|unique:stores,store_phone',
                'store_password' => 'required|bail|min:6|confirmed',
                
                // 'lname' => 'required|bail|string',
                // 'profile_picture' => 'file'
            ]);
    
            try 
            {
                $store = new StoresModel();
                $store->store_name= $request->input('store_name');
                $store->store_cat_id= $request->input('store_cat_id');
                $store->store_location_id= $request->input('store_location_id');
                $store->store_phone= $request->input('store_phone');
                $store->store_email= $request->input('store_email');
                $store->store_password = Hash::make($request->input('store_password')); 
                $type = 'Store';
                
                $store->save();
                return response()->json( [
                            'data' => $store, 
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