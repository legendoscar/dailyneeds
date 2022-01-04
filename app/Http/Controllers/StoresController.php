<?php

namespace App\Http\Controllers;

use App\Models\StoresModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;



class StoresController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getAllStores', 'getSingleStore']]);
        $this->middleware('store', ['only' => ['updateStore', 'deleteStore']]);
    }

    public function getAllStores(StoresModel $StoresModel)
    {
        return $StoresModel->getAllStores();
    } 
     

    public function getSingleStore(Request $request, StoresModel $StoresModel)
    {

        return $StoresModel->getSingleStore($request->id);
    }


    public function createStore(Request $request, StoresModel $StoresModel)
    {
        $rules = [
            'store_name' => 'bail|required|unique:stores|string',
            'store_address' => 'bail|required|string',
            'store_phone' => 'bail|required|unique:stores|numeric|digits:11',
            'store_email' => 'bail|required|email:filter|unique:Stores',
            'store_image' => 'bail|file',
            'store_password' => 'bail|required|min:8|string',
            'store_about' => 'bail|string',
            'verification_status' => 'bail|string|in:0,1',
            'status' => 'bail|string|in:active,suspended,deactivated',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
         };
        
        return $StoresModel->createStore($request);

    }

    public function login(Request $request)
    {
        $rules = [
        'email' => 'bail|required',
        'password' => 'bail|required|min:8'
         ];

         $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()) {
             return response()->json([
                 'errorMsg' => $validator->errors(), 
                 'statusCode' => 422
             ], 422);
          };

       $user = StoresModel::where('email', $request->input('email'))->first();
    //   if(Hash::check($request->input('password'), $user->password)){
        //    $apikey = base64_encode(Str::random(40));
        //    StoresModel::where('email', $request->input('email'));

           return response()->json(['status' => 'log in successful']);
    //    }else{
    //        return response()->json(['status' => 'fail'],401);
    //    }
    }
 

    public function updateStore(Request $request, StoresModel $StoresModel)
    {
        $rules =  [
            'store_name' => 'bail|unique:stores|string',
            'store_cat_id' => 'bail|exists:stores,store_cat_id|integer',
            'store_address' => 'bail|string',
            'store_phone' => 'bail|unique:stores|numeric|digits:11',
            'store_email' => 'bail|email:filter|exists:stores,email|unique:Stores',
            'store_image' => 'bail|file',
            'store_password' => 'bail|min:8|string',
            'store_about' => 'bail|string',
            'verification_status' => 'bail|string|in:0,1',
            'status' => 'bail|string|in:active,suspended,deactivated',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
         };

        return $StoresModel->updateStore($request);
    }


    public function deleteStore(Request $request, $id, StoresModel $StoresModel)
    {

        $StoresModelData = StoresModel::findOrFail($id);

        if(auth()->guard('store')->user()->id === $StoresModelData->id || auth()->user()->user_role === 1){

            return $StoresModel->deleteStore($request->id);
        }
        return response()->json([
            'errorMsg' => 'Forbidden! Unauthorized access!',
            'statusCode' => 422
        ], 422);
    }


     /**
         * Get user details.
         *
         * @param  Request  $request
         * @return Response
         */	 	
        public function profile()
        {
                    
            $token = JWTAuth::getToken();

            if(!$token){
            //   throw new JWTException('Token not provided');
            return response()->json(['msg' => 'token_missing'], 404);
            }
            if (! $parse = JWTAuth::parseToken()) {
                return response()->json(['user_not_found'], 404);
            }

            try {

                if (!JWTAuth::parseToken()) {
                        return response()->json(['user_not_found'], 404);
                }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            
            return response()->json([
                'data' => auth()->guard('store')->user()
            ], 200);
        }

    //
}
