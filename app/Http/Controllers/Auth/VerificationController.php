<?php
	/*

	=========================================================

	* Song Page: https://my-laravel-saas-site.com/product
	* Copyright 2018 my-laravel-saas-site.com (https://my-laravel-saas-site.com)

	* Coded by my-laravel-saas-site.com

	=========================================================

	* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	*/

	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\VerifiesEmails;
	use Illuminate\Http\Request;
	use Illuminate\Auth\Events\Verified;
	use Illuminate\Auth\Access\AuthorizationException;

	class VerificationController extends Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Email Verification Controller
		|--------------------------------------------------------------------------
		|
		| This controller is responsible for handling email verification for any
		| user that recently registered with the application. Emails may also
		| be re-sent if the user didn't receive the original email message.
		|
		*/

		use VerifiesEmails;

		/**
		 * Where to redirect users after verification.
		 *
		 * @var string
		 */
		protected $redirectTo = '/';

		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			$this->middleware('auth');
			$this->middleware('signed')->only('verify');
			$this->middleware('throttle:6,1')->only('verify', 'resend');
		}

		public function verify(Request $request)
		{
			$locale = \App::getLocale() ?: config('app.fallback_locale', 'zh_TW');

			auth()->loginUsingId($request->route('id'));


			if ($request->user()->hasVerifiedEmail()) {
				if ($locale == 'en_US') {
					throw new AuthorizationException('You have already verified your email.');
				} else if ($locale == 'zh_TW') {
					throw new AuthorizationException('您已經驗證了您的電子郵件。');
				} else if ($locale == 'tr_TR') {
					throw new AuthorizationException('E-postanızı zaten doğruladınız.');
				} else {
					throw new AuthorizationException('You have already verified your email.');
				}
			}

			if ($request->user()->markEmailAsVerified()) {
				event(new Verified($request->user()));
			}

			if ($locale == 'en_US') {
				return redirect()->route('verify-thank-you');
			} else if ($locale == 'zh_TW') {
				return redirect()->route('verify-thank-you-zh_TW');
			} else if ($locale == 'tr_TR') {
				return redirect()->route('verify-thank-you-tr_TR');
			} else {
				return redirect()->route('verify-thank-you-zh_TW');
			}
		}
	}
