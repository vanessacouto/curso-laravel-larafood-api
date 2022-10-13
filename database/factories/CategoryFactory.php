<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->unique()->name,
            'description' => $this->faker->sentence,
        ];
    }
}
