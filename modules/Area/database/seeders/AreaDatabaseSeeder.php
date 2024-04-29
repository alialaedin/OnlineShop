<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;

class AreaDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(ProvinceSeederTableSeeder::class);
		$this->call(CitySeederTableSeeder::class);
	}
}
