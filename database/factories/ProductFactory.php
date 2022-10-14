<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'title' => $this->faker->unique()->name,
            'description' => $this->faker->sentence,
            'image' => 'pizza.png',
            'price' => 12.9
        ];
    }
}
