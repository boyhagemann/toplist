<?php namespace App\Console\Commands\Scrape\Cnet;

use App\Repositories\TagRepository;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Yandex\Translate\Translator;
use Yandex\Translate\Translation;
use App\Product;

class ProductInfo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scrape:cnet.product';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get product specs from Cnet.com';

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
	public function fire()
	{
		$client = new Client();
		$url = $this->argument('url');
		$this->info('Scraping url: ' . $url);
		$crawler = $client->request('GET', $url);

		// Get the product title
		$name = $crawler->filter('#modelInfo h3')->first()->text();
		$this->info('Name: ' . $name);

		// Convert the title to a uniform slug
		$slug = Str::slug($name);
		$this->info('Slug: ' . $slug);

		// Create or update the product with the unique slug
		Product::unguard();
		$product = Product::firstOrNew([
			'slug' => $slug,
		]);

		// Only continue if the product is not scraped yet
		if($product->description) {
			$this->info('Product is already scraped');
		}
		else {

			// Get the original description, only if it does not exist yet
			$description = $crawler->filter('#seodescription')->first()->text();
			$this->info('Description: ' . $description);

			// Translate the description
			$translator = new Translator(getenv('YANDEX_API_KEY'));

			/** @var Translation $translation */
			$translation = $translator->translate($description, 'en-nl');
			$description = implode(' ', $translation->getResult());
			$this->info('Translation: ' . $description);


			$product->name = $name;
			$product->description = $description;
			$product->save();
			$this->info('Saved product with ID: ' . $product->id);
		}

		//----- Tags ------

		// Get the brand
		$partnumber = $crawler->filter('#modelInfo .partNumber')->first()->text();
		$partnumber = str_replace('Part Number: ', '', $partnumber);
		$brand = trim(str_replace($partnumber, '', $name));

		// Create or update the given tags
		$tagNames = array_merge($this->option('tag'), explode(' ', $brand));
		$tags = TagRepository::save($tagNames);

		// Sync the tags for this product
		$product->tags()->sync($tags->lists('id'));
		$this->info('Synced tags: ' . json_encode($tags->lists('name')));

		// Search all vendors for offers
		if($this->option('search-vendors')) {
			$this->call('import:all', ['--name' => $product->name]);
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
			['url', InputArgument::REQUIRED, 'The url to the product page'],
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
			['tag', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The basic tags for all products on this page'],
			['search-vendors', null, InputOption::VALUE_NONE, 'Walk all registered vendors and see if they have an offer for this product'],
		];
	}

}
