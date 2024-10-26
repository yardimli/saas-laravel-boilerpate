<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{__('default.SAAS LARAVEL BOILERPLATE')}} - {{__('default.Boilerplate Site Tagline')}}</title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="my-laravel-saas-site.com">
	<meta name="description" content="{{__('default.SAAS LARAVEL BOILERPLATE')}} - {{__('default.Boilerplate Site Tagline')}}">

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
			if(el != 'undefined' && el != null) {
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

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">

</head>

<body>

<main>

<!-- **************** MAIN CONTENT START **************** -->

<!-- Main content START -->
<div class="bg-primary pt-5 pb-0 position-relative">
  @include('layouts.svg-image')
  
  <!-- Container START -->
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-12">
        <!-- Title -->
        <h1 class="display-4 text-white mb-4 position-relative">Welcome back!</h1>
        @include('layouts.svg2-image')
      </div>
      <!-- Main content START -->
      <div class="col-sm-10 col-md-8 col-lg-6 position-relative z-index-1">
        <!-- Sign in form START -->
        <div class="card card-body p-4 p-sm-5 mt-sm-n5 mb-n5">
          <!-- Title -->
          <h2 class="h1 mb-2 mt-4">Forgot password?</h2>
           <p>Enter the email address associated with account.</p>
           <!-- form START -->
           <form class="mt-3">
             <!-- New password -->
             <div class="mb-3 position-relative">
              <!-- Input group -->
              <div class="input-group input-group-lg">
                <input class="form-control fakepassword psw-input" type="password" id="psw-input" placeholder="Enter new password">
                <span class="input-group-text p-0">
                  <i class="fakepasswordicon fa-solid fa-eye-slash cursor-pointer p-2 w-40px"></i>
                </span>
              </div>
              <!-- Pswmeter -->
              <div id="pswmeter" class="mt-2"></div>
              <div class="d-flex mt-1">
                <div id="pswmeter-message" class="rounded"></div>
                <!-- Password message notification -->
                <div class="ms-auto">
                  <i class="bi bi-info-circle ps-1" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Include at least one uppercase, one lowercase, one special character, one number and 8 characters long." data-bs-original-title="" title=""></i>
                </div>
              </div>
             </div>
             <!-- Back to sign in -->
             <div class="mb-3">
               <p>Back to <a href="{{route('login')}}">Sign in</a></p>
             </div>
             <!-- Button -->
             <div class="d-grid"><button type="submit" class="btn btn-lg btn-primary">Reset password</button></div>
             <!-- Copyright -->
             <p class="mb-0 mt-3">©2024 <a target="_blank" href="https://www.my-laravel-saas-site.com/">{{__('default.SAAS LARAVEL BOILERPLATE')}}.</a> All rights reserved</p>
           </form>
          <!-- Form END -->
        </div>
        <!-- Sign in form START -->
      </div>

    </div> <!-- Row END -->
  </div>
  <!-- Container END -->
</div>
 <!-- Main content START -->

</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
Footer START -->
<footer class="pt-5 pb-2 pb-sm-4 position-relative bg-mode">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-8 col-lg-6">
        <div class="d-grid d-sm-flex justify-content-center justify-content-sm-between align-items-center mt-3">
          <!-- Nav -->
          <ul class="nav">
            <li class="nav-item"><a class="nav-link fw-bold ps-0 pe-2" href="#">Terms</a></li>
            <li class="nav-item"><a class="nav-link fw-bold px-2" href="#">Privacy</a></li>
            <li class="nav-item"><a class="nav-link fw-bold px-2" href="#">Cookies</a></li>
          </ul>
          <!-- Social icon -->
          <ul class="nav justify-content-center justify-content-sm-end">
            <li class="nav-item">
              <a class="nav-link px-2 fs-5" href="#"><i class="fa-brands fa-facebook-square"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-2 fs-5" href="#"><i class="fa-brands fa-twitter-square"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-2 fs-5" href="#"><i class="fa-brands fa-linkedin"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-2 fs-5" href="#"><i class="fa-brands fa-youtube-square"></i></a>
            </li>
           </ul>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- =======================
Footer END -->

<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
JS libraries, plugins and custom scripts -->

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="/assets/vendor/pswmeter/pswmeter.js"></script>

<!-- Theme Functions -->
<script src="/assets/js/functions.js"></script>

</body>
</html>
