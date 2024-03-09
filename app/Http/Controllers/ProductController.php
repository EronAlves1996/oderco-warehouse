<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::query()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = $request->validate([
            'name' => ['required', 'unique:product,name', 'max:100'],
            'quantity' => ['required', 'numeric', 'integer', 'min:0'],
            'picture_filename' => ['nullable', 'extensions:jpg,png'],
            'price' => ['required', 'decimal:1,2', 'min:0']
        ]);

        $createdProduct = Product::create($product);

        return response(null, 201, ['location' => $request->getRequestUri() . $createdProduct->public_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
