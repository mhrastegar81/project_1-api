<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ApiSellerProuctsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = auth()->user()->id;
        $products = Product::where('user_id' , $id)->get();
        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = auth()->user()->id;
        $product = Product::create([
            'category_id' => $request->category_id,
            'user_id' => $id,
            'name' => $request->name,
            'price' => $request->price,
            'inventory' => $request->inventory,
            'sold_number' => $request->sold_number
        ]);

        return response()->json([
            'text' => 'your product stored successfuly',
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return response()->json([
            'text' => 'see your product',
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_id = auth()->user()->id;
        Product::where('id', $id)->update([
            'category_id' => $request->category_id,
            'user_id' => $user_id,
            'name' => $request->name,
            'price' => $request->price,
            'inventory' => $request->inventory,
            'sold_number' => $request->sold_number
        ]);
        $product = Product::find($id);
        return response()->json([
            'text' => 'you edit product successfuly',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id = null)
    {
        if (!empty($id)) {
            $product = Product::where('id', $id)->first();
            $product->delete();
            return response()->json([
                'product' => 'you product deleted successfuly'
            ]);
        }
    }
}
