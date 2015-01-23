<?php

use Illuminate\Database\Seeder;
use App\Vendor;

class VendorTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('vendors')->truncate();

		Vendor::create([
			'id' => 1,
			'name' => 'MyShop.com',
			'slug' => 'myshop_com',
			'description' => 'Some description about the shop.',
			'image' => 'path/to/image',
			'url' => 'http://my-shop.com',
		]);

		Vendor::create([
			'id' => 2,
			'name' => 'Bol.com',
			'slug' => 'bol_com',
			'description' => 'Some description about the shop.',
			'image' => 'path/to/image',
			'url' => 'http://bol.com',
		]);
	}

}
