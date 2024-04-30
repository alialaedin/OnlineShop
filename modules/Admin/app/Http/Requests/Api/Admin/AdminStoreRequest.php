<?php

namespace Modules\Admin\Http\Requests\Api\Admin;
 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\App\Rules\IranMobile;

class AdminStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'mobile' => ['required', 'numeric', 'unique:admins,mobile', new IranMobile],
			'role' => ['required', 'exists:roles,name'],
			'email' => ['nullable', 'email', 'unique:admins,email'],
			'password' => ['required', 'string', Password::min(6), 'confirmed']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
