<?php

namespace Modules\Admin\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\App\Rules\IranMobile;

class ProfileUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$adminId = auth()->user()->id;

		return [
			'name' => ['required', 'string', 'max:100'],
			'email' => ['nullable', 'email', 'max:191', Rule::unique('admins', 'email')->ignore($adminId)],
			'mobile' => ['nullable', 'numeric', 'digits:11', new IranMobile()]
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
