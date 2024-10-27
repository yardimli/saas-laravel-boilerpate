<?php

	namespace App\Http\Controllers;

	use App\Helpers\MyHelper;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Hash;
	use Laravel\Socialite\Facades\Socialite;
	use App\Models\User;
	use Illuminate\Support\Facades\Auth;
	use Exception;
	use Illuminate\Http\File;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;

	use App\Models\TokenUsage;

	class LoginWithGoogleController extends Controller
	{

		public function redirectToGoogle()
		{
//		return Socialite::driver('google')->stateless()->redirect();
			return Socialite::driver('google')->redirect();
		}

		public function getSocialAvatar($file, $path)
		{
			$fileContents = file_get_contents($file);
			return File::put(public_path() . $path . $user->getId() . ".jpg", $fileContents);
		}

		public function handleGoogleCallback()
		{
			try {

				$user = Socialite::driver('google')->user();

				$finduser = User::where('google_id', $user->id)->first();

				if ($finduser) {

					// Update the user's information
					$finduser->update([
//						'name' => $user->name,
//						'username' => $user->getNickname() ?? Str::slug($user->name),
						'email' => $user->email,
//						'picture' => $user['picture']
						// Any other fields you want to update
					]);

					// Save and update the avatar image locally

//					$avatarUrl = $user->getAvatar();
//					$avatarContents = file_get_contents($avatarUrl);
//					$avatarName = $finduser->id . '.jpg';
//					$avatarPath = 'public/user_avatars/' . $avatarName;
//					Storage::put($avatarPath, $avatarContents);

					// Update the avatar field with the local path
//					$finduser->update([
//						'avatar' => $avatarPath
//					]);

					Auth::login($finduser);

					return redirect()->intended('/chat');

				} else {
					$username = $user->getNickname() ?? Str::slug($user->name);
					//verify if username exists if so add a number to it
					$checkUsername = User::where('username', $username)->first();
					if ($checkUsername) {
						$username = $username . rand(1, 100);
					}

					// Create the user first to get the user_id
					$new_user = User::create([
						'name' => $user->name,
						'email' => $user->email,
						'password' => Hash::make('123456dummy_password'),
						'picture' => $user['picture'],
						'username' => $username,
						'about_me' => 'I am a new writer!',
						'member_status' => 1,
						'member_type' => 2,
						'last_ip' => request()->ip(),
						'background_image' => '',
						'google_id' => $user->id,
						'email_verified_at' => now(), // Set the email as verified for Google signups
					]);

					// Save the avatar image locally with user_id in the filename
					$avatarUrl = $user->getAvatar();
					$avatarContents = file_get_contents($avatarUrl);
					$avatarName = $new_user->id . '.jpg';
					$avatarPath = 'public/user_avatars/' . $avatarName;
					Storage::put($avatarPath, $avatarContents);

					// Update the avatar and picture fields with the local path and URL
					$new_user->update([
						'avatar' => $avatarPath
					]);

					//MyHelper::addStarterPackage($new_user->id);
					///-------------- ADD NEW USER TOKENS

					Auth::login($new_user);

					return redirect()->intended('/chat');
				}

			} catch
			(Exception $e) {
				dd($e);
			}
		}
	}
