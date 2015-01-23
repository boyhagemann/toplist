<?php namespace App\Console\Commands\Generate;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Eloquent\Collection;

class User extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generate:user';

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
		$count = $this->option('count') ?: 5;
		$this->info(sprintf('Start generating %d users', $count));
		$users = UserRepository::fakeMultiple($count);

		foreach($users as $user) {
			$this->info(sprintf('Generated "%s (%s)"', $user->name, $user->email));
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
//			['prefix', InputArgument::REQUIRED, 'Title prefix'],
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
			['count', null, InputOption::VALUE_REQUIRED, 'The number of users to generate (defaults to 5)', 5],
		];
	}

}
