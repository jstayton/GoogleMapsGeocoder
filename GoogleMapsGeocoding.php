<?php

  class GoogleMapsGeocoding {

    const OUTPUT_FORMAT_JSON = "json";
    const OUTPUT_FORMAT_KML = "kml";
    const OUTPUT_FORMAT_XML = "xml";
    const OUTPUT_FORMAT_CSV = "csv";

    const STATUS_SUCCESS = 200;
    const STATUS_SERVER_ERROR = 500;
    const STATUS_MISSING_QUERY = 601;
    const STATUS_UNKNOWN_ADDRESS = 602;
    const STATUS_UNAVAILABLE_ADDRESS = 603;
    const STATUS_BAD_KEY = 610;
    const STATUS_TOO_MANY_QUERIES = 620;

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

    private $address;
    private $apiKey;
    private $isFromLocationSensor;
    private $outputFormat;
    private $encoding;
    private $viewpointCenterLatitude;
    private $viewpointCenterLongitude;
    private $viewpointPerimeterLatitude;
    private $viewpointPerimeterLongitude;
    private $viewpointCountryCodeTld;

    public function __construct($apiKey, $address, $outputFormat) {
      $this->setApiKey($apiKey);
      $this->setAddress($address);
      $this->setOutputFormat($outputFormat);
      $this->setIsFromLocationSensor(false);
      $this->setEncoding('utf8');
    }

    public function setAddress($address) {
      $this->address = $address;
    }

    public function setApiKey($apiKey) {
      $this->apiKey = $apiKey;
    }

    public function setIsFromLocationSensor($isFromLocationSensor) {
      $this->isFromLocationSensor = $isFromLocationSensor;
    }

    public function setOutputFormat($outputFormat) {
      $this->outputFormat = $outputFormat;
    }

    public function setEncoding($encoding) {
      $this->encoding = $encoding;
    }

    public function setViewpointCenter($viewpointCenterLatitude, $viewpointCenterLongitude) {
      $this->viewpointCenterLatitude = $viewpointCenterLatitude;
      $this->viewpointCenterLongitude = $viewpointCenterLongitude;
    }

    public function setViewpointPerimeter($viewpointPerimeterLatitude, $viewpointPerimeterLongitude) {
      $this->viewpointPerimeterLatitude = $viewpointPerimeterLatitude;
      $this->viewpointPerimeterLongitude = $viewpointPerimeterLongitude;
    }

    public function setViewpointCountryCodeTld($viewpointCountryCodeTld) {
      $this->viewpointCountryCodeTld = $viewpointCountryCodeTld;
    }

    public function getAddress() {
      return $this->address;
    }

    public function getApiKey() {
      return $this->apiKey;
    }

    public function getIsFromLocationSensor() {
      return $this->isFromLocationSensor;
    }

    public function getOutputFormat() {
      return $this->outputFormat;
    }

    public function getEncoding() {
      return $this->encoding;
    }

    public function getViewpointCenterLatitude() {
      return $this->viewpointCenterLatitude;
    }

    public function getViewpointCenterLongitude() {
      return $this->viewpointCenterLongitude;
    }

    public function getViewpointPerimeterLatitude() {
      return $this->viewpointPerimeterLatitude;
    }

    public function getViewpointPerimeterLongitude() {
      return $this->viewpointPerimeterLongitude;
    }

    public function getViewpointCountryCodeTld() {
      return $this->viewpointCountryCodeTld;
    }

    private function buildGeocodeUrl() {
      $geocodeUrl  = "http://maps.google.com/maps/geo?";
      $geocodeUrl .= "q=" . urlencode($this->address);
      $geocodeUrl .= "&key=" . $this->apiKey;
      $geocodeUrl .= "&sensor=" . $this->isFromLocationSensor;
      $geocodeUrl .= "&output=" . $this->outputFormat;

      if (isset($this->encoding)) {
        $geocodeUrl .= "&oe=" . $this->encoding;
      }

      if (isset($this->viewpointCenterLatitude) && isset($this->viewpointCenterLongitude) &&
          isset($this->viewpointPerimeterLatitude) && isset($this->viewpointPerimeterLongitude)) {
        $geocodeUrl .= "&ll=" . $this->viewpointCenterLatitude . "," . $this->viewpointCenterLongitude;
        $geocodeUrl .= "&spn=" . $this->viewpointPerimeterLatitude . "," . $this->viewpointPerimeterLongitude;
      }

      if (isset($this->viewpointCountryCodeTld)) {
        $geocodeUrl .= "&gl=" . $this->viewpointCountryCodeTld;
      }

      return $geocodeUrl;
    }

    public function geocode() {
      return file_get_contents($this->buildGeocodeUrl());
    }

  }

?>