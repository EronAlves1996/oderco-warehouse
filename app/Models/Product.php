<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product';
    protected $guarded = ['id', 'public_id', 'created_at', 'updated_at', 'deleted_at'];
    protected $hidden = ['id', 'deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->public_id = Uuid::uuid4();
        });
    }
}
