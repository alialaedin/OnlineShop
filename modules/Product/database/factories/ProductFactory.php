<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Category;
use Ybazli\Faker\Facades\Faker;

class ProductFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 */
	protected $model = \Modules\Product\Models\Product::class;

	/**
	 * Define the model's default state.
	 */
	public function definition(): array
	{
		return [
			'title' => Faker::word(),
			'category_id' => Category::all()->random()->id,
			'desription' => Faker::paragraph(),
			'status' => $this->faker->boolean(),
			'quantity' => rand(20, 100),
			'price' => $this->faker->numberBetween(100000, 10000000),
			'discount' => rand(1, 100),
			'discount_type' => 'percent',
		];
	}
}
