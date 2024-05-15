<?php

namespace Modules\Area\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Area\Models\Province;

class ProvinceController extends Controller
{
	public function index()
	{
		$provinces =  Province::query()
			->select(['id', 'name'])
			->orderBy('name', 'asc')
			->get();

		return response()->success('Get all provinces', compact('provinces'));
	}
}
