<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\SubCatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCatController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['getAllStoreCat','getCatSingle']]);
        $this->middleware('admin', ['only' => ['createSubCat','updateSubCat', 'deleteSubCat', 'deleteSubCatPerm']]);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showAllStoreSubCat(SubCatModel $SubCatModel)
    {
        //id=1
        return $SubCatModel->storeSubCatGetAll(); 

    }

    public function showAllProductSubCat(SubCatModel $SubCatModel) 
    {
        //id=2
        return $SubCatModel->productSubCatGetAll();

    }

 
    public function getSubCatSingle(Request $request, SubCatModel $SubCatModel)
    {

        return $SubCatModel->getSubCatSingle($request->id);
    }


    public function validData(Request $request){

        return $this->validate($request, [
            'sub_cat_title' => 'bail|required|unique:sub_categories|string',
            'sub_cat_desc' => 'bail|string',
            'cat_id' => 'bail|numeric|required',
            'sub_cat_image' => 'bail|file',
        ]);

    }

    public function createSubCat(Request $request, SubCatModel $SubCatModel)
    {

        $validData=$this->validData($request);

        $SubCatModel = new SubCatModel;

        return $SubCatModel->createSubCat($request);

    }


    public function updateSubCat(Request $request)
    {
        $rules = [
            'sub_cat_title' => 'bail|unique:sub_categories|string',
            'sub_cat_desc' => 'bail|string',
            'cat_id' => 'bail|numeric',
            'sub_cat_image' => 'bail|file',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
         };

        $SubCatModel = new SubCatModel;

        return $SubCatModel->updateSubCat($request);
    }


    public function deleteSubCat(Request $request, SubCatModel $SubCatModel)
    {
        $id = $request->id;
        return $SubCatModel->deleteSubCat($request, $id);
    }

    public function getProductCategory(Request $request, SubCatModel $SubCatModel)
    {

        
        $id = $request->all();
    //     return $model = $CategoryModel->find($id);
    //    $id = $CategoryModel->find($id)->id;
    //     // return $id = $SubCatModel->findOrFail($request->id);
        return $SubCatModel->ProductCategory($id);


    }
}
