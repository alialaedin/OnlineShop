<?php

namespace Modules\Specification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
// use Modules\Core\App\Traits\HasCache;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Specification extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = ['name', 'status'];

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'مشخصه ' . __('logs.' . $eventName));
	}

	// Cache
	protected static function clearAllCaches()
	{
		if (Cache::has('specifications')) {
			Cache::forget('specifications');
		}
	}

	// Relations
	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'category_specification');
	}

	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class, 'product_specification')
			->withPivot('value');
	}
}
