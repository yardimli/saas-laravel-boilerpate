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
					
					<h5>{{__('default.Chat with AI')}}</h5>
					
					
					<!-- Text Blocks Div -->
					<div class="mt-2 mb-2">
						
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
					
					<div class="mb-3">
						<label for="userPrompt" class="form-label">{{__('default.User Prompt')}}</label>
						<textarea class="form-control" id="userPrompt" rows="8"></textarea>
					</div>
					<div class="mb-3">
						<label for="llmResponse" class="form-label">{{__('default.LLM Response')}}</label>
						<textarea class="form-control" id="llmResponse" rows="10" readonly></textarea>
					</div>
					
					<button type="button" class="btn btn-primary" id="sendPromptBtn">{{__('default.Send Prompt')}}</button>
				</div> <!-- Row END -->
				<div class="col-12 col-xl-4 col-lg-4 mx-auto">
					
					<h5>{{__('default.Chat History')}}</h5>
				
				</div>
			</div>
			<!-- Container END -->
	</main>
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		let savedLlm = localStorage.getItem('edit-book-llm') || 'anthropic/claude-3-haiku:beta';
		
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
		
		$(document).ready(function () {
			
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
				localStorage.setItem('edit-book-llm', $(this).val());
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
			
			
			// Chat with AI
			$('#sendPromptBtn').on('click', function () {
				const userPrompt = $('#userPrompt').val();
				const llm = savedLlm; // Assuming you have a savedLlm variable
				
				// Disable buttons and show loading state
				$('#sendPromptBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
				$('#llmResponse').val('Processing...');
				
				$.ajax({
					url: "{{route('send-llm-prompt')}}",
					method: 'POST',
					data: {
						user_prompt: userPrompt,
						llm: llm
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							$('#llmResponse').val(response.result);
						} else {
							$('#llmResponse').val('Error: ' + response.message);
						}
					},
					error: function (xhr, status, error) {
						$('#llmResponse').val('An error occurred while processing the request.');
					},
					complete: function () {
						// Re-enable button and restore original text
						$('#sendPromptBtn').prop('disabled', false).text('Send Prompt');
					}
				});
				
			});
			
		});
	</script>

@endpush

