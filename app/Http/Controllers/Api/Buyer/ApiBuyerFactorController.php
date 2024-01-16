<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Factor;
use App\Models\Order;
use Illuminate\Http\Request;

class ApiBuyerFactorController extends Controller
{
    public function index()
    {
        $factors = Factor::all();
        if ($factors != null) {
            return response()->json([
                'status' => 'true',
                'factors' => $factors
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'text' => 'there is no aviable factor to show'
            ]);
        }
    }


    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $order = Order::find($request->order_id);
        if ($order != null) {
            $factor = Factor::create([
                'order_id' => $order->id,
                'user_id' => $user_id,
                'finaly_price' => $order->total_price,
            ]);
            return response()->json([
                'status' => 'true',
                'factors' => $factor
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'factors' => 'this order is not exists'
            ]);
        }
    }

    public function show($id)
    {
        $factor = Factor::find($id);
        if ($factor != null) {
            return response()->json([
                'status' => 'true',
                'factor' => $factor,
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'factor' => 'this factor is not aviable to show'
            ]);
        }
    }
    // public function update(Request $request, $id)
    // {
    //     $user_id = auth()->user()->id;
    //     $order = Order::find($request->order_id);
    //     Factor::find($id)->update([
    //         'order_id' => $order->id,
    //         'user_id' => $user_id,
    //         'finaly_price' => $order->total_price,
    //     ]);
    //     $factor = Factor::find($id);
    //     return response()->json([
    //         'status' => 'true',
    //         'factors' => $factor
    //     ]);
    // }

    public function destroy($id)
    {
        $factor = Factor::find($id);
        if($factor != null){
        $factor->delete();
        return response()->json([
            'status' => 'true',
            'factors' => 'you delete factor successfuly'
        ]);
    }else{
        return response()->json([
            'status' => 'false',
            'factors' => 'this factor dosent aviable to delete it'
        ]);
    }
    }

    // public function update_status($id)
    // {
    //     $status = Factor::findOrfail($id);
    //     $status->update(['status' => 'payed']);
    //     return response()->json([
    //         'status' => 'true',
    //         'factors' => 'you payed successfuly'
    //     ]);
    // }
}
