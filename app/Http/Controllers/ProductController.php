<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     *
     *  @OA\Get(
     *      path="/api/products",
     *      tags={"Product"},
     *      summary="Get all products",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *      )
     *  )
     *
     */
    public function index()
    {
        $products = Product::with('categories')->get();

        return response()->json($products);
    }

    /**
     *
     *  @OA\Post(
     *      path="/api/products",
     *      tags= {"Product"},
     *      summary="Insert new product",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="price", type="number"),
     *                  @OA\Property(property="category_ids", type="array", @OA\Items(type="integer")
     *                  )
     *              )
     *          ),
     *          @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 )
     *             )
     *         )
     *      )
     *  )
     *
     **/
    public function store(Request $request)
    {
        $product = Product::create($request->all());

        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

        return response()->json($product->load('categories'));
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Product"},
     *     summary="Show single product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $product = Product::with('categories')->find($id);

        if($product) {

            return response()->json($product);

        } else {

            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Product"},
     *     summary="Update product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *                 @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="price", type="number"),
     *                  @OA\Property(property="category_ids", type="array", @OA\Items(type="integer")
     *                  )
     *             )
     *         ),
     *          @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 )
     *             )
     *         )
     *      )
     *  )
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

            return response()->json($product->load('categories'));

        } else {

            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Product"},
     *     summary="Delete product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
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
