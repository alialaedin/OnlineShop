<?php

namespace Modules\Admin\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\App\Rules\IranMobile;

class AdminUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'mobile' => [
				'required',
			 	'numeric', 
				Rule::unique('admins', 'mobile')->ignore($this->route('admin')->id), 
				new IranMobile
			],
			'role' => ['required', 'exists:roles,name'],
			'email' => [
				'nullable', 
				'email', 
				Rule::unique('admins', 'email')->ignore($this->route('admin')->id), 
			],
			'password' => ['nullable', 'string', 'min:6', 'confirmed']
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
