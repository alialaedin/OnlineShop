<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\App\Helpers\Helpers;
use Modules\Customer\Models\Address;

class OrderStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'address_id' => ['required', 'integer', 'exists:addresses,id'],
			'driver' => ['required', 'string']
		];
	}

	public function passedValidation()
	{
		$addressId = $this->input('address_id');
		$driver = $this->input('driver');
		$address = Address::query()->findOrFail($addressId, ['id', 'customer_id']);

		if ($address->customer_id != auth('customer-api')->user()->id) {
			throw Helpers::makeValidationException('آدرس مطعلق به این کاربر نمی باشد');
		}
	}
	public function authorize(): bool
	{
		return true;
	}
}
