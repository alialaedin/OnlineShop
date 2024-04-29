<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Product\Events\ProductCreated;
use Modules\Product\Listeners\AddProductToStore;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		ProductCreated::class => [
			AddProductToStore::class,
		]
	];
}
