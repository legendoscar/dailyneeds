<?php

namespace App\Http\Controllers;

// use App\Models\;
use App\Models\FavoritesModel;
use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Closure;


class FavoritesController extends Controller 
{

    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->middleware('store', ['only' => ['createProduct']]);
        
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showAllProducts(FavoritesModel $FavoritesModel)
    { 

       return $FavoritesModel->productGetAll();
    }


    public function showOneProduct(Request $request, FavoritesModel $FavoritesModel)
    {
        // return $FavoritesModel->productsHas;
        return $FavoritesModel->showOneProduct($request->id);
    }
    
    
    public function showStoreProducts(Request $request, FavoritesModel $FavoritesModel)
    {
        // return $request->all();
        return $FavoritesModel->showStoreProducts($request->id, $request);
        
    }


    public function store(Request $request, ProductsModel $ProductsModel, Closure $next)
    {          
        // return $ProductsModel->favorite();


        $rules = [
            'user_id' => 'bail|required|numeric|exists:users,id',
            // 'store_id' => 'bail|required|numeric|exists:stores,id',
            'product_id' => 'bail|required|numeric|exists:products,id',
            // 'is_fav_store' => 'bail|boolean',
            // 'is_fav_product' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        return $this->validateData($validator, $request, $next);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'errorMsg' => $validator->errors(), 
        //         'statusCode' => 422
        //     ]);
        // };        


        return 34;
        // return $FavoritesModel->createFavoriteProduct($request);


       
    }


    public function updateProduct(Request $request, FavoritesModel $FavoritesModel, $id)
    {

        $FavoritesModelData = FavoritesModel::findOrFail($id);

        $response = $this->authorize('getProductOwner', $FavoritesModelData);

        if($response->allowed()){
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
                ], 422);
             };

             return $FavoritesModel->updateProduct($request);

        }
        return 2;
        // return $FavoritesModel->store_id === $StoresModel->id;
        // Gate::allowIf(fn ($user) => auth()->user()->user_role === 1);

        // Gate::before(function ($user, $FavoritesModelData) {
        //     if (auth()->user()->user_role === 1) {
        //         return true;
        //     }
        // });

        // if(Gate::denies('getProductOwner', $FavoritesModelData)){
        // //     return $FavoritesModel->store_id;
        //     return 33;
        // };

        //  return $request->all();

       
    }


    public function deleteProduct($id)
    {

        // return $FavoritesModel->ProductCategory();
        $FavoritesModelData = FavoritesModel::findOrFail($id);

       $response = $this->authorize('getProductOwner', $FavoritesModelData);

        if($response->allowed()){
            try {
                $FavoritesModelData->delete();
                return response()->json([
                    'msg' => 'Deleted successfully!',
                    'statusCode' => 200]);
                }catch(\Exception $e){
                    return response()->json([
                        'msg' => 'Delete operation failed!',
                        'err' => $e->getMessage(),
                        'statusCode' => 409
                    ], 409);
            }
        }
    }


    public function ProductBelongsTo($id){
        try {
            $data = FavoritesModel::find($id)->ProductsCategory;
            return response()->json([
                'msg' => 'Category selection successful!',
                'data' => $data,
                'statusCode' => 200]);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }
    
    
    // public function ProductBelongsToStore($id){
    //     try {
    //         $data = FavoritesModel->
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
