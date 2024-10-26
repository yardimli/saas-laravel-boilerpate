<?php
	
	namespace App\Http\Controllers\Auth;
	
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\Password;
	use App\Http\Controllers\ValidationException;
	use App\Mail\ResetPasswordMail;
	use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Mail;
	
	class ForgotPasswordController extends Controller
	{
		use SendsPasswordResetEmails;
		
		public function showForgotPasswordForm()
		{
			return view('auth.passwords.email');
		}
		
		public function sendResetLinkEmail(Request $request)
		{
			$this->validateEmail($request);
			
			$user = $this->broker()->getUser($this->credentials($request));
			
			if (!$user) {
				return back()->withErrors(['email' => 'User not found.']);
			}
			
			// Create a new token to be sent to the user.
			$token = $this->broker()->createToken($user);
			
			// Send the email with the reset link using the custom Mailable class.
//			dd(new ResetPasswordMail($token, $request->input('email')));
			Mail::to($request->input('email'))->send(new ResetPasswordMail($token, $request->input('email')));
			
			return back()->with('status', '重新設定密碼的連結已經寄到您的信箱囉！');
		}
		
		public function sendPasswordResetEmail(Request $request)
		{
			try {
				$this->validateEmail($request);
			} catch (ValidationException $e) {
				return back()->withErrors(['email' => $e->errors()['email'][0]]);
			}
			
			try {
				$response = $this->broker()->sendResetLink($request->only('email'));
				
				if ($response == Password::RESET_LINK_SENT) {
					return back()->with(['status' => trans($response)]);
				} else {
					return back()->withErrors(['email' => trans($response)]);
				}
			} catch (\Exception $e) {
				return back()->withErrors(['email' => $e->getMessage()]);
			}
		}
	}
