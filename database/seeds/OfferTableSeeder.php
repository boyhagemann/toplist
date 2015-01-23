<?php

use Illuminate\Database\Seeder;
use App\Offer;

class OfferTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('offers')->truncate();

		Offer::create([
			'id' => 1,
			'product_id' => 1,
			'vendor_id' => 1,
			'price' => 29995,
			'url' => 'http://my-affiliate-link.com/product-page',
		]);

		Offer::create([
			'id' => 2,
			'product_id' => 1,
			'vendor_id' => 2,
			'price' => 27995,
			'url' => 'http://my-affiliate-link.com/product-page',
		]);
	}

}
