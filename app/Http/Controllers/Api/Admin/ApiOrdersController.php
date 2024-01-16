<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ApiOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();

        return response()->json([
            'orders' => $orders
        ]);
    }
    public function filter()
    {
        $orders = QueryBuilder::for(Order::class)
            ->with(['products'])
            ->allowedFilters([
                AllowedFilter::callback('Total_PriceMin', function (Builder $query, $value) {
                    $query->where('total_price', '>=', (int)$value);
                })->ignore(null),
                AllowedFilter::callback('Total_PriceMax', function ($query, $value) {
                    $query->where('total_price', '<=', (int)$value);
                })->ignore(null),
                AllowedFilter::exact('total_price')->ignore(null),
                AllowedFilter::exact('user_id')->ignore(null),
                AllowedFilter::exact('title')->ignore(null),
            ])
            ->get();
        return response()->json([
            'status' => 'true',
            'text' => 'filter worked succesfuly',
            'orders' => $orders
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if ($order != null) {
            return response()->json([
                'order' => $order
            ]);
        } else {
            return response()->json([
                'text' => 'order is not aviable to show'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if ($order->role != 'Admin' && $order != null) {
            Order::where('id', $id)->update($request->only(
                'user_id',
                'title',
                'total_price',
            ));
            $order = Order::find($id);
            return response()->json([
                'status' => 'true',
                'text' => 'your edit successful saved',
                'order' => $order
            ]);
        } elseif ($order == null) {
            return response()->json([
                'status' => 'false',
                'text' => 'this order does not exists'
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'text' => 'you cant edit this order'
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
}
