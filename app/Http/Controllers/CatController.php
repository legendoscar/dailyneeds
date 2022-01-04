<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['getAllStoreCat','getCatSingle']]);
        $this->middleware('admin', ['only' => ['createCat','updateCat', 'deleteCat', 'deleteCatPerm']]);
    }


    /** 
     * Create a new controller instance.
     *
     * @return void
     */

    public function getAllStoreCat(CategoryModel $CategoryModel)
    {
        // return 33;
        return $CategoryModel->storeCatGetAll();

    }

    public function getAllProductCat(CategoryModel $CategoryModel)
    {
        return $CategoryModel->productCatGetAll();

    }


    public function getCatSingle(Request $request, CategoryModel $CategoryModel)
    {

        return $CategoryModel->getCatSingle($request->id);
    }


    public function createCat(Request $request, CategoryModel $CategoryModel)
    {

        $rules = [
            'cat_title' => 'bail|required|unique:categories|string',
            'cat_desc' => 'bail|string',
            'cat_type' => 'bail|numeric',
            'cat_image' => 'bail|file',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
         };

        $CategoryModel = new CategoryModel;

        return $CategoryModel->createCat($request);

    }


    public function updateCat(Request $request, CategoryModel $CategoryModel)
    {
        // return 44;
        $rules = [
            'cat_title' => 'bail|unique:categories|string',
            'cat_desc' => 'bail|string',
            'cat_type' => 'bail|numeric',
            'cat_image' => 'bail|file',
            'is_active' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ]);
         }; 

        return $CategoryModel->updateCat($request);
    }


    public function deleteCat(Request $request, CategoryModel $CategoryModel) #recycle bin
    {
        $id = $request->id;
        return $CategoryModel->deleteCat($id);
    }
    
    
    public function deleteCatPerm(Request $request, CategoryModel $CategoryModel) #delete permanently
    {
        $id = $request->id;
        return $CategoryModel->deleteCatPerm($id);
    }


    public function getTrashed(Request $request, CategoryModel $CategoryModel) #retrieve only thrashed items
    {

        return $CategoryModel->getCatSingle($request->id);
    }


    /**
         * Create a new controller instance for getting Sub categories of stores and products.
         *
         * @return void
     */

    public function getCatSub(Request $request, CategoryModel $CategoryModel){

        $id = $request->id;
        return $CategoryModel->catSub($id); 


    }
}
