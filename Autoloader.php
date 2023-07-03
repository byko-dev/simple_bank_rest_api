<?php

class Autoloader
{
    public static function loadClass($className)
    {
        $classFile = __DIR__ . '/' . $className . '.php';
        if (file_exists($classFile)) {
            require_once $classFile;
        }
    }
}
