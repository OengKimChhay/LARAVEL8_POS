<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ui\Order;
use App\Models\Auth\User;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function listing(Request $req){
        // 2 lines above is how to take token
        // $token = $req->bearerToken();
        // $order =  $req->user()->currentAccessToken();   
        $auth = auth()->user();
        $order = Order::select('*')->with(['cashier','details']);

        // ==========================>> Date Range
        if($req->from != "" && $req->to != ""){
            $from = Carbon::createFromFormat('dd/mm/yyyy', $req->from);
            $to = Carbon::createFromFormat('dd/mm/yyyy', $req->to);
            $order = $order->whereBetween('created_at', [$from, $to]);
        }
        
        // =========================== Search receipt number
        if( $req->receipt_number && $req->receipt_number !="" ){
            $order = $order->where('receipt_number', $req->receipt_number);
        }
        
        // =========================== If Not admin, get only record that this user make order
        if($auth->userType == "User"){ 
            $order = $order->where('cashier_id', $auth->id); 
        }

        $order = $order->orderBy('id', 'desc')->paginate(15);
        return response()->json($order, 200);
    }
    public function delete($id){
        $auth = auth()->user();
        if($auth->userType == "Admin"){ 
            $order = Order::where('id',$id)->first();
            if($order){
                $order->delete();
                return response()->json([
                    'status'=>'success',
                    'message'=>'An order has been deleted.',
                ]);
            }else{
                return response()->json([
                    'status'=>'fail',
                    'message'=>'Order not found.',
                ]);
            }
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'You do not have permission to delete'
            ]);
        }
    }
}
