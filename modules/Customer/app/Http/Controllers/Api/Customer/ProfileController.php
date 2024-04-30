<?php

namespace Modules\Customer\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Http\Requests\Customer\Profile\PasswordUpdateRequest;
use Modules\Customer\Http\Requests\Customer\Profile\ProfileUpdateRequest;
use Modules\Customer\Models\Customer;

class ProfileController extends Controller
{
  private function getCustomerId()
  {
    return auth('customer-api')->user()->id;
  }

  public function showProfile(): JsonResponse
  {
    $customer = Customer::query()
      ->select(['id', 'name', 'email', 'national_code'])
      ->findOrFail($this->getCustomerId());

    return response()->success('نمایش پروفایل مشتری', compact('customer'));
  }

  public function updateProfile(ProfileUpdateRequest $request): JsonResponse
  {
    $customer = Customer::findOrFail($this->getCustomerId());
    $customer->update($request->validated());

    return response()->success('پروفایل کاربر با موفقیت بروزرسانی شد');
  }

  public function changePassword(PasswordUpdateRequest $request): JsonResponse
  {
    $customer = Customer::findOrFail($this->getCustomerId());

    if (Hash::check($request->input('old_password'), $customer->password)) {
      $customer->password = Hash::make($request->input('password'));
      $customer->save();

      return response()->success('کلمه عبور با موفقیت ویرایش شد.');
    } else {
      return response()->success('کلمه عبور قبلی شما به درستی وارد نشده است.');
    }
  }
}
