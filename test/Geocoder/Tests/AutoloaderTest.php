<?php
namespace GoogleMaps\Geocoder\Tests;

use PHPUnit_Framework_TestCase;
use GoogleMaps\Geocoder\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals($declaredCount, count(get_declared_classes()), 'GoogleMaps\\Geocoder\\Autoloader::autoload() is trying to load classes outside of the GoogleMaps\\Geocoder namespace');
        Autoloader::autoload('GoogleMaps\Geocoder\Geocoder');
        $this->assertTrue(in_array('GoogleMaps\Geocoder\Geocoder', get_declared_classes()), 'GoogleMaps\\Geocoder\\Autoloader::autoload() failed to autoload the GoogleMaps\\Geocoder\\Geocoder class');
    }
}