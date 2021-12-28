<?php

namespace App\Http\Controllers;

// use App\Models\;
use App\Models\ProductsModel;
use App\Models\StoresModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class ProductsController extends Controller 
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['showAllProducts', 'showOneProduct', 'showStoreProducts']]);
        $this->middleware('store', ['only' => ['createProduct']]);
        
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showAllProducts(ProductsModel $ProductsModel)
    { 

       return $ProductsModel->productGetAll();
    }


    public function showOneProduct(Request $request, ProductsModel $ProductsModel)
    {
        return $ProductsModel->showOneProduct($request->id);
    }
    
    
    public function showStoreProducts(Request $request, ProductsModel $ProductsModel)
    {
        // return $request->all();
            return $ProductsModel->showStoreProducts($request->id, $request);
        
    }


    public function createProduct(Request $request, ProductsModel $ProductsModel)
    {          
        $rules = [
            'prod_cat_id' => 'bail|required|numeric|exists:sub_categories,id',
            'store_id' => 'bail|required|numeric|exists:stores,id',
            'product_title' => 'bail|required|string',
            'product_sub_title' => 'bail|string',
            'product_desc' => 'bail|string',
            'unit' => 'bail|string',
            'product_price' => 'bail|required|regex:/^\d+(\.\d{1,2})?$/',
            'product_banner_img' => 'bail|file',
            'product_code' => 'bail|string|unique:products,product_code',
            'is_available' => 'bail|boolean',
            'is_new' => 'bail|boolean',
            'is_popular' => 'bail|boolean',
            'is_recommended' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ]);
        };        
        return $ProductsModel->createProduct($request);
       
    }


    public function updateProduct(Request $request, ProductsModel $ProductsModel, $id)
    {

        $ProductsModelData = ProductsModel::findOrFail($id);

       $response = $this->authorize('getProductOwner', $ProductsModelData);

        if($response->allowed()){
            // return 33;
            $rules = [
                'prod_cat_id' => 'bail|numeric|exists:sub_categories,id',
                'store_id' => 'bail|numeric|exists:stores,id',
                'product_title' => 'bail|string',
                'product_sub_title' => 'bail|string',
                'product_desc' => 'bail|string',
                'unit' => 'bail|string',
                'product_banner_img' => 'bail|file',
                'product_code' => 'bail|string',
                'product_price' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'old_price' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'is_available' => 'bail|integer',
                'is_new' => 'bail|integer',
                'is_popular' => 'bail|integer',
                'is_recommended' => 'bail|integer',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ]);
             };

             return $ProductsModel->updateProduct($request);

        }
        return 2;
        // return $ProductsModel->store_id === $StoresModel->id;
        // Gate::allowIf(fn ($user) => auth()->user()->user_role === 1);

        // Gate::before(function ($user, $ProductsModelData) {
        //     if (auth()->user()->user_role === 1) {
        //         return true;
        //     }
        // });

        // if(Gate::denies('getProductOwner', $ProductsModelData)){
        // //     return $ProductsModel->store_id;
        //     return 33;
        // };

        //  return $request->all();

       
    }


    public function deleteProduct($id)
    {

        // return $ProductsModel->ProductCategory();
        $ProductsModelData = ProductsModel::findOrFail($id);

       $response = $this->authorize('getProductOwner', $ProductsModelData);

        if($response->allowed()){
            try {
                $ProductsModelData->delete();
                return response()->json([
                    'msg' => 'Deleted successfully!',
                    'statusCode' => 200]);
                }catch(\Exception $e){
                    return response()->json([
                        'msg' => 'Delete operation failed!',
                        'err' => $e->getMessage(),
                        'statusCode' => 409
                    ]);
            }
        }
    }


    public function ProductBelongsTo($id){
        try {
            $data = ProductsModel::find($id)->ProductsCategory;
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
    
    
    // public function ProductBelongsToStore($id){
    //     try {
    //         $data = ProductsModel->
    //         where('store_id', auth()->user()->id);
    //         // ProductsCategory;
    //         return response()->json([
    //             'msg' => 'Category selection successful!',
    //             'data' => $data,
    //             'statusCode' => 200]);
    //     }catch(\Exception $e){
    //         return response()->json([
    //             'msg' => 'Failed to retrieve data!',
    //             'err' => $e->getMessage(),
    //             'statusCode' => 409
    //         ]);
    //     }
    // }

    //
}
