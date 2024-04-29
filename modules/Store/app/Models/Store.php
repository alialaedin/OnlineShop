<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Store extends Model
{
	use LogsActivity;
	protected $fillable = ['product_id', 'balance'];

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'محصول ' . __('logs.' . $eventName));
	}

	// Cache
	protected static function clearAllCaches()
	{
		if (Cache::has('stores')) {
			Cache::forget('stores');
		}
	}
	
	// Relations 
	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	public function transactions(): HasMany
	{
		return $this->hasMany(StoreTransaction::class);
	}
}
