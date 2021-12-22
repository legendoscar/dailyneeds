<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddressModel;
use Illuminate\Auth\Access\Response;


class UserAddressPolicy
{

    public function before(){
        if(auth()->user()->user_role === 1){
            return true;
        }
    }


    public function getOwner(User $user, UserAddressModel $UserAddressModel) {
        return $UserAddressModel->user_id === $user->id;
        
    }
    

}