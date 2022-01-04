<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class UserAddressModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'user_address';

    protected $fillable = ['user_id', 'address_title', 'address_country', 'address_state', 'address_city',
    'address_zip_code', 'address_street', 'address_latitude', 'address_longitude', 'user_location_id'
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

    public function showAllUserAddress(){ 

        try {
            return response()->json([
                'data' => $this->all(),
                'statusCode' => 200,
                'msg' => 'Records returned successfully.'
         ], 200);
         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'No record found!', 
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ], 409);
         }
    }

    public function showOneUserAddress(request $request){
        // return 33;
        try {
            $id = $request->id;
            $data = $this->findOrFail($id); 
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => 'Record returned successfully.',
                    'statusCode' => 200
                ], 200)
                : $ret = response()->json([
                'msg' => 'No Record found for location with ID: ' . $id,
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

    public function createUserAddress(Request $request){
        try{
            // return 'ready';
            $UserAddressModel = new UserAddressModel;
                
                $UserAddressModel->user_id = $request->user_id;
                $UserAddressModel->user_location_id = $request->user_location_id;
                $UserAddressModel->address_title = $request->address_title;
                $UserAddressModel->address_street = $request->address_street;
                $UserAddressModel->address_city = $request->address_city;
                $UserAddressModel->address_state = $request->address_state;
                $UserAddressModel->address_country = $request->address_country;
                $UserAddressModel->address_zip_code = $request->address_zip_code;
                $UserAddressModel->address_latitude = $request->address_latitude;
                $UserAddressModel->address_longitude = $request->address_longitude;
                $UserAddressModel->address_primary = $request->address_primary;
                $UserAddressModel->save();
                
            // }
            return response()->json([
                'data' => $UserAddressModel,
                // 'total' => $count,
                'msg' => 'New User address created successfully',
                'statusCode' => 201
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'New user address creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }


    public function updateUserAddress(Request $request){
        try {
            // return $request->all();
            $request->updated_at = Carbon::now()->toDateTimeString();


            $UserAddressModel = UserAddressModel::findorFail($request->id);

            $UserAddressModel->user_id = $request->filled('user_id') ? $request->user_id : $UserAddressModel->user_id;
            $UserAddressModel->user_location_id = $request->filled('user_location_id') ? $request->user_location_id : $UserAddressModel->user_location_id;
            $UserAddressModel->address_street = $request->filled('address_street') ? $request->address_street : $UserAddressModel->address_street;
            $UserAddressModel->address_city = $request->filled('address_city') ? $request->address_city : $UserAddressModel->address_city;
            $UserAddressModel->address_state = $request->filled('address_state') ? $request->address_state : $UserAddressModel->address_state;
            $UserAddressModel->address_country = $request->filled('address_country') ? $request->address_country : $UserAddressModel->address_country;
            $UserAddressModel->address_zip_code = $request->filled('address_zip_code') ? $request->address_zip_code : $UserAddressModel->address_zip_code;
            $UserAddressModel->address_latitude = $request->filled('address_latitude') ? $request->address_latitude : $UserAddressModel->address_latitude;
            $UserAddressModel->address_longitude = $request->filled('address_longitude') ? $request->address_longitude : $UserAddressModel->address_longitude;
            $UserAddressModel->address_primary = $request->filled('address_primary') ? $request->address_primary : $UserAddressModel->address_primary;

            $UserAddressModel->save();

            // $UserAddressModel->update($request->all());

            return response()->json([
                'data' => $UserAddressModel,
                'msg' => 'User Address updated successfully.',
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'User Address update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ], 409); 
        }
    }


    public function deleteUserAddress($id){

        // return 33;

        try {
            $this->findorFail($id)->delete();
            return response()->json([
                'msg' => 'Deleted successfully!',
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Delete operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }

        // $data = $this->findorFail($id)->delete();
        // return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
}
