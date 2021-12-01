<?php

namespace App\Models;


use App\Models\CategoryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class SubCatModel extends Model {

    use HasFactory, SoftDeletes;

    protected $table = 'sub_categories';

    protected $fillable = ['cat_id', 'sub_cat_title', 'sub_cat_desc', 'cat_type', 'sub_cat_image']; 


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

    public function storeSubCatGetAll(){ 

        $data = $this->where('cat_type', 1)->get();
        return $this->exception($data);

    }

    public function productSubCatGetAll(){

        /* All the Sub categories */
        $data = $this->where('cat_type', 2)->get();
        return $this->exception($data);

    }

    public function getSubCatSingle($id){

        $data = $this->find($id);
        return $this->exception($data);
    }

    public function createSubCat(Request $request){

        $image_name = $request->cat_image;
        if($request->hasFile('sub_cat_image')){
            $image_name = $request->cat_image->getClientOriginalName();

            $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $destinationPath = app()->basePath($path);
            $request->file('sub_cat_image')->move($destinationPath, $image_name);

            if($request->file('sub_cat_image')->isValid()){
                return response()->json([
                    'msg' => 'Image upload unsuccessful'
                ]);
            }
        }

        try {
            $SubCategoryModel = new SubCatModel;

            $SubCategoryModel->sub_cat_title = $request->sub_cat_title;
            $SubCategoryModel->sub_cat_desc = $request->sub_cat_desc;
            $SubCategoryModel->sub_cat_type = $request->sub_cat_type;
            $SubCategoryModel->sub_cat_image = $image_name;
            $SubCategoryModel->save();

            return response()->json([
                'data' => $SubCategoryModel,
                'msg' => 'New Sub Category created successfully',
                'statusCode' => 201
            ]);
         }catch(\Exception $e){
            return response()->json([
                'msg' => 'Sub Category creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }


    public function updateCat(Request $request){

        $image_name = $request->cat_image;
        if($request->hasFile('sub_cat_image')){
            $image_name = $request->cat_image->getClientOriginalName();

            $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $destinationPath = app()->basePath($path);
            $request->file('sub_cat_image')->move($destinationPath, $image_name);

            if($request->file('sub_cat_image')->isValid()){
                return response()->json([
                    'msg' => 'Image upload unsuccessful'
                ]);
            }
        }

        try {
            $request->updated_at = Carbon::now()->toDateTimeString();

            $SubCategoryModel = SubCatModel::findorFail($request->id);

            $SubCategoryModel->sub_cat_title = $request->has('sub_cat_title') ? $request->sub_cat_title : $SubCategoryModel->sub_cat_title;
            $SubCategoryModel->sub_cat_desc = $request->has('sub_cat_desc') ? $request->sub_cat_desc : $SubCategoryModel->sub_cat_desc;
            $SubCategoryModel->sub_cat_image = $request->has('sub_cat_image') ? $request->sub_cat_image : $SubCategoryModel->sub_cat_image;
            $SubCategoryModel->cat_type = $request->has('cat_type') ? $request->cat_type : $SubCategoryModel->cat_type;
            $SubCategoryModel->save();

            return response()->json([
                'data' => $SubCategoryModel,
                'msg' => 'Records updated successfully.',
                'statusCode' => 200]);
        }
        catch(\Exception $e){
            return response()->json([
                'msg' => 'Update operation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }


    public function ProductCategory($id){

        return $id;
        try {
            $data = $this->find($id)    ->maincategory();
            return response()->json([
                'data' => $data,
                'msg' => 'Sub Category selection successful!',
                'statusCode' => 200]);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }
}
