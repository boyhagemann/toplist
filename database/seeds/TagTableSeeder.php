<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('tags')->truncate();

		Tag::create([
			'id' => 1,
			'name' => 'Phillips',
			'slug' => 'phillips',
		]);

		Tag::create([
			'id' => 2,
			'name' => 'Samsung',
			'slug' => 'samsung',
		]);

		Tag::create([
			'id' => 3,
			'name' => 'curved',
			'slug' => 'curved',
		]);

		Tag::create([
			'id' => 4,
			'name' => 'TV',
			'slug' => 'tv',
		]);

		Tag::create([
			'id' => 5,
			'name' => '37 inch',
			'slug' => '37-inch',
		]);

		Tag::create([
			'id' => 6,
			'name' => '46 inch',
			'slug' => '46-inch',
		]);
	}

}
