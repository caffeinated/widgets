<?php
namespace Caffeinated\Widgets;

abstract class AbstractWidget
{
	/**
	 * Constructor.
	 *
	 * @param  mixed  $parameters
	 */
	public function __construct($parameters)
	{
		foreach ($parameters as $property => $value) {
			if (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}

	/**
	 * Widget run method.
	 *
	 * @return  mixed
	 */
	abstract public function run();
}