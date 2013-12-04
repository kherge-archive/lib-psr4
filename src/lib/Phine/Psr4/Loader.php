<?php

namespace Phine\Psr4;

/**
 * Implements the PSR-4 standard for autoloading classes from files.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class Loader
{
    /**
     * The namespace prefix to base directory path map.
     *
     * @var array
     */
    protected $map = array();

    /**
     * Sets the namespace prefixes and their base directory paths.
     *
     * You may provide an array of namespace prefix to base directory paths:
     *
     *     use Phine\Psr\Psr4;
     *
     *     $psr4 = new Psr4(
     *         array(
     *             'One\\Prefix' => '/one/path/to/dir',
     *             'Another\\Prefix' => array(
     *                 '/a/directory/path',
     *                 '/another/directory/path',
     *             ),
     *         )
     *     );
     *
     * @param array $map The namespace prefix to directory path map.
     */
    public function __construct(array $map = array())
    {
        foreach ($map as $prefix => $dirs) {
            $this->map($prefix, $dirs);
        }
    }

    /**
     * Loads a class using one of the mapped namespace prefixes.
     *
     * @param string $class The name of the class.
     */
    public function load($class)
    {
        if (null !== ($path = $this->getPath($class))) {
            require $path;
        }
    }

    /**
     * Maps a namespace prefix to a base directory path.
     *
     * This method is used to map a single namespace to one or more directory
     * paths. The `$paths` argument may be a single directory path (string),
     * or an array of directory paths.
     *
     *     $psr4->map('Example\\Prefix', '/single/dir/path');
     *
     *     $psr4->map(
     *         'Example\\Prefix',
     *         array(
     *             '/one/directory/path',
     *             '/another/directory/path',
     *         )
     *     );
     *
     * You may also chain calls to this method together:
     *
     *     $psr4->map(...)->map(...);
     *
     * @param string       $prefix The namespace prefix.
     * @param array|string $paths  The base directory path(s).
     *
     * @return Loader The instance of this class.
     */
    public function map($prefix, $paths)
    {
        $prefix = ltrim($prefix, '\\');
        $paths = (array) $paths;

        if (!isset($this->map[$prefix])) {
            $this->map[$prefix] = array();
        }

        foreach ($paths as $path) {
            $this->map[$prefix][] = rtrim($path, '\\/') . DIRECTORY_SEPARATOR;
        }

        return $this;
    }

    /**
     * Registers this instance with the SPL autoloading mechanism.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * Unregisters this instance with the SPL autoloading mechanism.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load'));
    }

    /**
     * Returns the file path for the class name.
     *
     * This method will loop through the mapped namespace prefixes to find a
     * directory that contains the class. If the file for the class is found,
     * it will be returned. Otherwise, nothing (`null`) is returned.
     *
     * @param string $class The name of the class.
     *
     * @return string The file path, if any.
     */
    protected function getPath($class)
    {
        foreach ($this->map as $prefix => $paths) {
            if (0 !== strpos($class, $prefix)) {
                continue;
            }

            foreach ($paths as $path) {
                $relative = ltrim(substr($class, strlen($prefix)), '\\');
                $relative = preg_replace('/\\\\+/', DIRECTORY_SEPARATOR, $relative);
                $relative .= '.php';

                if (file_exists($path . $relative)) {
                    return $path . $relative;
                }
            }
        }

        return null;
    }
}
