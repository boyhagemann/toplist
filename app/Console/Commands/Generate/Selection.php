<?php namespace App\Console\Commands\Generate;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\TagRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Eloquent\Collection;

class Selection extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generate:selection';

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
		$tags = TagRepository::save($this->option('tag'));

		$parts[] = $this->argument('prefix');

		foreach($tags as $tag) {
			$parts[] = $tag->name;
		}
		$parts[] = $this->argument('suffix');

		// Create the selection title
		$title = implode(' ', $parts);
		$this->info('Title: ' . $title);

		// Build the unique slug
		$slug = Str::slug($title);
		$this->info('Slug: ' . $slug);

		$selection = \App\Selection::firstOrNew(['slug' => $slug]);
		$selection->title = $title;
		$selection->save();

		// Sync the tags for this selection
		$selection->tags()->sync($tags->lists('id'));
		$this->info('Saved tags: ' . json_encode($tags->lists('name')));

		// Randomly pick a number of items
		$selected = ProductRepository::random($tags, $this->option('pick'));

		foreach($selected  as $i => $product) {
			$this->info(sprintf('%d: %s', $i + 1, $product->name));
		}

		// Sync the picked products to the selection
		$selection->products()->sync($selected->lists('id'));
		$this->info('Added products to selection');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['prefix', InputArgument::REQUIRED, 'Title prefix'],
			['suffix', InputArgument::REQUIRED, 'Title suffix'],
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
			['pick', null, InputOption::VALUE_REQUIRED, 'The number of items in a selection (defaults to 5)', 5],
			['tag', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The tags to base the selection on'],
		];
	}

}
