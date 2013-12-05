<?php

namespace Phine\PSR4;

/**
 * Implements the PSR-4 standard with APC caching.
 *
 * The `APCLoader` class is an extension of the `Loader` class. It has been
 * modified so that APC is used to cache paths found for specific class names.
 * Note that if a path changes, APC will not be updated. You will need to flush
 * the cache use or use a versioning scheme for the cache key prefix.
 *
 * Create a new loader is slightly different:
 *
 *     use Phine\PSR4\APCLoader;
 *
 *     // create a new loader using a cache key prefix
 *     $loader = new APCLoader('cache-key-prefix');
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @api
 */
class APCLoader extends Loader
{
    /**
     * The APC fetch cache.
     *
     * @var array
     */
    private $cache = array();

    /**
     * The cache key prefix.
     *
     * @var string
     */
    private $key;

    /**
     * Sets the cache key prefix.
     *
     * @param string $key The cache key prefix.
     *
     * @api
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @override
     */
    protected function getPath($class)
    {
        if (!isset($this->cache[$class])) {
            $key = $this->key . $class;

            if (false === ($path = apc_fetch($key))) {
                if (null === ($path = parent::getPath($class))) {
                    $path = false;
                }
            }

            apc_store($key, $path);

            $this->cache[$class] = $path;
        }

        return $this->cache[$class] ?: null;
    }
}
