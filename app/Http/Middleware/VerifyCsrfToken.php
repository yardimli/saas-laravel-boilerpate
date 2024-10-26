<?php

	namespace App\Http\Middleware;

	use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

	class VerifyCsrfToken extends Middleware
	{
		/**
		 * The URIs that should be excluded from CSRF verification.
		 *
		 * @var array<int, string>
		 */
		protected $except = [
			'/jobs',
			'/jobs-set-status',
			'/jobs-options',
			'/jobs-set-status-options',
			'/jobs-set-results',
			'/jobs-set-results-options',
			'/meditations-admin/update-aws-from-local',
			'/meditations-admin/echo-local-requests',
			'/meditations-admin/post-render-queue',
			'/meditations-admin/check-render-queue',
			'/meditations-admin/post-delete-image',
			'/meditations-admin/select-single-image'
		];
	}
