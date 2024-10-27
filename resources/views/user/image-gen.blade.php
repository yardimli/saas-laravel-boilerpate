@extends('layouts.app')

@section('title', 'Chat')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		<!-- Container START -->
		<div class="container" style="min-height: calc(88vh);">
			<div class="row mt-3">
				<!-- Main content START -->
				<div class="col-12 col-xl-8 col-lg-8 mx-auto">
					
					<h5>{{__('default.Image Generation')}}</h5>
					
					<!-- Generated Image Display Area -->
					<div id="generatedImageArea" class="mb-4 d-none">
						<div class="card">
							<img id="generatedImage" src="" class="card-img-top" alt="Generated Image">
							<div class="card-body">
								<h6 class="card-title">Generation Details</h6>
								<p class="card-text" id="image_prompt"></p>
								<p class="card-text"><small class="text-muted" id="tokensDisplay"></small></p>
							</div>
						</div>
					</div>
					
					
					<div class="mb-3">
						{{__('default.Prompt Enhancer')}}:
						<textarea class="form-control" id="promptEnhancer" rows="6">
##UserPrompt##
Write a prompt to create an image using the above text.:
Write in English even if the above text is written in another language.
With the above information, compose a image. Write it as a single paragraph. The instructions should focus on the text elements of the image. If the prompt above mentions texts then add them with the instructions of placement. The texts should not repeat. If no texts are mentioned don't add anything to the prompt.</textarea>
					</div>
					
					
					<div class="mb-3">
						{{__('default.User Prompt')}}:
						<textarea class="form-control" id="userPrompt" rows="3"></textarea>
					</div>
					<button type="button" class="btn btn-primary" id="sendPromptBtn">{{ __('default.Send Prompt') }}</button>
					
					
					<div class="mt-5 mb-2">
						
						<span for="llmSelect" class="form-label">{{__('default.AI Engines:')}}
							@if (Auth::user() && Auth::user()->isAdmin())
								<label class="badge bg-danger">Admin</label>
							@endif
						
						</span>
						<select id="llmSelect" class="form-select mx-auto">
							<option value="">{{__('default.Select an AI Engine')}}</option>
							@if (Auth::user() && Auth::user()->isAdmin())
								<option value="anthropic-sonet">anthropic :: claude-3.5-sonnet (direct)</option>
								<option value="anthropic-haiku">anthropic :: haiku (direct)</option>
								<option value="open-ai-gpt-4o">openai :: gpt-4o (direct)</option>
								<option value="open-ai-gpt-4o-mini">openai :: gpt-4o-mini (direct)</option>
							@endif
							@if (Auth::user() && !empty(Auth::user()->anthropic_key))
								<option value="anthropic-sonet">anthropic :: claude-3.5-sonnet (direct)</option>
								<option value="anthropic-haiku">anthropic :: haiku (direct)</option>
							@endif
							@if (Auth::user() && !empty(Auth::user()->openai_api_key))
								<option value="open-ai-gpt-4o">openai :: gpt-4o (direct)</option>
								<option value="open-ai-gpt-4o-mini">openai :: gpt-4o-mini (direct)</option>
							@endif
						</select>
					</div>
					
					<div class="mb-5" id="modelInfo">
						<div class="mt-1 small" style="border: 1px solid #ccc; border-radius: 5px; padding: 5px;">
							<div id="modelDescription"></div>
							<div id="modelPricing"></div>
						</div>
					</div>
				
				</div> <!-- Row END -->
				<div class="col-12 col-xl-4 col-lg-4 mx-auto">
					
					<h5>{{__('default.Image History')}}</h5>
					
					<div id="imageGens" class="list-group">
					</div>
				
				</div>
			</div>
			<!-- Container END -->
	</main>
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		let savedLlm = localStorage.getItem('image-gen-llm') || 'anthropic/claude-3-haiku:beta';
		let sessionId = null;
		
		function getLLMsData() {
			return new Promise((resolve, reject) => {
				$.ajax({
					url: '/check-llms-json',
					type: 'GET',
					success: function (data) {
						resolve(data);
					},
					error: function (xhr, status, error) {
						reject(error);
					}
				});
			});
		}
		
		function linkify(text) {
			const urlRegex = /(https?:\/\/[^\s]+)/g;
			return text.replace(urlRegex, function (url) {
				return '<a href="' + url + '" target="_blank" rel="noopener noreferrer">' + url + '</a>';
			});
		}
		
		function loadImageHistory() {
			$.ajax({
				url: '/image-gen/sessions',
				type: 'GET',
				success: function (response) {
					const sessionsDiv = $('#imageGens');
					sessionsDiv.empty();
					response.forEach(session => {
						sessionsDiv.append(`
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/image-gen/${session.session_id}" class="text-decoration-none flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">${session.user_prompt.substring(0, 30)}...</h6>
                                    <small>${new Date(session.created_at).toLocaleDateString()}</small>
                                </div>
                                <small>Model: ${session.llm}</small>
                            </a>
                            <button class="btn btn-sm btn-danger ms-2 delete-image"
                                    data-session-id="${session.session_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `);
					});
					
					// Add delete button click handler
					$('.delete-image').on('click', function(e) {
						e.preventDefault();
						const sessionId = $(this).data('session-id');
						if (confirm('Are you sure you want to delete this image?')) {
							deleteImage(sessionId);
						}
					});
				}
			});
		}
		
		function deleteImage(sessionId) {
			$.ajax({
				url: `/image-gen/${sessionId}`,
				type: 'DELETE',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					if (response.success) {
						// Reload the image history
						loadImageHistory();
						// If we're viewing the deleted image, redirect to the main page
						if (window.location.pathname.includes(sessionId)) {
							window.location.href = '/image-gen';
						}
					} else {
						alert('Error deleting image');
					}
				},
				error: function() {
					alert('Error deleting image');
				}
			});
		}
		
		$(document).ready(function () {
			loadImageHistory();
			
			@if(isset($current_session))
			const currentSession = @json($current_session);
			if (currentSession) {
				$('#generatedImageArea').removeClass('d-none');
				$('#generatedImage').attr('src', '/storage/' + currentSession.image_path);
				$('#image_prompt').text(currentSession.image_prompt);
				$('#tokensDisplay').text(`Tokens Used: ${currentSession.prompt_tokens}/${currentSession.completion_tokens}`);
				$('#userPrompt').val(currentSession.user_prompt);
				$('#promptEnhancer').val(currentSession.llm_prompt);
				
				// Set the LLM select
				if (currentSession.llm) {
					$('#llmSelect').val(currentSession.llm).trigger('change');
				}
			}
			@endif
			
			getLLMsData().then(function (llmsData) {
				const llmSelect = $('#llmSelect');
				
				llmsData.forEach(function (model) {
					
					// Calculate and display pricing per million tokens
					let promptPricePerMillion = ((model.pricing.prompt || 0) * 1000000).toFixed(2);
					let completionPricePerMillion = ((model.pricing.completion || 0) * 1000000).toFixed(2);
					
					llmSelect.append($('<option>', {
						value: model.id,
						text: model.name + ' - $' + promptPricePerMillion + ' / $' + completionPricePerMillion,
						'data-description': model.description,
						'data-prompt-price': model.pricing.prompt || 0,
						'data-completion-price': model.pricing.completion || 0,
					}));
				});
				
				// Set the saved LLM if it exists
				if (savedLlm) {
					llmSelect.val(savedLlm);
				}
				
				llmSelect.on('click', function () {
					$('#modelInfo').removeClass('d-none');
				});
				
				// Show description on change
				llmSelect.change(function () {
					const selectedOption = $(this).find('option:selected');
					const description = selectedOption.data('description');
					const promptPrice = selectedOption.data('prompt-price');
					const completionPrice = selectedOption.data('completion-price');
					$('#modelDescription').html(linkify(description || ''));
					
					// Calculate and display pricing per million tokens
					const promptPricePerMillion = (promptPrice * 1000000).toFixed(2);
					const completionPricePerMillion = (completionPrice * 1000000).toFixed(2);
					
					$('#modelPricing').html(`
                <strong>Pricing (per million tokens):</strong> Prompt: $${promptPricePerMillion} - Completion: $${completionPricePerMillion}
            `);
				});
				
				// Trigger change to show initial description
				llmSelect.trigger('change');
			}).catch(function (error) {
				console.error('Error loading LLMs data:', error);
			});
			
			$("#llmSelect").on('change', function () {
				localStorage.setItem('image-gen-llm', $(this).val());
				savedLlm = $(this).val();
			});
			
			// change $llmSelect to savedLlm
			console.log('set llmSelect to ' + savedLlm);
			var dropdown = document.getElementById('llmSelect');
			var options = dropdown.getElementsByTagName('option');
			
			for (var i = 0; i < options.length; i++) {
				if (options[i].value === savedLlm) {
					dropdown.selectedIndex = i;
				}
			}
			
			
			$('#sendPromptBtn').on('click', function () {
				const promptEnhancer = $('#promptEnhancer').val();
				const userPrompt = $('#userPrompt').val();
				const llm = $('#llmSelect').val();
				
				$('#sendPromptBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
				
				$.ajax({
					url: '{{ route('send-image-gen-prompt') }}',
					method: 'POST',
					data: {prompt_enhancer: promptEnhancer, user_prompt: userPrompt, llm: llm},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: 'json',
					success: function (result) {
						if (result.success) {
							$('#generatedImageArea').removeClass('d-none');
							$('#generatedImage').attr('src', '/storage/' + result.output_path);
							$('#image_prompt').text(result.image_prompt);
							$('#tokensDisplay').text(`Tokens Used: ${result.prompt_tokens}/${result.completion_tokens}`);
						} else {
							alert('Error generating image');
						}
						$('#sendPromptBtn').prop('disabled', false).text('Send Prompt');
					}
				});
			});
			
		});
	</script>

@endpush

