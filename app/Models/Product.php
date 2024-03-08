<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $guarded = ['id', 'public_id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->public_id = Uuid::uuid4();
        });
    }
}
