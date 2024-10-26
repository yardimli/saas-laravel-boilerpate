@extends('layouts.app')

@section('title', 'About')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
			<!-- Container START -->
		<div class="container" style="min-height: calc(88vh);">
			<div class="row g-4">
				<!-- Main content START -->
				<div class="col-lg-8 mx-auto">
					<!-- Card START -->
					<div class="card">
						<div class="card-header py-3 border-0 d-flex align-items-center justify-content-between">
							<h1 class="h5 mb-0">Welcome Aboard</h1>
						</div>
						<div class="card-body p-3 mb-3">
							<div style="text-align: center; ">
								<img src="{{ asset('assets/images/logo/logo-large.png') }}"
								     style="height: 200px;" alt="Thank You" class="img-fluid">
							</div>

							<?php
								$about = file_get_contents(resource_path('texts/about2.txt'));
								$about = str_replace("\n", "<br>", $about);
								echo $about;
?>
						</div>
					</div>
					<!-- Card END -->
				</div>
			</div> <!-- Row END -->
		</div>
		<!-- Container END -->
	
	</main>
	
	
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
      var current_page = 'my.about';
      $(document).ready(function () {
      });
	</script>
	
@endpush
