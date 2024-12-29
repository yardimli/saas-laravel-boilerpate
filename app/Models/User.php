<?php

	namespace App\Models;

	use Illuminate\Contracts\Auth\MustVerifyEmail;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Contracts\Auth\CanResetPassword;


//use Laravel\Fortify\TwoFactorAuthenticatable;
//use Laravel\Jetstream\HasProfilePhoto;
//use Laravel\Jetstream\HasTeams;
	use Laravel\Sanctum\HasApiTokens;

	use App\Notifications\CustomVerifyEmail;


	class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
	{
		use HasApiTokens;
		use HasFactory;

//	use HasProfilePhoto;
//	use HasTeams;
		use Notifiable;

//	use TwoFactorAuthenticatable;


		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<int, string>
		 */
		protected $fillable = [
			'name',
			'email',
			'password',
			'google_id',
			'line_id',
			'facebook_id',
			'avatar',
			'picture',
			'username',
			'about_me',
			'member_status',
			'member_type',
			'last_login',
			'last_ip',
			'background_image',
			'email_verified_at',
			'openai_api_key',
			'anthropic_key',
			'openrouter_key',


		];

		/**
		 * The attributes that should be hidden for serialization.
		 *
		 * @var array<int, string>
		 */
		protected $hidden = [
			'password',
			'remember_token',
			'two_factor_recovery_codes',
			'two_factor_secret',
		];

		/**
		 * The attributes that should be cast.
		 *
		 * @var array<string, string>
		 */
		protected $casts = [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];

		protected $appends = [
			'profile_photo_url',
		];

		public function sendEmailVerificationNotification()
		{
			$this->notify(new CustomVerifyEmail());
		}


		public function isAdmin()
		{
			return $this->member_type === 1;
		}


	}
