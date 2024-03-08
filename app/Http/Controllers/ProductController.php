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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = $request->validate([
            'name' => ['required', 'unique:product,name', 'max:100'],
            'quantity' => ['required', 'numeric', 'integer', 'min:0'],
            'pictureFilename' => ['nullable', 'extensions:jpg,png'],
            'price' => ['required', 'decimal:1,2', 'min:0']
        ]);

        $createdProduct = Product::create($product);

        return response($createdProduct, 201, ['location' => '/product/' . $createdProduct->public_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
