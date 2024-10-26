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
	use App\Mail\WelcomeMail;
	use App\Models\TokenUsage;
	use App\Models\User;
	use Illuminate\Foundation\Auth\RegistersUsers;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Str;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Session;
	use Symfony\Component\VarDumper\Cloner\VarCloner;
	use App\Helpers\MyHelper;

	class RegisterController extends Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Register Controller
		|--------------------------------------------------------------------------
		|
		| This controller handles the registration of new users as well as their
		| validation and creation. By default this controller uses a trait to
		| provide this functionality without requiring any additional code.
		|
		*/

		use RegistersUsers;

		/**
		 * Where to redirect users after registration.
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
			$this->middleware('guest');
		}

		/**
		 * Get a validator for an incoming registration request.
		 *
		 * @param array $data
		 *
		 * @return \Illuminate\Contracts\Validation\Validator
		 */
		protected function validator(array $data)
		{
			// Load the blacklist of domains
//			$blacklist = array_map('trim', file(resource_path('texts/free-domains-2.csv'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
//
//			Validator::extend('not_in_domain_blacklist', function ($attribute, $value, $parameters, $validator) use ($blacklist) {
//				// Extract the domain from the email
//				$domain = substr(strrchr($value, "@"), 1);
//				// Check if the domain is in the blacklist
//				return !in_array($domain, $blacklist);
//			}, 'The selected email domain is not allowed for registration.');

			return Validator::make($data, [
				'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'policy' => ['required', 'accepted'],
			]);
		}

		/**
		 * Create a new user instance after a valid registration.
		 *
		 * @param array $data
		 *
		 * @return \App\Models\User
		 */
		protected function create(array $data)
		{
			$new_user = User::create([
				                         'name' => $data['username'],
				                         'email' => $data['email'],
				                         'password' => Hash::make($data['password']),
				                         'avatar' => '',
				                         'picture' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($data['email']))) . '?s=200&d=mp',
				                         'username' => $data['username'],
				                         'about_me' => 'I am a new writer!',
				                         'member_status' => 1,
				                         'member_type' => 2,
				                         'last_ip' => request()->ip(),
				                         'background_image' => '',
			                         ]);

			return $new_user;
		}

		protected function registered(Request $request, $user)
		{
			Mail::to($request->input('email'))->send(new WelcomeMail($user->name, $user->email));
			return redirect()->route('my-settings');
		}
	}
