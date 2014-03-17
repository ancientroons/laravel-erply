<?php 
namespace Mochaka\Erply;

use Config;
use Illuminate\Support\ServiceProvider;

class ErplyServiceProvider extends ServiceProvider {

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
		$this->package('mochaka/erply');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Erply', 'Mochaka\Erply\Facades\Erply');
		});

        $this->app['erply'] = $this->app->share(function($app)
        {
            return new Erply(Config::get('erply::clientcode'), Config::get('erply::username'), Config::get('erply::password'));
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('erply');
	}

}

