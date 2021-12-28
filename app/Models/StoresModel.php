<?php

namespace App\Models;


use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

Class StoresModel extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject {

    use Authenticatable, Authorizable, HasFactory, SoftDeletes;
    protected $table = 'stores';
    protected $guard = 'store';

 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['store_name', 'store_address', 'store_phone', 'store_email',
     'store_image', 'store_about', 'verification_status', 'status'];

   /** 
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'rememberToken'
    ];



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


     /**
     * Get products of stores.
     *
     * @var array
     */
    // public function products()
    // {
    //     return $this->hasMany('App\Models\ProductsModel','store_id');
    // } 


    public function exception($data, $success = 'Records returned successfully.',
    $failed = 'No Record found.'
    ){

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

  
    public function getAllStores(){

        $data = $this->get();
        return $this->exception($data);

    }

    
    public function getSingleStore($id){

        $data = $this->find($id);
        return $this->exception($data);
    }

    public function registerStore(Request $request){

        try 
            {
                $store = new StoresModel();
                $store->store_name= $request->input('store_name');
                $store->store_cat_id= $request->input('store_cat_id');
                $store->store_location_id= $request->input('store_location_id');
                $store->store_phone= $request->input('store_phone');
                $store->store_email= $request->input('store_email');
                $store->password = Hash::make($request->input('password')); 
                $type = 'Store';
                
                $store->save();
                return response()->json( [
                            'data' => $store, 
                            'action' => 'create', 
                            'msg' => $type . ' account created successfully.',                             
                ], 201);
    
            } 
            catch (\Exception $e) 
            {
                return response()->json( [
                           'action' => 'create', 
                           'err' => $e->getMessage(),
                           'msg' => $type . ' account creation failed'
                ], 409);
            }
    }

    public function updateStore(Request $request){

        // return 33;
        $image_name = $request->store_image;
        if($request->hasFile('store_image')){
            $image_name = $request->store_image->getClientOriginalName();

            $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $destinationPath = app()->basePath($path);
            $request->file('store_image')->move($destinationPath, $image_name);

            if($request->file('store_image')->isValid()){
                return response()->json([
                    'msg' => 'Image upload unsuccessful'
                ]);
            }
        }

        try {
            $request->updated_at = Carbon::now()->toDateTimeString();

            $StoreModel = $this->findorFail($request->id); 

            $StoreModel->store_name = $request->has('store_name') ? $request->store_name : $StoreModel->store_name;
            $StoreModel->store_cat_id = $request->has('store_cat_id') ? $request->store_cat_id : $StoreModel->store_cat_id;
            $StoreModel->store_address = $request->has('store_address') ? $request->store_address : $StoreModel->store_address;
            $StoreModel->store_phone = $request->has('store_phone') ? $request->store_phone : $StoreModel->store_phone;
            $StoreModel->store_email = $request->has('store_email') ? $request->store_email : $StoreModel->store_email;
            $StoreModel->store_image = $request->has('store_image') ? $request->store_image : $StoreModel->store_image;
            $StoreModel->store_about = $request->has('store_about') ? $request->store_about : $StoreModel->store_about;
            $StoreModel->password = $request->has('password') ? $request->password : $StoreModel->password;
            $StoreModel->verification_status = $request->has('verification_status') ? $request->verification_status : $StoreModel->verification_status;
            $StoreModel->status = $request->has('status') ? $request->status : $StoreModel->status;
            $StoreModel->save();

            return response()->json([
                'data' => $StoreModel,
                'msg' => 'Store Records updated successfully.',
                'statusCode' => 200]);
        }
        catch(\Exception $e){
            return response()->json([
                'msg' => 'Store Update operation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }


    }

    public function deleteStore($id){
 
        // return 33;

        $data = $this->findorFail($id)->delete();
        return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }

}
