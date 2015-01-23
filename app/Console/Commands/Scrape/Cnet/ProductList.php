<?php namespace App\Console\Commands\Scrape\Cnet;

use App\Repositories\TagRepository;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

class ProductList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scrape:cnet.product.list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$request  = Request::create($url);
		$crawler = $client->request('GET', $request->getUri());

		// Create or update the given tags
		$tags = TagRepository::save($this->option('tag'));

		// Get the urls from the list
		$crawler->filter('.itemInfo > a')->each(function(Crawler $node) use($request, $tags) {

			// Get the product info
			$url = $request->getSchemeAndHttpHost() . $node->attr('href');
			$this->info('Start getting product info: ' . $url);
			$this->call('scrape:cnet.product', [
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
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['url', InputArgument::REQUIRED, 'The url for the list of products'],
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
