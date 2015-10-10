<?php namespace Mascame\ArtificerPaginationPlugin;

use Illuminate\Support\ServiceProvider;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Plugin\PluginManager;
use Mascame\ArtificerPaginationPlugin;
use Route;
use App;

class ArtificerPaginationPluginServiceProvider extends ServiceProvider {

    protected $name = 'artificer-pagination-plugin';

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
        $this->addPublishCommand();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        if (Artificer::isBooted()) {
            $name = $this->name;

            App::bind($this->name, function () use ($name) {
                return new PaginationPlugin($name);
            });

            $pluginManager = new PluginManager($this->name);

            $pluginManager->addRoutes(function () {
                Route::group(array('prefix' => 'model'), function () {
                    Route::get('{slug}', array('as' => 'admin.model.all', 'uses' => '\Mascame\ArtificerPaginationPlugin\PaginationController@all'));
                    Route::post('{slug}/filter', array('as' => 'admin.model.filter', 'uses' => '\Mascame\ArtificerPaginationPlugin\PaginationController@filter'));
                    Route::post('{slug}/pagination', array('as' => 'admin.model.pagination', 'uses' => '\Mascame\ArtificerPaginationPlugin\PaginationController@paginate'));
                });
            });
        }
	}

    private function addPublishCommand()
    {
        $command_key = $this->name . '-command-publish';

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
