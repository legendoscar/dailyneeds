<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class OrderItemsModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'order_items';

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'
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

    public function orderItemsGetAll(){ 

        try {
            return response()->json([
             'data' => $this->join('products', 'order_items.product_id', '=', 'products.id')
             ->get(),
            //  ->get('products.created_at'),
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

    public function showOneOrderItem($id){
        try {
            $data = $this->join('products', 'order_items.product_id', '=', 'products.id')
            ->findOrFail($id); 
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

    public function createOrderItem(Request $request){
        try{
            
            foreach($request->all() as $items=>$item){

                return $item;

                $OrderItemsModel = new OrderItemsModel;
                $OrderItemsModel->order_id = $request->order_id;
                $OrderItemsModel->product_id = $request->product_id;
                $OrderItemsModel->quantity = $request->quantity;
                $OrderItemsModel->amount = $request->amount;
                $OrderItemsModel->save  ();
            }


            return response()->json([
                'data' => $OrderItemsModel,
                'msg' => 'New Order Item created successfully',
                'statusCode' => 201
            ]);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'New Order Item creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ]);
        }
    }




    public function updateOrderItem(Request $request){
        try {
            $request->updated_at = Carbon::now()->toDateTimeString();


            $OrderItemsModel = OrderItemsModel::findorFail($request->id);

            $OrderItemsModel->order_id = $request->filled('order_id') ? $request->order_id : $OrderItemsModel->order_id;
            $OrderItemsModel->product_id = $request->filled('product_id') ? $request->product_id : $OrderItemsModel->product_id;
            $OrderItemsModel->amount = $request->filled('amount') ? $request->amount : $OrderItemsModel->amount;
            $OrderItemsModel->quantity = $request->filled('quantity') ? $request->quantity : $OrderItemsModel->quantity;

            // return $request->all();
            $OrderItemsModel->save();

            // $OrderItemsModel->update($request->all());

            return response()->json([
                'data' => $OrderItemsModel,
                'msg' => 'Order Items updated successfully.',
                'statusCode' => 200]);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Order Items update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ]);
        }
    }


    public function deleteOrderItem($id){

        // return 33;

        $data = $this->findorFail($id)->delete();
        return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
}
