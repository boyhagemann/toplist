<?php namespace App\Console\Commands\Import;

use App\Repositories\OfferRepository;
use App\Repositories\ProductRepository;
use App\Repositories\VendorRepository;
use App\Services\TradeTracker\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Algebeld extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:algebeld';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import products from Algebeld.nl';

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
		$products = $client->getFeedProducts(48216, [
			'campaignID' => 1078,
			'limit' => 1000,
		]);

		$vendor = VendorRepository::firstOrCreateByName('Algebeld.nl');

		foreach($products as $data) {

			// Build the product name with the brand
			$nameParts = [$data->name];

			foreach($data->additional as $attribute) {
				if($attribute->name == 'brand') {
					array_unshift($nameParts, $attribute->value);
				}
			}

			$name = implode(' ', $nameParts);
			$slug = Str::slug($name);

			$product = ProductRepository::findBySlug($slug);

			$offer = OfferRepository::firstOrNew($product, $vendor);
			$offer->price = $data->price * 100;
			$offer->url = $data->productURL;
			$offer->save();
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
//			['example', InputArgument::REQUIRED, 'An example argument.'],
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
