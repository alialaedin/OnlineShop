<?php

namespace Modules\Area\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Area\App\Http\Requests\Api\Admin\CityStoreRequest;
use Modules\Area\App\Http\Requests\Api\Admin\CityUpdateRequest;
use Modules\Area\Models\City;
use Modules\Area\Models\Province;

class CityController extends Controller
{
	public function index(Province $province): JsonResponse
	{
		$cities = Cache::rememberForever('cities_' . $province->id, function () use ($province) {
			return $province->cities()
				->select(['id', 'name'])
				->orderBy('name', 'asc')
				->get();
		});

		return response()->success('Get all province cities #' . $province->id, compact('cities'));
	}

	public function store(CityStoreRequest $request): JsonResponse
	{
		$province = Province::find($request->input('province_id'));
		$province->cities()->create([
			'name' => $request->input('name'),
			'status' => $request->input('status')
		]);

		//clear cache
		City::clearCitiesCacheByProvince($province->id);

		return response()->success('شهر با موفقیت ساخته شد!');
	}

	public function update(CityUpdateRequest $request, City $city): JsonResponse
	{
		$city->update($request->safe()->except('province_id'));

		if ($city->province_id != $request->input('province_id')) {
			$province = Province::find($request->input('province_id'));
			$city->province()->associate($province);
			$city->save();
		}

		//clear cache
		City::clearCitiesCacheByProvince($city->province_id);

		return response()->success('شهر با موفقیت ویرایش شد!');
	}

	public function destroy(City $city): JsonResponse
	{
		$city->delete();

		//clear cache
		City::clearCitiesCacheByProvince($city->province_id);

		return response()->success('شهر با موفقیت حذف شد!');
	}
}
