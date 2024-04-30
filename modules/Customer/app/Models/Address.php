<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Modules\Area\Models\City;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;

class Address extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'mobile',
		'address',
		'postal_code'
	];

	protected static function booted(): void
	{
		// static::deleting(function (Address $address) {
		// 	$messages = 'این آدرس قابل حذف نمی باشد زیرا در سفارشی استفاده شده است!';
		// 	if ($address->orders()->exists()) {
		// 		throw new ModelCannotBeDeletedException($messages);
		// 	}
		// });
	}

	// Relations
	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function city(): BelongsTo
	{
		return $this->belongsTo(City::class);
	}

	// Query 
	public function scopeWhereCustomerId(Builder $query, int $customerId)
	{
		$query->where('customer_id', $customerId);
	}
	public function scopeSelectAllWithoutTimestamp(Builder $query)
	{
		$query->select('id', 'customer_id', 'name', 'mobile', 'city_id', 'address', 'postal_code');
	}
}
