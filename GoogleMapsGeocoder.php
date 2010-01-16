<?php

  /**
   * A PHP 5 object-oriented interface to the Google Maps Geocoding Service.
   *
   * @author  Justin Stayton <justin.stayton@gmail.com>
   * @link    http://code.google.com/apis/maps/documentation/geocoding/index.html
   * @version 1.0 1/16/2010
   */
  class GoogleMapsGeocoder {

    /**
     * Response format codes.
     *
     * @link http://code.google.com/apis/maps/documentation/geocoding/index.html#GeocodingResponses
     * @see  GoogleMapsGeocoder::getOutputFormat()
     * @see  GoogleMapsGeocoder::setOutputFormat()
     */
    const OUTPUT_FORMAT_JSON = "json";
    const OUTPUT_FORMAT_KML = "kml";
    const OUTPUT_FORMAT_XML = "xml";
    const OUTPUT_FORMAT_CSV = "csv";

    /**
     * Status codes used in Google's response.
     *
     * @link http://code.google.com/apis/maps/documentation/geocoding/index.html#StatusCodes
     */
    const STATUS_SUCCESS = 200;
    const STATUS_SERVER_ERROR = 500;
    const STATUS_MISSING_QUERY = 601;
    const STATUS_UNKNOWN_ADDRESS = 602;
    const STATUS_UNAVAILABLE_ADDRESS = 603;
    const STATUS_BAD_KEY = 610;
    const STATUS_TOO_MANY_QUERIES = 620;

    /**
     * Accuracy codes used in Google's response.
     *
     * @link http://code.google.com/apis/maps/documentation/geocoding/index.html#GeocodingAccuracy
     */
    const ACCURACY_UNKNOWN = 0;
    const ACCURACY_COUNTRY = 1;
    const ACCURACY_REGION = 2;
    const ACCURACY_SUB_REGION = 3;
    const ACCURACY_TOWN = 4;
    const ACCURACY_POST_CODE = 5;
    const ACCURACY_STREET = 6;
    const ACCURACY_INTERSECTION = 7;
    const ACCURACY_ADDRESS = 8;
    const ACCURACY_PREMISE = 9;

    /**
     * Helps calculate a more realistic bounding box by taking into account the
     * curvature of the earth's surface.
     */
    const EQUATOR_LAT_DEGREE_IN_MILES = 69.172;

    /**
     * Address to geocode.
     *
     * @var string
     */
    private $address;

    /**
     * Google Maps API key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Whether the geocoding request is from a device with a location sensor.
     *
     * @var bool
     */
    private $isFromLocationSensor;

    /**
     * Requested response format from Google.
     *
     * @var string
     */
    private $outputFormat;

    /**
     * Viewport center latitude for influencing results.
     *
     * @var float|int|string
     */
    private $viewportCenterLatitude;

    /**
     * Viewport center longitude for influencing results.
     *
     * @var float|int|string
     */
    private $viewportCenterLongitude;

    /**
     * Viewport perimeter latitude for influencing results.
     *
     * @var float|int|string
     */
    private $viewportPerimeterLatitude;

    /**
     * Viewport perimeter longitude for influencing results.
     *
     * @var float|int|string
     */
    private $viewportPerimeterLongitude;

    /**
     * Country-code top-level domain for influencing results.
     *
     * @var string
     */
    private $viewportCountryCodeTld;

    /**
     * Constructor. The request is not executed until
     * {@link GoogleMapsGeocoder::geocode()} is called.
     *
     * @param  string $apiKey Google Maps API key
     * @param  string $address address to geocode
     * @param  string $outputFormat optional response format (JSON default)
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::setApiKey()
     * @uses   GoogleMapsGeocoder::setAddress()
     * @uses   GoogleMapsGeocoder::setOutputFormat()
     * @uses   GoogleMapsGeocoder::setIsFromLocationSensor()
     * @uses   GoogleMapsGeocoder::OUTPUT_FORMAT_JSON
     */
    public function __construct($apiKey, $address, $outputFormat = self::OUTPUT_FORMAT_JSON) {
      $this->setApiKey($apiKey)
           ->setAddress($address)
           ->setOutputFormat($outputFormat)
           ->setIsFromLocationSensor('false');
    }

    /**
     * Sets the address to geocode. A latitude/longitude value can be specified
     * for reverse geocoding.
     *
     * @param  string $address address to geocode
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$address
     */
    public function setAddress($address) {
      $this->address = $address;

      return $this;
    }

    /**
     * Sets your Google Maps API key.
     *
     * @param  string $apiKey Google Maps API key
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$apiKey
     */
    public function setApiKey($apiKey) {
      $this->apiKey = $apiKey;

      return $this;
    }

    /**
     * Sets whether the geocoding request is from a device with a location
     * sensor.
     *
     * @param  bool $isFromLocationSensor has location sensor?
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$isFromLocationSensor
     */
    public function setIsFromLocationSensor($isFromLocationSensor) {
      $this->isFromLocationSensor = $isFromLocationSensor;

      return $this;
    }

    /**
     * Sets the requested response format from Google. Use one of the
     * OUTPUT_FORMAT_* class constants when specifying.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#GeocodingResponses
     * @param  string $outputFormat response format
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$outputFormat
     */
    public function setOutputFormat($outputFormat) {
      $this->outputFormat = $outputFormat;

      return $this;
    }

    /**
     * Sets the viewport center for influencing results.
     * {@link GoogleMapsGeocoder::setViewportPerimeter()} must also be set.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#Viewports
     * @param  float|int|string $viewportCenterLatitude center latitude
     * @param  float|int|string $viewportCenterLongitude center longitude
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$viewportCenterLatitude
     * @uses   GoogleMapsGeocoder::$viewportCenterLongitude
     */
    public function setViewportCenter($viewportCenterLatitude, $viewportCenterLongitude) {
      $this->viewportCenterLatitude = $viewportCenterLatitude;
      $this->viewportCenterLongitude = $viewportCenterLongitude;

      return $this;
    }

    /**
     * Sets the viewport perimeter (or span) for influencing results.
     * {@link GoogleMapsGeocoder::setViewportCenter()} must also be set.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#Viewports
     * @param  float|int|string $viewportPerimeterLatitude perimeter latitude
     * @param  float|int|string $viewportPerimeterLongitude perimeter longitude
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$viewportPerimeterLatitude
     * @uses   GoogleMapsGeocoder::$viewportPerimeterLongitude
     */
    public function setViewportPerimeter($viewportPerimeterLatitude, $viewportPerimeterLongitude) {
      $this->viewportPerimeterLatitude = $viewportPerimeterLatitude;
      $this->viewportPerimeterLongitude = $viewportPerimeterLongitude;

      return $this;
    }

    /**
     * Sets the country-code top-level domain for influencing results.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#CountryCodes
     * @param  string $viewportCountryCodeTld top-level domain
     * @return GoogleMapsGeocoder
     * @uses   GoogleMapsGeocoder::$viewportCountryCodeTld
     */
    public function setViewportCountryCodeTld($viewportCountryCodeTld) {
      $this->viewportCountryCodeTld = $viewportCountryCodeTld;

      return $this;
    }

    /**
     * Returns the address to geocode.
     *
     * @return string
     * @uses   GoogleMapsGeocoder::$address
     */
    public function getAddress() {
      return $this->address;
    }

    /**
     * Returns your Google Maps API key.
     *
     * @return string
     * @uses   GoogleMapsGeocoder::$apiKey
     */
    public function getApiKey() {
      return $this->apiKey;
    }

    /**
     * Returns whether the geocoding request is from a device with a location
     * sensor.
     *
     * @return bool
     * @uses   GoogleMapsGeocoder::$isFromLocationSensor
     */
    public function getIsFromLocationSensor() {
      return $this->isFromLocationSensor;
    }

    /**
     * Returns the requested response format from Google. Should correspond to
     * one of the OUTPUT_FORMAT_* class constants.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#GeocodingResponses
     * @return string
     * @uses   GoogleMapsGeocoder::$outputFormat
     */
    public function getOutputFormat() {
      return $this->outputFormat;
    }

    /**
     * Returns the viewport center latitude for influencing results.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#Viewports
     * @return float|int|string
     * @uses   GoogleMapsGeocoder::$viewportCenterLatitude
     */
    public function getViewportCenterLatitude() {
      return $this->viewportCenterLatitude;
    }

    /**
     * Returns the viewport center longitude for influencing results.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#Viewports
     * @return float|int|string
     * @uses   GoogleMapsGeocoder::$viewportCenterLongitude
     */
    public function getViewportCenterLongitude() {
      return $this->viewportCenterLongitude;
    }

    /**
     * Returns the viewport perimeter latitude for influencing results.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#Viewports
     * @return float|int|string
     * @uses   GoogleMapsGeocoder::$viewportPerimeterLatitude
     */
    public function getViewportPerimeterLatitude() {
      return $this->viewportPerimeterLatitude;
    }

    /**
     * Returns the viewport perimeter longitude for influencing results.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#Viewports
     * @return float|int|string
     * @uses   GoogleMapsGeocoder::$viewportPerimeterLongitude
     */
    public function getViewportPerimeterLongitude() {
      return $this->viewportPerimeterLongitude;
    }

    /**
     * Returns the country-code top-level domain for influencing results.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#CountryCodes
     * @return string
     * @uses   GoogleMapsGeocoder::$viewportCountryCodeTld
     */
    public function getViewportCountryCodeTld() {
      return $this->viewportCountryCodeTld;
    }

    /**
     * Builds the URL (with full query string) to request from Google.
     *
     * @return string|false false returned if all required values not set
     * @uses   GoogleMapsGeocoder::getAddress()
     * @uses   GoogleMapsGeocoder::getApiKey()
     * @uses   GoogleMapsGeocoder::getIsFromLocationSensor()
     * @uses   GoogleMapsGeocoder::getOutputFormat()
     * @uses   GoogleMapsGeocoder::getViewportCenterLatitude()
     * @uses   GoogleMapsGeocoder::getViewportCenterLongitude()
     * @uses   GoogleMapsGeocoder::getViewportPerimeterLatitude()
     * @uses   GoogleMapsGeocoder::getViewportPerimeterLongitude()
     * @uses   GoogleMapsGeocoder::getViewportCountryCodeTld()
     */
    private function buildGeocodeUrl() {
      $geocodeUrl  = "http://maps.google.com/maps/geo?";

      // The following four parameters are all required by Google.
      $geocodeUrl .= "q=" . urlencode($this->getAddress());
      $geocodeUrl .= "&key=" . $this->getApiKey();
      $geocodeUrl .= "&sensor=" . $this->getIsFromLocationSensor();
      $geocodeUrl .= "&output=" . $this->getOutputFormat();

      // All four coordinates must be specified for viewport to work. Optional.
      if ($this->getViewportCenterLatitude() && $this->getViewportCenterLongitude() &&
          $this->getViewportPerimeterLatitude() && $this->getViewportPerimeterLongitude()) {
        $geocodeUrl .= "&ll=" . $this->getViewportCenterLatitude() . "," . $this->getViewportCenterLongitude();
        $geocodeUrl .= "&spn=" . $this->getViewportPerimeterLatitude() . "," . $this->getViewportPerimeterLongitude();
      }

      // Optional.
      if ($this->getViewportCountryCodeTld()) {
        $geocodeUrl .= "&gl=" . $this->getViewportCountryCodeTld();
      }

      return $geocodeUrl;
    }

    /**
     * Executes the geocoding request. Google's raw response is returned.
     *
     * @link   http://code.google.com/apis/maps/documentation/geocoding/index.html#GeocodingResponses
     * @return string|false false returned if all required values not set
     * @uses   GoogleMapsGeocoder::buildGeocodeUrl()
     */
    public function geocode() {
      return file_get_contents($this->buildGeocodeUrl());
    }

    /**
     * Computes a four point bounding box around the specified location. This
     * can then be used to find all locations within an X-mile range of a central
     * location. A bounding box is much easier and faster to compute than a
     * bounding radius.
     *
     * The returned array contains two keys: 'lat' and 'lon'. Each of these
     * contains another array with two keys: 'max' and 'min'. Four points are
     * returned in total.
     *
     * @return array
     * @uses   GoogleMapsGeocoder::EQUATOR_LAT_DEGREE_IN_MILES
     */
    public static function computeBoundingBoxCoordinates($latitude, $longitude, $mileRange) {
      $maxLatitude = $latitude + $mileRange / self::EQUATOR_LAT_DEGREE_IN_MILES;
      $minLatitude = $latitude - ($maxLatitude - $latitude);

      $maxLongitude = $longitude + $mileRange / (cos($minLatitude * M_PI / 180) * self::EQUATOR_LAT_DEGREE_IN_MILES);
      $minLongitude = $longitude - ($maxLongitude - $longitude);

      return array('lat' => array('max' => $maxLatitude,
                                  'min' => $minLatitude),
                   'lon' => array('max' => $maxLongitude,
                                  'min' => $minLongitude));
    }

  }

?>