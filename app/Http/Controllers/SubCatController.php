<?php

namespace App\Http\Controllers;

use App\Models\SubCatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCatController extends Controller
{
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
            'cat_title' => 'bail|required|unique:categories|string',
            'cat_desc' => 'bail|string',
            'cat_type' => 'bail|numeric|required',
            'cat_image' => 'bail|file',
        ]);

    }

    public function createSubCat(Request $request, SubCatModel $SubCatModel)
    {

        $validData=$this->validData($request);

        $SubCatModel = new SubCatModel;

        return $SubCatModel->createSubCat($request);

    }


    public function updateCat(Request $request)
    {
        $this->validate($request, [
            'cat_title' => 'bail|unique:categories|string',
            'cat_desc' => 'bail|string',
            'cat_type' => 'bail|numeric',
            'cat_image' => 'bail|file',
        ]);

        $SubCatModel = new SubCatModel;

        return $SubCatModel->storeCatUpdate($request);
    }


    public function deleteCat(Request $request, SubCatModel $SubCatModel)
    {
        $id = $request->id;
        return $SubCatModel->storeCatDeleteOne($id);
    }

    public function getProductCategory(Request $request, SubCatModel $SubCatModel){

        // $id = $request->id;
        return $SubCatModel->ProductCategory($request->id);


    }
}
