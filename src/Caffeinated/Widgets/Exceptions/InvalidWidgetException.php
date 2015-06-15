<?php
namespace Caffeinated\Widgets\Exceptions;

use Exception;

class InvalidWidgetException extends Exception
{
	protected $message = 'Widget class must be an instance of Caffeinated\Widget';
}
