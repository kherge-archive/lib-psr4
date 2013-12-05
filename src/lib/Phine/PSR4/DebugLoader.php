<?php

namespace Phine\PSR4;

use RuntimeException;

/**
 * Implements the PSR-4 standard with error checking.
 *
 * The `DebugLoader` class is an extension of the `Loader` class. The `load()`
 * method has been modified so that exceptions are thrown if specific scenarios
 * are encountered:
 *
 * - A class does not exist.
 * - A class file exists, but the class is not in it.
 *
 * It is important to note that this class violates one of the conditions for
 * a class autoloader to be PSR-4 conformant: "Autoloader implementations MUST
 * NOT throw exceptions, [...]". It is not advised that this class be used in
 * production, but only for the purposes of debugging classes that fail to
 * load.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * @api
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
