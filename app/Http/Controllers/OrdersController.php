<?php

namespace App\Http\Controllers;

use App\Models\OrdersModel;
// use App\Models\OrdersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller 
{

    public function __construct()
    {
        $this->middleware('auth:api');
        
        $this->middleware('admin', ['only' => [
            'showAllOrders',
            //  'showOneOrder'
            ]
            ]);
        // $this->middleware('admin', ['only' => ['createCat','updateCat', 'deleteCat', 'deleteCatPerm']]);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showAllOrders(OrdersModel $OrdersModel)
    { 

       return $OrdersModel->orderGetAll();
    }


    public function showOneOrder(Request $request, OrdersModel $OrdersModel)
    {
        return $OrdersModel->showOneOrder($request->id);
    }


    public function createOrder(Request $request, OrdersModel $OrdersModel)
    {          
        // if(auth()->guard('store')){
            // return $request->all();

            $rules = [
                'user_id' => 'bail|required|numeric|exists:users,id',
                'store_id' => 'bail|required|numeric|exists:stores,id',
                // 'driver_id' => 'bail|required|numeric|exists:driver_details,id',
                // 'order_code' => 'bail|required|string|min:6|unique:orders,order_code',
                'kitchen_instructions' => 'bail|string',
                'location' => 'bail|required|numeric|exists:locations,id',
                'destination_address' => 'bail|required|numeric|exists:user_address,id',
                'tax_charge' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'store_charge' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'delivery_charge' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'cash_change_amount' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'total_amount' => 'bail|required|regex:/^\d+(\.\d{1,2})?$/',
                'delivery_mode' => 'bail|string|in:pick-up,delivery',
                'payment_mode' => 'bail|string|in:cash,transfer,card,pos',

                // 'order_id' => 'bail|exists:orders,id',
                'product_id' => 'bail|numeric|exists:products,id',
                'amount' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
                'quantity' => 'bail|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ]);
            };        
            // return 'passed';
            // return $OrdersModel->generateBarcodeNumber();
            return $OrdersModel->createOrder($request);
        // }
    
        return response()->json([
            'msg' => 'Forbidden! Not allowed to create products!',
            'statusCode' => 409
        ]);
    }


    public function updateProduct(Request $request, OrdersModel $OrdersModel)
    {

        $rules = [
            'prod_cat_id' => 'bail|numeric|exists:sub_categories,id',
            'store_id' => 'bail|numeric|exists:stores,id',
            'product_title' => 'bail|string',
            'product_sub_title' => 'bail|string',
            'product_desc' => 'bail|string',
            'unit' => 'bail|string',
            'product_banner_img' => 'bail|file',
            'product_code' => 'bail|string',
            'price' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
            'old_price' => 'bail|regex:/^\d+(\.\d{1,2})?$/',
            'is_available' => 'bail|integer',
            'is_new' => 'bail|integer',
            'is_popular' => 'bail|integer',
            'is_recommended' => 'bail|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ]);
         };

         return $request->all();

         return $OrdersModel->updateProduct($request);

       
    }


    public function deleteProduct($id)
    {

        // return $OrdersModel->ProductCategory();
        try {
            OrdersModel::findorFail($id)->delete();
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
    }

    public function ProductBelongsTo($id){
        try {
            $data = OrdersModel::find($id)->ProductsCategory;
            return response()->json([
                'msg' => 'Category selection successful!',
                'data' => $data,
                'statusCode' => 200]);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }

    public function deleteOrder($id)
    {

        // return $OrderItemsModel->ProductCategory();
        try {
            OrdersModel::findorFail($id)->delete();
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
    }
}
