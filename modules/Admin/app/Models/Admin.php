<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
	use HasFactory, HasRoles, HasApiTokens, LogsActivity;

	protected $fillable = [
		'name',
		'mobile',
		'email',
		'email_verified_at',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'ادمین ' . __('logs.' . $eventName));
	}

	// Cache
	protected static function clearAllCaches(): Void
	{
		if (Cache::has('admins')) {
			Cache::forget('admins');
		}
	}

	protected static function booted(): void
	{
		static::deleting(function (Admin $admin) {
			foreach ($admin->getRoleNames() as $roleName) {
				$admin->removeRole($roleName);
			}
		});
	}
}
