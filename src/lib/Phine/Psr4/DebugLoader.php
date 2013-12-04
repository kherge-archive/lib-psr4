<?php

namespace Phine\Psr4;

use RuntimeException;

/**
 * Implements the PSR-4 standard with support logging diagnostic information.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class DebugLoader extends Loader
{
    /**
     * @override
     */
    public function load($class)
    {
        if (null === ($file = $this->getPath($class))) {
            throw new RuntimeException(
                "The class file for \"$class\" does not exist."
            );
        }

        require $file;

        if (!class_exists($class, false)) {
            throw new RuntimeException(
                "The class \"$class\" does not exist in the file \"$file\"."
            );
        }
    }
}
