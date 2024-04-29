<?php

namespace Modules\Customer\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Http\Requests\Customer\PasswordUpdateRequest;
use Modules\Customer\Http\Requests\Customer\ProfileUpdateRequest;
use Modules\Customer\Models\Customer;

class ProfileController extends Controller
{
  public function showProfile(): JsonResponse
  {
    $customerId = auth()->user()->id;
    $customer = Customer::query()
      ->select(['id', 'name', 'email', 'national_code'])
      ->findOrFail($customerId);

    return response()->success('نمایش پروفایل مشتری', compact('customer'));
  }

  public function updateProfile(ProfileUpdateRequest $request): JsonResponse
  {
    $customer = Customer::findOrFail(auth()->user()->id);
    $customer->update($request->validated());

    return response()->success('پروفایل کاربر با موفقیت بروزرسانی شد');
  }

  public function changePassword(PasswordUpdateRequest $request): JsonResponse
  {
    $customer = Customer::findOrFail(auth()->user()->id);

    if (Hash::check($request->input('old_password'), $customer->password)) {
      $customer->password = Hash::make($request->input('password'));
      $customer->save();

      return response()->success('کلمه عبور با موفقیت ویرایش شد.');
    } else {
      return response()->success('کلمه عبور قبلی شما به درستی وارد نشده است.');
    }
  }
}
