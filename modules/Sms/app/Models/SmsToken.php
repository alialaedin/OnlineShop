<?php

namespace Modules\Sms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsToken extends Model
{
	use HasFactory;

	protected $fillable = [
		'mobile',
		'token',
		'expires_at'
	];
}
