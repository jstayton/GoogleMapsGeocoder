<?php

@include_once realpath(__DIR__ . '/vendor') . '/' . 'autoload.php';

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$loader = function($class) {
    $path = realpath(__DIR__ . '/lib');
    
    @include_once $path.'/'.str_replace("\\", "/", $class).'.php';
};

spl_autoload_register($loader);
