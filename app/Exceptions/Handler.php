<?php

	namespace App\Exceptions;

	use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use Throwable;
	use Symfony\Component\HttpKernel\Exception\HttpException;

	class Handler extends ExceptionHandler
	{
		/**
		 * The list of the inputs that are never flashed to the session on validation exceptions.
		 *
		 * @var array<int, string>
		 */
		protected $dontFlash = [
			'current_password',
			'password',
			'password_confirmation',
		];

		public function render($request, Throwable $exception)
		{
			if ($exception instanceof NotFoundHttpException) {
				return response()->view('errors.error-404', [], 404);
			}

			if ($exception instanceof HttpException && $exception->getStatusCode() === 419) {
				return response()->view('errors.error-419', [], 419);
			}

			return parent::render($request, $exception);
		}

		/**
		 * Register the exception handling callbacks for the application.
		 */
		public function register(): void
		{
			$this->reportable(function (Throwable $e) {
				//
			});

			$this->renderable(function (NotFoundHttpException $e, $request) {
				if ($request->is('*.map')) {
					return response()->json(['message' => 'Not Found'], 404);
				}
			});

			$this->renderable(function (\Exception $e) {
				if ($e instanceof HttpException && $e->getStatusCode() === 419) {
					return response()->view('errors.error-419', [], 419);
				}

				if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
					return redirect()->route('/');
				};
			});


		}
	}
