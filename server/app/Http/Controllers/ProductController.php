<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenOperationException;
use App\Exceptions\UnexpectedDatabaseException;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private static $base_validation = [
        "quantity" => ["required", "numeric", "integer", "min:0"],
        "image" => ["nullable", "extensions:jpg,png"],
        "price" => ["required", "decimal:0,2", "min:0"],
    ];

    private static $name_validations = [
        "update" => ["required", "max:100"],
        "creation" => ["required", "unique:product,name", "max:100"],
    ];

    /**
     * @param array<int,mixed> $requestArray
     * @return array<int,mixed>|null
     */
    private static function saveImage(array $requestArray): array
    {
        if (array_key_exists("image", $requestArray)) {
            $fileName = $requestArray["image"]->storePublicly();
            $requestArray["picture"] = $fileName;
        }
        return $requestArray;
    }

    /**
     * @param mixed $runnable
     * @param mixed $recoverFunction
     */
    private static function safeExecute($runnable, $recoverFunction)
    {
        try {
            return $runnable();
        } catch (Exception $e) {
            $recoverFunction();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): LengthAwarePaginator
    {
        return Product::query()->paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response|ResponseFactory
    {
        $product = $request->validate(
            array_merge(static::$base_validation, [
                "name" => static::$name_validations["creation"],
            ])
        );

        $productWithImage = static::saveImage($product);

        $createdProduct = static::safeExecute(
            fn() => Product::query()->create($productWithImage),
            function () use ($productWithImage) {
                if (array_key_exists("picture", $productWithImage)) {
                    Storage::delete($productWithImage["picture"]);
                }
                throw new UnexpectedDatabaseException();
            }
        );

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
        if ($request->input("public_id") !== $this->public_id) {
            throw new ForbiddenOperationException(
                "Não é possível atualizar uma entidade diferente desta!"
            );
        }

        $toUpdate = $request->validate(
            array_merge(
                static::$base_validation,
                ["name" => static::$name_validations["update"]],
                static::$public_id_validation
            )
        );

        $toUpdateWithImage = static::saveImage($toUpdate);

        static::safeExecute(
            function () use ($toUpdateWithImage, $product) {
                $product->fill($toUpdateWithImage);
                $product->save();
            },
            function () use ($toUpdateWithImage) {
                if (array_key_exists("picture", $toUpdateWithImage)) {
                    Storage::delete($toUpdateWithImage["picture"]);
                }
                throw new UnexpectedDatabaseException();
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response|ResponseFactory
    {
        try {
            $product->delete();
        } catch (Exception $e) {
            throw new UnexpectedDatabaseException();
        }
        return response(null, 204);
    }
}
