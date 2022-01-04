<?php

namespace App\Http\Controllers;

use App\Models\OrderItemsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderItemsController extends Controller 
{

    public function __construct()
    {
        $this->middleware('admin', ['only' => [
            'showAllOrderItems',
            //  'showOneOrderItem', 
            //  'deleteOrderItem'
             ]]);
        // $this->middleware('auth:user', ['except' => ['showAllProducts','showOneProduct']]);
        // $this->middleware('admin', ['only' => ['createCat','updateCat', 'deleteCat', 'deleteCatPerm']]);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showAllOrderItems(OrderItemsModel $OrderItemsModel)
    { 

       return $OrderItemsModel->orderItemsGetAll();
    }


    public function showOneOrderItem(Request $request, OrderItemsModel $OrderItemsModel)
    {
        return $OrderItemsModel->showOneOrderItem($request->id);
    }


    public function createOrderItem(Request $request, OrderItemsModel $OrderItemsModel)
    {          
        // if(auth()->guard('store')){

            $rules = [
                'order_id' => 'bail|exists:orders,id',
                'product_id' => 'bail|numeric|exists:products,id',
                'amount' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'quantity' => 'bail|integer',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };

            return $OrderItemsModel->createOrderItem($request);
        // }
    
        // return response()->json([
        //     'msg' => 'Forbidden! Not allowed to create products!',
        //     'statusCode' => 409
        // ]);
    }


    public function updateOrderItem(Request $request, OrderItemsModel $OrderItemsModel)
    {

        $rules = [
            'order_id' => 'bail|numeric|exists:orders,id',
            'product_id' => 'bail|numeric|exists:products,id',
            'amount' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'bail|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
         };

        //  return $request->all();

         return $OrderItemsModel->updateOrderItem($request);

       
    }


    public function deleteOrderItem($id)
    {

        // return $OrderItemsModel->ProductCategory();
        try {
            OrderItemsModel::findorFail($id)->delete();
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
    }

    public function ProductBelongsTo($id){
        try {
            $data = OrderItemsModel::find($id)->ProductsCategory;
            return response()->json([
                'msg' => 'Category selection successful!',
                'data' => $data,
                'statusCode' => 200
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }

    //
}
