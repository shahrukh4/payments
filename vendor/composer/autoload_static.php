<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita17e47150c20b56269c75575e7641857
{
    public static $files = array (
        '63a7883a66ec5bc4563c71529680d33c' => __DIR__ . '/../..' . '/helpers/ErrorHelper.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Shahrukh\\Payments\\Tests\\' => 24,
            'Shahrukh\\Payments\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Shahrukh\\Payments\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Shahrukh\\Payments\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita17e47150c20b56269c75575e7641857::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita17e47150c20b56269c75575e7641857::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}