<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): LengthAwarePaginator
    {
        return Product::query()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response|ResponseFactory
    {
        $createdProduct = Product::newFromRequest($request);

        return response(null, 201, [
            "location" =>
                $request->getRequestUri() . $createdProduct->public_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Response|ResponseFactory
    {
        return response($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): void
    {
        $product->updateFromRequest($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response|ResponseFactory
    {
        $status_code = 204;
        try {
            $product->delete();
        } catch (Exception $e) {
            $status_code = 409;
        }
        return response(null, $status_code);
    }
}
