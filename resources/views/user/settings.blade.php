@extends('layouts.app')

@section('title', 'Settings')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		<!-- Container START -->
		<div class="container">
			<div class="row">
				
				<!-- Sidenav START -->
				<div class="col-lg-3">
					
					<!-- Advanced filter responsive toggler START -->
					<!-- Divider -->
					<div class="d-flex align-items-center mb-4 d-lg-none">
						<button class="border-0 bg-transparent" type="button" data-bs-toggle="offcanvas"
						        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
							<span class="btn btn-primary"><i class="fa-solid fa-sliders-h"></i></span>
							<span class="h6 mb-0 fw-bold d-lg-none ms-2">{{__('default.Settings')}}</span>
						</button>
					</div>
					<!-- Advanced filter responsive toggler END -->
					
					<nav class="navbar navbar-light navbar-expand-lg mx-0">
						<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
							<!-- Offcanvas header -->
							<div class="offcanvas-header">
								<button type="button" class="btn-close text-reset ms-auto" data-bs-dismiss="offcanvas"
								        aria-label="{{__('default.Close')}}"></button>
							</div>
							
							<!-- Offcanvas body -->
							<div class="offcanvas-body p-0">
								<!-- Card START -->
								<div class="card w-100">
									<!-- Card body START -->
									<div class="card-body">
										
										<!-- Side Nav START -->
										<ul class="nav nav-tabs nav-pills nav-pills-soft flex-column fw-bold gap-2 border-0">
											<li class="nav-item" data-bs-dismiss="offcanvas">
												<a class="nav-link d-flex mb-0 active" href="#nav-setting-tab-1"
												   data-bs-toggle="tab"> <img
														class="me-2 h-20px "
														src="/assets/images/icon/person-outline-filled.svg"
														alt=""><span>{{__('default.Account')}}</span></a>
											</li>
{{--											<li class="nav-item" data-bs-dismiss="offcanvas">--}}
{{--												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-2"--}}
{{--												   data-bs-toggle="tab"> <img--}}
{{--														class="me-2 h-20px "--}}
{{--														src="/assets/images/icon/creditcard-outline-filled.svg"--}}
{{--														alt=""><span>Purchase History </span></a>--}}
{{--											</li>--}}
{{--											<li class="nav-item" data-bs-dismiss="offcanvas">--}}
{{--												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-3"--}}
{{--												   data-bs-toggle="tab"> <img--}}
{{--														class="me-2 h-20px "--}}
{{--														src="/assets/images/icon/chat-outline-filled.svg"--}}
{{--														alt=""><span>Token Usage </span></a>--}}
{{--											</li>--}}
											<li class="nav-item" data-bs-dismiss="offcanvas">
												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-6"
												   data-bs-toggle="tab"> <img
														class="me-2 h-20px "
														src="/assets/images/icon/trash-var-outline-filled.svg"
														alt=""><span>{{__('default.Close Account')}}</span></a>
											</li>
										</ul>
										<!-- Side Nav END -->
									
									</div>
									<!-- Card body END -->
									<!-- Card footer -->
									<div class="card-footer text-center py-2 pb-3">
										<a class="btn btn-secondary-soft btn-sm w-100 mb-2" href="{{route('my-settings')}}">{{__('default.My Books')}} </a>
{{--										<a class="btn btn-info-soft btn-sm w-100" href="{{route('buy-packages')}}">Purchase Tokens</a>--}}
									</div>
								
								</div>
								<!-- Card END -->
							</div>
							<!-- Offcanvas body -->
						</div>
					</nav>
				</div>
				<!-- Sidenav END -->
				
				<!-- Main content START -->
				<div class="col-lg-6 vstack gap-4">
					<!-- Setting Tab content START -->
					<div class="tab-content py-0 mb-0">
						
						<!-- Account setting tab START -->
						<div class="tab-pane show active fade" id="nav-setting-tab-1">
							<!-- Account settings START -->
							<div class="card mb-4">
								<!-- Show success message if available -->
								@if (session('status'))
									<div class="alert alert-success" role="alert">
										{{ session('status') }}
									</div>
								@endif
								
								<!-- Title START -->
								<div class="card-header border-0 pb-0">
									<h1 class="h5 card-title">{{__('default.Account Settings')}}</h1>
								</div>
								<!-- Card header START -->
								<!-- Card body START -->
								<div class="card-body">
									<!-- Form settings START -->
									
									<!-- Display success or error messages -->
									@if (session('success'))
										<div class="alert alert-success mt-2">
											{{ session('success') }}
										</div>
									@endif
									
									<form action="{{ route('settings-update') }}" method="post" class="row g-3"
									      enctype="multipart/form-data">
										@csrf
										<!-- First name -->
										<div class="col-sm-6 col-lg-6">
											<label class="form-label">{{__('default.Name')}}</label>
											<input type="text" name="name" class="form-control" placeholder=""
											       value="{{ old('name', $user->name) }}">
										</div>
										<!-- User name -->
										<div class="col-sm-6">
											<label class="form-label">{{__('default.User name')}}</label>
											<input type="text" name="username" class="form-control" placeholder=""
											       value="{{ old('username', $user->username) }}">
										</div>
										
										<!-- Email address -->
										<div class="col-sm-6">
											<label class="form-label">{{__('default.Email')}}</label>
											<input type="email" name="email" class="form-control" placeholder=""
											       value="{{ old('email', $user->email) }}">
										</div>
										
										<!-- Avatar upload -->
										<div class="col-sm-6">
											<label class="form-label">{{__('default.Avatar')}}</label>
											<input type="file" name="avatar" class="form-control" accept="image/*">
										</div>
										
										<!-- Button -->
										<div class="col-12 text-start">
											<button type="submit" class="btn btn-sm btn-primary mb-0">{{__('default.Save changes')}}
											</button>
										</div>
										
										<!-- Display success or error messages -->
										@if (session('success'))
											<div class="alert alert-success mt-2">
												{{ session('success') }}
											</div>
										@endif
										
										@if ($errors->any())
											<div class="alert alert-danger mt-2">
												<ul>
													@foreach ($errors->all() as $error)
														<li>{{ $error }}</li>
													@endforeach
												</ul>
											</div>
										@endif
									</form>
									<!-- Settings END -->
								</div>
								<!-- Card body END -->
								
								<!-- API Keys START -->
								<div class="card mb-4">
									<div class="card-header border-0 pb-0">
										<h1 class="h5 card-title">{{__('default.API Keys')}}</h1>
										<p class="mb-0">{{__('default.Set your personal API keys for unmetered usage.')}}</p>
									</div>
									<div class="card-body">
										<form action="{{ route('settings-update-api-keys') }}" method="post" class="row g-3">
											@csrf
											<div class="col-12">
												<label class="form-label">{{__('default.OpenAI API Key')}}</label>
												<input type="text" name="openai_api_key" class="form-control" value="{{ old('openai_api_key', $user->openai_api_key) }}">
											</div>
											<div class="col-12">
												<label class="form-label">{{__('default.Anthropic API Key')}}</label>
												<input type="text" name="anthropic_key" class="form-control" value="{{ old('anthropic_key', $user->anthropic_key) }}">
											</div>
											<div class="col-12">
												<label class="form-label">{{__('default.OpenRouter API Key')}}</label>
												<input type="text" name="openrouter_key" class="form-control" value="{{ old('openrouter_key', $user->openrouter_key) }}">
											</div>
											<div class="col-12 text-end">
												<button type="submit" class="btn btn-primary mb-0">{{__('default.Update API Keys')}}</button>
											</div>
										</form>
									</div>
								</div>
								<!-- API Keys END -->
								
								<!-- Account settings END -->
								
								<!-- Change your password START -->
								
								<div class="card">
									<!-- Title START -->
									<div class="card-header border-0 pb-0">
										<h5 class="card-title">{{__('default.Change your password')}}</h5>
										<p class="mb-0">{{__('default.If you signed up with Google, leave the current password blank the first time you update your password.')}}</p>
									</div>
									<!-- Title START -->
									<div class="card-body">
										
										<form action="{{ route('settings-password-update') }}" method="post"
										      class="row g-3">
											@csrf
											<!-- Current password -->
											<div class="col-12">
												<label class="form-label">{{__('default.Current password')}}</label>
												<input type="password" name="current_password" class="form-control"
												       placeholder="">
											</div>
											<!-- New password -->
											<div class="col-12">
												<label class="form-label">{{__('default.New password')}}</label>
												<!-- Input group -->
												<div class="input-group">
													<input class="form-control fakepassword psw-input" type="password"
													       name="new_password" id="psw-input"
													       placeholder="Enter new password">
													<span class="input-group-text p-0">
                          <i class="fakepasswordicon fa-solid fa-eye-slash cursor-pointer p-2 w-40px"></i>
                        </span>
												</div>
												<!-- Pswmeter -->
												<div id="pswmeter" class="mt-2"></div>
												<div id="pswmeter-message" class="rounded mt-1"></div>
											</div>
											
											<!-- Confirm new password -->
											<div class="col-12">
												<label class="form-label">{{__('default.Confirm password')}}</label>
												<input type="password" name="new_password_confirmation"
												       class="form-control" placeholder="">
											</div>
											<!-- Button -->
											<div class="col-12 text-end">
												<button type="submit" class="btn btn-primary mb-0">{{__('default.Update password')}}
												</button>
											</div>
											
											<!-- Display success or error messages -->
											@if (session('success'))
												<div class="alert alert-success mt-2">
													{{ session('success') }}
												</div>
											@endif
											
											@if ($errors->any())
												<div class="alert alert-danger mt-2">
													<ul>
														@foreach ($errors->all() as $error)
															<li>{{ $error }}</li>
														@endforeach
													</ul>
												</div>
											@endif
										</form>
										
										<!-- Settings END -->
									</div>
								</div>
								<!-- Card END -->
							</div>
						</div>
						<!-- Account setting tab END -->
						
						<!-- Purchase History tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-2">
							<!-- Card START -->
							<div class="card">
								<!-- Card header START -->
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">Purchase History</h5>
									<p class="mb-2">We are sorry to hear that you wish to delete your account.</p>
								</div>
								<!-- Card header START -->
								<!-- Card body START -->
								<div class="card-body p-2">
									<ul class="list-unstyled">
									</ul>
								</div>
								<!-- Card body END -->
							</div>
							<!-- Card END -->
						</div>
						<!-- Purchase History account tab END -->
						
						<!-- Token Usage tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-3">
							<!-- Card START -->
							<div class="card">
								<!-- Card header START -->
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">Token Usage</h5>
									<p class="mb-2">Here is your token usage and credits breakdown.</p>
								</div>
								<!-- Card header START -->
								<!-- Card body START -->
								<div class="card-body">
								</div>
								<!-- Card body END -->
							</div>
							<!-- Card END -->
						</div>
						<!-- Token Usage tab END -->
						
						<!-- Close account tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-6">
							<!-- Card START -->
							<div class="card">
								<!-- Card header START -->
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">{{__('default.Delete account')}}</h5>
									<p class="mb-2">{{__('default.We are sorry to hear that you wish to delete your account.')}}</p>
									<p class="mb-2">{{__('default.Please note that deleting your account may result in the permanent loss of your data.')}}</p>
									<p class="mb-2">{{__('default.We are sad to see you go, but we hope that SAAS LARAVEL BOILERPLATE has been an enjoyable experience for you. We wish you the best in your future endeavors. Goodbye!')}}</p>
								</div>
								<!-- Card header START -->
								<!-- Card body START -->
								<div class="card-body">
									<!-- Delete START -->
									<h6>{{__('default.Before you go...')}}</h6>
									<ul>
										<li>{{__('default.If you delete your account, you will lose your all data.')}}</li>
									</ul>
									<div class="form-check form-check-md my-4">
										<input class="form-check-input" type="checkbox" value=""
										       id="deleteaccountCheck">
										<label class="form-check-label" for="deleteaccountCheck">{{__('default.Yes, I\'d like to delete my account')}}</label>
									</div>
									<a href="#" class="btn btn-success-soft btn-sm mb-2 mb-sm-0">{{__('default.Keep my account')}}</a>
									<a href="#" class="btn btn-danger btn-sm mb-0">{{__('default.Delete my account')}}</a>
									<!-- Delete END -->
								</div>
								<!-- Card body END -->
							</div>
							<!-- Card END -->
						</div>
						<!-- Close account tab END -->
					
					</div>
					<!-- Setting Tab content END -->
				</div>
			
			</div> <!-- Row END -->
		</div>
		<!-- Container END -->
	
	</main>
	<!-- **************** MAIN CONTENT END **************** -->
	<!-- Vendors -->
	<script src="/assets/vendor/pswmeter/pswmeter.js"></script>
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
      var current_page = 'settings';
      $(document).ready(function () {
      });
	</script>
	
@endpush
