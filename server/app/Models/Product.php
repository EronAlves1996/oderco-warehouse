<?php

namespace App\Models;

use App\Exceptions\ForbiddenOperationException;
use App\Exceptions\UnexpectedDatabaseException;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "product";

    protected $guarded = [
        "id",
        "public_id",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $hidden = ["id", "deleted_at"];

    protected $appends = ["price"];

    private static $name_validations = [
        "update" => ["required", "max:100"],
        "creation" => ["required", "unique:product,name", "max:100"],
    ];

    private static $base_validation = [
        "quantity" => ["required", "numeric", "integer", "min:0"],
        "image" => ["nullable", "extensions:jpg,png"],
        "price" => ["required", "decimal:1,2", "min:0"],
    ];

    private static $public_id_validation = [
        "public_id" => ["required", "uuid"],
    ];

    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            $model_public_id = $model->public_id;
            if (is_null($model_public_id)) {
                $model->public_id = Uuid::uuid4();
            }
        });
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => $value / 100,
            set: fn(float $value) => $value * 100
        )->shouldCache();
    }

    protected function picture(): Attribute
    {
        return Attribute::make(
            get: fn(string|null $value) => is_null($value)
                ? ""
                : asset("/storage/" . $value)
        )->shouldCache();
    }

    public static function newFromRequest(Request $request): Product
    {
        $product = $request->validate(
            array_merge(static::$base_validation, [
                "name" => static::$name_validations["creation"],
            ])
        );

        static::saveImage($product);

        static::safeExecute(
            fn() => Product::query()->create($product),
            function () use ($product) {
                if (array_key_exists("picture", $product)) {
                    Storage::delete($product["picture"]);
                }
                throw new UnexpectedDatabaseException();
            }
        );
    }
    /**
     * @param mixed $runnable
     * @param mixed $recoverFunction
     */
    private static function safeExecute($runnable, $recoverFunction): Product
    {
        try {
            return $runnable();
        } catch (Exception $e) {
            $recoverFunction();
        }
    }

    /**
     * @param array<int,mixed> $requestArray
     */
    private static function saveImage(array $requestArray): void
    {
        if (array_key_exists("image", $requestArray)) {
            $requestArray["picture"] = $requestArray["image"]->storePublicly();
        }
    }

    public function updateFromRequest(Request $request): void
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

        static::saveImage($toUpdate);

        static::safeExecute(
            function () use ($toUpdate) {
                $this->fill($toUpdate);
                $this->save();
            },
            function () use ($toUpdate) {
                if (array_key_exists("picture", $toUpdate)) {
                    Storage::delete($toUpdate["picture"]);
                }
                throw new UnexpectedDatabaseException();
            }
        );
    }
}
