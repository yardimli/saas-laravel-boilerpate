<?php

	namespace App\Mail;

	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Mail\Mailable;
	use Illuminate\Mail\Mailables\Content;
	use Illuminate\Mail\Mailables\Envelope;
	use Illuminate\Queue\SerializesModels;

	class WelcomeMail extends Mailable
	{
		use Queueable, SerializesModels;

		public $name;
		public $email;

		public function __construct($name, $email)
		{
			$this->name = $name;
			$this->email = $email;
		}

		public function build()
		{
			$locale = \App::getLocale() ?: config('app.fallback_locale', 'zh_TW');

			$subject = '【織音】- 最懂你的心';
			$email_view = 'emails.welcome_zh_TW';
			if ($locale == 'en_US') {
				$subject = 'Welcome to WBWAI! Your exciting journey to create songs begins here.';
				$email_view = 'emails.welcome';
			}
			if ($locale == 'tr_TR') {
				$subject = 'Minik Dersler\'e Hoşgeldiniz! Eğlenceli öğrenme yolculuğunuz burada başlıyor.';
				$email_view = 'emails.welcome_tr';
			}


			return $this->from(env('MAIL_FROM_ADDRESS','support@my-laravel-saas-site.com'), env('MAIL_FROM_NAME', 'my-laravel-saas-site.com Support'))
				->subject($subject)
				->view($email_view)
				->with(['name' => $this->name, 'email' => $this->email]);
		}
	}
