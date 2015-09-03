<?php

  /**
   * A PHP wrapper for the Google Maps Geocoding API v3.
   *
   * @author    Justin Stayton
   * @copyright Copyright 2015 by Justin Stayton
   * @license   https://github.com/jstayton/Miner/blob/master/LICENSE-MIT MIT
   * @link      https://developers.google.com/maps/documentation/geocoding/intro
   * @package   GoogleMapsGeocoder
   * @version   2.3.0
   */
  class GoogleMapsGeocoder {

    /**
     * Domain portion of the Google Geocoding API URL.
     */
    const URL_DOMAIN = "maps.googleapis.com";

    /**
     * Path portion of the Google Geocoding API URL.
     */
    const URL_PATH = "/maps/api/geocode/";

    /**
     * HTTP URL of the Google Geocoding API.
     */
    const URL_HTTP = "http://maps.googleapis.com/maps/api/geocode/";

    /**
     * HTTPS URL of the Google Geocoding API.
     */
    const URL_HTTPS = "https://maps.googleapis.com/maps/api/geocode/";

    /**
     * JSON response format.
     */
    const FORMAT_JSON = "json";

    /**
     * XML response format.
     */
    const FORMAT_XML = "xml";

    /**
     * No errors occurred, the address was successfully parsed and at least one
     * geocode was returned.
     */
    const STATUS_SUCCESS = "OK";

    /**
     * Geocode was successful, but returned no results.
     */
    const STATUS_NO_RESULTS = "ZERO_RESULTS";

    /**
     * Over limit of 2,500 (100,000 if premier) requests per day.
     */
    const STATUS_OVER_LIMIT = "OVER_QUERY_LIMIT";

    /**
     * Request denied, usually because of missing sensor parameter.
     */
    const STATUS_REQUEST_DENIED = "REQUEST_DENIED";

    /**
     * Invalid request, usually because of missing parameter that's required.
     */
    const STATUS_INVALID_REQUEST = "INVALID_REQUEST";

    /**
     * Unnown server error. May succeed if tried again.
     */
    const STATUS_UNKNOWN_ERROR = "UNKNOWN_ERROR";

    /**
     * Returned result is a precise street address.
     */
    const LOCATION_TYPE_ROOFTOP = "ROOFTOP";

    /**
     * Returned result is between two precise points (usually on a road).
     */
    const LOCATION_TYPE_RANGE = "RANGE_INTERPOLATED";

    /**
     * Returned result is the geometric center of a polyline (i.e., a street)
     * or polygon (i.e., a region).
     */
    const LOCATION_TYPE_CENTER = "GEOMETRIC_CENTER";

    /**
     * Returned result is approximate.
     */
    const LOCATION_TYPE_APPROXIMATE = "APPROXIMATE";

    /**
     * A precise street address.
     */
    const TYPE_STREET_ADDRESS = "street_address";

    /**
     * A named route (such as "US 101").
     */
    const TYPE_ROUTE = "route";

    /**
     * A major intersection, usually of two major roads.
     */
    const TYPE_INTERSECTION = "intersection";

    /**
     * A political entity, usually of some civil administration.
     */
    const TYPE_POLITICAL = "political";

    /**
     * A national political entity (country). The highest order type returned.
     */
    const TYPE_COUNTRY = "country";

    /**
     * A first-order civil entity below country (states within the US).
     */
    const TYPE_ADMIN_AREA_1 = "administrative_area_level_1";

    /**
     * A second-order civil entity below country (counties within the US).
     */
    const TYPE_ADMIN_AREA_2 = "administrative_area_level_2";

    /**
     * A third-order civil entity below country.
     */
    const TYPE_ADMIN_AREA_3 = "administrative_area_level_3";

    /**
     * A fourth-order civil entity below country.
     */
    const TYPE_ADMIN_AREA_4 = "administrative_area_level_4";

    /**
     * A fifth-order civil entity below country.
     */
    const TYPE_ADMIN_AREA_5 = "administrative_area_level_5";

    /**
     * A commonly-used alternative name for the entity.
     */
    const TYPE_COLLOQUIAL_AREA = "colloquial_area";

    /**
     * An incorporated city or town.
     */
    const TYPE_LOCALITY = "locality";

    /**
     * A specific type of Japanese locality.
     */
    const TYPE_WARD = "ward";

    /**
     * A first-order civil entity below a locality.
     */
    const TYPE_SUB_LOCALITY = "sublocality";

    /**
     * A named neighborhood.
     */
    const TYPE_NEIGHBORHOOD = "neighborhood";

    /**
     * A named location, usually a building or collection of buildings.
     */
    const TYPE_PREMISE = "premise";

    /**
     * A first-order entity below a named location, usually a single building
     * within a collection of building with a common name.
     */
    const TYPE_SUB_PREMISE = "subpremise";

    /**
     * A postal code as used to address mail within the country.
     */
    const TYPE_POSTAL_CODE = "postal_code";

    /**
     * A prominent natural feature.
     */
    const TYPE_NATURAL_FEATURE = "natural_feature";

    /**
     * An airport.
     */
    const TYPE_AIRPORT = "airport";

    /**
     * A named park.
     */
    const TYPE_PARK = "park";

    /**
     * A named point of interest that doesn't fit within another category.
     */
    const TYPE_POINT_OF_INTEREST = "point_of_interest";

    /**
     * A floor of a building address.
     */
    const TYPE_FLOOR = "floor";

    /**
     * A place that has not yet been categorized.
     */
    const TYPE_ESTABLISHMENT = "establishment";

    /**
     * A parking lot or parking structure.
     */
    const TYPE_PARKING = "parking";

    /**
     * A specific postal box.
     */
    const TYPE_POST_BOX = "post_box";

    /**
     * A grouping of geographic areas used for mailing addresses in some
     * countries.
     */
    const TYPE_POSTAL_TOWN = "postal_town";

    /**
     * A room of a building address.
     */
    const TYPE_ROOM = "room";

    /**
     * A precise street number.
     */
    const TYPE_STREET_NUMBER = "street_number";

    /**
     * A bus stop.
     */
    const TYPE_BUS_STATION = "bus_station";

    /**
     * A train stop.
     */
    const TYPE_TRAIN_STATION = "train_station";

    /**
     * A public transit stop.
     */
    const TYPE_TRANSIT_STATION = "transit_station";

    /**
     * Helps calculate a more realistic bounding box by taking into account the
     * curvature of the earth's surface.
     */
    const EQUATOR_LAT_DEGREE_IN_MILES = 69.172;

    /**
     * Response format.
     *
     * @var string
     */
    private $format;

    /**
     * Address to geocode.
     *
     * @var string
     */
    private $address;

    /**
     * Latitude to reverse geocode to the closest address.
     *
     * @var float|string
     */
    private $latitude;

    /**
     * Longitude to reverse geocode to the closest address.
     *
     * @var float|string
     */
    private $longitude;

    /**
     * Southwest latitude of the bounding box within which to bias geocode
     * results.
     *
     * @var float|string
     */
    private $boundsSouthwestLatitude;

    /**
     * Southwest longitude of the bounding box within which to bias geocode
     * results.
     *
     * @var float|string
     */
    private $boundsSouthwestLongitude;

    /**
     * Northeast latitude of the bounding box within which to bias geocode
     * results.
     *
     * @var float|string
     */
    private $boundsNortheastLatitude;

    /**
     * Northeast longitude of the bounding box within which to bias geocode
     * results.
     *
     * @var float|string
     */
    private $boundsNortheastLongitude;

    /**
     * Two-character, top-level domain (ccTLD) within which to bias geocode
     * results.
     *
     * @var string
     */
    private $region;

    /**
     * Language code in which to return results.
     *
     * @var string
     */
    private $language;

    /**
     * Address type(s) to restrict results to.
     *
     * @var array
     */
    private $resultType = array();

    /**
     * Location type(s) to restrict results to.
     *
     * @var array
     */
    private $locationType = array();

    /**
     * Whether the request is from a device with a location sensor.
     *
     * @deprecated 2.3.0 no longer required by the Google Maps API
     * @var        string
     */
    private $sensor;

    /**
     * API key to authenticate with.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Client ID for Business clients.
     *
     * @var string
     */
    private $clientId;

    /**
     * Cryptographic signing key for Business clients.
     *
     * @var string
     */
    private $signingKey;

    /**
     * Constructor. The request is not executed until `geocode()` is called.
     *
     * @param  string $address optional address to geocode
     * @param  string $format optional response format (JSON default)
     * @param  bool|string $sensor deprecated as of v2.3.0
     * @return GoogleMapsGeocoder
     */
    public function __construct($address = null, $format = self::FORMAT_JSON, $sensor = false) {
      $this->setAddress($address)
           ->setFormat($format)
           ->setSensor($sensor);
    }

    /**
     * Set the response format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingResponses
     * @param  string $format response format
     * @return GoogleMapsGeocoder
     */
    public function setFormat($format) {
      $this->format = $format;

      return $this;
    }

    /**
     * Get the response format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingResponses
     * @return string response format
     */
    public function getFormat() {
      return $this->format;
    }

    /**
     * Whether the response format is JSON.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#JSON
     * @return bool whether JSON
     */
    public function isFormatJson() {
      return $this->getFormat() == self::FORMAT_JSON;
    }

    /**
     * Whether the response format is XML.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#XML
     * @return bool whether XML
     */
    public function isFormatXml() {
      return $this->getFormat() == self::FORMAT_XML;
    }

    /**
     * Set the address to geocode.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#geocoding
     * @param  string $address address to geocode
     * @return GoogleMapsGeocoder
     */
    public function setAddress($address) {
      $this->address = $address;

      return $this;
    }

    /**
     * Get the address to geocode.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#geocoding
     * @return string
     */
    public function getAddress() {
      return $this->address;
    }

    /**
     * Set the latitude/longitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @param  float|string $latitude latitude to reverse geocode
     * @param  float|string $longitude longitude to reverse geocode
     * @return GoogleMapsGeocoder
     */
    public function setLatitudeLongitude($latitude, $longitude) {
      $this->setLatitude($latitude)
           ->setLongitude($longitude);

      return $this;
    }

    /**
     * Get the latitude/longitude to reverse geocode to the closest address
     * in comma-separated format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @return string|false comma-separated coordinates, or false if not set
     */
    public function getLatitudeLongitude() {
      $latitude = $this->getLatitude();
      $longitude = $this->getLongitude();

      if ($latitude && $longitude) {
        return $latitude . "," . $longitude;
      }
      else {
        return false;
      }
    }

    /**
     * Set the latitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @param  float|string $latitude latitude to reverse geocode
     * @return GoogleMapsGeocoder
     */
    public function setLatitude($latitude) {
      $this->latitude = $latitude;

      return $this;
    }

    /**
     * Get the latitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @return float|string latitude to reverse geocode
     */
    public function getLatitude() {
      return $this->latitude;
    }

    /**
     * Set the longitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @param  float|string $longitude longitude to reverse geocode
     * @return GoogleMapsGeocoder
     */
    public function setLongitude($longitude) {
      $this->longitude = $longitude;

      return $this;
    }

    /**
     * Get the longitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @return float|string longitude to reverse geocode
     */
    public function getLongitude() {
      return $this->longitude;
    }

    /**
     * Set the bounding box coordinates within which to bias geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @param  float|string $southwestLatitude southwest latitude boundary
     * @param  float|string $southwestLongitude southwest longitude boundary
     * @param  float|string $northeastLatitude northeast latitude boundary
     * @param  float|string $northeastLongitude northeast longitude boundary
     * @return GoogleMapsGeocoder
     */
    public function setBounds($southwestLatitude, $southwestLongitude, $northeastLatitude, $northeastLongitude) {
      $this->setBoundsSouthwest($southwestLatitude, $southwestLongitude)
           ->setBoundsNortheast($northeastLatitude, $northeastLongitude);

      return $this;
    }

    /**
     * Get the bounding box coordinates within which to bias geocode results
     * in comma-separated, pipe-delimited format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return string|false comma-separated, pipe-delimited coordinates, or
     *                      false if not set
     */
    public function getBounds() {
      $southwest = $this->getBoundsSouthwest();
      $northeast = $this->getBoundsNortheast();

      if ($southwest && $northeast) {
        return $southwest . "|" . $northeast;
      }
      else {
        return false;
      }
    }

    /**
     * Set the southwest coordinates of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @param  float|string $latitude southwest latitude boundary
     * @param  float|string $longitude southwest longitude boundary
     * @return GoogleMapsGeocoder
     */
    public function setBoundsSouthwest($latitude, $longitude) {
      $this->boundsSouthwestLatitude = $latitude;
      $this->boundsSouthwestLongitude = $longitude;

      return $this;
    }

    /**
     * Get the southwest coordinates of the bounding box within which to bias
     * geocode results in comma-separated format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return string|false comma-separated coordinates, or false if not set
     */
    public function getBoundsSouthwest() {
      $latitude = $this->getBoundsSouthwestLatitude();
      $longitude = $this->getBoundsSouthwestLongitude();

      if ($latitude && $longitude) {
        return $latitude . "," . $longitude;
      }
      else {
        return false;
      }
    }

    /**
     * Get the southwest latitude of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return float|string southwest latitude boundary
     */
    public function getBoundsSouthwestLatitude() {
      return $this->boundsSouthwestLatitude;
    }

    /**
     * Get the southwest longitude of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return float|string southwest longitude boundary
     */
    public function getBoundsSouthwestLongitude() {
      return $this->boundsSouthwestLongitude;
    }

    /**
     * Set the northeast coordinates of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @param  float|string $latitude northeast latitude boundary
     * @param  float|string $longitude northeast longitude boundary
     * @return GoogleMapsGeocoder
     */
    public function setBoundsNortheast($latitude, $longitude) {
      $this->boundsNortheastLatitude = $latitude;
      $this->boundsNortheastLongitude = $longitude;

      return $this;
    }

    /**
     * Get the northeast coordinates of the bounding box within which to bias
     * geocode results in comma-separated format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return string|false comma-separated coordinates, or false if not set
     */
    public function getBoundsNortheast() {
      $latitude = $this->getBoundsNortheastLatitude();
      $longitude = $this->getBoundsNortheastLongitude();

      if ($latitude && $longitude) {
        return $latitude . "," . $longitude;
      }
      else {
        return false;
      }
    }

    /**
     * Get the northeast latitude of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return float|string northeast latitude boundary
     */
    public function getBoundsNortheastLatitude() {
      return $this->boundsNortheastLatitude;
    }

    /**
     * Get the northeast longitude of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#Viewports
     * @return float|string northeast longitude boundary
     */
    public function getBoundsNortheastLongitude() {
      return $this->boundsNortheastLongitude;
    }

    /**
     * Set the two-character, top-level domain (ccTLD) within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#RegionCodes
     * @param  string $region two-character, top-level domain (ccTLD)
     * @return GoogleMapsGeocoder
     */
    public function setRegion($region) {
      $this->region = $region;

      return $this;
    }

    /**
     * Get the two-character, top-level domain (ccTLD) within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#RegionCodes
     * @return string two-character, top-level domain (ccTLD)
     */
    public function getRegion() {
      return $this->region;
    }

    /**
     * Set the language code in which to return results.
     *
     * @link   https://developers.google.com/maps/faq#languagesupport
     * @param  string $language language code
     * @return GoogleMapsGeocoder
     */
    public function setLanguage($language) {
      $this->language = $language;

      return $this;
    }

    /**
     * Get the language code in which to return results.
     *
     * @link   https://developers.google.com/maps/faq#languagesupport
     * @return string language code
     */
    public function getLanguage() {
      return $this->language;
    }

    /**
     * Set the address type(s) to restrict results to.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#reverse-restricted
     * @param  string|array $resultType address type(s)
     * @return GoogleMapsGeocoder
     */
    public function setResultType($resultType) {
      $this->resultType = is_array($resultType) ? $resultType : array($resultType);

      return $this;
    }

    /**
     * Get the address type(s) to restrict results to.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#reverse-restricted
     * @return array address type(s)
     */
    public function getResultType() {
      return $this->resultType;
    }

    /**
     * Get the address type(s) to restrict results to separated by a pipe (|).
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#reverse-restricted
     * @return string address type(s) separated by a pipe (|)
     */
    public function getResultTypeFormatted() {
      return implode('|', $this->getResultType());
    }

    /**
     * Set the location type(s) to restrict results to.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#reverse-restricted
     * @param  string|array $locationType location type(s)
     * @return GoogleMapsGeocoder
     */
    public function setLocationType($locationType) {
      $this->locationType = is_array($locationType) ? $locationType : array($locationType);

      return $this;
    }

    /**
     * Get the location type(s) to restrict results to.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#reverse-restricted
     * @return array location type(s)
     */
    public function getLocationType() {
      return $this->locationType;
    }

    /**
     * Get the location type(s) to restrict results to separated by a pipe (|).
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#reverse-restricted
     * @return string location type(s) separated by a pipe (|)
     */
    public function getLocationTypeFormatted() {
      return implode('|', $this->getLocationType());
    }

    /**
     * Set whether the request is from a device with a location sensor.
     *
     * @deprecated 2.3.0 no longer required by the Google Maps API
     * @link       https://developers.google.com/maps/documentation/geocoding/intro#Sensor
     * @param      bool|string $sensor boolean or 'true'/'false' string
     * @return     GoogleMapsGeocoder
     */
    public function setSensor($sensor) {
      if ($sensor == 'true' || $sensor == 'false') {
        $this->sensor = $sensor;
      }
      elseif ($sensor) {
        $this->sensor = "true";
      }
      else {
        $this->sensor = "false";
      }

      return $this;
    }

    /**
     * Get whether the request is from a device with a location sensor.
     *
     * @deprecated 2.3.0 no longer required by the Google Maps API
     * @link       https://developers.google.com/maps/documentation/geocoding/intro#Sensor
     * @return     string 'true' or 'false'
     */
    public function getSensor() {
      return $this->sensor;
    }

    /**
     * Set the API key to authenticate with.
     *
     * @link   https://developers.google.com/console/help/new/#UsingKeys
     * @param  string $apiKey API key
     * @return GoogleMapsGeocoder
     */
    public function setApiKey($apiKey) {
      $this->apiKey = $apiKey;

      return $this;
    }

    /**
     * Get the API key to authenticate with.
     *
     * @link   https://developers.google.com/console/help/new/#UsingKeys
     * @return string API key
     */
    public function getApiKey() {
      return $this->apiKey;
    }
    /**
     * Set the client ID for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#client_id
     * @param  string $clientId client ID
     * @return GoogleMapsGeocoder
     */
    public function setClientId($clientId) {
      $this->clientId = $clientId;

      return $this;
    }

    /**
     * Get the client ID for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#client_id
     * @return string client ID
     */
    public function getClientId() {
      return $this->clientId;
    }

    /**
     * Set the cryptographic signing key for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#cryptographic_signing_key
     * @param  string $signingKey cryptographic signing key
     * @return GoogleMapsGeocoder
     */
    public function setSigningKey($signingKey) {
      $this->signingKey = $signingKey;

      return $this;
    }

    /**
     * Get the cryptographic signing key for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#cryptographic_signing_key
     * @return string cryptographic signing key
     */
    public function getSigningKey() {
      return $this->signingKey;
    }

    /**
     * Whether the request is for a Business client.
     *
     * @return bool whether the request is for a Business client
     */
    public function isBusinessClient() {
      return $this->getClientId() && $this->getSigningKey();
    }

    /**
     * Generate the signature for a Business client geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/auth#digital_signatures
     * @param  string $pathQueryString path and query string of the request
     * @return string Base64 encoded signature that's URL safe
     */
    private function generateSignature($pathQueryString) {
      $decodedSigningKey = self::base64DecodeUrlSafe($this->getSigningKey());

      $signature = hash_hmac('sha1', $pathQueryString, $decodedSigningKey, true);
      $signature = self::base64EncodeUrlSafe($signature);

      return $signature;
    }

    /**
     * Build the query string with all set parameters of the geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingRequests
     * @return string encoded query string of the geocode request
     */
    private function geocodeQueryString() {
      $queryString = array();

      // One of the following is required.
      $address = $this->getAddress();
      $latitudeLongitude = $this->getLatitudeLongitude();

      // If both are set for some reason, favor address to geocode.
      if ($address) {
        $queryString['address'] = $address;
      }
      elseif ($latitudeLongitude) {
        $queryString['latlng'] = $latitudeLongitude;
      }

      // Optional parameters.
      $queryString['bounds'] = $this->getBounds();
      $queryString['region'] = $this->getRegion();
      $queryString['language'] = $this->getLanguage();
      $queryString['result_type'] = $this->getResultTypeFormatted();
      $queryString['location_type'] = $this->getLocationTypeFormatted();

      // Required.
      $queryString['sensor'] = $this->getSensor();

      // Remove any unset parameters.
      $queryString = array_filter($queryString);

      // The signature is added later using the path + query string.
      if ($this->isBusinessClient()) {
        $queryString['client'] = $this->getClientId();
      }
      elseif ($this->getApiKey()) {
        $queryString['key'] = $this->getApiKey();
      }

      // Convert array to proper query string.
      return http_build_query($queryString);
    }

    /**
     * Build the URL (with query string) of the geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingRequests
     * @param  bool $https whether to make the request over HTTPS
     * @return string URL of the geocode request
     */
    private function geocodeUrl($https = false) {
      // HTTPS is required if an API key is set.
      if ($https || $this->getApiKey()) {
        $scheme = "https";
      }
      else {
        $scheme = "http";
      }

      $pathQueryString = self::URL_PATH . $this->getFormat() . "?" . $this->geocodeQueryString();

      if ($this->isBusinessClient()) {
        $pathQueryString .= "&signature=" . $this->generateSignature($pathQueryString);
      }

      return $scheme . "://" . self::URL_DOMAIN . $pathQueryString;
    }

    /**
     * Execute the geocoding request. The return type is based on the requested
     * format: associative array if JSON, SimpleXMLElement object if XML.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingResponses
     * @param  bool $https whether to make the request over HTTPS
     * @param  bool $raw whether to return the raw (string) response
     * @return string|array|SimpleXMLElement response in requested format
     */
    public function geocode($https = false, $raw = false) {
      $response = file_get_contents($this->geocodeUrl($https));

      if ($raw) {
        return $response;
      }
      elseif ($this->isFormatJson()) {
        return json_decode($response, true);
      }
      elseif ($this->isFormatXml()) {
        return new SimpleXMLElement($response);
      }
      else {
        return $response;
      }
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
     * @param  float|string $latitude to draw the bounding box around
     * @param  float|string $longitude to draw the bounding box around
     * @param  int|float|string $mileRange mile range around point
     * @return array 'lat' and 'lon' 'min' and 'max' points
     */
    public static function boundingBox($latitude, $longitude, $mileRange) {
      $maxLatitude = $latitude + $mileRange / self::EQUATOR_LAT_DEGREE_IN_MILES;
      $minLatitude = $latitude - ($maxLatitude - $latitude);

      $maxLongitude = $longitude + $mileRange / (cos($minLatitude * M_PI / 180) * self::EQUATOR_LAT_DEGREE_IN_MILES);
      $minLongitude = $longitude - ($maxLongitude - $longitude);

      return array('lat' => array('max' => $maxLatitude,
                                  'min' => $minLatitude),
                   'lon' => array('max' => $maxLongitude,
                                  'min' => $minLongitude));
    }

    /**
     * Encode a string with Base64 using only URL safe characters.
     *
     * @param  string $value value to encode
     * @return string encoded value
     */
    private static function base64EncodeUrlSafe($value) {
      return strtr(base64_encode($value), '+/', '-_');
    }

    /**
     * Decode a Base64 string that uses only URL safe characters.
     *
     * @param  string $value value to decode
     * @return string decoded value
     */
    private static function base64DecodeUrlSafe($value) {
      return base64_decode(strtr($value, '-_', '+/'));
    }

  }

?>
