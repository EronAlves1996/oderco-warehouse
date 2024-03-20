<?php

namespace App\Models;

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
            get: fn(string $value) => asset("/storage/" . $value)
        )->shouldCache();
    }

    public static function newFromRequest(Request $request): Product
    {
        $product = $request->validate(
            array_merge(static::$base_validation, [
                "name" => static::$name_validations["creation"],
            ])
        );

        if (array_key_exists("image", $product)) {
            $fileName = $product["image"]->storePublicly();
            $product["picture"] = $fileName;
        }

        try {
            return Product::query()->create($product);
        } catch (Exception $e) {
            // Providencia o rollback da imagem
            if (array_key_exists("image", $product)) {
                Storage::delete($fileName);
            }
            throw new UnexpectedDatabaseException();
        }
    }

    public function updateFromRequest(Request $request): void
    {
        if ($request->get("public_id") !== $this->public_id) {
            throw new Exception("Not allowed to update another entity");
        }

        $toUpdate = $request->validate(
            array_merge(
                static::$base_validation,
                ["name" => static::$name_validations["update"]],
                static::$public_id_validation
            )
        );

        $this->fill($toUpdate);
        $this->save();
    }
}
