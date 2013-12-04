<?php

namespace Phine\Psr\Tests;

use Phine\Psr4\DebugLoader;
use Phine\Test\Method;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Tests the methods in the `DebugLoader` class.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class DebugLoaderTest extends TestCase
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
     * @var DebugLoader
     */
    private $loader;

    /**
     * Make sure we can still load classes, despite customizations.
     */
    public function testLoad()
    {
        $class = 'Phine\\Psr4\\Example\\DebugGood';

        $this->loader->load($class);

        $this->assertTrue(
            class_exists($class, false),
            'The class should have been loaded.'
        );
    }

    /**
     * Make sure exceptions are thrown for class files that do not exist.
     */
    public function testLoadNoFile()
    {
        $this->setExpectedException(
            'RuntimeException',
            'The class file for "Not\\Exist" does not exist.'
        );

        $this->loader->load('Not\\Exist');
    }

    /**
     * Make sure exceptions are thrown for classes that do not exist.
     */
    public function testLoadNoClass()
    {
        $class = 'Phine\\Psr4\\Example\\Debug';
        $file = Method::invoke($this->loader, 'getPath', $class);

        $this->setExpectedException(
            'RuntimeException',
            "The class \"$class\" does not exist in the file \"$file\"."
        );

        $this->loader->load($class);
    }

    /**
     * Creates a new instance of the loader for testing.
     */
    protected function setUp()
    {
        $this->path = realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR;

        $this->loader = new DebugLoader();
        $this->loader->map('Phine\\Psr4', $this->path);
    }
}
