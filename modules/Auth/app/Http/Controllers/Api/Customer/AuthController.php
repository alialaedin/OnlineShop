<?php

namespace Modules\Auth\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Http\Requests\Customer\LoginRequest;
use Modules\Auth\Http\Requests\Customer\LogoutRequest;
use Modules\Auth\Http\Requests\Customer\RegisterLoginRequest;
use Modules\Auth\Http\Requests\Customer\RegisterRequest;
use Modules\Auth\Http\Requests\Customer\SendTokenRequest;
use Modules\Auth\Http\Requests\Customer\VerifyRequest;
use Modules\Core\App\Helpers\Helpers;
use Modules\Customer\Events\SmsVerify;
use Modules\Customer\Models\Customer;
use Modules\Sms\Models\SmsToken;

class AuthController extends Controller
{
	public function registerLogin(RegisterLoginRequest $request): JsonResponse
	{

		try {
			$mobile = $request->input('mobile');
			$customer = Customer::query()->where('mobile', $mobile)->exists();
			$status = $customer ? 'login' : 'register';

			// if ($status === 'register') {
			// 	$result = event(new SmsVerify($mobile));
			// 	if ($result[0]['status'] != 200) {
			// 		throw Helpers::makeValidationException('ارسال کدفعال سازی ناموفق بود.لطفا دوباره تلاش کنید.');
			// 	}
			// }

			return response()->success('بررسی وضعیت ثبت نام مشتری', compact('status', 'mobile'));
		} catch (Exception $exception) {
			Log::error($exception->getTraceAsString());
			$message = 'مشکلی در برنامه بوجود آمده است. لطفا با پشتیبانی تماس بگیرید: ';

			return response()->error($message . $exception->getMessage(), 500);
		}
	}

	public function sendToken(SendTokenRequest $request): JsonResponse
	{
		$mobile = $request->input('mobile');
		try {
			SmsToken::query()->updateOrCreate(
				['mobile' => $mobile],
				[
					'token' => Helpers::randomNumbersCode(4),
					'expires_at' => Carbon::now()->addMinutes(2)
				]
			);

			// $result = event(new SmsVerify($mobile));
			// if ($result[0]['status'] != 200) {
			// 	throw new Exception($result[0]['message']);
			// }

			return response()->success('بررسی وضعیت ثبت نام مشتری', compact('mobile'));
		} catch (Exception $exception) {
			Log::error($exception->getTraceAsString());
			$message = 'مشکلی در برنامه بوجود آمده است. لطفا با پشتیبانی تماس بگیرید: ';

			return response()->error($message . $exception->getMessage(), 500);
		}
	}

	public function verify(VerifyRequest $request): JsonResponse
	{
		try {
			$request->smsToken->verified_at = now();
			$request->smsToken->save();
			$data['mobile'] = $request->input('mobile');

			if ($request->type === 'login') {
				$customer = $request->input('customer');
				$token = $customer->createToken('authToken')->plainTextToken;
				$data['access_token'] = $token;
				$data['customer'] = $customer;
				$data['token_type'] = 'Bearer';
				Sanctum::actingAs($customer);
			}

			return response()->success('', compact('data'));
		} catch (Exception $exception) {
			Log::error($exception->getTraceAsString());
			$message = 'مشکلی در برنامه بوجود آمده است. لطفا با پشتیبانی تماس بگیرید: ';

			return response()->error($message . $exception->getMessage(), 500);
		}
	}

	public function register(RegisterRequest $request): JsonResponse
	{
		$customer = Customer::query()->create($request->validated());
		$token = $customer->createToken('authToken')->plainTextToken;
		Sanctum::actingAs($customer);
		$data = [
			'access_token' => $token,
			'token_type' => 'Bearer',
			'customer' => $customer,
		];

		return response()->success('ثبت نام با موفقیت انجام شد', compact('data'));
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$customer = $request->input('customer');

		if (!$customer || !Hash::check($request->password, $customer->password)) {
			dd(1);
			return response()->error('اطلاعات وارد شده اشتباه است!', [], 422);
		}

		$token = $customer->createToken('authToken');
		Sanctum::actingAs($customer);

		$data = [
			'customer' => $customer,
			'access_token' => $token->plainTextToken,
			'token_type' => 'Bearer'
		];

		return response()->success('کاربر با موفقیت وارد شد!', compact('data'));
	}

	public function logout(LogoutRequest $request): JsonResponse
	{
		$customer = $request->user();
		$customer->currentAccessToken()->delete();

		return response()->success('کاربر با موفقیت از برنامه خارج شد!');
	}
}
