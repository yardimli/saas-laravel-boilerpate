<!-- footer START -->
<footer class="bg-mode py-3">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- Footer nav START -->
				<ul class="nav justify-content-center justify-content-md-start lh-1">
					<li class="nav-item">
						<a class="nav-link" href="{{route('about-page')}}"><i class="bi bi-info-circle  me-2"></i>{{__('default.About')}}</a>
					</li>
					<li class="nav-item">
						<div class="dropup mt-0 text-center text-sm-end">
							<a class="dropdown-toggle nav-link" href="{{ route('landing-page') }}" role="button"
							   id="languageSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="bi bi-globe  me-2"></i>{{__('default.Language')}}
							</a>
							<ul class="dropdown-menu min-w-auto" aria-labelledby="languageSwitcher">
								<li><a class="dropdown-item me-4" href="{{ route('changeLang') }}?lang=en_US"><img
											class="me-2" style="width: 20px;" src="/assets/images/flags/uk.svg" alt="">{{__('default.English')}}</a></li>
								<li><a class="dropdown-item me-4" href="{{ route('changeLang') }}?lang=tr_TR"><img
											class="me-2" style="width: 20px;" src="/assets/images/flags/tr.svg" alt="">{{__('default.Turkish')}}</a></li>
							</ul>
						</div>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="{{route('help-page')}}"><i class="bi bi-exclamation-circle  me-2"></i>{{__('default.Help')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('terms-page')}}"><i class="bi bi-check-all  me-2"></i>{{__('default.Terms')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('privacy-page')}}"><i class="bi bi-list  me-2"></i>{{__('default.Privacy')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('change-log-page')}}"><i class="bi bi-list-check  me-2"></i>{{__('default.Change Log')}}</a>
					</li>
				</ul>
				<!-- Footer nav START -->
			</div>
			<div class="col-md-4">
				<!-- Copyright START -->
				<p class="text-center text-md-end mb-0">©2024 <a class="text-body" href="https://www.my-laravel-saas-site.com"> {{__('default.SAAS LARAVEL BOILERPLATE')}}</p>
				<!-- Copyright END -->
			</div>
		</div>
	</div>
</footer>
<!-- footer END -->


@include('layouts.modals')

<?php
