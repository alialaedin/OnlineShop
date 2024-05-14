<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// create roles
		$roles = [
			'super_admin' => 'مدیر ارشد'
		];

		foreach ($roles as $name => $label) {
			Role::query()->firstOrCreate(
				['name' => $name],
				['label' => $label, 'guard_name' => 'admin-api']
			);
		}

		//create permissions
		$permissions = [
			'view dashboard stats' => 'مشاهده آمارهای داشبورد',
			//admins
			'view admins' => 'مشاهده ادمین ها',
			'create admins' => 'ایجاد ادمین ها',
			'edit admins' => 'ویرایش ادمین ها',
			'delete admins' => 'حذف ادمین ها',
			//customers
			'view customers' => 'مشاهده مشتریان',
			'create customers' => 'ایجاد مشتریان',
			'edit customers' => 'ویرایش مشتریان',
			'delete customers' => 'حذف مشتریان',
			//cities
			'view cities' => 'مشاهده شهر ها',
			'create cities' => 'ایجاد شهر ها',
			'edit cities' => 'ویرایش شهر ها',
			'delete cities' => 'حذف شهر ها',
			//provinces
			'view provinces' => 'مشاهده استان ها',
			//roles NEW
			'view roles' => 'مشاهده نقش ها',
			'create roles' => 'ایجاد نقش ها',
			'edit roles' => 'ویرایش نقش ها',
			'delete roles' => 'حذف نقش ها',
			//sliders NEW
			'view sliders' => 'مشاهده اسلایدر ها',
			'create sliders' => 'ایجاد اسلایدر ها',
			'edit sliders' => 'ویرایش اسلایدر ها',
			'delete sliders' => 'حذف اسلایدر ها',
			//Category NEW
			'view categories' => 'مشاهده دسته بندی ها',
			'create categories' => 'ایجاد دسته بندی ها',
			'edit categories' => 'ویرایش دسته بندی ها',
			'delete categories' => 'حذف دسته بندی ها',
			//Specification NEW
			'view specifications' => 'مشاهده مشخصات',
			'create specifications' => 'ایجاد مشخصات',
			'edit specifications' => 'ویرایش مشخصات',
			'delete specifications' => 'حذف مشخصات',
			//Product NEW
			'view products' => 'مشاهده محصولات',
			'create products' => 'ایجاد محصولات',
			'edit products' => 'ویرایش محصولات',
			'delete products' => 'حذف محصولات',
			'delete products image' => 'حذف عکس محصول',
			// Stores
			'view stores' => 'مشاهده انبار',
			'create stores' => 'ایجاد انبار',
			//settings
			'view settings' => 'مشاهده تنظیمات',
			'create settings' => 'ایجاد تنظیمات',
			'edit settings' => 'ویرایش تنظیمات',
			// Order
			'view orders' => 'مشاهده سفارشات',
			'edit orders' => 'ویرایش سفارشات'
		];

		foreach ($permissions as $name => $label) {
			Permission::query()->firstOrCreate(
				['name' => $name],
				['label' => $label, 'guard_name' => 'admin-api']
			);
		}
	}
}
