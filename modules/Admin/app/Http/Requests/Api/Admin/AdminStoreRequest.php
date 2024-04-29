<?php

namespace Modules\Admin\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\App\Rules\IranMobile;

class AdminStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'mobile' => ['required', 'numeric', 'unique:admins,mobile', new IranMobile],
			'role' => ['required', 'exists:roles,name'],
			'email' => ['nullable', 'email', 'unique:admins,email'],
			'password' => ['required', 'string', 'min:6', 'confirmed']
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
