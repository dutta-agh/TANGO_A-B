<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit7d2c6d2af6c431b6bf6c5ce31ddd9eb7
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit7d2c6d2af6c431b6bf6c5ce31ddd9eb7', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit7d2c6d2af6c431b6bf6c5ce31ddd9eb7', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit7d2c6d2af6c431b6bf6c5ce31ddd9eb7::getInitializer($loader));

        $loader->register(true);

        $includeFiles = \Composer\Autoload\ComposerStaticInit7d2c6d2af6c431b6bf6c5ce31ddd9eb7::$files;
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire7d2c6d2af6c431b6bf6c5ce31ddd9eb7($fileIdentifier, $file);
        }

        return $loader;
    }
}

/**
 * @param string $fileIdentifier
 * @param string $file
 * @return void
 */
function composerRequire7d2c6d2af6c431b6bf6c5ce31ddd9eb7($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

        require $file;
    }
}
