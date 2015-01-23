<?php namespace App\Console\Commands\Import;

use App\Repositories\OfferRepository;
use App\Repositories\ProductRepository;
use App\Repositories\VendorRepository;
use App\Services\TradeTracker\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Product extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:product';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import one product from all vendors';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(Client $client)
	{
		$commands = [
			'import:algebeld',
		];

		$arguments = [
			'--name' => $this->argument('name'),
		];

		foreach($commands as $command) {
			$this->call($command, $arguments);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'The name of the product to search for'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
//			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
