<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ApiFactorController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $factors = Factor::all();

        return response()->json([
            'factors' => $factors
        ]);
    }
    public function filter()
    {
        $factors = QueryBuilder::for(Factor::class)
            ->allowedFilters([
                AllowedFilter::exact('order_id')->ignore(null),
                AllowedFilter::exact('user_id')->ignore(null),
                AllowedFilter::exact('total_price')->ignore(null),
            ])
            ->get();
        return response()->json([
            'status' => 'true',
            'text' => 'filter worked succesfuly',
            'factors' => $factors
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $factor = Factor::find($id);
        if ($factor != null) {
            return response()->json([
                'factor' => $factor
            ]);
        } else {
            return response()->json([
                'text' => 'factor is not aviable to show'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $factor = Factor::find($id);
        if ($factor != null) {
            Factor::where('id', $id)->update($request->only(
                'user_id',
                'order_id',
                'total_price'
            ));
            $factor = Factor::find($id);
            return response()->json([
                'status' => 'true',
                'text' => 'your edit successful saved',
                'factor' => $factor
            ]);
        } else{
            return response()->json([
                'status' => 'false',
                'text' => 'this factor does not exists'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $factor = Factor::find($id);
        if ($factor != null) {
            Factor::where('id', $id)->delete();
            return response()->json([
                'status' => 'true',
                'text' => "your factor delete successfuly"
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'this factor does not exists'
            ]);
        }
    }
}
