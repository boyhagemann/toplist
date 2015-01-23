<?php

use Illuminate\Database\Seeder;
use App\Review;

class ReviewTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('reviews')->truncate();

		Review::create([
			'id' => 1,
			'product_id' => 1,
			'user_id' => 1,
 			'body' => 'Review about the product.',
			'rating' => 7,
		]);

		Review::create([
			'id' => 2,
			'product_id' => 1,
			'user_id' => 2,
			'body' => 'Review about the product 2.',
			'rating' => 8,
		]);
	}

}
