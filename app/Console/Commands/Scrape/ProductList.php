<?php namespace App\Console\Commands\Scrape;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Request;

class ProductList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scrape:product-list';

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get the urls to the product info';

	/**
	 * @var array
	 */
	protected $urls = [
		'trashcans' => 'http://www.wayfair.com/All-Residential-Trash-Cans-C1776019.html',
	];

	protected $info = [
		'trashcans' => 'scrape:wayfair-product',
	];

	/**
	 * @var array
	 */
	protected $paths = [
		'trashcans' => '#sbprodgrid a.productbox',
		'tv' => '.itemInfo > a',
	];

	/**
	 * Create a new command instance.
	 *
	 * @param Client $client
	 * @return void
	 */
	public function __construct(Client $client)
	{
		parent::__construct();

		$this->client = $client;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$client = new Client();
		$url = $this->getUrl();
		$path = $this->getPath();
		$info = $this->getInfo();
		$request  = Request::create($url);

		// Scrape the website page
		$this->info(sprintf('Scraping "%s"...', $url));
		$crawler = $client->request('GET', $request->getUri());

		// Get the urls from the list
		$crawler->filter($path)->each(function(Crawler $node) use($request, $info) {

			// Get the product info
			$url = $request->getSchemeAndHttpHost() . $node->attr('href');
			$this->info('Start getting product info: ' . $url);
			$this->call($info, [
				'url' => $url,
				'--tag' => $this->option('tag'),
			]);

		});

		// Check if there is a paginated next page with products
		if($next = $crawler->filter('.pagination a.next')->first()) {
			$url = $request->getSchemeAndHttpHost() . $next->attr('href');
			$this->info('Next page: ' . $url);
		}
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	protected function getPath()
	{
		$product = $this->argument('product');

		if(!isset($this->paths[$product])) {
			throw new \Exception('Path does not exist');
		}

		return $this->paths[$product];
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	protected function getUrl()
	{
		$product = $this->argument('product');

		if(!isset($this->urls[$product])) {
			throw new \Exception('Url does not exist');
		}

		return $this->urls[$product];
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	protected function getInfo()
	{
		$product = $this->argument('product');

		if(!isset($this->info[$product])) {
			throw new \Exception('Info command does not exist');
		}

		return $this->info[$product];
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['product', InputArgument::REQUIRED, 'The name of the product to scrape (plural)'],
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
		];
	}

}
