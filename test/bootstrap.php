<?php
date_default_timezone_set('UTC');

$vendor = realpath(__DIR__ . '/../vendor');

if (file_exists($vendor . "/autoload.php")) {
    require $vendor . "/autoload.php";
} else {
    $vendor = realpath(__DIR__ . '/../../../');
    if (file_exists($vendor . "/autoload.php")) {
        require $vendor . "/autoload.php";
    } else {
        throw new Exception("Unable to load dependencies");
    }
}

spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    $prefix = 'GoogleMaps\\Geocoder\\Tests';
    if (strpos($class, $prefix) === 0) {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $class = 'Geocoder/' . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR . '_includes' . substr($class, strlen($prefix));
        $file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require_once __DIR__ . "/../src/Geocoder/Autoloader.php";
GoogleMaps\Geocoder\Autoloader::register();