<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{__('default.SAAS LARAVEL BOILERPLATE')}} - @yield('title', 'Home')</title>
	
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="fictionfusion.io">
	<meta name="description"
	      content="{{__('default.SAAS LARAVEL BOILERPLATE')}} - {{__('default.Boilerplate Site Tagline')}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<script src="/assets/js/core/jquery.min.js"></script>
	
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	
	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
	
	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">
	
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
	
	{{--	@lemonJS--}}

</head>
@php
	use Carbon\Carbon;
@endphp

<script>
	
	// <!-- Dark mode -->
	const storedTheme = localStorage.getItem('theme')
	
	const getPreferredTheme = () => {
		if (storedTheme) {
			return storedTheme
		}
		return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
	}
	
	const setTheme = function (theme) {
		if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			document.documentElement.setAttribute('data-bs-theme', 'dark')
		} else {
			document.documentElement.setAttribute('data-bs-theme', theme)
		}
	}
	
	setTheme(getPreferredTheme())
	
	window.addEventListener('DOMContentLoaded', () => {
		var el = document.querySelector('.theme-icon-active');
		if (el != 'undefined' && el != null) {
			const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')
				
				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})
				
				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}
			
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})
			
			showActiveTheme(getPreferredTheme())
			
			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})
			
		}
	});
</script>

@if (Auth::check())
	@php
		$found_package = false;
	@endphp
@endif

<body>

