<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product';

    protected $guarded = ['id', 'public_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['id', 'deleted_at'];

    protected $appends = ['price'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->public_id = Uuid::uuid4();
        });
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => $value / 100,
            set: fn(float $value) => $value * 100
        )->shouldCache();
    }

    public static function newFromRequest(Request $request): Product
    {
        $product = $request->validate([
            'name' => ['required', 'unique:product,name', 'max:100'],
            'quantity' => ['required', 'numeric', 'integer', 'min:0'],
            'picture_path' => ['nullable', 'extensions:jpg,png'],
            'price' => ['required', 'decimal:1,2', 'min:0']
        ]);

        return Product::create($product);
    }
}
