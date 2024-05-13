<?php

namespace Modules\Order\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Cart\Models\Cart;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\Payment;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Http\Requests\OrderStoreRequest;
use Modules\Order\Http\Requests\OrderVerifyRequest;
use Shetabit\Multipay\Invoice as ShetabitInvoice;
use Shetabit\Payment\Facade\Payment as ShetabitPayment;
use Modules\Order\Models\Order;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;

class OrderController extends Controller
{
	public function purchase(OrderStoreRequest $request)
	{
		$customer = Customer::findOrFail(auth('customer-api')->user()->id);
		$address = Address::findOrFail($request->address_id);

		try {
			// Create Order
			$order = Order::query()->create([
				'customer_id' => $customer->id,
				'address_id' => $address->id,
				'address' => $address->toJson(),
				'amount' => $customer->calcTheSumOfPricesInCart(),
				'status' => 'wait_for_payment'
			]);

			// Create OrderItem and OrderStatusLog
			Event::dispatch(new OrderCreated($order));

			// Clear Cart
			Cart::where('customer_id', $customer->id)->delete();

			$driver = $request->input('driver');
			$route = route('payments.verify', $driver);

			// Create Invoice 
			$invoice = Invoice::create([
				'order_id' => $order->id,
				'amount' => $order->amount,
				'status' => 0
			]);

			// make Payment 
			$payment = Payment::create([
				'invoice_id' => $invoice->id,
				'amount' => $invoice->amount,
				'driver' => $request->driver,
				'tracking_code' => null,
				'description' => null,
				'token' => null,
				'status' => 0
			]);

			//amount always Toman
			$response = ShetabitPayment::via($driver)->callbackUrl($route)
				->purchase((new ShetabitInvoice)->amount($payment->amount),
					function ($driver, $transactionId) use ($payment) {
						$payment->update([
							'token' => $transactionId
						]);
					}
				)->pay()->toJson();
			$url = json_decode($response)->action;

			return response()->success('', compact('url'));
		} catch (\Exception $exception) {
			return response()->error('مشکلی رخ داده است: ' . $exception->getMessage(), 500);
		}
	}

	public function verify(OrderVerifyRequest $request, String $driver)
	{
		$drivers = Payment::getAllDrivers();
		$transactionId = $drivers[$driver]['options']['transaction_id'];
		$payment = Payment::query()->where('token', $request->{$transactionId})->first();

		DB::beginTransaction();
		try {

			if (!$payment) {
				throw new InvoiceNotFoundException('پرداختی نامعتبر است!');
			}

			$receipt = ShetabitPayment::via($driver)
				->amount($payment->amount)
				->transactionId($payment->token)
				->verify();

			//Update payment
			$payment->update([
				'tracking_code' => $receipt->getReferenceId(),
				'status' => 1
			]);

			//Update order status
			$order = $payment->order;
			$order->update(['status' => 'success']);
			$order->statusLogs()->create(['status' => 'success']);

			//update invoice status
			$invoice = $payment->invoice;
			$invoice->update(['status' => 1]);

			DB::commit();

			return response()->success('', compact('url'));
		} catch (InvalidPaymentException | InvoiceNotFoundException $exception) {
			DB::rollBack();
			$message = $exception->getMessage();
			$payment->update(['description' => $message]);

			$order = $payment->order;
			if ($order->status == 'wait_for_payment') {
				$order->update(['status' => 'failed']);
				$order->statusLogs()->create(['status' => 'failed']);
			}

			return response()->error('مشکلی رخ داده است: ' . $exception->getMessage(), 500);
		}
	}
}
