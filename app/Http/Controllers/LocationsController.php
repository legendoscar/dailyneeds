<?php

namespace App\Http\Controllers;

use App\Models\LocationsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationsController extends Controller 
{

    public function __construct()
    {
        $this->middleware('admin', ['only' => [
            'createLocation',
            'deleteLocation',
            'updateLocation',
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

    public function showAllLocations(LocationsModel $LocationsModel)
    { 

       return $LocationsModel->showAllLocations();
    }


    public function showOneLocation(Request $request, LocationsModel $LocationsModel)
    {
        return $LocationsModel->showOneLocation($request->id);
    }


    public function createLocation(Request $request, LocationsModel $LocationsModel)
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
                ], 422);
             };

            return $LocationsModel->createLocation($request);
        // }
    
        // return response()->json([
        //     'msg' => 'Forbidden! Not allowed to create products!',
        //     'statusCode' => 409
        // ]);
    }


    public function updateLocation(Request $request, LocationsModel $LocationsModel)
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
            ], 422);
         };

        //  return $request->all();

         return $LocationsModel->updateLocation($request);

       
    }


    public function deleteLocation(Request $request, LocationsModel $LocationsModel)
    {

        return $LocationsModel->deleteLocation($request->id);
        
    }

    public function ProductBelongsTo($id){
        try {
            $data = LocationsModel::find($id)->ProductsCategory;
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
