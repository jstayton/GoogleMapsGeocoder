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

    public function testGetSetAddress()
    {
        $this->object->setAddress("123 Main Street");
        $this->assertEquals(
            "123 Main Street",
            $this->object->getAddress()
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

    public function testGetSetLongitude()
    {
        $this->object->setLongitude(37.92);
        $this->assertEquals(
            37.92,
            $this->object->getLongitude()
        );
    }

    public function testGetSetLatitudeLongitude()
    {
        $this->assertEquals(
            $this->object->getLatitudeLongitude(),
            false
        );

        $this->object->setLatitude(20.91);
        $this->assertEquals(
            $this->object->getLatitudeLongitude(),
            false
        );

        $this->object->setLatitude('');
        $this->object->setLongitude(20.91);
        $this->assertEquals(
            $this->object->getLatitudeLongitude(),
            false
        );

        $this->object->setLatitude(19.20);
        $this->object->setLongitude(-137.32);
        $this->assertEquals(
            $this->object->getLatitudeLongitude(),
            "19.2,-137.32"
        );

        $this->object->setLatitudeLongitude(19.20,-137.32);
        $this->assertEquals(
            $this->object->getLatitudeLongitude(),
            "19.2,-137.32"
        );

        $this->object->setLatitudeLongitude("19.20","-137.32");
        $this->assertEquals(
            $this->object->getLatitudeLongitude(),
            "19.20,-137.32"
        );
    }

    public function testGetSetBounds()
    {
        $this->object->setBounds(100,200,10,20);
        $this->assertEquals(
            $this->object->getBounds(),
            "100,200|10,20"
        );

    }

    public function testGetSetRegion()
    {
        $this->object->setRegion("Mexico");
        $this->assertEquals(
            "Mexico",
            $this->object->getRegion()
        );
    }

    public function testGetSetLanguage()
    {
        $this->object->setLanguage("English");
        $this->assertEquals(
            "English",
            $this->object->getLanguage()
        );
    }

    public function testGetSetSensor()
    {
        $this->object->setSensor(true);
        $this->assertEquals(
            true,
            $this->object->getSensor()
        );

        $this->object->setSensor(false);
        $this->assertEquals(
            false,
            $this->object->getSensor()
        );
    }

    public function testGetSetClientId()
    {
        $this->object->setClientId('123456789');
        $this->assertEquals(
            '123456789',
            $this->object->getClientId()
        );
    }

    public function testGetSetSigningKey()
    {
        $this->object->setSigningKey('@34fg%99ae');
        $this->assertEquals(
            '@34fg%99ae',
            $this->object->getSigningKey()
        );
    }

    public function testIsBusinessClient()
    {
        $this->object->setClientId("12345");
        $this->object->setSigningKey("%^88db9");
        $this->assertEquals(
            true,
            $this->object->isBusinessClient()
        );

        $this->object->setSigningKey("");
        $this->assertEquals(
            false,
            $this->object->isBusinessClient()
        );
    }

    public function testGeocode()
    {
        $this->object->setAddress("123 Main Street");
        $this->assertEquals(
            $this->object->geocode(),
            "https://maps.googleapis.com/maps/api/geocode/json?address=123+main+street"
        );
    }
}