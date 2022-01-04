<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class CategoryModel extends Model { 

    use SoftDeletes, HasFactory;

    protected $table = 'categories';

    protected $fillable = ['cat_title', 'cat_desc', 'cat_type', 'cat_image'];


    public function subCategory(){
        return $this->hasMany('App\Models\SubCatModel', 'cat_id');
        // return 44;
    }
    
    public function mainCategory(){
        return $this->hasOne('App\Models\CategoryModel'); 
        // return 44;
    }
 
    public function exception($data, $success = 'Records returned successfully.',
    $failed = 'No Record found.'
    ){

        try{
             !empty($data)
                 ? $ret = response()->json([
                     'data' => $data,
                     'statusCode' => 200,
                     'msg' => $success
                 ], 200)
         : $ret = response()->json([
             'data' => $data,
             'msg' => $failed,
             'statusCode' => 422
         ], 422);

         return $ret;


         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'Ooops!! Error encountered!',
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ], 409);
         }
    }

    public function storeCatGetAll(){ 

        $data = $this->where('cat_type', 1)->get();
        return $this->exception($data);  

    }

    public function productCatGetAll(){

        $data = $this->where('cat_type', 2)->get();
        return $this->exception($data);

    }

    public function getCatSingle($id){

        $data = $this->find($id);
        return $this->exception($data);
    }

    public function createCat(Request $request){

        $image_name = $request->cat_image;
        if($request->hasFile('cat_image')){
            $image_name = $request->cat_image->getClientOriginalName();

            $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $destinationPath = app()->basePath($path);
            $request->file('cat_image')->move($destinationPath, $image_name);

            if($request->file('cat_image')->isValid()){
                return response()->json([ 
                    'msg' => 'Image upload unsuccessful'
                ]);
            }
        }

        try {
            $CategoryModel = new CategoryModel;

            $CategoryModel->cat_title = $request->cat_title;
            $CategoryModel->cat_desc = $request->cat_desc;
            $CategoryModel->cat_type = $request->cat_type;
            $CategoryModel->cat_image = $image_name;

            if($request->getRequestUri() == '/api/category/store') {
                $CategoryModel->cat_type = 1;
                $type = 'Store';
            }
           else{
                $CategoryModel->cat_type = 2;
                $type = 'Product';
           }



            $CategoryModel->save();

            return response()->json([
                'data' => $CategoryModel,
                'msg' => 'New '. $type. ' category created successfully',
                'statusCode' => 201
            ], 201);
         }catch(\Exception $e){
            return response()->json([
                'msg' => 'Store '. $type . 'Category creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }

    public function updateCat(Request $request){

        // return 33;
        $image_name = $request->cat_image;
        if($request->hasFile('cat_image')){
            $image_name = $request->cat_image->getClientOriginalName();

            $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $destinationPath = app()->basePath($path);
            $request->file('cat_image')->move($destinationPath, $image_name);

            if($request->file('cat_image')->isValid()){
                return response()->json([
                    'msg' => 'Image upload unsuccessful'
                ]);
            }
        }

        try {
            $request->updated_at = Carbon::now()->toDateTimeString();

            $CategoryModel = CategoryModel::findorFail($request->id);

            $CategoryModel->cat_title = $request->has('cat_title') ? $request->cat_title : $CategoryModel->cat_title;
            $CategoryModel->cat_desc = $request->has('cat_desc') ? $request->cat_desc : $CategoryModel->cat_desc;
            $CategoryModel->cat_image = $request->has('cat_image') ? $request->cat_image : $CategoryModel->cat_image;
            $CategoryModel->cat_type = $request->has('cat_type') ? $request->cat_type : $CategoryModel->cat_type;
            $CategoryModel->is_active = $request->has('is_active') ? $request->is_active : $CategoryModel->is_active;


            if($request->getRequestUri() == '/api/category/store') {
                // $CategoryModel->cat_type = 1;
                $type = 'Store';
            }
           else{
                // $CategoryModel->cat_type = 2;
                $type = 'Product';
           }

            $CategoryModel->save();

            return response()->json([
                'data' => $CategoryModel,
                'msg' => $type . ' updated successfully.',
                'statusCode' => 200
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'msg' => $type . ' update operation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }


    }

    public function deleteCat($id){ #trash

        $data = $this->findorFail($id)->delete();
        return $this->exception($data, $success = 'Category deleted successfully.', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
    
    public function deleteCatPerm($id){ #delete permanenetly

        $data = $this->findorFail($id)->forceDelete();
        return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }

    public function getTrashed($id){ #get thrashed items

        $data = $this->find($id);
        return $this->exception($data);
    }

    public function catSub($id){
        try {
            $data = $this->find($id)->subCategory;
            return response()->json([
                'data' => $data,
                'msg' => 'Sub Category selection successful!',
                'statusCode' => 200,
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }
}
