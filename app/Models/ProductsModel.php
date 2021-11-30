<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

Class ProductsModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'products';

    protected $fillable = ['cat_id', 'store_id', 'product_title', 'product_sub_title', 
    'product_desc', 'availability_status', 'unit', 'product_image', 'product_code', 'amount'];

     public function ProductsCategory(){
        return $this->hasOne('App\Models\ProductsSubCatModel', 'id', 'cat_id');
    }
}
