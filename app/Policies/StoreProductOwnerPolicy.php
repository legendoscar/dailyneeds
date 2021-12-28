<?php

namespace App\Policies;

use App\Models\StoresModel;
use App\Models\ProductsModel;
use Illuminate\Auth\Access\Response;


class StoreProductOwnerPolicy
{

    public function before(){
        if(auth()->user()->user_role === 1){
            return true;
        }
    }


    public function getProductOwner($StoresModel, $ProductsModel) {
        return $ProductsModel->store_id === $StoresModel->id;
        
    } 
    

}