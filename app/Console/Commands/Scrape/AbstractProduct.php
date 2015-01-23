<?php namespace App\Console\Commands\Scrape;

use App\Product;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Yandex\Translate\Translator;
use Yandex\Translate\Translation;
use Request;

abstract class AbstractProduct extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * @var Client
     */
    protected $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * Create a new command instance.
     *
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
        $url = $this->argument('url');
        $this->info('Scraping url: ' . $url);
        $crawler = $this->client->request('GET', $url);

        // Get the product title
        $name = $this->getProductName($crawler);
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
            $description = $this->getProductDescription($crawler);
            $this->info('Description: ' . $description);

            // Translate the description
            $translation = $this->translate($description);
            $description = implode(' ', $translation->getResult());
            $this->info('Translation: ' . $description);

            $product->name = $name;
            $product->description = $description;
            $product->save();
            $this->info('Saved product with ID: ' . $product->id);
        }

        //----- Tags ------

        // Get the brand
        $brand = $this->getBrand($product, $crawler);

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

    abstract public function getProductName(Crawler $crawler);

    abstract public function getProductDescription(Crawler $crawler);

    abstract public function getBrand(Crawler $crawler);

    /**
     * @param        $text
     * @param string $locale
     * @return Translation
     */
    protected function translate($text, $locale = 'en-nl')
    {
        return $this->translator->translate($text, $locale);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['url', InputArgument::REQUIRED, 'The url to get the product information from'],
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
