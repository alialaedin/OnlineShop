<?php

namespace Modules\Admin\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Http\Requests\Api\Admin\AdminStoreRequest;
use Modules\Admin\Http\Requests\Api\Admin\AdminUpdateRequest;
use Modules\Admin\Models\Admin;

class AdminController extends Controller
{
	public function index(): JsonResponse
	{
		$admins = Cache::rememberForever('admins', function () {
			return Admin::query()
				->select(['id', 'name', 'mobile', 'email', 'created_at'])
				->latest('id')
				->where('name', '!=', 'super admin')
				->paginate();
		});

		return response()->success('', compact('admins'));
	}

	public function store(AdminStoreRequest $request): JsonResponse
	{
		$admin = Admin::query()->create([
			'name' => $request->input('name'),
			'mobile' => $request->input('mobile'),
			'email' => $request->input('email'),
			'password' => Hash::make($request->input('password'))
		]);

		$role = $request->input('role');
		$admin->assignRole($role);

		Admin::clearAllCaches();

		return response()->success('ادمین جدید با موفقیت ساخته شد!');
	}

	public function update(AdminUpdateRequest $request, Admin $admin): JsonResponse
	{
		$inputs = [
			'name' => $request->input('name'),
			'mobile' => $request->input('mobile'),
			'email' => $request->input('email'),
		];

		if ($request->filled("password")) {
			$inputs['password'] = Hash::make($request->input('password'));
		}

		$admin->update($inputs);
		$admin->syncRoles($request->input('role'));

		Admin::clearAllCaches();

		return response()->success('ادمین جدید با موفقیت ویرایش شد!');
	}

	public function destroy(Admin $admin): JsonResponse
	{
		$admin->delete();
		Admin::clearAllCaches();

		return response()->success('ادمین جدید با موفقیت حذف شد!');
	}
}
