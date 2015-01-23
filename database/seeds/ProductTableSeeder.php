<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('products')->truncate();

		$product = Product::create([
			'id' => 1,
			'name' => 'Phillips 3408EM',
			'slug' => 'phillips-3408em',
			'description' => 'Some description about the product.',
			'image' => 'path/to/image',
		]);
		$product->tags()->sync([1,4,5]);

		$product = Product::create([
			'id' => 2,
			'name' => 'Samsung 123af',
			'slug' => 'samsung-123af',
			'description' => 'Some description about the product.',
			'image' => 'path/to/image',
		]);
		$product->tags()->sync([2,3,4,6]);
	}

}
