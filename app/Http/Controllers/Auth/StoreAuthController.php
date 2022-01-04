<?php

    namespace App\Http\Controllers\Auth;
    
    use App\Models\StoresModel;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;


    class StoreAuthController extends Controller
    {

        
    
        public function __construct()
        {
            // $this->middleware('auth:api', ['except' => ['loginStore', 'registerStore']]);
            // $this->middleware('storeCanCreate', ['only' => ['registerStore']]);
            // $this->middleware('store', ['only' => ['loginStore']]);
        }


        public function validateRequest(Request $request, $rules){

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };
        }
        
         /**
         * Create a new store account.
         *
         * @param  Request  $request
         * @return Response
         */	 

         public function registerStore(Request $request, StoresModel $StoresModel)
        {
            
            //validate incoming request 
            $rules = [
                'store_name' => 'required|bail|string|unique:stores,store_name',
                'store_cat_id' => 'required|bail|integer|exists:categories,id',
                'store_location_id' => 'bail|integer|exists:locations,id',
                'store_email' => 'required|bail|email|unique:users,email|unique:stores,store_email',
                'store_phone' => 'required|bail|numeric|unique:users,phone|unique:stores,store_phone',
                'password' => 'required|bail|min:6|confirmed',
       
            ];

            // return $this->validateRequest($request, $rules);
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };
    
            
            $StoresModel = new $StoresModel;
            return $StoresModel->registerStore($request);
            
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
            $rules = [
                'store_email' => 'required|string',
                'password' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };
    
             $type = 'Store';
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
         $user = json_encode(auth()->guard('store')->user());
         $token = json_encode($this->respondWithToken($token));
         $data = [];

         $data[] = json_decode($user, true);
         $data[] = json_decode($token, true);

            return  response()->json([
                
                'statusCode' => 200,                    
                'msg' =>  $type . ' Login Success',                    
                'userData' => $data,
            ], 200);
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

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    
}