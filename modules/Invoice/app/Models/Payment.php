<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Models\Order;

class Payment extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 */
	protected $fillable = [
		'invoice_id',
		'amount',
		'driver',
		'tracking_code',
		'description',
		'token',
		'status'
	];

	public static function getAllDriverKeys(): array
	{
		return config('order.drivers') ? array_keys(config('order.drivers')) : [];
	}

	public static function getAllDrivers(): array
	{
		return config('order.drivers');
	}

	// Relations
	public function invoice(): BelongsTo
	{
		return $this->belongsTo(Invoice::class);
	}
	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}
}
