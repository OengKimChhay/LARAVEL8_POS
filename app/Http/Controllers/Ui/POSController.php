<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ui\Order;
use App\Models\Ui\OrderDetail;
use App\Models\Ui\Product;

class POSController extends Controller
{
    public function takeOrder(Request $req){
        //===========>> Check validation
        $this->validate($req, [
            "cart"       => "required", 
            "discount"   => "required",
            "cashier_id" => "required",
            "table"      => "required"
        ],[
            "table.required" => "Please select table number."
        ]);

        
        // ====================== Order ==========================
            $order                          = new Order;
            $order->receipt_number          = $this->generateReceiptNumber();
            $order->cashier_id              = $req->cashier_id;
            $order->total_received_khr      = $req->total_received_khr;
            $order->total_received_usd      = $req->total_received_usd;
            $order->amount_pay_price_khr    = $req->amount_pay_price_khr;
            $order->amount_pay_price_usd    = $req->amount_pay_price_usd;
            $order->table                   = $req->table;
            $order->ordered_at              = Date('Y-m-d H:i:s');
            $order->paid_at                 = Date('Y-m-d H:i:s');
            $success1 = $order->save();
        // ======================Order Detail ====================
            $details = [];
            $carts = json_decode($req->cart); // we have to convert from string to php variable
                foreach($carts as $cart){

                    $product = Product::find($cart->product_id); //product_id is data in obj from card bar
                    if($product){
                        $details[] = [
                            'order_id'      => $order->id, 
                            'product_id'    => $product->id, 
                            'qty'           => $cart->qty, 
                            'discount'      => $req->discount,
                            'unit_price'    => $product->unitprice,
                            'ordered_at'    => Date('Y-m-d H:i:s')
                        ];
                    }
                } 
            $success2 = OrderDetail::insert($details);
              
            if(!$success1 && !$success2){
                return response()->json([
                    'status' =>'fail',
                    'message'=>"Somthing went wrong please try to order again!."
                ]);
            }else{
                return response()->json([
                    'status' =>'success',
                    'message'=>"Order success!."
                ]);
            }
    }

    function generateReceiptNumber(){
        $number = rand(1000000, 9999999); 
        $check = Order::where('receipt_number', $number)->first(); 
        if($check){
            return $this->generateReceiptNumber();
        }

        return $number; 
    }
}
