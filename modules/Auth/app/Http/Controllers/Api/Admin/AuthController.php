<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Admin\Models\Admin;
use Modules\Auth\Http\Requests\Admin\AdminLoginRequest;
use Modules\Auth\Http\Requests\Admin\AdminLogoutRequest;

class AuthController extends Controller
{
	public function login(AdminLoginRequest $request): JsonResponse
	{
		$admin = Admin::query()->where('mobile', $request->mobile)->first();

		if (!$admin || !Hash::check($request->password, $admin->password)) {
			return response()->error('اطلاعات وارد شده اشتباه است!', [], 422);
		}

		$token = $admin->createToken('authToken');
		Sanctum::actingAs($admin);

		$data = [
			'admin' => $admin,
			'access_token' => $token->plainTextToken,
			'token_type' => 'Bearer'
		];

		return response()->success('کاربر با موفقیت وارد شد!', compact('data'));
	}

	public function logout(AdminLogoutRequest $request): JsonResponse
	{
		$admin = $request->user();
		$admin->currentAccessToken()->delete();

		return response()->success('کاربر با موفقیت از برنامه خارج شد!');
	}
}
