<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddressModel;
use Illuminate\Auth\Access\Response;


class UserAddressPolicy
{

    // public function before($role = 1){
    //     if(auth()->user()->user_role === $role){
    //         return true;
    //     }
    // }


    public function getOne(User $user, UserAddressModel $UserAddressModel) {
        return $UserAddressModel->user_id === $user->id;
            
        // ? response()->allow()->json(['msg' => 'allowed'])
        // : response()->allow()->json(['msg' => 'You do not own this Adress']);
        // ? Response::allow()
        // : Response::deny('You do not own this Adress');
        // || auth()->user()->user_role == 1
        // return Response::deny('Sorry, your level is not high enough to do that!');
        ;
    }
    

}