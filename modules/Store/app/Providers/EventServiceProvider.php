<?php

namespace Modules\Store\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Store\Events\StoreCreated;
use Modules\Store\Listeners\CreateTransactionForStore;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		StoreCreated::class => [
			CreateTransactionForStore::class,
		]
	];
}
