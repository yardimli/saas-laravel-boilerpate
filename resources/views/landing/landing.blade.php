<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{__('default.SAAS LARAVEL BOILERPLATE')}} - {{__('default.Boilerplate Site Tagline')}}</title>
	
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description"
	      content="{{__('default.SAAS LARAVEL BOILERPLATE')}} - {{__('default.Boilerplate Site Tagline')}}">
	
	<!-- Dark mode -->
	<script>
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
		})
	
	</script>
	
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
	<link rel="stylesheet" type="text/css" href="/assets/vendor/plyr/plyr.css">
	
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">

</head>
<body>

<!-- =======================
Header START -->

<header class="navbar-light header-static bg-transparent">
	<!-- Navbar START -->
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand" href="{{ route('login') }}">
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
				<ul class="navbar-nav navbar-nav-scroll me-auto">
					<!-- Nav item -->
					<li class="nav-item">
						<a class="nav-link" href="{{route('login')}}">{{__('default.Login')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('register')}}">{{__('default.Register')}}</a>
					</li>
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
			<div class="ms-3 ms-lg-auto">
				{{--          <a class="btn btn-dark" href="app-download.html"> Download app </a>--}}
			</div>
			<!-- Nav right END -->
		</div>
	</nav>
	<!-- Navbar END -->
</header>

<!-- =======================
Header END -->

<main>
	
	<!-- **************** MAIN CONTENT START **************** -->
	
	<!-- Main banner START -->
	<section class="pt-3 pb-0 position-relative">
		
		<!-- Container START -->
		<div class="container">
			<!-- Row START -->
			<div class="row text-center position-relative z-index-1">
				<div class="col-lg-7 col-12 mx-auto">
					<!-- Heading -->
					<h1 class="display-4">{{__('default.SAAS LARAVEL BOILERPLATE')}}</h1>
					<p class="lead">"{{__('default.Boilerplate Site Tagline')}}"</p>
					<div class="d-sm-flex justify-content-center">
						<!-- button -->
						<a href="{{route('register')}}" class="btn btn-primary">{{__('default.Sign up')}}</a>
					</div>
					<br>
				</div>
			</div>
			<!-- Row END -->
		</div>
		<!-- Container END -->
		
		<!-- Svg decoration START -->
		<div class="position-absolute top-0 end-0 mt-5 pt-5">
			<img class="h-300px blur-9 mt-5 pt-5" src="/assets/images/elements/07.svg" alt="">
		</div>
		<div class="position-absolute top-0 start-0 mt-n5 pt-n5">
			<img class="h-300px blur-9" src="/assets/images/elements/01.svg" alt="">
		</div>
		<div class="position-absolute top-50 start-50 translate-middle">
			<img class="h-300px blur-9" src="/assets/images/elements/04.svg" alt="">
		</div>
		<!-- Svg decoration END -->
	
	</section>
	<!-- Main banner END -->
	
	<!-- Messaging feature START -->
	<section>
		<div class="container">
			<div class="row justify-content-center">
				<!-- Title -->
				<div class="col-lg-7 col-12 mx-auto  text-center mb-4">
					<h2 class="h1">{{__('default.Welcome to SaaS Laravel Boilerplate')}}</h2>
					<p>{{__('default.Within a few steps create your SaaS project skipping over all the boring parts.')}}</p>
				</div>
			</div>
			<!-- Row START -->
			<div class="row justify-content-center" style="min-height: 500px;">
				<!-- Feature START -->
				<div class="col-lg-9 col-12 mx-auto  text-center mb-4">
					<div class="card card-body bg-mode shadow-none border-1">
						<!-- Info -->
						<h4 class="mt-0 mb-3">{{__('default.Start Your Site Here')}}</h4>
						<p class="mb-3">{{__('default.Now it\'s up to you to code your site using your imagination, creativity and hard work.')}}
						<br>
							<img src="/images/logo-big.png" style="max-width: 350px; width: 350px; height: 350px;" alt="Thank You" class="img-fluid mt-5 mb-5">
						
						</p>
					</div>
				</div>
				<!-- Feature END -->
			</div>
			<!-- Row START -->
			
			
	</section>
	<!-- Messaging feature END -->
	
	
	
	<!-- Main content END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->

@include('layouts.footer')

<!-- =======================
JS libraries, plugins and custom scripts -->

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Functions -->
<script src="/assets/js/functions.js"></script>

</body>
</html>
