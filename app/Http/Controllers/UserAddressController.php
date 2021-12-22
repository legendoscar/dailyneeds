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


    public function showOneUserAddress(Request $request, UserAddressModel $UserAddressModel)
    {
        // return 33;
// return        $token = $request->header( 'Authorization' );
        $UserAddressModel1 = UserAddressModel::findOrFail($request->id);

        // $UserAddressModel = $UserAddressModel->id;

        // return 44;
        $response = $this->authorize('getOne', $UserAddressModel1);

        if($response->allowed()){
            return $UserAddressModel->showOneUserAddress($request); 

        }
    }


    public function createLocation(Request $request, UserAddressModel $UserAddressModel)
    {          
        // if(auth()->guard('store')){

            $rules = [
                'name' => 'bail|string|required|unique:locations,name',
                'desc' => 'bail|string',
                'location_country_name' => 'bail|string',
                'location_country_code' => 'bail|string',
                'is_popular' => 'bail|boolean',
                'is_recommended' => 'bail|boolean',
                'is_active' => 'bail|boolean',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ]);
             };

            return $UserAddressModel->createLocation($request);
        // }
    
        // return response()->json([
        //     'msg' => 'Forbidden! Not allowed to create products!',
        //     'statusCode' => 409
        // ]);
    }


    public function updateLocation(Request $request, UserAddressModel $UserAddressModel)
    {

        $rules = [
            'name' => 'bail|string|required|unique:locations,name',
            'desc' => 'bail|string',
            'location_country_name' => 'bail|string',
            'location_country_code' => 'bail|string',
            'is_popular' => 'bail|boolean',
            'is_recommended' => 'bail|boolean',
            'is_active' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ]);
         };

        //  return $request->all();

         return $UserAddressModel->updateLocation($request);

       
    }


    public function deleteUserAddress(Request $request, UserAddressModel $UserAddressModel)
    {
        $UserAddressModel = UserAddressModel::findOrFail($request->id);

       $response = $this->authorize('getOne', $UserAddressModel);

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
                'statusCode' => 200]);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }

    //
}
