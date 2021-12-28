<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

Class ProductsModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'products';

    protected $fillable = ['prod_cat_id', 'store_id', 'product_title', 'product_sub_title', 
    'product_desc', 'unit', 'product_banner_img', 'product_images' , 'product_code', 'product_price', 'old_price', 
    'is_available', 'is_new', 'is_popular', 'is_recommended'
]; 

    //  public function ProductsCategory(){
    //     return $this->hasOne('App\Models\ProductsSubCatModel', 'id', 'cat_id');
    // }

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

    public function productGetAll(){ 

        try {
            $count = count($this->all());
            return response()->json([
            'msg' => $count . ' Records returned successfully.',
             'data' => $this->all(),
             'statusCode' => 200,
         ]);
         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'No record found!', 
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ]);
         }
    }

    public function showOneProduct($id){
        // return $id; $request->all();
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
   
   
    public function showStoreProducts($id, Request $request){
        // return $id; $request->all();
        
        // $id = 3;
        // return response()->json([
        //     'data' => $store_data
        // ]);

        $id = $request->id;
        if(auth()->guard('store')->user()){
            $id = auth()->guard('store')->user()->id;
        }

        try {
            $data = $this->where('store_id', $id)
            ->get(); 
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
            
            $store_id = $request->store_id;

            if(auth()->guard('store')->user()){
                $store_id = auth()->guard('store')->user()->id;
                // return $store_id;
            }
            
            // return 'a';

            $ProductsModel->prod_cat_id = $request->prod_cat_id;
            $ProductsModel->store_id = $store_id;
            $ProductsModel->product_title = $request->product_title;
            $ProductsModel->product_sub_title = $request->product_sub_title;
            $ProductsModel->product_desc = $request->product_desc;
            $ProductsModel->unit = $request->unit;
            $ProductsModel->product_banner_img = $image_name;
            $ProductsModel->product_price = $request->product_price;
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


            // // try{
                $ProductsModel = ProductsModel::findorFail($request->id);
            // }catch(ModelNotFoundException $e){
            //     return 33;
            // };
            // return 21;

            $store_id =  $request->filled('store_id') ? $request->store_id : $ProductsModel->store_id;

            if(auth()->guard('store')->user()){
                $store_id = auth()->guard('store')->user()->id;
            }
            // return $store_id;

            $ProductsModel->prod_cat_id = $request->filled('prod_cat_id') ? $request->prod_cat_id : $ProductsModel->prod_cat_id;
            $ProductsModel->store_id = $store_id;
            $ProductsModel->product_title = $request->filled('product_title') ? $request->product_title : $ProductsModel->product_title;
            $ProductsModel->product_sub_title = $request->filled('product_sub_title') ? $request->product_sub_title : $ProductsModel->product_sub_title;
            $ProductsModel->product_desc = $request->filled('product_desc') ? $request->product_desc : $ProductsModel->product_desc;
            $ProductsModel->unit = $request->filled('unit') ? $request->unit : $ProductsModel->unit;
            $ProductsModel->product_banner_img = $request->filled('product_banner_img') ? $request->product_banner_img : $ProductsModel->product_banner_img;
            $ProductsModel->product_code = $request->filled('product_code') ? $request->product_code : $ProductsModel->product_code;
            $ProductsModel->product_price = $request->filled('product_price') ? $request->product_price : $ProductsModel->product_price;
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
        }catch(ModelNotFoundException $e){
            return 33;
        };
    }

    public function deleteProduct($id){
 
        // return 33;

        $data = $this->findorFail($id)->delete();
        return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
}
