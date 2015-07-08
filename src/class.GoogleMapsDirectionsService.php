<?php
/**
   * A PHP wrapper for the Google Maps Geocoding API v3.<br/>
   * This class based on Justin Stayton PHP Wrapper.<br/>
   * Justin's Wrapper is extended to be able to also serve the DirectionService interface. <br/>
   * Therefore some code is moved to a base class and some functions visibility has been changed.<br/>
   *
   * @author    Justin Stayton
   * @author    Stephan Watermeyer
   * @copyright Copyright 2015 by Stephan Watermeyer
   * @license   https://github.com/phreakadelle/Miner/blob/master/LICENSE-MIT MIT
   * @link      https://developers.google.com/maps/documentation/geocoding/
   * @package   GoogleMapsDirectionsService
   * @version   0.0.1
   */
class GoogleMapsDirectionsService extends GoogleMapsWebService {
  
  /**
   * Path portion of the Google Geocoding API URL.
   */
  const URL_PATH = "/maps/api/directions/";
  
  /**
   * HTTP URL of the Google Geocoding API.
   */
  const URL_HTTP = "http://maps.googleapis.com/maps/api/directions/";
  
  /**
   * HTTPS URL of the Google Geocoding API.
   */
  const URL_HTTPS = "https://maps.googleapis.com/maps/api/directions/";
  private $mOrigin = "";
  private $mDestination = "";

  function __construct($format = self::FORMAT_JSON, $sensor = false) {
    parent::__construct(self::URL_PATH, self::URL_HTTP, self::URL_HTTPS, $format, $sensor);
  }

  function getDirections($https = false, $raw = false) {
    // TODO: Maybe rename the geocode Method in super-class to "service" or similar.
    return parent::geocode($https, $raw);
  }

  public function getOrigin() {
    return $this->mOrigin;
  }

  public function getDestination() {
    return $this->mDestination;
  }

  public function setOrigin($mOrigin) {
    $this->mOrigin = $mOrigin;
    return $this;
  }

  public function setDestination($mDestination) {
    $this->mDestination = $mDestination;
    return $this;
  }

  /**
   * FIXME: This hook is necessary as the DirectionsService is not accepting the encoded ampersands.
   * Though the encoded ampersand is replaced by the native &
   * 
   * @see GoogleMapsWebService::override_http_build_query()
   */
  function override_http_build_query($pQuery) {
    $retVal = parent::override_http_build_query($pQuery);
    return str_replace("&amp;", "&", $retVal);
  }

  function geocodeQueryStringOverride($queryString) {
    $queryString['origin'] = $this->getOrigin();
    $queryString['destination'] = $this->getDestination();
    return $queryString;
  }
}