<?php
namespace Caffeinated\Widgets;

use Illuminate\Foundation\Application;
use Caffeinated\Widgets\Exceptions\InvalidWidgetException;

class WidgetFactory
{
	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * @var array
	 */
	protected $namespace = array();

	/**
	 * Create a new factory instance.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Register a new namespace location where widgets may be found.
	 *
	 * @param  string  $namespace
	 */
	public function register($namespace)
	{
		if (! array_key_exists($namespace, $this->namespace)) {
			$this->namespace[] = $namespace;
		}
	}

	/**
	 * Determine the full namespace for the given class.
	 *
	 * @param  string  $className
	 * @return string
	 */
	protected function determineNamespace($className)
	{
		if (count($this->namespace) > 0) {
			foreach ($this->namespace as $namespace) {
				if (class_exists($namespace.'\\'.$className)) {
					return $namespace;
				}
			}
		}

		return 'App\\Widgets';
	}

	/**
	 * Flattens the given array.
	 *
	 * @param  array  $parameters
	 * @return array
	 */
	protected function flattenParameters(array $parameters)
	{
		$flattened = array();

		foreach($parameters as $parameter) {
			array_walk($parameter, function($value, $key) use (&$flattened) {
				$flattened[$key] = $value;
			});
		}

		return $flattened;
	}

	/**
	 * Magic method to call widget instances.
	 *
	 * @param  string  $signature
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($signature, $parameters)
	{
		$parameters  = $this->flattenParameters($parameters);
		$className   = studly_case($signature);
		$namespace   = $this->determineNamespace($className);
		$widgetClass = $namespace.'\\'.$className;
		$widget      = $this->app->make($widgetClass);

		if ($widget instanceof Widget === false) {
			throw new InvalidWidgetException;
		}

		$widget->registerParameters($parameters);

		return $widget->handle();
	}
}
