<?php

namespace App\Http\Controllers;

use App\Models\UserAddressModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller 
{

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('admin', ['only' => [
            'showAllUserAddress',
            // 'deleteLocation',
            // 'updateLocation',
            // 'showAllOrderItems',
            //  'showOneOrderItem', 
            //  'deleteOrderItem'
             ]]);
        // $this->middleware('auth:user', ['except' => ['showAllProducts','showOneProduct']]);
        // $this->middleware('admin', ['only' => ['createCat','updateCat', 'deleteCat', 'deleteCatPerm']]);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showAllUserAddress(UserAddressModel $UserAddressModel)
    { 

       return $UserAddressModel->showAllUserAddress();
    }


    public function showOneUserAddress(Request $request, UserAddressModel $UserAddressModel, $id)
    {
        $UserAddressModelData = UserAddressModel::findOrFail($id);

        $response = $this->authorize('getOwner', $UserAddressModelData);

        if($response->allowed()){ 
            return $UserAddressModel->showOneUserAddress($request); 

        }
    }
 

    public function createUserAddress(Request $request, UserAddressModel $UserAddressModel)
    {          
        // if(auth()->guard('store')){

            $rules = [
                'user_id' => 'bail|string|required|exists:users,id',
                'user_location_id' => 'bail|string|required|exists:locations,id',
                'address_title' => 'bail|required|string',
                'address_street' => 'bail|required|string',
                'address_city' => 'bail|required|string',
                'address_state' => 'bail|required|string',
                'address_country' => 'bail|required|regex:/(^[-0-9A-Za-z.,\/ ]+$)/',
                'address_zip_code' => 'bail|required|regex:/\b\d{6}\b/',
                'address_latitude' => ['bail', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'address_longitude' => ['bail', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                'address_primary' => 'bail|boolean',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };

            return $UserAddressModel->createUserAddress($request);
        // }
    
        // return response()->json([
        //     'msg' => 'Forbidden! Not allowed to create products!',
        //     'statusCode' => 409
        // ]);
    }


    public function updateUserAddress(Request $request, UserAddressModel $UserAddressModel)
    {

        $rules = [
            'user_id' => 'bail|string|required|exists:users,id',
            'user_location_id' => 'bail|string|exists:locations,id',
            'address_title' => 'bail|string',
            'address_street' => 'bail|string',
            'address_city' => 'bail|string',
            'address_state' => 'bail|string',
            'address_country' => 'bail|regex:/(^[-0-9A-Za-z.,\/ ]+$)/',
            'address_zip_code' => 'bail|regex:/\b\d{6}\b/',
            'address_latitude' => ['bail', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'address_longitude' => ['bail', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'address_primary' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
         };

        //  return $request->all();

        $UserAddressModel = UserAddressModel::findOrFail($request->id);

        $response = $this->authorize('getOwner', $UserAddressModel);
 
         if($response->allowed()){
            return $UserAddressModel->updateUserAddress($request);
         }

       
    }


    public function deleteUserAddress(Request $request, UserAddressModel $UserAddressModel)
    {
        $UserAddressModel = UserAddressModel::findOrFail($request->id);

       $response = $this->authorize('getOwner', $UserAddressModel);

        if($response->allowed()){
            return $UserAddressModel->deleteUserAddress($request->id); 

        }


        // return $UserAddressModel->deleteLocation($request->id);
        
    }

    public function ProductBelongsTo($id){
        try {
            $data = UserAddressModel::find($id)->ProductsCategory;
            return response()->json([
                'msg' => 'Category selection successful!',
                'data' => $data,
                'statusCode' => 200
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }

    //
}
