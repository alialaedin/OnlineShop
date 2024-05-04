<?php

namespace Modules\Order\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Listeners\CreateInvoiceForOrder;
use Modules\Order\Listeners\CreateOrderItems;
use Modules\Order\Listeners\EmptyTheCustomerCart;
use Modules\Order\Listeners\LoggingOfOrderStatus;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event handler mappings for the application.
	 *
	 * @var array<string, array<int, string>>
	 */
	protected $listen = [
		OrderCreated::class => [
			LoggingOfOrderStatus::class,
			CreateOrderItems::class,
			CreateInvoiceForOrder::class,
			EmptyTheCustomerCart::class
		]
	];

	/**
	 * Indicates if events should be discovered.
	 *
	 * @var bool
	 */
	protected static $shouldDiscoverEvents = true;

	/**
	 * Configure the proper event listeners for email verification.
	 *
	 * @return void
	 */
	protected function configureEmailVerification(): void
	{
	}
}
