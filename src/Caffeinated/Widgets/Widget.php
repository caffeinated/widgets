<?php
namespace Caffeinated\Widgets;

abstract class Widget
{
	/**
	 * Create a new widget instance
	 *
	 * @return null
	 */
	public function __construct($arguments)
	{
		foreach ($arguments as $argument => $value) {
			if (property_exists($this, $argument)) {
				$this->$argument = $value;
			}
		}
	}

	/**
	 * Handle the widget instance.
	 *
	 * @return mixed
	 */
	abstract public function handle();
}
