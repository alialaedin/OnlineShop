<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusLog extends Model
{
	use HasFactory;

	protected $fillable = [
		'order_id',
		'status'
	];

	// Relations 
	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}
}
