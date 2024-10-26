<?php

	namespace App\Helpers;

	use App\Models\SentencesTable;
	use BinshopsBlog\Models\BinshopsCategory;
	use BinshopsBlog\Models\BinshopsCategoryTranslation;
	use BinshopsBlog\Models\BinshopsLanguage;
	use BinshopsBlog\Models\BinshopsPostTranslation;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Http;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\Validator;
	use Intervention\Image\ImageManagerStatic as Image;
	use Ahc\Json\Fixer;

	class MyHelper
	{

		public static function getBlogData()
		{
			$locale = \App::getLocale() ?: config('app.fallback_locale', 'zh_TW');

			// the published_at + is_published are handled by BinshopsBlogPublishedScope, and don't take effect if the logged in user can manageb log posts

			//todo
			$title = 'Blog Page'; // default title...
			$category_slug = null;

			$categoryChain = null;
			$posts = array();
			if ($category_slug) {
				$category = BinshopsCategoryTranslation::where("slug", $category_slug)->with('category')->firstOrFail()->category;
				$categoryChain = $category->getAncestorsAndSelf();
				$posts = $category->posts()->where("binshops_post_categories.category_id", $category->id)->get(); //->where("lang_id", '=', 2)->get();

				$posts = BinshopsPostTranslation::join('binshops_posts', 'binshops_post_translations.post_id', '=', 'binshops_posts.id')
//					->where('lang_id', 2)
					->where("is_published", '=', true)
					->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
					->orderBy("posted_at", "desc")
					->whereIn('binshops_posts.id', $posts->pluck('id'))
					->paginate(config("binshopsblog.per_page", 10));

				// at the moment we handle this special case (viewing a category) by hard coding in the following two lines.
				// You can easily override this in the view files.
				\View::share('binshopsblog_category', $category); // so the view can say "You are viewing $CATEGORYNAME category posts"
				$title = 'Posts in ' . $category->category_name . " category"; // hardcode title here...
			} else {
				$posts = BinshopsPostTranslation::join('binshops_posts', 'binshops_post_translations.post_id', '=', 'binshops_posts.id')
//					->where('lang_id', 2)
					->where("is_published", '=', true)
					->where('posted_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
					->orderBy("posted_at", "desc")
					->paginate(config("binshopsblog.per_page", 10));

				foreach ($posts as $post) {
					$post->category_name = '';
					//get post categories
					$categories = BinshopsCategory::join('binshops_post_categories', 'binshops_categories.id', '=', 'binshops_post_categories.category_id')
						->where('binshops_post_categories.post_id', $post->id)
						->get();
					//get category translations
					$categories = json_decode(json_encode($categories), true);
					foreach ($categories as $category) {
						if ($post->category_name == '' || $post->category_name == null) {
							$post->category_name = BinshopsCategoryTranslation::where('category_id', $category['category_id'])->first()->category_name ?? '';
						}
					}
				}
			}

			//load category hierarchy
			$rootList = BinshopsCategory::roots()->get();
			BinshopsCategory::loadSiblingsWithList($rootList);

			$blogData = [
				'lang_list' => BinshopsLanguage::all('locale', 'name'),
				'locale' => $locale, // $request->get("locale"),
				'category_chain' => $categoryChain,
				'categories' => $rootList,
				'posts' => $posts,
				'title' => $title,
			];

			return $blogData;

		}

		public static function moderation($message)
		{
			function isValidUtf8($string)
			{
				return mb_check_encoding($string, 'UTF-8');
			}

			$openai_api_key = self::getOpenAIKey();
			//make sure $message can be json encoded
			if (!isValidUtf8($message)) {
				$message = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $message);
			}


			$response = Http::withHeaders([
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $openai_api_key,
			])->post(env('OPEN_AI_API_BASE_MODERATION'), [
				'input' => $message,
			]);

			return $response->json();
		}

		public static function validateJson($str)
		{
			Log::info('Starting JSON validation.');

			$error = json_last_error();
			json_decode($str);
			$error = json_last_error();

			switch ($error) {
				case JSON_ERROR_NONE:
					return "Valid JSON";
				case JSON_ERROR_DEPTH:
					return "Maximum stack depth exceeded";
				case JSON_ERROR_STATE_MISMATCH:
					return "Underflow or the modes mismatch";
				case JSON_ERROR_CTRL_CHAR:
					return "Unexpected control character found";
				case JSON_ERROR_SYNTAX:
					return "Syntax error, malformed JSON";
				case JSON_ERROR_UTF8:
					return "Malformed UTF-8 characters, possibly incorrectly encoded";
				default:
					return "Unknown error";
			}
		}

		public static function repaceNewLineWithBRInsideQuotes($input)
		{
			$output = '';
			$inQuotes = false;
			$length = strlen($input);
			$i = 0;

			while ($i < $length) {
				$char = $input[$i];

				if ($char === '"') {
					$inQuotes = !$inQuotes;
					$output .= $char;
				} elseif ($inQuotes) {
					if ($char === "\n" || $char === "\r") {
						$output .= '<BR>';
						if ($char === "\r" && $i + 1 < $length && $input[$i + 1] === "\n") {
							$i++; // Skip the next character if it's a Windows-style line ending (\r\n)
						}
					} elseif ($char === '\\') {
						if ($i + 1 < $length) {
							$nextChar = $input[$i + 1];
							if ($nextChar === 'n') {
								$output .= '<BR>';
								$i++;
							} elseif ($nextChar === 'r') {
								$output .= '<BR>';
								$i++;
								if ($i + 1 < $length && $input[$i + 1] === '\\' && $i + 2 < $length && $input[$i + 2] === 'n') {
									$i += 2; // Skip the next two characters if it's \\r\\n
								}
							} elseif ($nextChar === '\\') {
								if ($i + 2 < $length) {
									$nextNextChar = $input[$i + 2];
									if ($nextNextChar === 'n' || $nextNextChar === 'r') {
										$output .= '<BR>';
										$i += 2;
										if ($nextNextChar === 'r' && $i + 2 < $length && $input[$i + 1] === '\\' && $input[$i + 2] === 'n') {
											$i += 2; // Skip the next two characters if it's \\r\\n
										}
									} else {
										$output .= $char;
									}
								} else {
									$output .= $char;
								}
							} else {
								$output .= $char;
							}
						} else {
							$output .= $char;
						}
					} else {
						$output .= $char;
					}
				} else {
					$output .= $char;
				}
				$i++;
			}

			return $output;
		}

		public static function getContentsInBackticksOrOriginal($input)
		{
			// Define a regular expression pattern to match content within backticks
			$pattern = '/`([^`]+)`/';

			// Initialize an array to hold matches
			$matches = array();

			// Perform a global regular expression match
			preg_match_all($pattern, $input, $matches);

			// Check if any matches were found
			if (empty($matches[1])) {
				return $input; // Return the original input if no matches found
			} else {
				return implode(' ', $matches[1]);
			}
		}

		public static function extractJsonString($input)
		{
			// Find the first position of '{' or '['
			$startPos = strpos($input, '{');
			if ($startPos === false) {
				$startPos = strpos($input, '[');
			}

			// Find the last position of '}' or ']'
			$endPos = strrpos($input, '}');
			if ($endPos === false) {
				$endPos = strrpos($input, ']');
			}

			// If start or end positions are not found, return an empty string
			if ($startPos === false || $endPos === false) {
				return '';
			}

			// Extract the JSON substring
			$jsonString = substr($input, $startPos, $endPos - $startPos + 1);

			return $jsonString;
		}

		public static function mergeStringsWithoutRepetition($string1, $string2, $maxRepetitionLength = 100)
		{
			$len1 = strlen($string1);
			$len2 = strlen($string2);

			// Determine the maximum possible repetition length
			$maxPossibleRepetition = min($maxRepetitionLength, $len1, $len2);

			// Find the length of the actual repetition
			$repetitionLength = 0;
			for ($i = 1; $i <= $maxPossibleRepetition; $i++) {
				if (substr($string1, -$i) === substr($string2, 0, $i)) {
					$repetitionLength = $i;
				} else {
					break;
				}
			}

			// Remove the repetition from the beginning of the second string
			$string2 = substr($string2, $repetitionLength);

			// Merge the strings
			return $string1 . $string2;
		}

		public static function getAnthropicKey()
		{
			$user = Auth::user();
			return !empty($user->anthropic_key) ? $user->anthropic_key : $_ENV['ANTHROPIC_KEY'];
		}

		public static function getOpenAIKey()
		{
			$user = Auth::user();
			return !empty($user->openai_api_key) ? $user->openai_api_key : $_ENV['OPEN_AI_API_KEY'];
		}

		public static function getOpenRouterKey()
		{
			$user = Auth::user();
			return !empty($user->openrouter_key) ? $user->openrouter_key : $_ENV['OPEN_ROUTER_KEY'];
		}

		//------------------------------------------------------------
		public static function function_call($llm, $example_question, $example_answer, $prompt, $schema, $language = 'english')
		{
			set_time_limit(300);
			session_write_close();

			if ($llm === 'anthropic-haiku') {
				$llm_base_url = $_ENV['ANTHROPIC_HAIKU_BASE'];
				$llm_api_key = getAnthropicKey();
				$llm_model = $_ENV['ANTHROPIC_HAIKU_MODEL'];

			} else if ($llm === 'anthropic-sonet') {
				$llm_base_url = $_ENV['ANTHROPIC_SONET_BASE'];
				$llm_api_key = getAnthropicKey();
				$llm_model = $_ENV['ANTHROPIC_SONET_MODEL'];

			} else if ($llm === 'open-ai-gpt-4o') {
				$llm_base_url = $_ENV['OPEN_AI_GPT4_BASE'];
				$llm_api_key = self::getOpenAIKey();
				$llm_model = $_ENV['OPEN_AI_GPT4_MODEL'];

			} else if ($llm === 'open-ai-gpt-4o-mini') {
				$llm_base_url = $_ENV['OPEN_AI_GPT4_MINI_BASE'];
				$llm_api_key = self::getOpenAIKey();
				$llm_model = $_ENV['OPEN_AI_GPT4_MINI_MODEL'];
			} else {
				$llm_base_url = $_ENV['OPEN_ROUTER_BASE'];
				$llm_api_key = self::getOpenRouterKey();
				$llm_model = $llm;
			}


			$chat_messages = [];
			if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {

				$chat_messages[] = [
					'role' => 'user',
					'content' => $prompt
				];
			} else {
//				$chat_messages[] = [
//					'role' => 'system',
//					'content' => 'You are an expert author advisor.'
//				];
				$chat_messages[] = [
					'role' => 'user',
					'content' => $prompt
				];
			}


			$temperature = rand(80, 100) / 100;
			$max_tokens = 4000;

			$tool_name = 'auto';
//			if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
//				$tool_name = $schema['function']['name'];
//			}

			$data = array(
				'model' => $llm_model,
				'messages' => $chat_messages,
				'tools' => [$schema],
				'tool_choice' => $tool_name,
				'temperature' => $temperature,
				'max_tokens' => $max_tokens,
				'top_p' => 1,
				'frequency_penalty' => 0,
				'presence_penalty' => 0,
				'n' => 1,
				'stream' => false
			);

			if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
				//remove tool_choice
				unset($data['tool_choice']);
				unset($data['frequency_penalty']);
				unset($data['presence_penalty']);
				unset($data['n']);
			}

			Log::info('================== FUNCTION CALL DATA =====================');
			Log::info($data);

			$post_json = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $llm_base_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);

			$headers = array();
			if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
				$headers[] = "x-api-key: " . $llm_api_key;
				$headers[] = 'anthropic-version: 2023-06-01';
				$headers[] = 'content-type: application/json';
			} else {
				$headers[] = 'Content-Type: application/json';
				$headers[] = "Authorization: Bearer " . $llm_api_key;
			}

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$complete = curl_exec($ch);
			if (curl_errno($ch)) {
				Log::info('CURL Error:');
				Log::info(curl_getinfo($ch));
			}
			curl_close($ch);
