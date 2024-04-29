<?php

namespace Modules\Customer\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Customer\Models\Customer;

class CustomerController extends Controller
{
	public function index(): JsonResponse
	{
		$customers = Cache::rememberForever('customers', function () {
			return Customer::query()
				->select(['id', 'naem', 'mobile', 'status', 'email'])
				->latest('id')
				->paginate();
		});

		return response()->success('تمام مشتری ها', compact('customers'));
	}

	public function show(Customer $customer): JsonResponse
	{
		$customer->load([
			'addresses' => function (Builder $query) {
				$query->select(['id', 'customer_id', 'city_id', 'address', 'postal_code'])
					->with(['city' => function (Builder $query) {
						$query->select(['id', 'province_id', 'name'])
							->with('province:id,name');
					}]);
			}
		]);

		return response()->success("مشتری با شناسه {$customer->id}", compact('customer'));
	}

	public function destroy(Customer $customer): JsonResponse
	{
		$customer->delete();

		return response()->success("مشتری با شناسه {$customer->id} با موفقیت پاک شد");
	}
}
