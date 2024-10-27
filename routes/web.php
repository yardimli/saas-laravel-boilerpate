<?php

	use App\Http\Controllers\ChatController;
	use App\Http\Controllers\ImageGenController;
	use App\Http\Controllers\LangController;
	use App\Http\Controllers\LoginWithGoogleController;
	use App\Http\Controllers\StaticPagesController;
	use App\Http\Controllers\UserController;
	use App\Http\Controllers\UserSettingsController;
	use App\Http\Controllers\VerifyThankYouController;
	use App\Mail\WelcomeMail;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Facades\Route;


	/*
	|--------------------------------------------------------------------------
	| Web Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register web routes for your application. These
	| routes are loaded by the RouteServiceProvider and all of them will
	| be assigned to the "web" middleware group. Make something great!
	|
	*/

	//-------------------------------------------------------------------------
	Route::get('/', [StaticPagesController::class, 'landing'])->name('landing-page');

	Route::get('/lang/home', [LangController::class, 'index']);
	Route::get('/lang/change', [LangController::class, 'change'])->name('changeLang');

	Route::get('login/google', [LoginWithGoogleController::class, 'redirectToGoogle']);
	Route::get('login/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);

	Route::get('/logout', [LoginWithGoogleController::class, 'logout']);

	Route::get('/verify-thank-you', [VerifyThankYouController::class, 'index'])->name('verify-thank-you')->middleware('verified');
	Route::get('/verify-thank-you-zh_TW', [VerifyThankYouController::class, 'index_zh_TW'])->name('verify-thank-you-zh_TW')->middleware('verified');

	Route::get('/privacy', [StaticPagesController::class, 'privacy'])->name('privacy-page');
	Route::get('/terms', [StaticPagesController::class, 'terms'])->name('terms-page');
	Route::get('/help', [StaticPagesController::class, 'help'])->name('help-page');
	Route::get('/help/{topic}', [StaticPagesController::class, 'helpDetails'])->name('help-details');
	Route::get('/about', [StaticPagesController::class, 'about'])->name('about-page');
	Route::get('/contact', [StaticPagesController::class, 'contact'])->name('contact-page');
	Route::get('/onboarding', [StaticPagesController::class, 'onboarding'])->name('onboarding-page');
	Route::get('/change-log', [StaticPagesController::class, 'changeLog'])->name('change-log-page');
	Route::get('/buy-packages', [UserSettingsController::class, 'buyPackages'])->name('buy-packages');

	Route::get('/help', [StaticPagesController::class, 'help'])->name('help-page');

	//-------------------------------------------------------------------------

	Route::get('/buy-packages', [UserSettingsController::class, 'buyPackages'])->name('buy-packages');

	Route::get('/buy-credits-test/{id}', [PayPalController::class, 'beginTransaction'])->name('beginTransaction');
	Route::get('/buy-credits/{id}', [PayPalController::class, 'processTransaction'])->name('processTransaction');
	Route::get('/success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
	Route::get('/cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

	Route::get('/user-profile/{username}', [StaticPagesController::class, 'userProfile'])->name('user-profile');


	//-------------------------------------------------------------------------
	Route::middleware(['auth'])->group(function () {

		Route::get('/check-llms-json', [ChatController::class, 'checkLLMsJson']);


		Route::get('/chat/sessions', [ChatController::class, 'getChatSessions']);
		Route::get('/chat/{session_id?}', [ChatController::class, 'index'])->name('chat');
		Route::post('/create-session', [ChatController::class, 'createSession'])->name('chat.create-session');
		Route::get('/chat/messages/{sessionId}', [ChatController::class, 'getChatMessages']);
		Route::post('/send-llm-prompt', [ChatController::class, 'sendLlmPrompt'])->name('send-llm-prompt');
		Route::delete('/chat/{sessionId}', [ChatController::class, 'destroy'])->name('chat.destroy');


		Route::get('/image-gen/sessions', [ImageGenController::class, 'getImageGenSessions'])->name('image-gen-sessions');
		Route::get('/image-gen/{session_id?}', [ImageGenController::class, 'index'])->name('image-gen');
		Route::post('/image-gen', [ImageGenController::class, 'makeImage'])->name('send-image-gen-prompt');
		Route::delete('/image-gen/{session_id}', [ImageGenController::class, 'destroy'])->name('image-gen.destroy');


		Route::get('/settings', [UserSettingsController::class, 'editSettings'])->name('my-settings');
		Route::post('/settings', [UserSettingsController::class, 'updateSettings'])->name('settings-update');

		Route::post('/settings/password', [UserSettingsController::class, 'updatePassword'])->name('settings-password-update');
		Route::post('/settings/api-keys', [UserSettingsController::class, 'updateApiKeys'])->name('settings-update-api-keys');

		Route::get('/users', [UserController::class, 'index'])->name('users-index');
		Route::post('/login-as', [UserController::class, 'loginAs'])->name('users-login-as');

		Route::post('/settings/password', [UserSettingsController::class, 'updatePassword'])->name('settings-password-update');

	});

	//-------------------------------------------------------------------------

	Auth::routes();
	Auth::routes(['verify' => true]);
