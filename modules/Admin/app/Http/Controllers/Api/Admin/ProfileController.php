<?php

namespace Modules\Admin\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Http\Requests\Api\Admin\PasswordUpdateRequest;
use Modules\Admin\Http\Requests\Api\Admin\ProfileUpdateRequest;
use Modules\Admin\Models\Admin;

class ProfileController extends Controller
{
	private function getAdminId()
  {
    return auth('admin-api')->user()->id;
  }

	public function showProfile(): JsonResponse
  {
    $admin = Admin::query()
      ->select(['id', 'name', 'email', 'mobile'])
      ->findOrFail($this->getAdminId());

    return response()->success('نمایش پروفایل مشتری', compact('admin'));
  }

	public function updateProfile(ProfileUpdateRequest $request): JsonResponse
  {
    $admin = Admin::findOrFail($this->getAdminId());
    $admin->update($request->validated());

    return response()->success('پروفایل کاربر با موفقیت بروزرسانی شد');
  }

	public function changePassword(PasswordUpdateRequest $request): JsonResponse
  {
    $admin = Admin::findOrFail($this->getAdminId());

    if (Hash::check($request->input('old_password'), $admin->password)) {
      $admin->password = Hash::make($request->input('password'));
      $admin->save();

      return response()->success('کلمه عبور با موفقیت ویرایش شد.');
    } else {
      return response()->success('کلمه عبور قبلی شما به درستی وارد نشده است.');
    }
  }
}
