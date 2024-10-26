@extends('layouts.app')

@section('title', 'Verify Thank You')

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
							<h1 class="h5 mb-0">Thank You!</h1>
						</div>
						<div class="card-body p-3">
							Thank you for verifying your email address. You man now start creating your stories.
							<br>
							<br>
							You can start writing your story by clicking the "Compose" link above.
							<br>
							<br>
							To view your books, click the "My Books" link above.
							<br>
							<br>
							<div style="text-align: center; ">
								<img src="{{ asset('assets/images/logo/logo-large.png') }}"
								     style="height: 200px;" alt="Thank You" class="img-fluid">
							</div>
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
      var current_page = 'verify_thank_you';
      $(document).ready(function () {
      });
	</script>
	
@endpush
