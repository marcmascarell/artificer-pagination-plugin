<?php namespace Mascame\ArtificerPaginationPlugin;

use Illuminate\Support\ServiceProvider;
use Mascame\Artificer\Plugin\PluginManager;
use Mascame\ArtificerPaginationPlugin;
use Route;
use App;

class ArtificerPaginationPluginServiceProvider extends ServiceProvider {

    protected $package = 'mascame/artificer-pagination-plugin';
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package($this->package);

        $this->addPublishCommand();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $package = $this->package;

        App::bind($this->package, function () use ($package) {
            return new PaginationPlugin($package);
        });

        $pluginManager = new PluginManager($this->package);

        $pluginManager->addRoutes(function () {
            Route::group(array('prefix' => 'model'), function () {
                Route::get('{slug}', array('as' => 'admin.model.all', 'uses' => '\Mascame\ArtificerPaginationPlugin\PaginationController@all'));
                Route::post('{slug}/filter', array('as' => 'admin.model.filter', 'uses' => '\Mascame\ArtificerPaginationPlugin\PaginationController@filter'));
                Route::post('{slug}/pagination', array('as' => 'admin.model.pagination', 'uses' => '\Mascame\ArtificerPaginationPlugin\PaginationController@paginate'));
            });
        });
	}

    private function addPublishCommand()
    {
        $command_key = $this->package . '-command-publish';

        App::bind($command_key, function () {
            return new PublishCommand();
        });

        $this->commands($command_key);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
