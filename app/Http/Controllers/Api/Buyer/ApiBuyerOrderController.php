<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiBuyerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $orders = Order::where('user_id', $user_id)->get();
        return response()->json([
            'orders' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total_price = 0;
        $user_id = auth()->user()->id;
        Order::create([
            'user_id' => $user_id,
            'title' => $request->title,
            'total_price' => $total_price,

        ]);

        foreach ($request->all() as $key => $product_count) {

            if (Str::is('Product*', $key)) {
                $product_id = explode("_", $key);
                $products = Product::where('id', $product_id[1])->first();

                $total_price += (int)$products->price * $product_count;


                $last_order_id = Order::select('id')->get()->max('id');
                if ($last_order_id == null) {
                    $last_order_id = 1;
                }

                $order = Order::find($last_order_id);
                $order->products()->attach($product_id[1], ['count' => $product_count]);
            }
        }

        Order::where('id', $last_order_id)->update([
            'total_price' => $total_price,
        ]);

        return response()->json([
            'status' =>'true',
            'text' => 'your order stored successfuly'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if($order != null){
        $products = $order->products()->get();
        return response()->json([
            'status' =>'true',
            'order' => $order,
            'products' => $products,
        ]);
    }else{
        return response()->json([
            'status' =>'false',
            'order' => 'this order is not aviable to show'
        ]);
    }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $total_price = 0;
        $order = Order::find($id);
        if($order != null){
        $flage = true;
        foreach ($request->all() as $key => $product_count) {

            if (Str::is('Product*', $key)) {
                $product_id = explode("_", $key);

                $products = Product::where('id', $product_id[1])->first();
                $total_price += (int)$products->price * $product_count;

                if ($flage == true) {

                    $order->products()->sync([$product_id[1] => ['count' => $product_count]]);
                    $flage = false;

                } else{
                    $order->products()->attach($product_id[1], ['count' => $product_count]);
                }
            }
        }

        Order::where('id', $id)->update([
            'total_price' => $total_price,
        ]);
        return response()->json([
            'status' =>'true',
            'text' => 'your order edited successfuly'
        ]);
    }else{
        return response()->json([
            'status' =>'false',
            'text' => 'this order is not exists'
        ]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if ($order != null) {
            Order::where('id', $id)->delete();
            return response()->json([
                'status' => 'true',
                'text' => "your order delete successfuly"
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'this order does not exists'
            ]);
        }
    }

    // public function pay($id){
    //     $orders = Order::where('id',$id)->get();
    //     Order::where('id', $id)->update([
    //         'pay_status' => 'payed',
    //     ]);


    // }
}
