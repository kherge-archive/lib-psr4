<?php

namespace Phine\Psr\Tests;

use Phine\Psr4\ApcLoader;
use Phine\Test\Method;
use Phine\Test\Property;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Tests the methods in the `ApcLoader` class.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ApcLoaderTest extends TestCase
{
    /**
     * The path to example classes.
     *
     * @var string
     */
    private $path;

    /**
     * The test cache key prefix.
     *
     * @var string
     */
    private $prefix;

    /**
     * The loader instance being tested.
     *
     * @var ApcLoader
     */
    private $loader;

    /**
     * Make sure we can set the cache key prefix.
     */
    public function testConstruct()
    {
        $this->assertRegExp(
            '/^test-(\d+)-$/',
            Property::get($this->loader, 'key'),
            'The cache key prefix should be set.'
        );
    }

    /**
     * Make sure that the APC cache is used for finding paths.
     */
    public function testGetPath()
    {
        $class = 'Phine\\Psr4\\Example\\Example';

        apc_delete($this->prefix . $class);

        $expected = sprintf(
            '%sExample%sExample.php',
            $this->path,
            DIRECTORY_SEPARATOR
        );

        // inject a map
        Property::set(
            $this->loader,
            'map',
            array(
                'Phine\\Psr4' => array(
                    '/does/not/exist',
                    $this->path
                )
            )
        );

        $this->assertEquals(
            $expected,
            Method::invoke($this->loader, 'getPath', $class),
            'The class file path should be returned.'
        );

        $this->assertEquals(
            $expected,
            apc_fetch($this->prefix . $class),
            'The class file path should be cached in APC.'
        );

        $this->assertNull(
            Method::invoke($this->loader, 'getPath', 'Not\\Exist'),
            'No class file path should be returned for a class that does not exist.'
        );
    }

    /**
     * Creates a new instance of the loader for testing.
     */
    protected function setUp()
    {
        $this->path = realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR;
        $this->prefix = 'test-' . rand() . '-';
        $this->loader = new ApcLoader($this->prefix);
    }
}
