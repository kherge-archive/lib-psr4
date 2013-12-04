<?php

namespace Phine\Psr\Tests;

use Phine\PSR4\Loader;
use Phine\Test\Method;
use Phine\Test\Property;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Tests the methods in the `PSR4` class.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class LoaderTest extends TestCase
{
    /**
     * The path to example classes.
     *
     * @var string
     */
    private $path;

    /**
     * The loader instance being tested.
     *
     * @var Loader
     */
    private $loader;

    /**
     * Make sure we can set the starting map.
     */
    public function testConstruct()
    {
        $map = array(__NAMESPACE__ => __DIR__);
        $psr4 = new Loader($map);

        $this->assertEquals(
            array(
                __NAMESPACE__ => array(__DIR__ . DIRECTORY_SEPARATOR)
            ),
            Property::get($psr4, 'map'),
            'The map should be set through the constructor.'
        );
    }

    /**
     * Make sure we can load classes.
     */
    public function testLoad()
    {
        // inject a bunch of mappings
        Property::set(
            $this->loader,
            'map',
            array(
                'Bad\\Example' => array(__DIR__ .  DIRECTORY_SEPARATOR),
                'Phine\\PSR4' => array($this->path)
            )
        );

        $this->loader->load('Phine\\PSR4\\Example\\Example');

        $this->assertTrue(
            class_exists('Phine\\PSR4\\Example\\Example'),
            'The example class should have been loaded.'
        );
    }

    /**
     * Make sure we can map namespace prefixes.
     */
    public function testMap()
    {
        $this->assertSame(
            $this->loader,
            $this->loader->map('Example\\Namespace', __DIR__),
            'The instance should be returned to allow call chains.'
        );

        $this->loader->map('Example\\Namespace', '/does/not/exist');

        $this->assertEquals(
            array(
                'Example\\Namespace' => array(
                    __DIR__ . DIRECTORY_SEPARATOR,
                    '/does/not/exist' . DIRECTORY_SEPARATOR
                )
            ),
            Property::get($this->loader, 'map'),
            'The modified base directory path should be added.'
        );
    }

    /**
     * Make sure we can register the autoloader.
     */
    public function testRegister()
    {
        // inject a map
        Property::set(
            $this->loader,
            'map',
            array(
                'Phine\\PSR4' => array($this->path)
            )
        );

        $this->loader->register();

        $this->assertTrue(
            class_exists('Phine\\PSR4\\Example\\Another'),
            'The autoloader should have been registered.'
        );

        // undo registration for next test
        spl_autoload_unregister(array($this->loader, 'load'));
    }

    /**
     * Make sure we can unregister the autoloader.
     *
     * @depends testRegister
     */
    public function testUnregister()
    {
        // inject a map
        Property::set(
            $this->loader,
            'map',
            array(
                'Phine\\PSR4' => array($this->path)
            )
        );

        $this->loader->register();
        $this->loader->unregister();

        $this->assertFalse(
            class_exists('Phine\\PSR4\\Example\\Never'),
            'The autoloader should have been unregistered.'
        );
    }

    /**
     * Make sure we can generate paths for valid mappings.
     */
    public function testGetPath()
    {
        // inject a map
        Property::set(
            $this->loader,
            'map',
            array(
                'Phine\\PSR4' => array(
                    '/does/not/exist',
                    $this->path
                )
            )
        );

        $class = 'Phine\\PSR4\\Example\\Example';

        $this->assertEquals(
            sprintf(
                '%sExample%sExample.php',
                $this->path,
                DIRECTORY_SEPARATOR
            ),
            Method::invoke($this->loader, 'getPath', $class),
            'The file path should be returned for the class.'
        );

        $this->assertNull(
            Method::invoke($this->loader, 'getPath', 'Not\\Exist'),
            'No path should be returned for classes that do not exist.'
        );
    }

    /**
     * Creates a new instance of the loader for testing.
     */
    protected function setUp()
    {
        $this->path = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;
        $this->loader = new Loader();
    }
}
