<?php

namespace App\Http\Controllers;

// use App\Models\;
use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller 
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['showAllProducts', 'showOneProduct']]);
        
        $this->middleware('store', ['except' => [
            // 'showAllProducts',
            'showOneProduct']]);
        // $this->middleware('admin', ['only' => ['createCat','updateCat', 'deleteCat', 'deleteCatPerm']]);
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


    public function createProduct(Request $request, ProductsModel $ProductsModel)
    {          
        if(auth()->guard('store')){

            $rules = [
                'prod_cat_id' => 'bail|required|numeric|exists:sub_categories,id',
                'store_id' => 'bail|required|numeric|exists:stores,id',
                'product_title' => 'bail|required|string',
                'product_sub_title' => 'bail|string',
                'product_desc' => 'bail|string',
                'unit' => 'bail|string',
                'price' => 'bail|required|regex:/^\d+(\.\d{1,2})?$/',
                'product_banner_img' => 'bail|file',
                'product_code' => 'bail|string',
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
    
        return response()->json([
            'msg' => 'Forbidden! Not allowed to create products!',
            'statusCode' => 409
        ]);
    }


    public function updateProduct(Request $request, ProductsModel $ProductsModel)
    {

        $rules = [
            'prod_cat_id' => 'bail|numeric|exists:sub_categories,id',
            'store_id' => 'bail|numeric|exists:stores,id',
            'product_title' => 'bail|string',
            'product_sub_title' => 'bail|string',
            'product_desc' => 'bail|string',
            'unit' => 'bail|string',
            'product_banner_img' => 'bail|file',
            'product_code' => 'bail|string',
            'price' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
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

         return $request->all();

         return $ProductsModel->updateProduct($request);

       
    }


    public function deleteProduct($id)
    {

        // return $ProductsModel->ProductCategory();
        try {
            ProductsModel::findorFail($id)->delete();
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
