PSR-4
=====

[![Build Status][]](https://travis-ci.org/phine/lib-psr4)
[![Coverage Status][]](https://coveralls.io/r/phine/lib-psr4)
[![Latest Stable Version][]](https://packagist.org/packages/phine/psr4)
[![Total Downloads][]](https://packagist.org/packages/phine/psr4)

A simple implementation of the [PSR-4][] standard.

Usage
-----

First you will need to create a new instance of the autoloader.

```php
// for development
$psr4 = new Phine\Psr4\Loader();

// for production
$psr4 = new Phine\Psr4\ApcLoader('key-prefix');

// for testing/development
$psr4 = new Phine\Psr4\DebugLoader();
```

You can then register the necessary mapping paths.

```php
// one path at a time
$psr4->map('Example\\Namespace', '/path/to/dir');

// multiple paths at a time
$psr4->map(
    'Example\\Namespace',
    array(
        '/path/to/dir',
        '/path/to/dir',
        '/path/to/dir',
    )
);
```

> Note that multiple calls to `map()` for the same namespace will simply
> append to the existing list of mapping paths.

Finally, register the autoloader.

```php
$psr4->register();
```

> Note that you can register multiple autoloaders if necessary.

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
