<?php

namespace App\Http\Controllers;

use App\Models\StoresModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class StoresController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
            ]);
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
             ]);
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
            ]);
         };

        return $StoresModel->updateStore($request);
    }


    public function deleteStore(Request $request, StoresModel $StoresModel)
    {

        return $StoresModel->deleteStore($request->id);
    }



    //
}
