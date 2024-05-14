<?php

namespace Modules\Auth\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\App\Helpers\Helpers;
use Modules\Core\App\Rules\IranMobile;
use Modules\Customer\Models\Customer;
use Modules\Sms\Models\SmsToken;

class RegisterRequest extends FormRequest
{
	public function prepareForValidation()
	{
		$mobile = $this->input('mobile');
		$isCustomerAlreadyExists = Customer::query()->where('mobile', $mobile)->exists();

		if ($isCustomerAlreadyExists) {
			$message = 'مشتری با این شماره موبایل از قبل ثبت شده';
			throw Helpers::makeValidationException($message);
		}
	}

	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'max:100'],
			'mobile' => ['required', 'digits:11', new IranMobile, 'unique:customers,mobile'],
			'email' => ['nullable', 'email', 'max:191', 'unique:customers,email'],
			'password' => ['required', Password::min(6)],
		];
	}

	protected function passedValidation(): void
	{
		$smsToken = SmsToken::query()->where('mobile', $this->input('mobile'))->first();

		if (!$smsToken) {
			throw Helpers::makeValidationException('کاربری با این مشخصات پیدا نشد!', 'mobile');
		} elseif (!$smsToken->verified_at) {
			throw Helpers::makeValidationException('شماره موبایل کاربر تایید نشده است!', 'mobile');
		} elseif (Customer::query()->where('mobile', $this->input('mobile'))->exists()) {
			throw Helpers::makeValidationException('شما قبلا ثبت نام کرده اید!', 'mobile');
		}

		$this->merge([
			'password' => bcrypt($this->input('password')),
			
		]);
	}

	public function validated($key = null, $default = null)
	{
		$validated = parent::validated();
		$validated['status'] = 1; 

		return $validated;
	}

	public function authorize(): bool
	{
		return true;
	}
}
