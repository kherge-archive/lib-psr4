<?php

namespace Phine\Psr4;

/**
 * Implements the PSR-4 standard with support for APC caching.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ApcLoader extends Loader
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
