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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Socialite;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
//	protected $redirectTo = '/';

	protected function authenticated(Request $request, $user)
	{
		return redirect()->route('my-settings');
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function redirectToProvider()
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function handleProviderCallback()
	{
		$user = Socialite::driver('google')->user();
// $user->token; (return as your need)
	}

	public function logout(Request $request)
	{
		Log::info('Logout');
		// Get the currently authenticated user before logging out
		$user = Auth::check() ? Auth::user() : null;

		$this->guard()->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		if ($user) {
			event(new Logout($this->guard(), $user)); // Dispatch the logout event only if there was an authenticated user
		}

		// Perform additional custom actions here, like cleanup or logging
		$_SESSION['guid'] = null;

		return $this->loggedOut($request) ?: redirect('/');
	}
}
