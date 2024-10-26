<?php

	namespace App\Notifications;

	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Notifications\Messages\MailMessage;
	use Illuminate\Notifications\Notification;
	use Illuminate\Auth\Notifications\VerifyEmail;
	use Illuminate\Support\Facades\URL;
	use Illuminate\Support\Facades\Config;

	class CustomVerifyEmail extends Notification
	{
		use Queueable;

		/**
		 * Create a new notification instance.
		 */
		public function __construct()
		{
			//
		}

		/**
		 * Get the notification's delivery channels.
		 *
		 * @return array<int, string>
		 */
		public function via(object $notifiable): array
		{
			return ['mail'];
		}

		/**
		 * Get the mail representation of the notification.
		 */
		public function toMail($notifiable)
		{
			$verificationUrl = $this->verificationUrl($notifiable);
			$logoPath = public_path('images/logo.png');
			$logoData = base64_encode(file_get_contents($logoPath));

			$locale = \App::getLocale() ?: config('app.fallback_locale', 'zh_TW');


			$subject = '織音-電子信箱驗證信';
			$email_view = 'emails.verify-email_zh_TW';
			if ($locale == 'en_US') {
				$subject = 'Please verify your SAAS LARAVEL BOILERPLATE email address.';
				$email_view = 'emails.verify-email';
			}
			if ($locale == 'tr_TR') {
				$subject = 'Lütfen SAAS LARAVEL BOILERPLATE e-posta adresinizi doğrulayın.';
				$email_view = 'emails.verify-email_tr';
			}


			return (new MailMessage)
				->subject($subject)
				->view($email_view, [
					'verificationUrl' => $verificationUrl,
					'logoData' => $logoData
				]);

		}


		/**
		 * Get the verification URL for the given notifiable.
		 *
		 * @param mixed $notifiable
		 * @return string
		 */
		protected function verificationUrl($notifiable)
		{
			return URL::temporarySignedRoute(
				'verification.verify',
				now()->addMinutes(Config::get('auth.verification.expire', 60)),
				['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
			);
		}

		/**
		 * Get the array representation of the notification.
		 *
		 * @return array<string, mixed>
		 */
		public function toArray(object $notifiable): array
		{
			return [
				//
			];
		}
	}
