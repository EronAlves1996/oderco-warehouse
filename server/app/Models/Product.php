<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    protected $appends = ["price", "picture"];

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
}
