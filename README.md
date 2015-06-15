Caffeinated Widgets
=================
[![Laravel 5.1](https://img.shields.io/badge/Laravel-5.1-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-caffeinated/menus-blue.svg?style=flat-square)](https://github.com/caffeinated/menus)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Easily create reusable widget components to be used throughout your Laravel application. Widgets are very similar to Laravel's view composers, but in more of a dedicated sense.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Documentation
-------------
You will find user friendly and updated documentation in the wiki here: [Caffeinated Widgets Wiki](https://github.com/caffeinated/widgets/wiki)

> **Note:** With the 2.0 release, the Caffeinated Widgets package has seen a complete refactor, in which the development of widgets has been drastically simplified. With this mind, the documentation here on the wiki will only be for v2.x, and as such, for Laravel 5.1.

Quick Installation
------------------
Begin by installing the package through Composer.

```
composer require caffeinated/widgets=~2.0
```

Once this operation is complete, simply add the service provider class and facade alias to your project's `config/app.php` file:

##### Service Provider
```php
Caffeinated\Widgets\WidgetsServiceProvider::class,
```

##### Facade
```php
'Widget' => Caffeinated\Widgets\Facades\Widget::class,
```

And that's it! With your coffee in reach, start building out some awesome widgets!
