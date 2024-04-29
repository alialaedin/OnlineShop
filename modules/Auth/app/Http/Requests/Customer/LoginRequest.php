<?php

namespace Modules\Auth\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\App\Helpers\Helpers;
use Modules\Core\App\Rules\IranMobile;
use Modules\Customer\Models\Customer;
use Modules\Sms\Models\SmsToken;

class LoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'mobile' => ['required', 'digits:11', new IranMobile()],
			'password' => ['required', Password::min(6)]
		];
	}

	public function passedValidation(): void
	{
		$customer = Customer::where('mobile', $this->input('mobile'))->first();
		if (!$customer) {
			throw Helpers::makeValidationException('اطلاعات وارد شده اشتباه است!', 'mobile');
		}

		//Check SMS token
		$smsToken = SmsToken::where('mobile', $this->input('mobile'))->first();
		if (!$smsToken) {
			throw Helpers::makeValidationException('کدفعالسازی برای شماره موبایل وارد شده ارسال نشده است!', 'mobile');
		} elseif (!$smsToken->verified_at) {
			throw Helpers::makeValidationException('شماره تلفن هنوز احراز نشده است!', 'mobile');
		}

		$this->merge([
			'customer' => $customer
		]);
	}

	public function authorize(): bool
	{
		return true;
	}
}
