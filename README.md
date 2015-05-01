Caffeinated Widgets
===================

Abstraction layer between Blade/Twig to allow the means to "plug in" data through a consistent interface.

Quick Usage
-----------
Build your widget:
**app\Widgets\YourWidget.php**
```php
<?php
namespace App\Widgets;

class YourWidget
{
	public function run()
	{
		return 'Whatever you want';
	}
}
```

Register your plugin, ideally within a service provider:

```php
Widget::register('widget_name', 'App\Widgets\YourWidget');
```

Now simply use it!

```php
{{ widget_name() }}  // Echo's "whatever you want" in this case
```
