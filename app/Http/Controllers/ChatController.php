<?php

	namespace App\Http\Controllers;

	use App\Helpers\MyHelper;
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


	class ChatController extends Controller
	{

		public function checkLLMsJson()
		{
			$llmsJsonPath = Storage::disk('public')->path('llms.json');

			if (!File::exists($llmsJsonPath) || Carbon::now()->diffInDays(Carbon::createFromTimestamp(File::lastModified($llmsJsonPath))) > 1) {
				$client = new Client();
				$response = $client->get('https://openrouter.ai/api/v1/models');
				$data = json_decode($response->getBody(), true);

				if (isset($data['data'])) {
					File::put($llmsJsonPath, json_encode($data['data']));
				} else {
					return response()->json([]);
				}
			}

			$openrouter_admin_or_key = false;
			if ((Auth::user() && Auth::user()->isAdmin()) ||
				(Auth::user() && !empty(Auth::user()->openrouter_key))) {
				$openrouter_admin_or_key = true;
			}

			$llms_with_rank_path = resource_path('data/llms_with_rank.json');
			$llms_with_rank = json_decode(File::get($llms_with_rank_path), true);

			$llms = json_decode(File::get($llmsJsonPath), true);
			$filtered_llms = array_filter($llms, function ($llm) use ($openrouter_admin_or_key) {
				if (isset($llm['id']) && (stripos($llm['id'], 'openrouter/auto') !== false)) {
					return false;
				}

				if (isset($llm['id']) && (stripos($llm['id'], 'vision') !== false)) {
					return false;
				}

				if (isset($llm['id']) && (stripos($llm['id'], '-3b-') !== false)) {
					return false;
				}

				if (isset($llm['id']) && (stripos($llm['id'], '-1b-') !== false)) {
					return false;
				}

				if (isset($llm['id']) && (stripos($llm['id'], 'online') !== false)) {
					return false;
				}

				if (isset($llm['id']) && (stripos($llm['id'], 'gpt-3.5') !== false)) {
					return false;
				}

				if (isset($llm['pricing']['completion'])) {
					$price_per_million = floatval($llm['pricing']['completion']) * 1000000;
					if ($openrouter_admin_or_key) {
						return $price_per_million <= 20;
					} else {
						return $price_per_million <= 1.5;
					}
				}

				if (!isset($llm['pricing']['completion'])) {
					return false;
				}

				return true;
			});

			foreach ($filtered_llms as &$filtered_llm) {
				$found_rank = false;
				foreach ($llms_with_rank as $llm_with_rank) {
					if ($filtered_llm['id'] === $llm_with_rank['id']) {
						$filtered_llm['score'] = $llm_with_rank['score'] ?? 0;
						$filtered_llm['ugi'] = $llm_with_rank['ugi'] ?? 0;
						$found_rank = true;
					}
				}
				if (!$found_rank) {
					$filtered_llm['score'] = 0;
					$filtered_llm['ugi'] = 0;
				}
			}

			// Sort $filtered_llms by score, then alphabetically for score 0
			usort($filtered_llms, function ($a, $b) {
				// First, compare by score in descending order
				$scoreComparison = $b['score'] <=> $a['score'];

				// If scores are different, return this comparison
				if ($scoreComparison !== 0) {
					return $scoreComparison;
				}

				// If scores are the same (particularly both 0), sort alphabetically by name
				return strcmp($a['name'], $b['name']);
			});

			//for each llm with score 0 sort them alphabetically
			return response()->json(array_values($filtered_llms));
		}

		public function sendLlmPrompt(Request $request)
		{
			$userPrompt = $request->input('user_prompt');
			$llm = $request->input('llm');

			try {
				$resultData = MyHelper::llm_no_tool_call($llm, '', $userPrompt, false);

				if (isset($resultData->error)) {
					return response()->json(['success' => false, 'message' => $resultData->error]);
				}

				return response()->json(['success' => true, 'result' => $resultData]);
			} catch (\Exception $e) {
				return response()->json(['success' => false, 'message' => $e->getMessage()]);
			}
		}

		public function index(Request $request)
		{
			//check if user is logged in
			if (Auth::check()) {
				return view('user.chat');
			} else {
				return redirect()->route('login');
			}
		}

	}
