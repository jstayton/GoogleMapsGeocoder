<?php
namespace GoogleMaps\Geocoder\Tests;

use PHPUnit_Framework_TestCase;
use GoogleMaps\Geocoder\Geocoder;

class GeocoderTest extends PHPUnit_Framework_TestCase
{
    public function testGeocode()
    {
        $address = "2707 Congress St., San Diego, CA 92110";

        $Geocoder = new Geocoder();
        $Geocoder->setAddress($address);
        $response = $Geocoder->geocode();

        $this->assertEquals('OK', $response['status']);
        $this->assertArrayHasKey('address_components', $response['results'][0]);
        $this->assertArrayHasKey('formatted_address', $response['results'][0]);
        $this->assertArrayHasKey('geometry', $response['results'][0]);
        $this->assertArrayHasKey('types', $response['results'][0]);
    }
}