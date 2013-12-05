PSR-4
=====

[![Build Status][]](https://travis-ci.org/phine/lib-psr4)
[![Coverage Status][]](https://coveralls.io/r/phine/lib-psr4)
[![Latest Stable Version][]](https://packagist.org/packages/phine/psr4)
[![Total Downloads][]](https://packagist.org/packages/phine/psr4)

A simple implementation of the [PSR-4][] standard.

Summary
-------

The PSR-4 library provides a simple implementation of the [PSR-4][] standard.
Also bundled with the library are classes for debugging the class autoloading
process, as well as caching for improved performance.

Usage
-----

You may want to start by using the standard loading class:

```php
use Phine\PSR4\Loader();

$loader = new Loader();
```

With a new loader available, you will then want to map your namespace prefixes
to their base directory paths.

```php
$loader->map('Namespace\\Prefix', '/base/directory/path');
```

While you may only register one namespace prefix at a time, you may specify
one or more directory paths for each call to `map()`. To pass more than one
directory path, you may simply pass an array of directory paths.

You may also chain calls to `map()` together:

```php
$loader
    ->map('One\\Prefix', '/one/path')
    ->map('Two\\Prefix', '/two/path')
    ->map('Three\\Prefix', '/three/path');
```

When you are ready to use the loader, you will then need to register it:

```php
$loader->register();
```

> You may register the loader at any point, such as before you begin mapping
> namespace prefixes to paths. Any namespace prefixes mapped after the loader
> is registered will be used by the loader as well.

You can now autoload classes for the namespace prefixes you registered:

```php
$myInstance = new One\Prefix\MyClass();
```

Debugging
---------

If you find that you are having problems autoloading classes, you may want to
use the `DebugLoader` class. This class will throw an exception when either the
file for the class could not be found, or if the class did not actually exist
in the file that was loaded.

Using the debugging loader is as simple as using the standard loader:

```php
use Phine\PSR4\DebugLoader;

$loader = new DebugLoader();
```

Caching
-------

When you are ready to use your project in a production environment, you may
want to use a version of the loader that supports caching. Currently, only
APC is supported, but additional support can be bundled with enough demand.
If you need to support a caching library, you will want to mimic the code
used for the bundled caching classes.

### APC

You will need to create a new instance of `APCLoader` to use APC caching:

```php
use Phine\PSR4\APCLoader;

$loader = new APCLoader($cacheKeyPrefix);
```

As part of the constructor, you need to specify a cache key prefix that will
be used when storing class file paths in APC. The prefix can be whatever you
need it to be.

Using the prefix `PSR4-Classes-` and loading the class `My\Example` will
generate the cache key `PSR4-Classes-My\Example` for the class's file path.

> It may be useful to know that once a class file path is cached, it will not
> expire or be refreshed if it no longer exists. You will need to flush the
> cache or using a versioning scheme for the prefix.

Requirement
-----------

- PHP >= 5.3.3

Installation
------------

Via [Composer][]:

    $ composer require "phine/psr4=~1.0"

License
-------

This library is available under the [MIT license](LICENSE).

[Build Status]: https://travis-ci.org/phine/lib-psr4.png?branch=master
[Coverage Status]: https://coveralls.io/repos/phine/lib-psr4/badge.png
[Latest Stable Version]: https://poser.pugx.org/phine/psr4/v/stable.png
[Total Downloads]: https://poser.pugx.org/phine/psr4/downloads.png
[PSR-4]: http://www.php-fig.org/psr/psr-4/
[Composer]: http://getcomposer.org/
