<?php

	namespace App\Http\Controllers;

	use App\Models\PaypalOrderPaymentDetail;
	use App\Models\PaypalOrder;
	use App\Models\PaypalOrderItem;
	use App\Models\User;
	use App\Models\TokenUsage;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Log;
	use Srmklive\PayPal\Services\PayPal as PayPalClient;
	use Carbon\Carbon;

	class PayPalController extends Controller
	{
		public function beginTransaction(Request $request)
		{
			return view('paypal.begin-transaction');
		}

		public function processTransaction(Request $request, $id)
		{
			if (Auth::check()) {
				if ($id === 'short-stories') {
					$product_name = 'Short Story Package';
					$product_description_short = 'Short Story Package - 40,000 Token Credits';
					$product_description = 'SAAS LARAVEL BOILERPLATE Short Story Package - 40,000 Token Credits';
					$product_price = '19.90';
					$reference_id = '1990';
					$sku = '11990';
					$custom_id = Auth::user()->id;
					$soft_descriptor = 'SAAS LARAVEL BOILERPLATE Shorts';
				} else if ($id === 'novella') {
					$product_name = 'Novella Package';
					$product_description_short = 'Novella Package - 100,000 Token Credits';
					$product_description = 'SAAS LARAVEL BOILERPLATE Novella Package - 100,000 Token Credits';
					$product_price = '39.90';
					$reference_id = '3990';
					$sku = '13990';
					$custom_id = Auth::user()->id;
					$soft_descriptor = 'SAAS LARAVEL BOILERPLATE Novella';

				} else if ($id === 'novel') {
					$product_name = 'Novel Package';
					$product_description_short = 'Novel Package - 240,000 Token Credits';
					$product_description = 'SAAS LARAVEL BOILERPLATE Novel Package - 240,000 Token Credits';
					$product_price = '69.90';
					$reference_id = '6990';
					$sku = '16990';
					$custom_id = Auth::user()->id;
					$soft_descriptor = 'SAAS LARAVEL BOILERPLATE Novel';

				} else {
					abort(404);
				}
			} else {
				abort(404);
			}

			$provider = new PayPalClient;
			$provider->setApiCredentials(config('paypal'));
			$paypalToken = $provider->getAccessToken();
			$response = $provider->createOrder([
				"intent" => "CAPTURE",
				"application_context" => [
					"return_url" => route('successTransaction'),
					"cancel_url" => route('cancelTransaction'),
					"shipping_preference" => "NO_SHIPPING",
				],
				"purchase_units" => [
					0 => [
						"reference_id" => $reference_id,
						"description" => $product_description_short,
						"custom_id" => $custom_id,
						"soft_descriptor" => $soft_descriptor,
						"items" => [
							0 => [
								"name" => $product_name,
								"description" => $product_description,
								"sku" => $sku,
								"quantity" => "1",
								"category" => "DIGITAL_GOODS",
								"unit_amount" => [
									"currency_code" => "USD",
									"value" => $product_price
								],
							]
						],
						"amount" => [
							"currency_code" => "USD",
							"value" => $product_price,
							"breakdown" => [
								"item_total" => [
									"currency_code" => "USD",
									"value" => $product_price
								]
							]
						]
					]
				]
			]);

			if (isset($response['id']) && $response['id'] != null) {
				// redirect to approve href
				foreach ($response['links'] as $links) {
					if ($links['rel'] == 'approve') {
						return redirect()->away($links['href']);
					}
				}
				return view('paypal.paypal-finish-page', ['result' => false, 'response' => $response, 'message' => 'Something went wrong.']);
			} else {
				return view('paypal.paypal-finish-page', ['result' => false, 'response' => $response, 'message' => $response['message'] ?? 'Something went wrong.']);
			}
		}

		public function successTransaction(Request $request)
		{
//			$response = null;
//			return view('paypal.paypal-finish-page', ['result' => true, 'response' => $response, 'message' => 'Transaction complete.']);

			$provider = new PayPalClient;
			$provider->setApiCredentials(config('paypal'));
			$provider->getAccessToken();
			$response = $provider->capturePaymentOrder($request['token']);

			if (isset($response['status']) && $response['status'] == 'COMPLETED') {

				$order = new PaypalOrder([
					'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
					'paypal_order_id' => $response['id'],
					'paypal_json_string' => json_encode($response),
					'total_amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
				]);

				$order->save();

				$orderItem = new PaypalOrderItem([
					'paypal_order_id' => $order->id,
					'reference_id' => $response['purchase_units'][0]['reference_id'],
				]);

				$orderItem->save();

				$capture = $response['purchase_units'][0]['payments']['captures'][0];

				$orderPaymentDetail = new PaypalOrderPaymentDetail([
					'paypal_order_id' => $order->id,
					'capture_id' => $capture['id'],
					'currency_code' => $capture['amount']['currency_code'],
					'capture_amount' => $capture['amount']['value'],
					'paypal_fee' => $capture['seller_receivable_breakdown']['paypal_fee']['value'],
					'capture_time' => Carbon::parse($capture['create_time'])->format('Y-m-d H:i:s'),
					'update_time' => Carbon::parse($capture['update_time'])->format('Y-m-d H:i:s'),
				]);

				$orderPaymentDetail->save();


				if ($response['purchase_units'][0]['reference_id'] == '1990') {

					$token = new TokenUsage([
						'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
						'usage_type' => 'GPT-4 Credits',
						'product_name' => 'Short Story Package',
						'credit_tokens' => 40000,
						'prompt_tokens' => 0,
						'completion_tokens' => 0,
						'order_id' => $order->id,
					]);

					$token->save();

					$token = new TokenUsage([
						'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
						'usage_type' => 'GPT-3.5 Credits',
						'product_name' => 'Short Story Package',
						'credit_tokens' => 400000,
						'prompt_tokens' => 0,
						'completion_tokens' => 0,
						'order_id' => $order->id,
					]);

					$token->save();

					$user_id           = $response['purchase_units'][0]['payments']['captures'][0]['custom_id'];
					$notification_type = 'purchase';
					$message           = '<b>Order: #'.$order->id.'</b><br>Thank you for your purchase of the SAAS LARAVEL BOILERPLATE Short Story Package. You have received 20,000 GPT-4 Token Credits and 200,000 GPT 3.5 Token Credits.';
					$chat_header_id    = null;

				} else
					if ($response['purchase_units'][0]['reference_id'] == '3990') {
						$token = new TokenUsage([
							'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
							'usage_type' => 'GPT-4 Credits',
							'product_name' => 'Novella Package',
							'credit_tokens' => 100000,
							'prompt_tokens' => 0,
							'completion_tokens' => 0,
							'order_id' => $order->id,
						]);

						$token->save();

						$token = new TokenUsage([
							'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
							'usage_type' => 'GPT-3.5 Credits',
							'product_name' => 'Novella Package',
							'credit_tokens' => 1000000,
							'prompt_tokens' => 0,
							'completion_tokens' => 0,
							'order_id' => $order->id,
						]);

						$token->save();

						$user_id           = $response['purchase_units'][0]['payments']['captures'][0]['custom_id'];
						$notification_type = 'purchase';
						$message           = '<b>Order: #'.$order->id.'</b><br>Thank you for your purchase of the SAAS LARAVEL BOILERPLATE Novella Package. You have received 50,000 GPT-4 Token Credits and 500,000 GPT 3.5 Token Credits.';
						$chat_header_id    = null;

					} else if ($response['purchase_units'][0]['reference_id'] == '6990') {
						$token = new TokenUsage([
							'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
							'usage_type' => 'GPT-4 Credits',
							'product_name' => 'Novel Package',
							'credit_tokens' => 240000,
							'prompt_tokens' => 0,
							'completion_tokens' => 0,
							'order_id' => $order->id,
						]);

						$token->save();

						$token = new TokenUsage([
							'user_id' => $response['purchase_units'][0]['payments']['captures'][0]['custom_id'],
							'usage_type' => 'GPT-3.5 Credits',
							'product_name' => 'Novel Package',
							'credit_tokens' => 2400000,
							'prompt_tokens' => 0,
							'completion_tokens' => 0,
							'order_id' => $order->id,
						]);

						$token->save();

						$user_id           = $response['purchase_units'][0]['payments']['captures'][0]['custom_id'];
						$notification_type = 'purchase';
						$message           = '<b>Order: #'.$order->id.'</b><br>Thank you for your purchase of the SAAS LARAVEL BOILERPLATE Novel Package. You have received 240,000 GPT-4 Token Credits and 2,400,000 GPT 3.5 Token Credits.';
						$chat_header_id    = null;


					} else {
						abort(404);
					}

				return view('paypal.paypal-finish-page', ['result' => true, 'response' => $response, 'message' => 'Transaction complete.']);

			} else {
				return view('paypal.paypal-finish-page', ['result' => false, 'response' => $response, 'message' => $response['message'] ?? 'Something went wrong.']);
			}
		}

		public function cancelTransaction(Request $request)
		{
			return view('paypal.paypal-finish-page', ['result' => false, 'response' => $request->all(), 'message' => 'You have canceled the transaction.']);
		}
	}
