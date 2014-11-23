<?php


namespace Mascame\ArtificerPaginationPlugin;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class PublishCommand extends Command {

    protected $package = 'mascame/artificer-pagination-plugin';

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'artificer-pagination-plugin:publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish assets and config.';

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
		if (file_exists(base_path() . '/workbench/'.$this->package.'/')) {
			$this->call('config:publish', array('--path' => "workbench/".$this->package."/src/config", 'package' => $this->package));
			$this->call('asset:publish', array('--bench' => $this->package));
		} else {
			$this->call('config:publish', array('package' => $this->package));
			$this->call('asset:publish', array('package' => $this->package));
		}

		$this->info("Done.");
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL,
				'An example option.', null)
		);
	}
}

?>