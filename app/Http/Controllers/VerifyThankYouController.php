<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;

	class VerifyThankYouController extends Controller
	{
		public function index()
		{
			return view('user.verify-thank-you');
		}

		public function index_zh_TW()
		{
			return view('user.verify-thank-you-zh_TW');
		}

	}
