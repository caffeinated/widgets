<?php
namespace Caffeinated\Widgets\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

class Widget extends Twig_Extension
{
	/**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
	public function getName()
	{
		return 'Caffeinated_Widgets_Extension_Widget';
	}

	/**
	 * Returns a list of global functions to add to the existing list.
	 *
	 * @return array An array of global functions
	 */
	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('widget_*', function ($name) {
					$arguments = array_slice(func_get_args(), 1);

					return \Widget::$name($arguments);
				}, ['is_safe' => ['html']]
			),
		];
	}
}
