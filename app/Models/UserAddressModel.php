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

    protected $fillable = ['user_id', 'address_title', 'address_body', 'address_state', 'address_city',
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
         ]);
         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'No record found!', 
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ]);
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
                ])
                : $ret = response()->json([
                'msg' => 'No Record found for location with ID: ' . $id,
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

    public function createLocation(Request $request){
        try{
            $LocationsModel = new LocationsModel;
                
                $LocationsModel->name = $request->name;
                $LocationsModel->desc = $request->desc;
                $LocationsModel->location_country_name = $request->location_country_name;
                $LocationsModel->location_country_code = $request->location_country_code;
                $LocationsModel->is_popular = $request->is_popular;
                $LocationsModel->is_recommended = $request->is_recommended;
                $LocationsModel->is_active = $request->is_active;
                $LocationsModel->save();
                
            // }
            return response()->json([
                'data' => $LocationsModel,
                // 'total' => $count,
                'msg' => 'New Location created successfully',
                'statusCode' => 201
            ]);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'New Locations creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }


    }




    public function updateLocation(Request $request){
        try {
            $request->updated_at = Carbon::now()->toDateTimeString();


            $LocationsModel = LocationsModel::findorFail($request->id);

            $LocationsModel->name = $request->filled('name') ? $request->name : $LocationsModel->name;
            $LocationsModel->desc = $request->filled('desc') ? $request->desc : $LocationsModel->desc;
            $LocationsModel->location_country_name = $request->filled('location_country_name') ? $request->location_country_name : $LocationsModel->location_country_name;
            $LocationsModel->location_country_code = $request->filled('location_country_code') ? $request->location_country_code : $LocationsModel->location_country_code;
            $LocationsModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $LocationsModel->is_popular;
            $LocationsModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $LocationsModel->is_recommended;
            $LocationsModel->is_active = $request->filled('is_active') ? $request->is_active : $LocationsModel->is_active;

            $LocationsModel->save();

            // $LocationsModel->update($request->all());

            return response()->json([
                'data' => $LocationsModel,
                'msg' => 'Location updated successfully.',
                'statusCode' => 200]);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Location update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ]); 
        }
    }


    public function deleteUserAddress($id){

        // return 33;

        try {
            $this->findorFail($id)->delete();
            return response()->json([
                'msg' => 'Deleted successfully!',
                'statusCode' => 200]);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Delete operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ]);
        }

        // $data = $this->findorFail($id)->delete();
        // return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
}
