<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id');
            $table->string('name', 100);
            $table->integer('quantity')->unsigned();
            $table->string('picture_filename')->nullable();
            $table->bigInteger('price')->unsigned();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->unique('name', 'unique_name');
            $table->unique('public_id', 'unique_public_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
