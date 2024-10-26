@extends('layouts.app')

@section('title', 'Buy Tokens')

@section('content')
{{--	<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}"></script>--}}
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		<!-- =======================
		Page Banner START -->
		<section class="py-5 price-wrap">
			<div class="container">
				<div class="row g-4 position-relative mb-4">
					
					@if(isset($top_message))
					<div class="alert alert-success alert-dismissible fade show mb-3" role="alert" id="error_box">
						{{$top_message}}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					@endif
					
					
					<!-- Title and Search -->
					<div class="col-lg-10 mx-auto text-center position-relative">
						<!-- Title -->
						<h1>Affordable Pricing Packages</h1>
						<p class="mb-4 pb-1">Start Small, Go Big!</p>
						
						<!-- Switch START -->
						{{--						<form class="d-flex align-items-center justify-content-center">--}}
						{{--							<!-- Label -->--}}
						{{--							<span class="h6 mb-0 fw-bold">Beginius</span>--}}
						{{--							<!-- Switch -->--}}
						{{--							<div class="form-check form-switch form-check-lg mx-3 mb-0">--}}
						{{--								<input class="form-check-input mt-0 price-toggle" type="checkbox" id="flexSwitchCheckDefault">--}}
						{{--							</div>--}}
						{{--							<!-- Label -->--}}
						{{--							<div class="position-relative">--}}
						{{--								<span class="h6 mb-0 fw-bold">Maximus</span>--}}
						{{--								<span--}}
						{{--									class="badge bg-danger bg-opacity-10 text-danger ms-1 position-absolute top-0 start-100 translate-middle mt-n2 ms-2 ms-md-5">2 Months Free</span>--}}
						{{--							</div>--}}
						{{--						</form>--}}
						<!-- Switch END -->
					</div>
				</div>
				<!-- Pricing START -->
				<div class="row g-4">
					
					<!-- Pricing item START -->
					<div class="col-md-6 col-xl-4">
						<div class="card border rounded-3 p-2 p-sm-4 h-100">
							<!-- Card Header -->
							<div class="card-header p-0">
								<!-- Price and Info -->
								<div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-2">
									<!-- Info -->
									<div>
										<h5 class="mb-0">Short Stories Pack</h5>
										<div class="badge text-bg-dark mb-0 rounded-pill">Starter</div>
									</div>
									<!-- Price -->
									<div>
										<h4 class="text-success mb-0 plan-price" data-monthly-price="$9.90" data-annual-price="$199">
											$19.90</h4>
									</div>
								</div>
							</div>
							
							<!-- Divider -->
							<div class="position-relative my-3 text-center">
								<hr>
								<p class="small position-absolute top-50 start-50 translate-middle bg-body px-3">Package Includes</p>
							</div>
							
							<!-- Card Body -->
							<div class="card-body pt-0">
								<ul class="list-unstyled mt-2 mb-0">
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>40,000 GPT-4 Tokens
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>400,000 GPT-3.5 Tokens
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>~140-280 Pages of GPT
										3.5 Text*
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>~14-28 Pages of GPT
										4.0 Text*
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use With GPT 3.5
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use WIth GPT 4.0
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Tokens Expire
										After 3 Months
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Delete Stories I write
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-x-octagon-fill text-danger me-2"></i>Control Initial Prompt**
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-x-octagon-fill text-danger me-2"></i>Private Stories
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-x-octagon-fill text-danger me-2"></i>Use Your Own OpenAI Key***
									</li>
								</ul>
							</div>
							<!-- Card Footer -->
							<div class="card-footer text-center d-grid pb-0">
								@if ($checkout_starter!==null)
									<a class="m-3" href="{{ route('processTransaction','short-stories') }}" alt = "Buy with PayPal"><img src="/assets/images/checkout-paypal-logo-large.png"></a>
									
									{{--									<x-lemon-button :href="$checkout_starter" class="dark btn btn-primary-soft w-100 mt-3">--}}
{{--										Buy Starter Pack - $9.90--}}
{{--									</x-lemon-button>--}}
								@else
									Login To Purchase
								@endif
								{{--								<button type="button" class="btn btn-light mb-0">Get Started</button>--}}
							</div>
						</div>
					</div>
					<!-- Pricing item END -->
					
					<!-- Pricing item START -->
					<div class="col-md-6 col-xl-4">
						<div class="card border rounded-3 p-2 p-sm-4 h-100">
							<!-- Card Header -->
							<div class="card-header p-0">
								<!-- Price and Info -->
								<div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-2">
									<!-- Info -->
									<div>
										<h5 class="mb-0">Novella Pack</h5>
										<div class="badge bg-grad mb-0 rounded-pill">Recommended</div>
									</div>
									<!-- Price -->
									<div>
										<h4 class="text-success mb-0 plan-price" data-monthly-price="$39.90" data-annual-price="$199">
											$39.90</h4>
									</div>
								</div>
							</div>
							
							<!-- Divider -->
							<div class="position-relative my-3 text-center">
								<hr>
								<p class="small position-absolute top-50 start-50 translate-middle bg-body px-3">Package Includes</p>
							</div>
							
							<!-- Card Body -->
							<div class="card-body pt-0">
								<ul class="list-unstyled mt-2 mb-0">
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>100,000 GTP-4 Tokens
										Credits
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>1,000,000 GPT-3.5 Tokens
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>~500 Pages of GPT
										3.5 Text*
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>~50 Pages of GPT
										4.0 Text*
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use With GPT 3.5
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use WIth GPT 4.0
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Tokens Expire
										After 6 Months
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Delete Stories I write
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Control Initial Prompt**
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Private Stories
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-x-octagon-fill text-danger me-2"></i>Use Your Own OpenAI Key***
									</li>
								</ul>
							</div>
							<!-- Card Footer -->
							<div class="card-footer text-center d-grid pb-0">
								@if ($checkout_novella!==null)
									<a class="m-3" href="{{ route('processTransaction','novella') }}" alt = "Buy with PayPal"><img src="/assets/images/checkout-paypal-logo-large.png"></a>
								@else
									Login To Purchase
								@endif
							</div>
						</div>
					</div>
					<!-- Pricing item END -->
					
					<!-- Pricing item START -->
					<div class="col-md-6 col-xl-4">
						<div class="card border rounded-3 p-2 p-sm-4 h-100">
							<!-- Card Header -->
							<div class="card-header p-0">
								<!-- Price and Info -->
								<div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-2">
									<!-- Info -->
									<div>
										<h5 class="mb-0">Novel Pack</h5>
										<div class="badge text-bg-dark mb-0 rounded-pill">Best value</div>
									</div>
									<!-- Price -->
									<div>
										<h4 class="text-success mb-0 plan-price" data-monthly-price="$69.90" data-annual-price="$699">
											$69.90</h4>
									</div>
								</div>
							</div>
							
							<!-- Divider -->
							<div class="position-relative my-3 text-center">
								<hr>
								<p class="small position-absolute top-50 start-50 translate-middle bg-body px-3">Package Includes</p>
							</div>
							
							<!-- Card Body -->
							<div class="card-body pt-0">
								<ul class="list-unstyled mt-2 mb-0">
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>240,000 GPT-4 Tokens
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>2,400,000 GPT-3.5 Tokens
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>~1500 Pages of
										GPT 3.5 Text*
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>~150 Pages of GPT
										4.0 Text*
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use With GPT 3.5
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use WIth GPT 4.0
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Tokens Expire
										After a Year
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Delete Stories I write
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Control Initial Prompt**
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Private Stories
									</li>
									<li class="mb-3 h6 fw-light"><i class="bi bi-patch-check-fill text-success me-2"></i>Use Your Own OpenAI Key***
									</li>
								</ul>
							</div>
							<!-- Card Footer -->
							<div class="card-footer text-center d-grid pb-0">
								@if ($checkout_novel!==null)
									<a class="m-3" href="{{ route('processTransaction','novel') }}" alt = "Buy with PayPal"><img src="/assets/images/checkout-paypal-logo-large.png"></a>
								@else
									Login To Purchase
								@endif
							</div>
						</div>
					</div>
					<div class="col-12" style="font-size: 12px;">
						*A single page of a fiction book typically contains around 300 words. The cost for each word ranges from 5 to 10 tokens, depending on the context's length. Please note that this is an estimate and the actual count may vary.
						<br>
						**Coming Soon: The Control Initial Prompt feature enables you to manage the starting point of a story, as well as guide the AI's suggestions and rewrites. This is helpful for steering the AI's direction when crafting a narrative.
						<br>
						***Coming Soon: When you use your own OpenAI key, you will not be using any tokens supplied by SAAS LARAVEL BOILERPLATE. Instead, you will utilize your own tokens and make payments directly to OpenAI for their usage. Please note that this feature requires annual renewal.
					</div>
					<!-- Pricing item END -->
				</div>  <!-- Row END -->
				<!-- Pricing END -->
			</div>
		</section>
		<!-- =======================
		Page Banner END -->
		
		<!-- =======================
		FAQ START -->
		<?php
			function parseFAQ($text)
			{
				$lines = preg_split('/\r\n|\r|\n/', $text);
				
				$categories      = [];
				$currentCategory = '';
				
				foreach ($lines as $line) {
					if (strpos($line, '===') === 0) {
						$currentCategory              = trim(substr($line, 3));
						$categories[$currentCategory] = [];
					} elseif (preg_match('/^Q[0-9]+:/', $line)) {
						$question = trim(preg_replace('/^Q[0-9]+:/', '', $line));
					} elseif (preg_match('/^A[0-9]+:/', $line)) {
						$answer                         = trim(preg_replace('/^A[0-9]+:/', '', $line));
						$categories[$currentCategory][] = ['question' => $question, 'answer' => $answer];
					}
				}
				
				return $categories;
			}
			
			//read the faq.txt file from public/texts folder
			$help_array = parseFAQ(file_get_contents(resource_path('texts/shop_faq.txt')));
			
			//find $topic in $help_array
		
		
		?>
		<section class="pt-0">
			<div class="container mt-4">
				<!-- Title -->
				<div class="row mb-5">
					<div class="col-md-8 text-center mx-auto">
						<h2>Frequently asked questions</h2>
						<p class="mb-0">Please visit the help pages for more answers</p>
					</div>
				</div>
				
				<div class="row g-4 g-md-5">
					<!-- FAQ item -->
					@foreach($help_array['Shop FAQ'] as $faq)
					<div class="col-md-6">
						<h5>{{$faq['question']}}</h5>
						<p>{{$faq['answer']}}</p>
					</div>
					@endforeach
					

				</div>
			</div>
		</section>
		<!-- =======================
		FAQ END -->
	
	</main>
	<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		var current_page = 'pricing';
		$(document).ready(function () {
		});
	</script>
	
@endpush
