<?php

namespace Modules\Permission\App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Permission\App\Http\Requests\Api\Admin\RoleStoreRequest;
use Modules\Permission\App\Http\Requests\Api\Admin\RoleUpdateRequest;
use Modules\Permission\App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
	private function permissions(): Collection
	{
		//        return Cache::rememberForever('all_permissions', function () {
		//            return Permission::query()
		//                ->oldest('id')
		//                ->select(['id', 'name', 'label'])
		//                ->get();
		//        });

		return Permission::query()
			->oldest('id')
			->select(['id', 'name', 'label'])
			->get();
	}

	public function index(): JsonResponse
	{
		$roles = Cache::rememberForever('roles', function() {
			return Role::query()
			->select(['id', 'name', 'label', 'created_at'])
			->latest('id')
			->paginate();
		});
		
		return response()->success('تمام نقش ها', compact('roles'));
	}

	public function store(RoleStoreRequest $request): JsonResponse
	{
		$role = Role::query()->create([
			'name' => $request->input('name'),
			'label' => $request->input('label'),
			'guard_name' => 'admin-api'
		]);

		$permissions = $request->input('permissions');
		if ($permissions) {
			foreach ($permissions as $permission) {
				$role->givePermissionTo($permission);
			}
		}

		return response()->success('نقش با موفقیت ثبت شد.');
	}


	public function update(RoleUpdateRequest $request, Role $role): JsonResponse
	{
		$role->update($request->only(['name', 'label']));

		$permissions = $request->input('permissions');
		$role->syncPermissions($permissions);

		return response()->success('نقش با موفقیت به روزرسانی شد.');
	}

	public function destroy(Role $role): JsonResponse
	{
		$permissions = $role->permissions;
		if ($role->delete()) {
			foreach ($permissions as $permission) {
				$role->revokePermissionTo($permission);
			}
		}
		return response()->success('نقش با موفقیت حذف شد.');
	}
}
