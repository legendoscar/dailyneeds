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

    protected $fillable = ['order_id', 'product_id', 'quantity', 'amount'
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
         ], 200); 
         }catch(\Exception $e){
             return response()->json([
                 'msg' => 'No record found!', 
                 'err' => $e->getMessage(),
                 'statusCode' => 409
             ], 409);
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
            ], 404);
    
            return $ret;
    
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
    }

    public function createOrderItem(Request $request, OrderItemsModel $OrderItemsModel){
        // return count($request->items);
        // return $request->all();
        try{
            // return $request->all()[0]['order_id'];
            $count = count($request->all());
            // return $count;
            for ($i=0; $i < $count; $i++) {
                $d = $request->all()[$i]['order_id'];
                print $val[$i] = $d;
            }
            return $d;

            // foreach($request->all() as $item){
                // $OrderItemsModel = new OrderItemsModel;
                
               $count = count($request->items);
                for ($i=0; $i < $count; $i++) {
               
                    // return $request->items[0];
                    
                    // $data = [
                    //         'order_id' => $last_id, 
                    //         'product_id' => $request->items[$i]['product_id'], 
                    //         'quantity' => $request->items[$i]['quantity'], 
                    //         'amount' => $request->items[$i]['amount'],
                    //         'created_at' => Carbon::now(),
                    //         'updated_at' => Carbon::now(),
                    //         'deleted_at' => NULL,
                    // ];

                $OrderItemsModel->order_id = $request->items[$i]['order_id'];
                $OrderItemsModel->product_id = $request->items[$i]['product_id'];
                $OrderItemsModel->amount = $request->items[$i]['amount'];
                $OrderItemsModel->quantity = $request->items[$i]['quantity'];

                    $OrderItemsModel->save();
                }
            // }
            return response()->json([
                'data' => $this->orderBy('id', 'desc')->limit($count)->get(),
                'total' => $count,
                'msg' => 'New Order Item created successfully',
                'statusCode' => 201
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'New Order Item creation failed!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
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
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Order Items update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ], 409);
        }
    }


    public function deleteOrderItem($id){
 
        // return 33;

        $data = $this->findorFail($id)->delete();
        return $this->exception($data, $success = 'Delete operation successful!', $failed = 'Delete operation failed! No record found for id: ' . $id . '!');

    }
}
