<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite8899a89060eb20e79f2f3c11b05cfb3
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WORDMAGIC\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WORDMAGIC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
            1 => __DIR__ . '/../..' . '/admin',
        ),
    );

    public static $classMap = array (
        'WORDMAGIC\\WMAI_Admin' => __DIR__ . '/../..' . '/classes/admin.php',
        'WORDMAGIC\\WMAI_Engine' => __DIR__ . '/../..' . '/classes/init.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite8899a89060eb20e79f2f3c11b05cfb3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite8899a89060eb20e79f2f3c11b05cfb3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite8899a89060eb20e79f2f3c11b05cfb3::$classMap;

        }, null, ClassLoader::class);
    }
}
