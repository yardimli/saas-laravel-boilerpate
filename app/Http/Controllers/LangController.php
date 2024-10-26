<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\App;

	class LangController extends Controller
	{
		public function index()
		{
			dd('what are you looking for?');
		}

		public function change(Request $request)
		{
			App::setLocale($request->lang);
			session()->put('locale', $request->lang);
			//go to home page
			return redirect()->route('landing-page');
		}

	}
