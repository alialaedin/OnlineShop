<?php

namespace Modules\Area\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\City;
use Modules\Area\Entities\Province;
use Modules\Area\Http\Requests\Admin\CityStoreRequest;
use Modules\Area\Http\Requests\Admin\CityUpdateRequest;
use Modules\Core\Filters\BooleanFilter;
use Modules\Core\Filters\IntegerFilter;
use Modules\Core\Filters\StringFilter;
use Pricecurrent\LaravelEloquentFilters\EloquentFilters;

class CityController extends Controller
{
    public function index(): Renderable
    {
        $filters = EloquentFilters::make([
            new StringFilter('name', request('name')),
            new IntegerFilter('province_id', request('province_id')),
            new BooleanFilter('status', request('status')),
        ]);

        $cities = City::query()
            ->sortable()
            ->filter($filters)
            ->orderBy('name', 'asc')
            ->with('province:id,name')
            ->paginate()
            ->withQueryString();

        $provinces = Province::getAllProvinces();

        return view('area::admin.city.index', compact('cities', 'provinces'));
    }

    public function create(): Renderable
    {
        $provinces = Province::getAllProvinces();

        return view('area::admin.city.create', compact('provinces'));
    }

    public function store(CityStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $province = Province::find($request->input('province_id'));
        $province->cities()->create([
            'name' => $request->input('name'),
            'status' => $request->input('status')
        ]);

        //clear cache
        City::clearCitiesCacheByProvince($province->id);

        return redirect()->route('admin.cities.index')
            ->with('success', 'شهر با موفقیت ثبت شد.');
    }

    public function edit(City $city): Renderable
    {
        $provinces = Province::getAllProvinces();

        return view('area::admin.city.edit', compact('provinces', 'city'));
    }

    public function update(CityUpdateRequest $request, City $city)
    {
        $city->update($request->safe()->except('province_id'));

        if ($city->province_id != $request->input('province_id')) {
            $province = Province::find($request->input('province_id'));
            $city->province()->associate($province);
            $city->save();
        }

        //clear cache
        City::clearCitiesCacheByProvince($city->province_id);

        return redirect()->route('admin.cities.index')
            ->with('success', 'شهر با موفقیت ویرایش شد.');
    }

    public function destroy(City $city): \Illuminate\Http\RedirectResponse
    {
        $city->delete();

        //clear cache
        City::clearCitiesCacheByProvince($city->province_id);

        return redirect()->route('admin.cities.index')
            ->with('success', 'شهر با موفقیت حذف شد.');
    }
}
