<?php

	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Str;

	class ResetPasswordController extends Controller
	{
		use ResetsPasswords;

		// Set the redirectTo property
		protected $redirectTo = '/settings';


		protected function resetPassword($user, $password)
		{
			$user->password = Hash::make($password);
			$user->setRememberToken(Str::random(60));
			$user->save();

			// Log the user in.
			Auth::login($user);
//			$this->guard()->login($user);

			// Redirect the user to the /settings page with a success message.
			return redirect('/settings')->with('status', 'Your password has been changed.');
		}

		public function showResetPasswordForm(Request $request, $token = null)
		{
			return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
		}
	}
