<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Models\Category;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Specification\Models\Specification;
use Ybazli\Faker\Facades\Faker;

class ProductDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		DB::beginTransaction();

		try {
			Category::factory()->count(10)->create();

			$products = Product::factory()->count(100)->create();
			foreach ($products as $product) {
				$product->store()->create([
					'balance' => $product->quantity
				]);
			}

			for ($i = 0; $i < 10; $i++) {
				$specification = Specification::factory()->count(1)->create();
				$categories = Category::all()->random(5)->select('id');
				$products = Product::all()->random(5)->select('id');

				$categoryIds = [];
				foreach ($categories as $category) {
					$categoryIds[] = $category->id;
				}

				$attachProducts = [];
				foreach ($products as $product) {
					$attachProducts[$product->id] = [
						'value' => Faker::word()
					];
				}

				$specification->categories()->attach($categoryIds);
				$specification->products()->attach($attachProducts);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			throw $e;
		}
	}
}
