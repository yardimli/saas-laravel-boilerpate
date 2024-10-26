<?php

	namespace App\Console\Commands;

	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\File;

	class ScanTranslationUsage extends Command
	{
		protected $signature = 'scan:translations';
		protected $description = 'Scan PHP files for translations and output used ones';

		public function handle()
		{
			$translations = [];
			$files = File::allFiles(base_path());

			foreach ($files as $file) {
				// Skip the storage folder
				if (strpos($file->getPathname(), 'storage') !== false) {
					continue;
				}

				if ($file->getExtension() === 'php') {
					$content = file_get_contents($file->getPathname());
					preg_match_all("/__\('default\.([^']+)'\)/", $content, $matches);

					if (!empty($matches[1])) {
						foreach ($matches[1] as $match) {
							$relativePath = $file->getRelativePathname();
							if (!isset($translations[$match])) {
								$translations[$match] = [];
							}
							if (!in_array($relativePath, $translations[$match])) {
								$translations[$match][] = $relativePath;
							}
						}
					}
				}
			}

			$output = "<?php\n\nreturn [\n";
			$lastFoundIn = '';
			foreach ($translations as $key => $files) {
				$foundIn = implode(', ', $files);
				if ($foundIn !== $lastFoundIn) {
					$output .= "\n// Found in: {$foundIn}\n";
					$lastFoundIn = $foundIn;
				}
				$output .= "'{$key}' => '{$key}',\n";
			}
			$output .= "];\n";

			$this->info('Translations used in the project:');
			$this->line($output);
		}
	}
