<?php

    namespace App\Http\Controllers\Auth;
    
    use App\Models\StoresModel;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class StoreAuthController extends Controller
    {

        
    
        public function __construct()
        {
            // $this->middleware('auth:api', ['except' => ['loginStore', 'registerStore']]);
            // $this->middleware('storeCanCreate', ['only' => ['registerStore']]);
            // $this->middleware('store', ['only' => ['loginStore']]);
        }


        
         /**
         * Create a new store account.
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
                'password' => 'required|bail|min:6|confirmed',
       
            ]);
    
            try 
            {
                $store = new StoresModel();
                $store->store_name= $request->input('store_name');
                $store->store_cat_id= $request->input('store_cat_id');
                $store->store_location_id= $request->input('store_location_id');
                $store->store_phone= $request->input('store_phone');
                $store->store_email= $request->input('store_email');
                $store->password = Hash::make($request->input('password')); 
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
         * Get a JWT via given credentials.
         *
         * @param  Request  $request
         * @return Response
         */	 
        public function loginStore(Request $request)
        {
              //validate incoming request 
            $this->validate($request, [
                'store_email' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $credentials = $request->only(['store_email', 'password']);
    
            try{
            if (! $token = auth()->guard('store')->attempt($credentials)) {	
                // return auth()->user();		
                return response()->json(['message' => 'Unauthorized. Invalid credentials'], 401);
            }

        }catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        };
            return  response()->json([
                
                'tokenData' => $this->respondWithToken($token),
                'userData' => auth()->guard('store')->user()                    
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