@extends('layouts.app', ['page_route' => 'blog'])

@section('title', 'Welcome')

@section('content')
	
	<!-- =======================
	Page content START -->
	<section class="position-relative pt-0 pt-lg-5" style="margin-top:100px; min-height: 800px;">
		<div class="container">
			<div class="row g-4">
				<?php
				?>
				@foreach($posts as $post)
						<?php
//            dd($post);
						?>
						
						<!-- Card item START -->
					<div class="col-sm-6 col-lg-4 col-xl-4">
						<div class="card">
							<div class="overflow-hidden rounded-3">
									<?= $post->image_tag("medium", true, ''); ?>
									
									<!-- Card body -->
								<div class="card-body">
									<!-- Title -->
									<h5 class="card-title"><a href="{{$post->url($locale, $routeWithoutLocale)}}">{{$post->title}}</a>
									</h5>
									
									<p class="text-truncate-2">{!! $post->short_description !!}</p>
									<!-- Info -->
									<div class="d-flex justify-content-between">
										<a href="{{$post->url($locale, $routeWithoutLocale)}}"
										   class="badge text-bg-danger">{{$post->category_name}}</a>
										
										{{--                <h6 class="mb-0"><a href="{{ route('landing-page', 'blog-detail') }}">Frances Guerrero</a></h6>--}}
										<span class="small">{{date('d M Y ', strtotime($post->post->posted_at))}}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Card item END -->
				@endforeach
				
				<!-- Card item START -->
			
			
			</div> <!-- Row end -->
		
		
		</div>
	</section>
	<!-- ======================= Page content END -->
	
	@include('layouts.footer')

@endsection



@push('scripts')
	<script>
		$(document).ready(function () {
		});
	</script>

@endpush


