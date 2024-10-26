@extends('layouts.app')

@section('title', 'Help')

@section('content')

	<!-- **************** MAIN CONTENT START **************** -->
<main>

    <!-- Container START -->
    <div class="container">
      <!-- Main content START -->

        <!-- Help search START -->
        <div class="row align-items-center pt-5 pb-5 pb-lg-3">
          <div class="col-md-3">
          @include('layouts.svg3-image')
          </div>
          <!-- Card START -->
          <div class="col-md-6 text-center">
            <!-- Title -->
            <h1>Hi Cer, we're here to help.</h1>
            <p class="mb-4">Start here to get answers to your questions.</p>
          </div>
          <div class="col-md-3">
          @include('layouts.svg4-image')
          </div>
        </div>
        <!-- Help search START -->
      
      <?php
        function parseFAQ($text) {
          $lines = preg_split('/\r\n|\r|\n/', $text);
          
          $categories = [];
          $currentCategory = '';
          
          foreach ($lines as $line) {
            if (strpos($line, '===') === 0) {
              $currentCategory = trim(substr($line, 3));
              $categories[$currentCategory] = [];
            } elseif (preg_match('/^Q[0-9]+:/', $line)) {
              $question = trim(preg_replace('/^Q[0-9]+:/', '', $line));
            } elseif (preg_match('/^A[0-9]+:/', $line)) {
              $answer = trim(preg_replace('/^A[0-9]+:/', '', $line));
              $categories[$currentCategory][] = ['question' => $question, 'answer' => $answer];
            }
          }
          
          return $categories;
        }
				
				//read the faq.txt file from public/texts folder
				$help_array = parseFAQ(file_get_contents(resource_path('texts/faq.txt')));
				
        ?>

        <!-- Recommended topics START -->
        <div class="py-5">
          <!-- Titles -->
          <h4 class="text-center mb-4">Topics</h4>
          <!-- Row START -->
          <div class="row g-4">

            @foreach($help_array as $category => $questions)
            <div class="col-md-4">
              <!-- Get started START -->
              <div class="card h-100">
                <!-- Title START -->
                <div class="card-header pb-0 border-0">
                  <h5 class="card-title mb-0 mt-2"><a class="nav-link d-flex" href="/help/{{$category}}">{{$category}}</a></h5>
                </div>
                <!-- Title END -->
                <!-- List START -->
                <div class="card-body">
                  <ul class="nav flex-column">
                    @php $i = 0; @endphp
                    @foreach($questions as $question)
                    @php $i++; @endphp
                    @if($i > 5) @break @endif
                    <li class="nav-item"><a class="nav-link d-flex" href="/help/{{$category}}"><i class="fa-solid fa-angle-right text-primary pt-1  me-2"></i>{{$question['question']}}</a></li>
                    @endforeach
                  </ul>
                </div>
                <!-- List END -->
              </div>
              <!-- Get started END -->
            </div>
            @endforeach
          </div>
          <!-- Row END -->
        </div>
		</div>
  <!-- Container END -->

</main>

<!-- **************** MAIN CONTENT END **************** -->

	@include('layouts.footer')


@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		var current_page = 'help.home';
		$(document).ready(function () {
		});
	</script>
	
@endpush
