<?php
namespace Caffeinated\Widgets;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

class Widgets
{
	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var BladeCompiler
	 */
	protected $blade;

	/**
	 * @var array
	 */
	protected $groups = array();

	/**
	 * @var array
	 */
	protected $widgets = array();

	/**
	 * Constructor method.
	 *
	 * @param  Container      $container
	 * @param  BladeCompiler  $blade
	 */
	public function __construct(Container $container, BladeCompiler $blade)
	{
		$this->container = $container;
		$this->blade     = $blade;
	}

	/**
	 * Register a new widget.
	 *
	 * @param   string           $name
	 * @param   string|callable  $callback
	 * @return  void
	 */
	public function register($name, $callback)
	{
		$this->widgets[$name] = $callback;

		$this->registerTag($name, 'Widget::');
	}

	/**
	 * Register Blade syntax for a specific widget.
	 *
	 * @param   string  $method
	 * @param   string  $namespace
	 * @return  void
	 */
	protected function registerTag($method, $namespace = '')
	{
		$this->blade->extend(function($view, $compiler) use ($method, $namespace) {
			$pattern = $compiler->createMatcher('widget_'.$method);

			$replace = '$1<?php echo '.$namespace.$method.'$2; ?>';

			return preg_replace($pattern, $replace, $view);
		});
	}

	/**
	 * Determine whether a widget exists or not.
	 *
	 * @param   string  $name
	 * @return  bool
	 */
	public function exists($name)
	{
		return array_key_exists($name, $this->widgets);
	}

	/**
	 * Call a specific widget.
	 *
	 * @param   string  $name
	 * @param   array   $parameters
	 * @return  mixed
	 */
	public function call($name, array $parameters = array())
	{
		if ($this->groupExists($name)) return $this->callGroup($name, $parameters);

		if ($this->exists($name)) {
			$callback = $this->widgets[$name];

			return $this->getCallback($callback, $parameters);
		}

		return null;
	}

	/**
	 * Get a callback from a specific widget.
	 *
	 * @param   mixed  $callback
	 * @param   array  $parameters
	 * @return  mixed
	 */
	protected function getCallback($callback, array $parameters)
	{
		if ($callback instanceof Closure) {
			return $this->createCallableCallback($callback, $parameters);
		} elseif (is_string($callback)) {
			return $this->createStringCallback($callback, $parameters);
		} else {
			return null;
		}
	}

	/**
	 * Get a result from a string callback.
	 *
	 * @param   string  $callback
	 * @param   array   $parameters
	 * @return  mixed
	 */
	protected function createStringCallback($callback, array $parameters)
	{
		if (function_exists($callback)) {
			return $this->createCallableCallback($callback, $parameters);
		} else {
			return $this->createClassCallback($callback, $parameters);
		}
	}

	/**
	 * Get a result from a callable callback.
	 *
	 * @param   callable  $callback
	 * @param   array     $parameters
	 * @return  mixed
	 */
	protected function createCallableCallback($callback, array $parameters)
	{
		return call_user_func_array($callback, $parameters);
	}

	/**
	 * Get a result from a class callback.
	 *
	 * @param   callable  $callback
	 * @param   array     $parameters
	 * @return  mixed
	 */
	protected function createClassCallback($callback, array $parameters)
	{
		list($className, $method) = Str::parseCallback($callback, 'register');

		$instance = $this->container->make($className);

		$callable = array($instance, $method);

		return $this->createCallableCallback($callable, $parameters);
	}

	/**
	 * Create a new widget group.
	 *
	 * @param   string  $name
	 * @param   array   $widgets
	 * @return  void
	 */
	public function group($name, array $widgets)
	{
		$this->groups[$name] = $widgets;

		$this->registerTag($name, 'Widget::');
	}

	/**
	 * Determine whether a group of widgets exists or not
	 *
	 * @param   string  $name
	 * @return  bool
	 */
	public function groupExists($name)
	{
		return array_key_exists($name, $this->groups);
	}

	/**
	 * Call a specific group of widgets.
	 *
	 * @param   string  $name
	 * @param   array   $parameters
	 * @return  null|string
	 */
	public function callGroup($name, $parameters = array())
	{
		if (! $this->groupExists($name)) return null;

		$result = '';

		foreach ($this->groups[$name] as $key => $widget) {
			$result .= $this->call($widget, array_get($parameters, $key, array()));
		}

		return $result;
	}

	/**
	 * Handle magic __call methods against the class.
	 *
	 * @param   string  $method
	 * @param   array   $parameters
	 * @return  mixed
	 */
	public function __call($method, $parameters = array())
	{
		return $this->call($method, $parameters);
	}
}