<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;


class StoreOwnerPolicy
{

    public function before(){
        if(auth()->user()->user_role === 1){
            return true;
        }
    }


    public function getStoreOwner($StoresModelData) {
        if(auth()->guard('store')->user()->id === $StoresModelData->id){
            return true;
        }
        
    } 
    

}