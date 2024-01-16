<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ApiProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'products' => $products
        ]);
    }
    public function filter()
    {
        $users = QueryBuilder::for(Product::class)
            ->with(['user', 'category'])
            ->allowedFilters([
                AllowedFilter::callback('PriceMin', function (Builder $query, $value) {
                    $query->where('price', '>=', (int)$value);
                })->ignore(null),
                AllowedFilter::callback('PriceMax', function ($query, $value) {
                    $query->where('price', '<=', (int)$value);
                })->ignore(null),
                AllowedFilter::exact('inventory')->ignore(null),
                AllowedFilter::exact('user.email')->ignore(null),
                AllowedFilter::exact('user.user_name')->ignore(null),
                AllowedFilter::exact('category_id')->ignore(null),
            ])
            ->get();
        dd($users);
        return response()->json([
            'status' => 'true',
            'text' => 'filter worked succesfuly',
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //if category-id throw error //
        //https://stackoverflow.com/questions/42659183/sqlstate23000-integrity-constraint-violation-1048-column-property-id-canno//


        $product = Product::create([
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'price' => $request->price,
            'inventory' => $request->inventory,
            'sold_number' => $request->sold_number
        ]);
        $product
            ->addMediaFromRequest('image')
            ->toMediaCollection();

            return response()->json([
                'status' => 'true',
                'text' => 'product stored succesfuly',
                'users' => $product
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if ($product != null) {
            return response()->json([
                'status' => 'true',
                'text' => 'see your product',
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'text' => 'product is not aviable to show'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if ($product != null) {
            Product::where('id', $id)->update($request->only(
                'category_id',
                'user_id',
                'name',
                'price',
                'inventory',
                'sold_number'
            ));
            return response()->json([
                'status' => 'true',
                'text' => 'your edit successful saved'
            ]);
        }else{
            return response()->json([
                'status' => 'false',
                'text' => 'this product does not exists'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
            if ($product != null) {
                Product::where('id', $id)->delete();
                return response()->json([
                    'status' => 'true',
                    'text' => "your product delete successfuly"
                ]);
            }else{
                return response()->json([
                    'status' => 'false',
                    'text' => 'this product does not exists'
                ]);
            }
    }
}
