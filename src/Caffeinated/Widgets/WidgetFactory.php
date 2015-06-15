<?php
namespace Caffeinated\Widgets;

use Caffeinated\Widgets\Exceptions\InvalidWidgetException;

class WidgetFactory
{
	/**
	 * Widget Config options
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Create a new factory instance.
	 *
	 * @param  array  $config
	 * @return void
	 */
	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
	 * Magic method to call widget instances.
	 *
	 * @param  string  $signature
	 * @param  array   $arguments
	 * @return mixed
	 */
	public function __call($signature, $arguments)
	{
		$arguments   = $this->flattenArguments($arguments);
		$className   = studly_case($signature);
		$namespace   = $this->determineNamespace($className);
		$widgetClass = $namespace.'\\'.$className;
		$widget      = new $widgetClass($arguments);

		if ($widget instanceof Widget === false) {
			throw new InvalidWidgetException;
		}

		return $widget->handle();
	}

	/**
	 * Determine the full namespace for the given class.
	 *
	 * @param  string  $className
	 * @return string
	 */
	protected function determineNamespace($className)
	{
		return 'App\\Widgets';
	}

	protected function flattenArguments(array $arguments)
	{
		$flattened = array();

		array_walk_recursive($arguments, function($value, $key) use (&$flattened) {
			$flattened[$key] = $value;
		});

		return $flattened;
	}
}
