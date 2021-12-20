<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class OrdersModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'orders';

    protected $fillable = ['user_id', 'store_id', 'driver_id', 'order_code', 'kitchen_instructions', 'location',
    'destination_address', 'tax_charge', 'store_charge', 'delivery_charge', 'total_amount', 'delivery_mode',
    'payment_mode', 'payment_status', 'cash_change_amount', 'time_order_accepted', 'time_order_assigned',
    'store_schedule_order_reason', 'store_schedule_order_time', 'store_cancel_reason', 'store_decline_cancel_time',
    'time_driver_accepted_delivery', 'user_schedule_order_reason', 'user_schedule_order_time', 'user_decline_cancel_reason',
    'user_decline_cancel_time', 'time_order_processing', 'time_order_in_transit', 'time_order_delivered', 'is_complete',
    'is_repeat', 'repeat_count'
]; 

     public function OrderItems(){
        return $this->hasMany('App\Models\OrderItemsModel', 'order_id', 'id');
    }

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

    public function orderGetAll(){ 

        try {
            return response()->json([
             'data' => $this->join('order_items', 'orders.id', '=', 'order_items.order_id')
             ->get(),
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

    public function showOneOrder($id){
        try {
            $data = $this
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->get();  
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => 'Record returned successfully.',
                    'statusCode' => 200
                ])
                : $ret = response()->json([
                'msg' => 'No Record found for product with ID: ' . $id,
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

    function generateOrderCodeNumber() {
        // return substr('hello', 1,-1);
        // return substr(
        //     uniqid(mt_rand(1000000000, 9999999999)),
        //      1, -1
        // );
        $number = mt_rand(1000000000, 9999999999); // better than rand()
    
        // call the same function if the barcode exists already
        if ($this->ordercodeNumberExists($number)) {
            return $this->generateOrderCodeNumber();
        }
    
        // otherwise, it's valid and can be used
        return $number;
    }
    
    function ordercodeNumberExists($number) {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        return OrdersModel::whereOrderCode($number)->exists();
    }


    public function createOrder(Request $request){
        try{

            // return $request->all();
            // DB::transaction(function () {
                $OrdersModel = new OrdersModel;

                $OrdersModel->user_id = $request->user_id;
                $OrdersModel->store_id = $request->store_id;
                $OrdersModel->order_code = $this->generateOrderCodeNumber();
                $OrdersModel->kitchen_instructions = $request->kitchen_instructions;
                $OrdersModel->location = $request->location;
                $OrdersModel->destination_address = $request->destination_address;

                $OrdersModel->total_amount = $request->total_amount;


                // $OrdersModel->product_banner_img = $image_name;
                // $OrdersModel->price = $request->price;
                $OrdersModel->save();

                $last_id = $OrdersModel->id;

                $OrderItemsModel = new OrderItemsModel;
                $OrderItemsModel->createOrderItem($request, $last_id);

                return response()->json([
                    'data' => $OrdersModel,
                    'msg' => 'New Order created successfully',
                    'statusCode' => 201
                ]);
            // });
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'Product creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }




    public function updateProduct(Request $request){
        try {
            $request->updated_at = Carbon::now()->toDateTimeString();


            $OrdersModel = OrdersModel::findorFail($request->id);

            $OrdersModel->prod_cat_id = $request->filled('prod_cat_id') ? $request->prod_cat_id : $OrdersModel->prod_cat_id;
            $OrdersModel->product_title = $request->filled('product_title') ? $request->product_title : $OrdersModel->product_title;
            $OrdersModel->product_sub_title = $request->filled('product_sub_title') ? $request->product_sub_title : $OrdersModel->product_sub_title;
            $OrdersModel->product_desc = $request->filled('product_desc') ? $request->product_desc : $OrdersModel->product_desc;
            $OrdersModel->unit = $request->filled('unit') ? $request->unit : $OrdersModel->unit;
            $OrdersModel->product_banner_img = $request->filled('product_banner_img') ? $request->product_banner_img : $OrdersModel->product_banner_img;
            $OrdersModel->product_code = $request->filled('product_code') ? $request->product_code : $OrdersModel->product_code;
            $OrdersModel->price = $request->filled('price') ? $request->price : $OrdersModel->price;
            $OrdersModel->old_price = $request->filled('old_price') ? $request->old_price : $OrdersModel->old_price;
            $OrdersModel->is_available = $request->filled('is_available') ? $request->is_available : $OrdersModel->is_available;
            $OrdersModel->is_new = $request->filled('is_new') ? $request->is_new : $OrdersModel->is_new;
            $OrdersModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $OrdersModel->is_popular;
            $OrdersModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $OrdersModel->is_recommended;

            // return $request->all();
            $OrdersModel->save();

            // $OrdersModel->update($request->all());

            return response()->json([
                'data' => $OrdersModel,
                'msg' => 'Product updated successfully.',
                'statusCode' => 200]);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Product update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ]);
        }
    }
    public function deleteOrder($id){

        // return 33;

        $data = $this->findorFail($id)->delete();
        return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
}
