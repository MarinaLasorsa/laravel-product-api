<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->all());

        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = Product::find($id);

        if($product) {

            return response()->json($product);

        } else {

            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $product = Product::find($id);

        if($product) {

            $product->update($request->all());

            if ($request->has('category_ids')) {
                $product->categories()->sync($request->category_ids);
            } else {
                $product->categories()->detach();
            }

            return response()->json($product);

        } else {

            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $product = Product::find($id);

        if($product) {

            $product->delete();

            return response()->json(['message' => 'Product deleted']);

        } else {

            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
