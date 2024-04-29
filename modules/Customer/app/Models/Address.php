<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Modules\Area\Models\City;

class Address extends Model
{
	use HasFactory;

	protected $fillable = [
		'customer_id',
		'city_id',
		'name',
		'mobile',
		'address',
		'postal_code'
	];
	
	// Cache
	protected static function clearAllCaches(): Void
	{
		if (Cache::has('addresses')) {
			Cache::forget('addresses');
		}
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
}
