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
}
