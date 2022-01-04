<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;

Class FavoritesModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'favorites';

    protected $guarded = [];

    protected $fillable = ['user_id', 'store_id', 'product_id', 'is_fav_store', 'is_fav_product']; 

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

     public function productsHas(){
        return $this->hasManyThrough('App\Models\FavoritesModel', 'App\Models\User');
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

    public function productGetAll(){ 

        try {
            $count = count($this->all());
            return response()->json([
            'msg' => $count . ' Records returned successfully.',
             'data' => $this->all(),
             'statusCode' => 200,
         ], 200);
         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'No record found!', 
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ], 409);
         }
    }

    public function showOneProduct($id){
        // return $id; $request->all();
        try {
            $user = auth()->user()->id;
            $data = $this
            ->join('favorites',  function($join){
                $join->on('products.id', '=', 'favorites.product_id')
                // ->orOn()
                    // ->where('favorites.user_id', '=', 2)
                    ;
            })
            ->join('users', function($join){
                $join->on('users.id', '=', 'favorites.user_id');
            })
            // ->join('favorites', 'products.id', '=', 'favorites.product_id')
            // ->where('favorites.user_id', '=', $user)
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
            ], 404);
    
            return $ret;
    
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
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
            ], 404);
    
            return $ret;
    
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
    }

    public function store(){

    }

    public function createFavoriteProduct(Request $request){
        try{

            // return 33;
        
            $FavoritesModel = new FavoritesModel;   
            
            $FavoritesModel->user_id = auth()->user()->id;
            $FavoritesModel->store_id = $request->store_id;
            $FavoritesModel->product_id = $request->product_id;
            
            if($this->is_fav_product == 0){
                $FavoritesModel->is_fav_product = 1;
            }else{

                $FavoritesModel->is_fav_product = 0;
            }
            
            // return $FavoritesModel->is_fav_product;

            $att = [
                'user_id' => $FavoritesModel->user_id, 
                'product_id' => $FavoritesModel->product_id, 
                // 'store_id' => $FavoritesModel->store_id
            ];

            if($this->where($att)->exists()){
                // return response()->json([
                //     'msg' => 'Forbidden! You can\'t favorite the same product more than once!',
                //     'statusCode' => 409
                // ], 409);
                $FavoritesModel->save();
    
                return response()->json([
                    'data' => $FavoritesModel,
                    'msg' => 'New Product favorited successfully',
                    'statusCode' => 201
                ], 201);
            };

          
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'Favorite operation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }



    public function updateProduct(Request $request){
        try {
            $request->updated_at = Carbon::now()->toDateTimeString();


            // // try{
                $FavoritesModel = FavoritesModel::findorFail($request->id);
            // }catch(ModelNotFoundException $e){
            //     return 33;
            // };
            // return 21;

            $store_id =  $request->filled('store_id') ? $request->store_id : $FavoritesModel->store_id;

            if(auth()->guard('store')->user()){
                $store_id = auth()->guard('store')->user()->id;
            }
            // return $store_id;

            $FavoritesModel->prod_cat_id = $request->filled('prod_cat_id') ? $request->prod_cat_id : $FavoritesModel->prod_cat_id;
            $FavoritesModel->store_id = $store_id;
            $FavoritesModel->product_title = $request->filled('product_title') ? $request->product_title : $FavoritesModel->product_title;
            $FavoritesModel->product_sub_title = $request->filled('product_sub_title') ? $request->product_sub_title : $FavoritesModel->product_sub_title;
            $FavoritesModel->product_desc = $request->filled('product_desc') ? $request->product_desc : $FavoritesModel->product_desc;
            $FavoritesModel->unit = $request->filled('unit') ? $request->unit : $FavoritesModel->unit;
            $FavoritesModel->product_banner_img = $request->filled('product_banner_img') ? $request->product_banner_img : $FavoritesModel->product_banner_img;
            $FavoritesModel->product_code = $request->filled('product_code') ? $request->product_code : $FavoritesModel->product_code;
            $FavoritesModel->product_price = $request->filled('product_price') ? $request->product_price : $FavoritesModel->product_price;
            $FavoritesModel->old_price = $request->filled('old_price') ? $request->old_price : $FavoritesModel->old_price;
            $FavoritesModel->is_available = $request->filled('is_available') ? $request->is_available : $FavoritesModel->is_available;
            $FavoritesModel->is_new = $request->filled('is_new') ? $request->is_new : $FavoritesModel->is_new;
            $FavoritesModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $FavoritesModel->is_popular;
            $FavoritesModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $FavoritesModel->is_recommended;

            // return $request->all();
            $FavoritesModel->save();

            // $FavoritesModel->update($request->all());

            return response()->json([
                'data' => $FavoritesModel,
                'msg' => 'Product updated successfully.',
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Product update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ], 409);
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
