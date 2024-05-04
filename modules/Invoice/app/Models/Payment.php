<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

	// Relations
	public function invoice(): BelongsTo
	{
		return $this->belongsTo(Invoice::class);
	}
}
