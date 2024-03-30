<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $download_image_path = fake()->image(
            "./storage/app/public/",
            200,
            200,
            "products"
        );
        $splitted = explode("/", $download_image_path);
        $image_name = end($splitted);
        return [
            "name" => fake()->unique()->word(),
            "quantity" => fake()->randomNumber(2, false),
            "picture" => $image_name,
            "price" => fake()->randomNumber(6, false),
        ];
    }
}
