<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Models\ChatHeader;
	use App\Models\ChatBody;
	use App\Models\User;
	use App\Models\TokenUsage;
	use App\Models\PaypalOrder;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Validator;
	use App\Helpers\MyHelper;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Validation\Rule;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Validation\ValidationException;


	class UserSettingsController extends Controller
	{
		//-------------------------------------------------------------------------
		// Index
		public function index(Request $request)
		{

		}


//-------------------------------------------------------------------------
		// Update user password
		public function updatePassword(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			// Validate input
			$validator = Validator::make($request->all(), [
				'current_password' => ['nullable', 'string'],
				'new_password' => ['required', 'string', 'min:6', 'confirmed'],
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$current_password = $request->input('current_password') ?? '123456dummy_password';
//dd($user->password, $current_password, Hash::check($current_password, $user->password), Hash::make($current_password));
			// Check if the current password is correct
			if (!Hash::check($current_password, $user->password)) {
				throw ValidationException::withMessages([
					'current_password' => ['Current password is incorrect.'],
				]);
			}

			// Update user password
			$user->password = Hash::make($request->input('new_password'));
			$user->save();

			// Redirect back with success message
			Session::flash('success', 'Your password has been updated successfully.');
			return redirect()->back();
		}

		//-------------------------------------------------------------------------
		// privacy
		public function buyPackages(Request $request)
		{
			if (Auth::check()) {
				$checkout_starter = 'starter';
				$checkout_novella = 'novella';
				$checkout_novel = 'novel';
			} else {
				$checkout_starter = null;
				$checkout_novella = null;
				$checkout_novel = null;
			}

			return view('user.buy-packages', compact('checkout_starter', 'checkout_novella', 'checkout_novel'));
		}


		//-------------------------------------------------------------------------
		// settings


		public function editSettings(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			return view('user.settings', compact('user'));
		}

// Update user settings
		public function updateSettings(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			// Validate input
			$validator = Validator::make($request->all(), [
				'name' => ['required', 'string', 'max:255'],
				'username' => [
					'required', 'string', 'max:255', 'alpha_dash',
					Rule::unique('users')->ignore($user->id),
				],
				'email' => [
					'required', 'string', 'email', 'max:255',
					Rule::unique('users')->ignore($user->id),
				],
				'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			if ($request->hasFile('avatar')) {
				$avatar = $request->file('avatar');
				$avatarContents = file_get_contents($avatar->getRealPath());
				$avatarName = $user->id . '.jpg';
				$avatarPath = 'public/user_avatars/' . $avatarName;
				Storage::put($avatarPath, $avatarContents);
				$user->avatar = $avatarPath;
			}

			// Update user
			$user->name = $request->input('name');
			$user->username = $request->input('username');
			$user->email = $request->input('email');
			$user->save();

			// Redirect back with success message
			Session::flash('success', 'Your settings have been updated successfully.');
			return redirect()->back();
		}

		public function updateApiKeys(Request $request)
		{
			$user = Auth::user();

			$validator = Validator::make($request->all(), [
				'openai_api_key' => 'nullable|string',
				'anthropic_key' => 'nullable|string',
				'openrouter_key' => 'nullable|string',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$user->update([
				'openai_api_key' => $request->input('openai_api_key'),
				'anthropic_key' => $request->input('anthropic_key'),
				'openrouter_key' => $request->input('openrouter_key'),
			]);

			Session::flash('success', 'Your API keys have been updated successfully.');
			return redirect()->back();
		}

	}
