<?php

namespace Modules\Customer\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Customer\Http\Requests\Customer\Address\AddressStoreRequest;
use Modules\Customer\Http\Requests\Customer\Address\AddressUpdateRequest;
use Modules\Customer\Models\Address;

class AddressController extends Controller
{
	public function index(): JsonResponse
	{
		$customerId = auth('customer-api')->user()->id;
		$addresses = Address::selectAllWithoutTimestamp()->whereCustomerId($customerId)->get();

		return response()->success('تمام آدرس ها', compact('addresses'));
	}

	public function store(AddressStoreRequest $request): JsonResponse
	{
		Address::query()->create($request->validated());

		return response()->success('آدرس جدید با موفقیت ثبت شد.');
	}

	public function update(AddressUpdateRequest $request, Address $address): JsonResponse
	{
		$address->update($request->validated());

		return response()->success('آدرس مورد نظر با موفقیت بروزرسانی شد.');
	}

	public function destroy(Address $address)
	{
		$address->delete();

		return response()->success('آدرس مورد نظر با موفقیت حذف شد.');
	}
}
