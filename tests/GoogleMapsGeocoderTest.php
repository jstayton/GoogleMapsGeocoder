<?php

class GoogleMapsGeocoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GoogleMapsGeocoder
     */
    protected $object;

    public function setUp()
    {
        $this->object = new GoogleMapsGeocoder();
    }

    public function testGetSetFormat()
    {
        $this->object->setFormat("json");
        $this->assertEquals(
           "json",
            $this->object->getFormat()
        );
    }

    public function testGetSetLatitude()
    {
        $this->object->setLatitude(37.92);
        $this->assertEquals(
            37.92,
            $this->object->getLatitude()
        );
    }
}