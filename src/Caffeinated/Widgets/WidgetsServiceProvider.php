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

	protected function registerServices()
	{
		$this->app->bindShared('widgets', function($app) {
			$blade = $app['view']->getEngineResolver()->resolve('blade')->getCompiler();

			return new Widgets($app, $blade);
		});

		$this->app->booting(function($app) {
			$file = app_path('widgets.php');

			if (file_exists($file)) include $file;
		});
	}

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