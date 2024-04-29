<?php

namespace Modules\Customer\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable
{
	use HasFactory, HasRoles, HasApiTokens, LogsActivity;

	protected $fillable = [
		'name',
		'mobile',
		'password',
		'email',
		'national_code',
		'mobile_verified_at',
		'status'
	];
	protected $hidden = [
		'password',
	];
	protected function casts(): array
	{
		return [
			'password' => 'hashed',
		];
	}

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'مشتری ' . __('logs.' . $eventName));
	}

	// Cache
	protected static function clearAllCaches(): Void
	{
		if (Cache::has('customers')) {
			Cache::forget('customers');
		}
	}

	// Relations
	public function addresses(): HasMany
	{
		return $this->hasMany(Address::class);
	}
}