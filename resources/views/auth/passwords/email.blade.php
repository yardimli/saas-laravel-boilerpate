<!doctype html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">




<head>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DSAThemes">
	<meta name="description" content="Discover a new beginning.">
	<meta name="keywords" content="Responsive, HTML5, DSAThemes, Landing, Software, Mobile App, SaaS, Startup, Creative, Digital Product">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- SITE TITLE -->
	<title>Discover a new beginning.</title>
	
	<!-- FAVICON AND TOUCH ICONS -->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
	
	<!-- BOOTSTRAP CSS -->
	<link href="/assets/v2/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- FONT ICONS -->
	<link href="/assets/v2/css/flaticon.css" rel="stylesheet">
	
	<!-- PLUGINS STYLESHEET -->
	<link href="/assets/v2/css/menu.css" rel="stylesheet">
	<link id="effect" href="/assets/v2/css/dropdown-effects/fade-down.css" media="all" rel="stylesheet">
	<link href="/assets/v2/css/magnific-popup.css" rel="stylesheet">
	<link href="/assets/v2/css/owl.carousel.min.css" rel="stylesheet">
	<link href="/assets/v2/css/owl.theme.default.min.css" rel="stylesheet">
	<link href="/assets/v2/css/lunar.css" rel="stylesheet">
	
	<!-- ON SCROLL ANIMATION -->
	<link href="/assets/v2/css/animate.css" rel="stylesheet">
	
	<!-- TEMPLATE CSS -->
	<link href="/assets/v2/css/blue-theme.css" rel="stylesheet">
	
	<!-- RESPONSIVE CSS -->
	<link href="/assets/v2/css/responsive.css" rel="stylesheet">

</head>




<body>








<!-- PAGE CONTENT ============================================= -->
<div id="page" class="page font--jakarta">
	
	
	
	
	<!-- RESET PASSWORD PAGE
	============================================= -->
	<section id="reset-password" class="bg--fixed reset-password-section division">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					
					
					<!-- LOGO -->
					<div style="text-align: center; margin-bottom: 20px">
						<img style="width: auto; max-height: 90px;" src="/images/logo.png" id="site_logo" alt="logo-image">
					</div>
					
					
					<!-- RESET PASSWORD FORM -->
					@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif
					<div class="reset-page-wrapper text-center">
						<form name="resetpasswordform" roll="form" class="row reset-password-form r-10" method="POST" action="{{ route('password.email') }}">
							@csrf
							<!-- Title-->
							<div class="col-md-12">
								<div class="reset-form-title">
									<h5 class="s-26 w-700">{{ __('default.Forgot password?') }}</h5>
									<p class="p-sm color--grey">Please enter your email address. If the account exists, we will send a password reset link to your email address.
									</p>
								</div>
							</div>
							
							<!-- Form Input -->
							<div class="col-md-12">
								<input class="form-control email @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="example@example.com" required autocomplete="email" autofocus>
								@error('email')
								<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
								@enderror
							</div>
							
							<!-- Form Submit Button -->
							<div class="col-md-12">
								<button type="submit" class="btn btn--theme hover--theme submit">{{ __('default.Reset Password') }}</button>
							</div>
							
							<!-- Form Data  -->
							<div class="col-md-12">
								<div class="form-data text-center">
									<span><a href="{{route('login')}}">It's okay, I found my password!</a></span>
								</div>
							</div>
							
							<!-- Form Message -->
							<div class="col-lg-12 reset-form-msg">
								<span class="loading"></span>
							</div>
						
						</form>
					</div>	<!-- END RESET PASSWORD FORM -->
				
				
				</div>
			</div>	   <!-- End row -->
		</div>	   <!-- End container -->
	</section>	<!-- END RESET PASSWORD PAGE -->




</div>	<!-- END PAGE CONTENT -->



</body>




</html>
