<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StoreTransaction extends Model
{
	use LogsActivity;

	protected $fillable = [
		'store_id',
		'order_id',
		'type',
		'quantity',
		'description'
	];

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
		if (Cache::has('store_transactions')) {
			Cache::forget('store_transactions');
		}
	}

	// Relations 
	public function store(): BelongsTo
	{
		return $this->belongsTo(Store::class);
	}
}
