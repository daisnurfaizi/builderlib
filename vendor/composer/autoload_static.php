<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit587b24c9534db670e0e66408c91c7b72
{
    public static $prefixLengthsPsr4 = array (
        'd' => 
        array (
            'daisnurfaizi\\bubuilder\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'daisnurfaizi\\bubuilder\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit587b24c9534db670e0e66408c91c7b72::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit587b24c9534db670e0e66408c91c7b72::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit587b24c9534db670e0e66408c91c7b72::$classMap;

        }, null, ClassLoader::class);
    }
}
