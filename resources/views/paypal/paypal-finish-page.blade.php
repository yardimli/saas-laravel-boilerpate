@extends('layouts.app')

@section('title', 'Shopping Complete')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main class="pt-5">
		
		<!-- Page header START -->
		<div class="py-5"
		     style="background-image:url(/assets/images/header/writing-5283739_1920_cr.jpg); background-position: center center; background-size: cover; background-repeat: no-repeat;">
			<div class="container">
				<div class="row justify-content-center py-5">
					<div class="col-md-6 text-center">
						<!-- Title -->
						<h1 class="text-white" style="background-color: rgba(0,0,0,0.5)">Purchase Results</h1>
						<span class="mb-4 text-white" style="background-color: rgba(0,0,0,0.5)">{{__('default.Boilerplate Site Tagline')}}</span>
					</div>
				</div>
			</div>
		</div>
		<!-- Page header END -->
		
		<!-- Container START -->
		<div class="py-5">
			<div class="container">
				
				<div class="tab-content mb-0 pb-0">
					<!-- For you tab START -->
					<div class="tab-pane fade show active" id="tab-1">
						
						<div class="row g-4">
							<div class="quote"
							<!-- Main content START -->
							
							<?php
								echo 'Result: ' . ($result ? 'Success' : 'Failure') . '<br>';
								echo $message . '<br>';
								echo '<br>RESPONSE FROM GATEWAY:<br>';
								echo '<textarea class="form-control" style="min-height:300px;">';
								print_r($response);
								echo '</textarea><br>';
								echo 'If you have any questions, please contact us at <a href="mailto:support@my-laravel-saas-site.com">support@my-laravel-saas-site.com</a>. Include the above information in your email. Thank you.';
							?>
								
								
								<!-- Main content END -->
						</div> <!-- Row END -->
					
					</div>
					<!-- For you tab END -->
				
				</div>
			</div>
		</div>
		<!-- Container END -->
	
	</main>
	<!-- **************** MAIN CONTENT END **************** -->
	
	
	
	
	@include('layouts.footer')
	
	
	<div class="js-container"></div>
	<style>
      @keyframes confetti-slow {
          0% {
              transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
          }
          100% {
              transform: translate3d(25px, 105vh, 0) rotateX(360deg) rotateY(180deg);
          }
      }

      @keyframes confetti-medium {
          0% {
              transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
          }
          100% {
              transform: translate3d(100px, 105vh, 0) rotateX(100deg) rotateY(360deg);
          }
      }

      @keyframes confetti-fast {
          0% {
              transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
          }
          100% {
              transform: translate3d(-50px, 105vh, 0) rotateX(10deg) rotateY(250deg);
          }
      }

      .js-container {
          height: 0px;
          left: 0px;
          position: fixed !important;
          top: 0px;
          width: 0px;
          z-index: 1111;
          pointer-events: none;

      }

      .confetti-container {
          pointer-events: none;


          perspective: 700px;
          position: absolute;
          overflow: hidden;
          top: 0;
          right: 0;
          bottom: 0;
          left: 0;
      }

      .confetti {
          position: absolute;
          z-index: 1;
          top: -10px;
          border-radius: 0%;
      }

      .confetti--animation-slow {
          animation: confetti-slow 2.25s linear 1 forwards;
      }

      .confetti--animation-medium {
          animation: confetti-medium 1.75s linear 1 forwards;
      }

      .confetti--animation-fast {
          animation: confetti-fast 1.25s linear 1 forwards;
      }
	</style>
@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		const Confettiful = function (el) {
			this.el = el;
			this.containerEl = null;
			this.confettiInterval = null;
			
			this.confettiFrequency = 3;
			this.confettiColors = ['#fce18a', '#ff726d', '#b48def', '#f4306d'];
			this.confettiAnimations = ['slow', 'medium', 'fast'];
			
			this._setupElements();
			this._renderConfetti();
		};
		
		Confettiful.prototype.destroy = function () {
			clearInterval(this.confettiInterval);
			
			while (this.containerEl.firstChild) {
				clearTimeout(this.containerEl.firstChild.removeTimeout);
				this.containerEl.removeChild(this.containerEl.firstChild);
			}
			
			this.el.style.position = '';
			this.el.removeChild(this.containerEl);
			
			window.confettiful = null;
		};
		
		Confettiful.prototype._setupElements = function () {
			const containerEl = document.createElement('div');
			const elPosition = this.el.style.position;
			
			if (elPosition !== 'relative' || elPosition !== 'absolute') {
				this.el.style.position = 'relative';
			}
			
			containerEl.classList.add('confetti-container');
			
			this.el.appendChild(containerEl);
			
			this.containerEl = containerEl;
		};
		
		Confettiful.prototype._renderConfetti = function () {
			this.confettiInterval = setInterval(() => {
				const confettiEl = document.createElement('div');
				const confettiSize = (Math.floor(Math.random() * 6) + 10) + 'px';
				const confettiBackground = this.confettiColors[Math.floor(Math.random() * this.confettiColors.length)];
				const confettiLeft = (Math.floor(Math.random() * this.el.offsetWidth)) + 'px';
				const confettiAnimation = this.confettiAnimations[Math.floor(Math.random() * this.confettiAnimations.length)];
				
				confettiEl.classList.add('confetti', 'confetti--animation-' + confettiAnimation);
				confettiEl.style.left = confettiLeft;
				confettiEl.style.width = confettiSize;
				confettiEl.style.height = confettiSize;
				confettiEl.style.backgroundColor = confettiBackground;
				
				confettiEl.removeTimeout = setTimeout(function () {
					confettiEl.parentNode.removeChild(confettiEl);
				}, 4000);
				
				this.containerEl.appendChild(confettiEl);
			}, 25);
		};
		
		
		var current_page = 'payment_end';
		$(document).ready(function () {
			@if ($result)
			$('.js-container').css('height', '100vh');
			$('.js-container').css('width', '100vw');
			window.confettiful = new Confettiful(document.querySelector('.js-container'));
			@endif
		});
	</script>
	
@endpush


