<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;

class ProductController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
    $products = Product::with(['category' => function ($query) {
        $query->select('id', 'category_name');
    }])->get(['id', 'name', 'description', 'price', 'color', 'Size', 'Weight', 'quantity', 'Discount', 'Brand', 'category_id']);

    return response()->json($products);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['category' => function ($query) {
            $query->select('id', 'category_name');
        }])->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'color' => $product->color,
            'Size' => $product->Size,
            'Weight' => $product->Weight,
            'quantity' => $product->quantity,
            'Discount' => $product->Discount,
            'Brand' => $product->Brand,
            'category_id' => $product->category_id,
            'category' => $product->category,
            ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'color' => 'nullable',
            'Size' => 'nullable',
            'Weight' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'Discount' => 'nullable|numeric',
            'Brand' => 'nullable',
            'commission_rate' => 'nullable|numeric',
        ]);

        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'color' => 'nullable',
            'Size' => 'nullable',
            'Weight' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'Discount' => 'nullable|numeric',
            'Brand' => 'nullable',
            'commission_rate' => 'nullable|numeric',
        ]);

        $product->update($validatedData);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Search for products by name.
     *
     * @param  string  $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByName($name)
    {
        if (!$name) {
            return response()->json(['error' => 'Search term is required'], 422);
        }

        $products = Product::with('category')
            ->where(function (Builder $query) use ($name) {
                $query->where('name', 'like', "%$name%")
                    ->orWhereHas('category', function (Builder $query) use ($name) {
                        $query->where('category_name', 'like', "%$name%");
                    });
            })
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found with the given search term'], 422);
        }

        return response()->json(['results' => $products]);
    }


}

