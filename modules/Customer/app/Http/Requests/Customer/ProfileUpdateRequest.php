<?php

namespace Modules\Customer\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$customerId = auth()->user()->id;

		return [
			'name' => ['required', 'string', 'max:100'],
			'email' => ['nullable', 'email', 'max:191', Rule::unique('customers', 'email')->ignore($customerId)],
			'national_code' => ['nullable', 'numeric', 'digits:10']
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
