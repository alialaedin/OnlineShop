<?php

namespace Modules\Customer\Http\Requests\Customer\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'max:190'],
			'city_id' => ['required', 'integer', 'exists:cities,id'],
			'address' => ['required', 'string'],
			'postal_code' => ['required', 'numeric', 'digits:10']
		];
	}

	public function validated($key = null, $default = null)
	{
		$validated = parent::validated();

		$validated['customer_id'] = auth('customer-api')->user()->id;
		$validated['mobile'] = auth('customer-api')->user()->mobile;

		return $validated;
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
