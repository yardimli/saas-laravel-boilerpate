@extends('layouts.app')

@section('title', 'Change Log')

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
							<h1 class="h5 mb-0">Change Log</h1>
						</div>
						<div class="card-body p-3 mb-3">
							<div style="text-align: center; ">
								<img src="{{ asset('/images/logo-big.png') }}"
								     style="max-width: 300px; width: 300px;" alt="Thank You" class="img-fluid">
							</div>
							
							<br>
							<strong>v0.1.0 - 10/22/2024</strong>
							<br>
							- - Create Boilerplate.
							
							<br><br>
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
      var current_page = 'my.change-log';
      $(document).ready(function () {
      });
	</script>
	
@endpush
