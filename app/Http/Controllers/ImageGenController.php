<?php

	namespace App\Http\Controllers;

	use App\Helpers\MyHelper;
	use App\Models\ImageGen;
	use Carbon\Carbon;
	use GuzzleHttp\Client;
	use Illuminate\Http\Request;
	use App\Models\User;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Validator;


	class ImageGenController extends Controller
	{

		public function getImageGenSessions(Request $request)
		{
			if (!Auth::check()) {
				return [];
			}

			$sessions = ImageGen::where('user_id', Auth::id())
				->orderBy('updated_at', 'desc')
				->get();

			return response()->json($sessions);
		}

		public function makeImage(Request $request)
		{
			if (!Auth::check()) {
				return [];
			}

			$model = 'fast'; //$request->input('model', 'fast');

			$prompt_enhancer = $request->input('prompt_enhancer', '##UserPrompt##');
			if ($prompt_enhancer === null || $prompt_enhancer === '') {
				$prompt_enhancer = '##UserPrompt##';
			}
			$user_prompt = $request->input('user_prompt', 'A fantasy picture of a cat');
			if ($user_prompt === null || $user_prompt === '') {
				$user_prompt = 'A fantasy picture of a cat';
			}
			$gpt_prompt = str_replace('##UserPrompt##', $user_prompt, $prompt_enhancer);
			$llm = $request->input('llm');

			$chat_history[] = [
				'role' => 'user',
				'content' => $gpt_prompt,
			];


			$image_prompt = MyHelper::llm_no_tool_call($llm, '', $chat_history, false);
			Log::info('Enhanced Cover Image Prompt');
			Log::info($image_prompt);

			if (!Storage::disk('public')->exists('ai-images')) {
				Storage::disk('public')->makeDirectory('ai-images');
			}

			$session_id = Str::uuid();
			$filename = $session_id . '.jpg';
			$outputFile = Storage::disk('public')->path('ai-images/' . $filename);

			$falApiKey = $_ENV['FAL_API_KEY'];
			if (empty($falApiKey)) {
				echo json_encode(['error' => 'FAL_API_KEY environment variable is not set']);
			}

			$client = new \GuzzleHttp\Client();

			$url = 'https://fal.run/fal-ai/flux/schnell';
			if ($model === 'fast') {
				$url = 'https://fal.run/fal-ai/flux/schnell';
			}
			if ($model === 'balanced') {
				$url = 'https://fal.run/fal-ai/flux/dev';
			}
			if ($model === 'detailed') {
				$url = 'https://fal.run/fal-ai/flux-pro';
			}

			$response = $client->post($url, [
				'headers' => [
					'Authorization' => 'Key ' . $falApiKey,
					'Content-Type' => 'application/json',
				],
				'json' => [
					'prompt' => $image_prompt['content'],
					'image_size' => 'square_hd',
					'safety_tolerance' => '5',
				]
			]);
			Log::info('FLUX image response');
			Log::info($response->getBody());

			$body = $response->getBody();
			$data = json_decode($body, true);

			if ($response->getStatusCode() == 200) {

				// In ImageGenController.php, add after making the image:

				if (isset($data['images'][0]['url'])) {
					$image_url = $data['images'][0]['url'];
					$image = file_get_contents($image_url);
					file_put_contents($outputFile, $image);

					// Save to database
					ImageGen::create([
						'session_id' => $session_id,
						'user_id' => Auth::id(),
						'user_prompt' => $user_prompt,
						'llm_prompt' => $prompt_enhancer,
						'image_prompt' => $image_prompt['content'],
						'image_path' => 'ai-images/' . $filename,
						'llm' => $llm,
						'prompt_tokens' => $image_prompt['prompt_tokens'] ?? 0,
						'completion_tokens' => $image_prompt['completion_tokens'] ?? 0
					]);

					return json_encode([
						'success' => true,
						'message' => __('Image generated successfully'),
						'output_filename' => $filename,
						'output_path' => 'ai-images/' . $filename,
						'data' => json_encode($data),
						'seed' => $data['seed'],
						'status_code' => $response->getStatusCode(),
						'user_prompt' => $user_prompt,
						'llm_prompt' => $prompt_enhancer,
						'image_prompt' => $image_prompt['content'],
						'prompt_tokens' => $image_prompt['prompt_tokens'] ?? 0,
						'completion_tokens' => $image_prompt['completion_tokens'] ?? 0
					]);
				} else {
					return json_encode(['success' => false, 'message' => __('Error (2) generating image'), 'status_code' => $response->getStatusCode()]);
				}
			} else {
				return json_encode(['success' => false, 'message' => __('Error (1) generating image'), 'status_code' => $response->getStatusCode()]);
			}
		}

		public function destroy($session_id)
		{
			if (!Auth::check()) {
				return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
			}

			$imageGen = ImageGen::where('session_id', $session_id)
				->where('user_id', Auth::id())
				->first();

			if (!$imageGen) {
				return response()->json(['success' => false, 'message' => 'Record not found'], 404);
			}

			// Delete the image file
			if ($imageGen->image_path && Storage::disk('public')->exists($imageGen->image_path)) {
				Storage::disk('public')->delete($imageGen->image_path);
			}

			// Delete the database record
			$imageGen->delete();

			return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
		}

		public function index(Request $request, $session_id = null)
		{
			if (!Auth::check()) {
				return redirect()->route('login');
			}

			$session = null;
			if ($session_id) {
				$session = ImageGen::where('session_id', $session_id)
					->where('user_id', Auth::id())
					->first();
			}

			return view('user.image-gen', [
				'current_session' => $session,
				'current_session_id' => $session_id
			]);
		}

	}
