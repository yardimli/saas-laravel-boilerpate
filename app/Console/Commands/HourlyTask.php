<?php

	namespace App\Console\Commands;

	use App\Models\User;
	use Illuminate\Console\Command;

	class HourlyTask extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'app:calculate-word-count';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Calculate Word Count for all stories and update the database';

		/**
		 * Execute the console command.
		 */
		public function handle()
		{
			$this->info('Calculate Word Count Hourly task running...');
		}
	}
