<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Area\App\Models\Province;

class ProvinceSeederTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$provinces = [
			[
				"id" => 1,
				"name" => "آذربایجان شرقی",
			],
			[
				"id" => 2,
				"name" => "آذربایجان غربی",
			],
			[
				"id" => 3,
				"name" => "اردبیل",
			],
			[
				"id" => 4,
				"name" => "اصفهان",
			],
			[
				"id" => 5,
				"name" => "البرز",
			],
			[
				"id" => 6,
				"name" => "ایلام",
			],
			[
				"id" => 7,
				"name" => "بوشهر",
			],
			[
				"id" => 8,
				"name" => "تهران",
			],
			[
				"id" => 9,
				"name" => "چهارمحال و بختیاری",
			],
			[
				"id" => 10,
				"name" => "خراسان جنوبی",
			],
			[
				"id" => 11,
				"name" => "خراسان رضوی",
			],
			[
				"id" => 12,
				"name" => "خراسان شمالی",
			],
			[
				"id" => 13,
				"name" => "خوزستان",
			],
			[
				"id" => 14,
				"name" => "زنجان",
			],
			[
				"id" => 15,
				"name" => "سمنان",
			],
			[
				"id" => 16,
				"name" => "سیستان و بلوچستان",
			],
			[
				"id" => 17,
				"name" => "فارس",
			],
			[
				"id" => 18,
				"name" => "قزوین",
			],
			[
				"id" => 19,
				"name" => "قم",
			],
			[
				"id" => 20,
				"name" => "کردستان",
			],
			[
				"id" => 21,
				"name" => "کرمان",
			],
			[
				"id" => 22,
				"name" => "کرمانشاه",
			],
			[
				"id" => 23,
				"name" => "کهگیلویه و بویراحمد",
			],
			[
				"id" => 24,
				"name" => "گلستان",
			],
			[
				"id" => 25,
				"name" => "لرستان",
			],
			[
				"id" => 26,
				"name" => "گیلان",
			],
			[
				"id" => 27,
				"name" => "مازندران",
			],
			[
				"id" => 28,
				"name" => "مرکزی",
			],
			[
				"id" => 29,
				"name" => "هرمزگان",
			],
			[
				"id" => 30,
				"name" => "همدان",
			],
			[
				"id" => 31,
				"name" => "یزد",
			]
		];

		foreach ($provinces as $province) {
			Province::query()->firstOrCreate([
				'name' => $province['name']
			]);
		}
	}
}
