<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class OrdersModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'orders';

    protected $fillable = ['prod_cat_id', 'store_id', 'product_title', 'product_sub_title', 
    'product_desc', 'unit', 'product_banner_img', 'product_images' , 'product_code', 'price', 'old_price', 
    'is_available', 'is_new', 'is_popular', 'is_recommended'
]; 

     public function OrderItems(){
        return $this->hasMany('App\Models\OrderItemsModel', 'order_id', 'id');
    }

    public function exception($data, $success = 'Records returned successfully.', $failed = 'No Record found.')
    {

        try{
             !empty($data)
                 ? $ret = response()->json([
                     'data' => $data,
                     'statusCode' => 200,
                     'msg' => $success
         ])
         : $ret = response()->json([
             'data' => $data,
             'msg' => $failed,
             'statusCode' => 404
         ]);

         return $ret;


         }catch(\Exception $e){
             return response()->json([ 
                 'msg' => 'Ooops!! Error encountered!',
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ]);
         }
    }

    public function orderGetAll(){ 

        try {
            return response()->json([
             'data' => $this->all(),
             'statusCode' => 200,
             'msg' => 'Records returned successfully.'
         ]);
         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'No record found!', 
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ]);
         }
    }

    public function showOneOrder($id){
        try {
            $data = $this->findOrFail($id); 
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => 'Record returned successfully.',
                    'statusCode' => 200
                ])
                : $ret = response()->json([
                'msg' => 'No Record found for product with ID: ' . $id,
                'statusCode' => 404
            ]);
    
            return $ret;
    
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ]);
            }
    }

    public function createProduct(Request $request){
        try{

            $image_name = $request->product_banner_img;
        if($request->hasFile('product_banner_img')){
            // $file = $request->product_banner_img;
            $image_name = $request->product_banner_img->getClientOriginalName();

            $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $destinationPath = app()->basePath($path);
            $request->file('product_banner_img')->move($destinationPath, $image_name);

            // if(!$request->file('product_banner_img')->isValid()){
            //     return response()->json([
            //         'msg' => 'Image upload not successful'
            //     ]);
            // }
        }
            $ProductsModel = new ProductsModel;

            $ProductsModel->prod_cat_id = $request->prod_cat_id;
            $ProductsModel->store_id = $request->store_id;
            $ProductsModel->product_title = $request->product_title;
            $ProductsModel->product_sub_title = $request->product_sub_title;
            $ProductsModel->product_desc = $request->product_desc;
            $ProductsModel->unit = $request->unit;
            $ProductsModel->product_banner_img = $image_name;
            $ProductsModel->price = $request->price;
            $ProductsModel->save();

            return response()->json([
                'data' => $ProductsModel,
                'msg' => 'New Product created successfully',
                'statusCode' => 201
            ]);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'Product creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }




    public function updateProduct(Request $request){
        try {
            $request->updated_at = Carbon::now()->toDateTimeString();


            $ProductsModel = ProductsModel::findorFail($request->id);

            $ProductsModel->prod_cat_id = $request->filled('prod_cat_id') ? $request->prod_cat_id : $ProductsModel->prod_cat_id;
            $ProductsModel->product_title = $request->filled('product_title') ? $request->product_title : $ProductsModel->product_title;
            $ProductsModel->product_sub_title = $request->filled('product_sub_title') ? $request->product_sub_title : $ProductsModel->product_sub_title;
            $ProductsModel->product_desc = $request->filled('product_desc') ? $request->product_desc : $ProductsModel->product_desc;
            $ProductsModel->unit = $request->filled('unit') ? $request->unit : $ProductsModel->unit;
            $ProductsModel->product_banner_img = $request->filled('product_banner_img') ? $request->product_banner_img : $ProductsModel->product_banner_img;
            $ProductsModel->product_code = $request->filled('product_code') ? $request->product_code : $ProductsModel->product_code;
            $ProductsModel->price = $request->filled('price') ? $request->price : $ProductsModel->price;
            $ProductsModel->old_price = $request->filled('old_price') ? $request->old_price : $ProductsModel->old_price;
            $ProductsModel->is_available = $request->filled('is_available') ? $request->is_available : $ProductsModel->is_available;
            $ProductsModel->is_new = $request->filled('is_new') ? $request->is_new : $ProductsModel->is_new;
            $ProductsModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $ProductsModel->is_popular;
            $ProductsModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $ProductsModel->is_recommended;

            // return $request->all();
            $ProductsModel->save();

            // $ProductsModel->update($request->all());

            return response()->json([
                'data' => $ProductsModel,
                'msg' => 'Product updated successfully.',
                'statusCode' => 200]);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Product update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ]);
        }
    }
}