//			session_start();

			Log::info('==================Log complete 1 =====================');
			$complete = trim($complete, " \n\r\t\v\0");
			Log::info($complete);

			$validateJson = self::validateJson($complete);
			if ($validateJson == "Valid JSON") {
				Log::info('==================Log JSON complete=====================');
				$complete_rst = json_decode($complete, true);
				Log::info($complete_rst);
				$arguments_rst = [];

				if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
					$contents = $complete_rst['content'];
					foreach ($contents as $content) {
						if ($content['type'] === 'tool_use') {
							$arguments_rst = $content['input'];
						}
					}
				} else {
					$content = $complete_rst['choices'][0]['message']['tool_calls'][0]['function'];
					$arguments = $content['arguments'];
					$validateJson = self::validateJson($arguments);
					if ($validateJson == "Valid JSON") {
						Log::info('==================Log JSON arguments=====================');
						$arguments_rst = json_decode($arguments, true);
						Log::info($arguments_rst);
					}
				}


				return $arguments_rst;
			} else {
				Log::info('==================Log JSON error=====================');
				Log::info($validateJson);
			}
		}

		public static function llm_no_tool_call($llm, $system_prompt, $prompt, $return_json = true)
		{
			set_time_limit(300);
			session_write_close();

			if ($llm === 'anthropic-haiku') {
				$llm_base_url = $_ENV['ANTHROPIC_HAIKU_BASE'];
				$llm_api_key = elf::getAnthropicKey();;
				$llm_model = $_ENV['ANTHROPIC_HAIKU_MODEL'];

			} else if ($llm === 'anthropic-sonet') {
				$llm_base_url = $_ENV['ANTHROPIC_SONET_BASE'];
				$llm_api_key = elf::getAnthropicKey();;
				$llm_model = $_ENV['ANTHROPIC_SONET_MODEL'];

			} else if ($llm === 'open-ai-gpt-4o') {
				$llm_base_url = $_ENV['OPEN_AI_GPT4_BASE'];
				$llm_api_key = self::getOpenAIKey();
				$llm_model = $_ENV['OPEN_AI_GPT4_MODEL'];

			} else if ($llm === 'open-ai-gpt-4o-mini') {
				$llm_base_url = $_ENV['OPEN_AI_GPT4_MINI_BASE'];
				$llm_api_key = self::getOpenAIKey();
				$llm_model = $_ENV['OPEN_AI_GPT4_MINI_MODEL'];
			} else {
				$llm_base_url = $_ENV['OPEN_ROUTER_BASE'];
				$llm_api_key = self::getOpenRouterKey();
				$llm_model = $llm;
			}

			$chat_messages = [];


			if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
			} else {
				$chat_messages[] = [
					'role' => 'system',
					'content' => $system_prompt];
			}


			$chat_messages[] = [
				'role' => 'user',
				'content' => $prompt
			];

			$temperature = 0.8;
			$max_tokens = 8096;

			$data = array(
				'model' => $llm_model,
				'messages' => $chat_messages,
				'temperature' => $temperature,
				'max_tokens' => $max_tokens,
				'top_p' => 1,
				'frequency_penalty' => 0,
				'presence_penalty' => 0,
				'n' => 1,
				'stream' => false
			);

			if ($llm === 'open-ai-gpt-4o' || $llm === 'open-ai-gpt-4o-mini') {
				$data['max_tokens'] = 4096;
				$data['temperature'] = 1;
			} else if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
				$data['max_tokens'] = 8096;
				unset($data['frequency_penalty']);
				unset($data['presence_penalty']);
				unset($data['n']);
				$data['system'] = $system_prompt;
			} else {
				$data['max_tokens'] = 8096;
				if (stripos($llm_model, 'anthropic') !== false) {
					unset($data['frequency_penalty']);
					unset($data['presence_penalty']);
					unset($data['n']);
				} else if (stripos($llm_model, 'openai') !== false) {
					$data['temperature'] = 1;
				} else if (stripos($llm_model, 'google') !== false) {
					$data['stop'] = [];
				} else {
					unset($data['frequency_penalty']);
					unset($data['presence_penalty']);
					unset($data['n']);
				}
			}

			Log::info('GPT NO TOOL USE: ' . $llm_base_url . ' (' . $llm . ')');
			Log::info($data);

			$post_json = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $llm_base_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);

			$headers = array();
			if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
				$headers[] = "x-api-key: " . $llm_api_key;
				$headers[] = 'anthropic-version: 2023-06-01';
				$headers[] = 'content-type: application/json';
			} else {
				$headers[] = 'Content-Type: application/json';
				$headers[] = "Authorization: Bearer " . $llm_api_key;
				$headers[] = "HTTP-Referer: https://my-laravel-saas-site.com";
				$headers[] = "X-Title: SAASLaravelBoilerplate";
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$complete = curl_exec($ch);
			if (curl_errno($ch)) {
				Log::info('CURL Error:');
				Log::info(curl_getinfo($ch));
			}
			curl_close($ch);

//			Log::info('==================Log complete 2 =====================');
			$complete = trim($complete, " \n\r\t\v\0");
//			Log::info($complete);

			$complete_rst = json_decode($complete, true);

			Log::info("GPT NO STREAM RESPONSE:");
			Log::info($complete_rst);

			if ($llm === 'open-ai-gpt-4o' || $llm === 'open-ai-gpt-4o-mini') {
				$content = $complete_rst['choices'][0]['message']['content'];
			} else if ($llm === 'anthropic-haiku' || $llm === 'anthropic-sonet') {
				$content = $complete_rst['content'][0]['text'];
			} else {
				if (isset($complete_rst['error'])) {
					Log::info('================== ERROR =====================');
					Log::info($complete_rst);
					Log::info($complete_rst['error']['message']);
					return json_decode($complete_rst['error']['message'] ?? '{}');
				}
				if (isset($complete_rst['choices'][0]['message']['content'])) {
					$content = $complete_rst['choices'][0]['message']['content'];
				} else {
					$content = '';
				}
			}

			if (!$return_json) {
				Log::info('Return is NOT JSON. Will return content presuming it is text.');
				return $content;
			}

//			$content = str_replace("\\\"", "\"", $content);
			$content = $content ?? '';
			$content = self::getContentsInBackticksOrOriginal($content);

			//remove all backticks
			$content = str_replace("`", "", $content);

			//check if content is JSON
			$content_json_string = self::extractJsonString($content);
			$content_json_string = self::repaceNewLineWithBRInsideQuotes($content_json_string);

			$validate_result = self::validateJson($content_json_string);

			if ($validate_result !== "Valid JSON") {
				Log::info('================== VALIDATE JSON ON FIRST PASS FAILED =====================');
				Log::info('String that failed:: ---- Error:' . $validate_result);
				Log::info("$content_json_string");

				$content_json_string = (new Fixer)->silent(true)->missingValue('"truncated"')->fix($content_json_string);
				$validate_result = self::validateJson($content_json_string);
			}

			if (strlen($content ?? '') < 20) {
				Log::info('================== CONTENT IS EMPTY =====================');
				Log::info($complete);
				return '';
			}

			//if JSON failed make a second call to get the rest of the JSON
			if ($validate_result !== "Valid JSON") {

				//------ Check if JSON is complete or not with a prompt to continue ------------
				//-----------------------------------------------------------------------------
				$verify_completed_prompt = 'If the JSON is complete output DONE otherwise continue writing the JSON response. Only write the missing part of the JSON response, don\'t repeat the already written story JSON. Continue from exactly where the JSON response left off. Make sure the combined JSON response will be valid JSON.';

				$chat_messages[] = [
					'role' => 'assistant',
					'content' => $content
				];
				$chat_messages[] = [
					'role' => 'user',
					'content' => $verify_completed_prompt
				];

				$data['messages'] = $chat_messages;
				Log::info('======== SECOND CALL TO FINISH JSON =========');
				Log::info($data);
				$post_json = json_encode($data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $llm_base_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$complete2 = curl_exec($ch);
				if (curl_errno($ch)) {
					Log::info('CURL Error:');
					Log::info(curl_getinfo($ch));
				}
				curl_close($ch);

				$complete2 = trim($complete2, " \n\r\t\v\0");

				Log::info("GPT NO STREAM RESPONSE FOR EXTENDED VERSION JSON CHECK:");
				Log::info($complete2);

				$complete2_rst = json_decode($complete2, true);
				$content2 = $complete2_rst['choices'][0]['message']['content'];

				//$content2 = str_replace("\\\"", "\"", $content2);
				$content2 = self::getContentsInBackticksOrOriginal($content2);

				if (!str_contains($content2, 'DONE')) {
					$content = self::mergeStringsWithoutRepetition($content, $content2, 255);
				}

				//------------------------------------------------------------

				$content_json_string = self::extractJsonString($content);
				$content_json_string = self::repaceNewLineWithBRInsideQuotes($content_json_string);

				$validate_result = self::validateJson($content_json_string);

				if ($validate_result !== "Valid JSON") {
					$content_json_string = (new Fixer)->silent(true)->missingValue('"truncated"')->fix($content_json_string);
					$validate_result = self::validateJson($content_json_string);
				}

			} else {
				Log::info("GPT NO STREAM RESPONSE:");
				Log::info($complete_rst);
			}

			if ($validate_result == "Valid JSON") {
				Log::info('================== VALID JSON =====================');
				$content_rst = json_decode($content_json_string, true);
				Log::info($content_rst);
				return $content_rst;
			} else {
				Log::info('================== INVALID JSON =====================');
				Log::info('JSON error : ' . $validate_result . ' -- ');
				Log::info($content);
			}
		}

		//-------------------------------------------------------------------------

	}
