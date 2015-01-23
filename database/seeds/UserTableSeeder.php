<?php

use Illuminate\Database\Seeder;
use App\Repositories\UserRepository;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->truncate();

		UserRepository::fakeMultiple(20);
	}

}
