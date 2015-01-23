<?php namespace App\Console\Commands\Scrape;

use Symfony\Component\DomCrawler\Crawler;
use App\Product;
use Request;

class WayfairProduct extends AbstractProduct {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scrape:cnet-product';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get the product information from the Cnet website';

	/**
	 * @param Crawler $crawler
	 * @return string
	 */
	public function getProductName(Crawler $crawler)
	{
		return $crawler->filter('#modelInfo h3')->first()->text();
	}

	/**
	 * @param Crawler $crawler
	 * @return string
	 */
	public function getProductDescription(Crawler $crawler)
	{
		return $crawler->filter('#seodescription')->first()->text();
	}

	/**
	 * @param Product $product
	 * @param Crawler $crawler
	 * @return string
	 */
	public function getBrand(Product $product, Crawler $crawler)
	{
		$partnumber = $crawler->filter('#modelInfo .partNumber')->first()->text();
		$partnumber = str_replace('Part Number: ', '', $partnumber);
		$brand = trim(str_replace($partnumber, '', $product->name));

		return $brand;
	}

}
