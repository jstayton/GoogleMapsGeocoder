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

    public function testIsFormat()
    {
        $this->assertTrue(
            $this->object->isFormatJson()
        );

        $this->assertFalse(
            $this->object->isFormatXml()
        );

        $this->object->setFormat("xml");
        $this->assertTrue(
            $this->object->isFormatXml()
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
            false,
            $this->object->getLatitudeLongitude()
        );

        $this->object->setLatitude(20.91);
        $this->assertEquals(
            false,
            $this->object->getLatitudeLongitude()
        );

        $this->object->setLatitude('');
        $this->object->setLongitude(20.91);
        $this->assertEquals(
            false,
            $this->object->getLatitudeLongitude()
        );

        $this->object->setLatitude(19.20);
        $this->object->setLongitude(-137.32);
        $this->assertEquals(
            "19.2,-137.32",
            $this->object->getLatitudeLongitude()
        );

        $this->object->setLatitudeLongitude(19.20,-137.32);
        $this->assertEquals(
            "19.2,-137.32",
            $this->object->getLatitudeLongitude()
        );

        $this->object->setLatitudeLongitude("19.20","-137.32");
        $this->assertEquals(
            "19.20,-137.32",
            $this->object->getLatitudeLongitude()
        );
    }

    public function testGetSetBounds()
    {
        $this->assertEquals(
            false,
            $this->object->getBounds()
        );

        $this->object->setBounds(100,200,10,20);
        $this->assertEquals(
            "100,200|10,20",
            $this->object->getBounds()
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

        $this->object->setSensor('true');
        $this->assertEquals(
            true,
            $this->object->getSensor()
        );

        $this->object->setSensor(false);
        $this->assertEquals(
            false,
            $this->object->getSensor()
        );

        $this->object->setSensor('cheese');
        $this->assertFalse(
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

    public function testBoundingBox()
    {
        $this->assertEquals(
            array(
                'lat' => array('max' => 19.289134331810558, 'min' => 18.710865668189442),
                'lon' => array('max' => -129.69473209425954, 'min' => -130.30526790574046)),
            $this->object->boundingBox(19, -130, 20)
        );
    }

    /**
     * Tested these functions by making them public. In reality, they are hard to test because they are called using
     * the geocode function which times out when making the api call to google maps to "get_file_contents"
     *
     * To continue testing on these methods, make them public during development
     */
//    public function testGeocodeQueryString()
//    {
//        $this->object->setLatitudeLongitude(19.20, 15);
//        $this->assertEquals(
//            'latlng=19.2%2C15',
//            $this->object->geocodeQueryString()
//        );
//
//        $this->object->setAddress("123 Main Street, Buffalo, NY 14203, USA");
//        $this->assertEquals(
//            'address=123+Main+Street%2C+Buffalo%2C+NY+14203%2C+USA',
//            $this->object->geocodeQueryString()
//        );
//
//        $this->object->setAddress("123 Main Street, Buffalo, NY 14203, USA");
//        $this->object->setClientId("12345");
//        $this->object->setSigningKey("%^88db9");
//        $this->assertEquals(
//            'address=123+Main+Street%2C+Buffalo%2C+NY+14203%2C+USA&client=12345',
//            $this->object->geocodeQueryString()
//        );
//    }
//
//    public function testGeocodeUrl()
//    {
//        $this->object->setAddress("123 Main Street, Buffalo, NY 14203, USA");
//        $this->object->setLatitudeLongitude(19.20, 15);
//        $this->assertEquals(
//            'https://maps.googleapis.com/maps/api/geocode/json?address=123+Main+Street%2C+Buffalo%2C+NY+14203%2C+USA',
//            $this->object->geocodeUrl(true)
//
//        );
//
//        $this->object->setAddress("123 Main Street, Buffalo, NY 14203, USA");
//        $this->object->setClientId("12345");
//        $this->object->setSigningKey("%^88db9");
//        $this->assertEquals(
//            'https://maps.googleapis.com/maps/api/geocode/json?address=123+Main+Street%2C+Buffalo%2C+NY+14203%2C+USA&client=12345&signature=fxJey1E4ORdzGr-LEuEaU3-okLw=',
//            $this->object->geocodeUrl(true)
//
//        );
//    }
}