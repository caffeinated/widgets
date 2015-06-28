<?php
namespace Caffeinated\Widgets;

use Illuminate\Support\ServiceProvider;

class WidgetsServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerServices();

		$this->configureSapling();
	}

	/**
	 * Register the package services.
	 *
	 * @return void
	 */
	protected function registerServices()
	{
		$this->app->bindShared('widgets', function($app) {
			return new WidgetFactory($app['app']);
		});
	}

	/**
	 * Configure Sapling
	 *
	 * Configures Sapling (Twig) extensions if the Sapling package
	 * is found to be installed.
	 *
	 * @return void
	 */
	protected function configureSapling()
	{
		if ($this->app['config']->has('sapling')) {
			$this->app['config']->push(
				'sapling.extensions',
				'Caffeinated\Widgets\Twig\Extensions\Widget'
			);
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['widgets'];
	}
}
