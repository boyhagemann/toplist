<?php

use Illuminate\Database\Seeder;
use App\Selection;

class SelectionTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('selections')->truncate();
		DB::table('product_selection')->truncate();

		Artisan::call('generate:selection', ['prefix' => 'Beste', 'suffix' => 'van 2015', '--tag' => ['Samsung', 'tv']]);

	}

}
