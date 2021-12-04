<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

Class ProductsModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'products';

    protected $fillable = ['prod_cat_id', 'store_id', 'product_title', 'product_sub_title', 
    'product_desc', 'unit', 'product_banner_img', 'product_images' , 'product_code', 'price', 'old_price', 
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

        $data = $this->where('cat_type', 1)->get();
        return $this->exception($data); 
    }
}
