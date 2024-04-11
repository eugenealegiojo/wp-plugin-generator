<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit87abe7b7fe5188bca4e917bb878ed29b
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EA\\WPPLuginGenerator\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EA\\WPPLuginGenerator\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit87abe7b7fe5188bca4e917bb878ed29b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit87abe7b7fe5188bca4e917bb878ed29b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit87abe7b7fe5188bca4e917bb878ed29b::$classMap;

        }, null, ClassLoader::class);
    }
}