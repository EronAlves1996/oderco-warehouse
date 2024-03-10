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
        $createdProduct = Product::newFromRequest($request);

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
        $product->updateFromRequest($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $status_code = 204;
        try {
            DB::delete('delete product where public_id = ?', [$id]);
        } catch (Excepetion $e) {
            $status_code = 409;
        }
        return response(null, $status_code);
    }
}
