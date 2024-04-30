<?php

namespace Modules\Customer\Http\Requests\Customer\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'max:190'],
			'city_id' => ['required', 'integer', 'exists:cities,id'],
			'address' => ['required', 'string'],
			'postal_code' => ['required', 'numeric', 'digits:10']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
