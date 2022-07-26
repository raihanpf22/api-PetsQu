<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
use App\Models\User;

class CartController extends Controller
{
    //
    public function index()
    {
        $order = Order::all();

        $data = [
            'messages'=>'Get All Data Orders',
            'status'=>200,
            'data'=>$order
            ];
            return response()->json($data, 200);
    }

    public function addCart(Request $request)
    {
        $id = $request->product_id;
        $quantity = $request->quantity;
        $product = Products::where('product_id',$id)->first();
        $user = User::where('email', auth()->user()->email)->first();

        $order = new Order;

        $order -> order_user_id = auth()->user()->user_id;
        $order -> order_product_id = $product->product_id;
        $order -> order_name_product = $product->name_product;
        $order -> order_img = $product->img;
        $order -> quantity = $quantity;
        $order -> ammount = ($product->price)*$quantity;
        $order -> order_address = $user->address;
        $order -> order_email = $user->email;
        $order -> order_status = "Keranjang";
        
        $order -> save();

        $data = [
            'messages'=>'Product Added To Cart',
            'status'=>201,
            'data'=>[
                'order_user_id'=>$order->order_user_id,
                'order_product_id'=>$order->order_product_id,
                'order_name_product'=>$order->order_name_product,
                'order_img'=>$order->order_img,
                'quantity'=>$order->quantity,
                'ammount'=>$order->ammount,
                'order_address'=>$order->order_address,
                'order_email'=>$order->order_email,
                'order_status'=>$order->order_status
            ]
            ];

            return response()->json($data, 201);
    }

    public function orderUser(Request $request)
    {
        $email = auth()->user()->email;
        $orders = Order::where('order_email',$email)->get();
        if($orders ->isEmpty()){
            $data = [
                'messages'=>'Not Found Data Orders',
                'status'=>400,
            ];
            return response()->json($data, 400);
        }else{
            $data = [
                'messages'=>'Get Data Orders User',
                'status'=>200,
                'data'=>$orders     
            ];
            return response()->json($data, 200);
        }
    }

    public function checkout(Request $request)
    {
        $email = auth()->user()->email;
        $orders = Order::where('order_email',$email)->get();
        $price = 0;
        if ($orders ->isEmpty()) {
            $data = [
                'messages'=>'Not Found Data Orders',
                'status'=>400,
            ];
            return response()->json($data, 400);
        } else {
            foreach ($orders as $item) {
                # code...
                $price = $price + $item->ammount;
            }
            foreach ($orders as $key ) {
                # code...
                $key -> order_status = "Checkout";
                
                $key -> update();        
            } 
            $totalItem = count($orders);
            $data = [
                'messages'=> 'All Products in Order, Successfully Checkout',
                'status'=>201,
                'data'=>[
                    'user'=>$email,
                    'products'=>$orders,
                    'total_item'=>$totalItem,
                    'total_price'=>$price
                ]
                ];
                return response()->json($data, 200);
        }
        
    }
}