<!-- =======================
Header START -->
<header class="navbar-light fixed-top header-static bg-mode">
	
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand" href="{{route('landing-page')}}">
				<img class="light-mode-item navbar-brand-item" src="/images/logo.png" alt="logo">
				<img class="dark-mode-item navbar-brand-item" src="/images/logo.png" alt="logo">
			</a>
			<!-- Logo END -->
			
			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto icon-md btn btn-light p-0" type="button" data-bs-toggle="collapse"
			        data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
			        aria-label="Toggle navigation">
        <span class="navbar-toggler-animation">
          <span></span>
          <span></span>
          <span></span>
        </span>
			</button>
			
			<!-- Main navbar START -->
			<div class="collapse navbar-collapse" id="navbarCollapse">
				
				<!-- Nav Search START -->
				<div class="nav mt-3 mt-lg-0 flex-nowrap align-items-center px-4 px-lg-0">
					<div class="nav-item w-100">
						<form class="rounded position-relative">
							<input class="form-control ps-5 bg-light" type="search" placeholder="{{__('default.Search...')}}"
							       aria-label="Search">
							<button class="btn bg-transparent px-2 py-0 position-absolute top-50 start-0 translate-middle-y"
							        type="submit"><i class="bi bi-search fs-5"> </i></button>
						</form>
					</div>
				</div>
				<!-- Nav Search END -->
				
				<ul class="navbar-nav navbar-nav-scroll ms-auto">
					<li class="nav-item">
						<a class="nav-link active" href="{{route('chat')}}">{{__('default.Chat')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="{{route('image-gen')}}">{{__('default.Image Gen')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="{{route('binshopsblog.index',['en_US'])}}">{{__('default.Blog')}}</a>
					</li>

					
					
				
				</ul>
			</div>
			<!-- Main navbar END -->
			
			<!-- Nav right START -->
			<ul class="nav flex-nowrap align-items-center ms-sm-3 list-unstyled">
				<li class="nav-item ms-2">
					<a class="nav-link icon-md btn btn-light p-0" href="{{route('help-page')}}" title="{{__('default.Help')}}">
						<i class="bi bi-life-preserver fs-6"> </i>
					</a>
				</li>
				<li class="nav-item ms-2 dropdown">
					<a class="nav-link btn icon-md p-0" href="#" id="profileDropdown" role="button"
					   data-bs-auto-close="outside"
					   data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
						@if (Auth::user())
							<img class="avatar-img rounded-circle"
							     src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/01.jpg' }}"
							     alt="avatar">
						@else
							<img class="avatar-img rounded-2" src="/assets/images/avatar/placeholder.jpg" alt="">
						@endif
					</a>
					<ul class="dropdown-menu dropdown-animation dropdown-menu-end pt-3 small me-md-n3"
					    aria-labelledby="profileDropdown">
						<!-- Profile info -->
						@if (Auth::user())
							<li class="px-3">
								<div class="d-flex align-items-center position-relative">
									<!-- Avatar -->
									<div class="avatar me-3">
										<img class="avatar-img rounded-circle"
										     src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/01.jpg' }}"
										     alt="avatar">
									</div>
									<div>
										<a class="h6 stretched-link"
										   href="{{route('my-settings')}}">{{ Auth::user()->name }}</a>
										<p class="small m-0">{{ Auth::user()->username }}</p>
									</div>
								</div>
								<a class="dropdown-item btn btn-primary-soft btn-sm my-2 text-center"
								   href="{{route('my-settings')}}">{{__('My Books')}}</a>
							</li>
							<a class="dropdown-item" href="{{route('my-settings')}}"><i
									class="bi bi-person  me-2"></i>{{__('default.Settings')}}</a>
						@endif
						<!-- Links -->
						{{--						<li class="dropdown-divider"></li>--}}
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
						@if (Auth::user())
							<li><a class="dropdown-item bg-danger-soft-hover" href="#"
							       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
										class="bi bi-power  me-2"></i>Sign Out</a></li>
						@else
							<li><a class="dropdown-item bg-primary-soft-hover" href="{{ route('login') }}"><i
										class="bi bi-unlock  me-2"></i>Sign In</a></li>
							<li><a class="dropdown-item bg-primary-soft-hover" href="{{ route('register') }}"><i
										class="bi bi-person-circle  me-2"></i>Sign Up</a></li>
						@endif
						<!-- Dark mode options START -->
						<hr class="dropdown-divider">
						<div
							class="modeswitch-item theme-icon-active d-flex justify-content-center gap-3 align-items-center p-2 pb-0">
							<span>Mode:</span>
							<button type="button" class="btn btn-modeswitch nav-link text-primary-hover mb-0"
							        data-bs-theme-value="light" data-bs-toggle="tooltip" data-bs-placement="top"
							        data-bs-title="Light">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
								     class="bi bi-sun  mode-switch" viewBox="0 0 16 16">
									<path
										d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
									<use href="#"></use>
								</svg>
							</button>
							<button type="button" class="btn btn-modeswitch nav-link text-primary-hover mb-0"
							        data-bs-theme-value="dark" data-bs-toggle="tooltip" data-bs-placement="top"
							        data-bs-title="Dark">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
								     class="bi bi-moon-stars  mode-switch" viewBox="0 0 16 16">
									<path
										d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
									<path
										d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
									<use href="#"></use>
								</svg>
							</button>
							<button type="button" class="btn btn-modeswitch nav-link text-primary-hover mb-0 active"
							        data-bs-theme-value="auto" data-bs-toggle="tooltip" data-bs-placement="top"
							        data-bs-title="Auto">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
								     class="bi bi-circle-half  mode-switch" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
									<use href="#"></use>
								</svg>
							</button>
						</div>
				</li>
				<!-- Dark mode options END-->
			</ul>
			</li>
			<!-- Profile START -->
			
			</ul>
			<!-- Nav right END -->
		</div>
	</nav>
	<!-- Logo Nav END -->
</header>
<!-- =======================
Header END -->


@yield('content')

<!-- =======================
JS libraries, plugins and custom scripts -->

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="/assets/vendor/tiny-slider/tiny-slider.js"></script>

<script src="/assets/vendor/choices/js/choices.min.js"></script>

<!-- Theme Functions -->
<script src="/assets/js/functions.js"></script>

@php($title = View::getSection('title', 'Home'))

@stack('scripts')

</body>
</html>
