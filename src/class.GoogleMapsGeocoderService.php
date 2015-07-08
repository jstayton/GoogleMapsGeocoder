<?php

  class GoogleMapsGeocoderService extends GoogleMapsWebService {
   
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
     * A commonly-used alternative name for the entity.
     */
    const TYPE_COLLOQUIAL_AREA = "colloquial_area";

    /**
     * An incorporated city or town.
     */
    const TYPE_LOCALITY = "locality";

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
     * A specific postal box.
     */
    const TYPE_POST_BOX = "post_box";

    /**
     * A precise street number.
     */
    const TYPE_STREET_NUMBER = "street_number";

    /**
     * A floor of a building address.
     */
    const TYPE_FLOOR = "floor";

    /**
     * A room of a building address.
     */
    const TYPE_ROOM = "room";

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
     * Whether the request is from a device with a location sensor.
     *
     * @var string
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
     * @param  bool|string $sensor optional whether device has location sensor
     * @return GoogleMapsGeocoder
     */
   function __construct($address, $format = self::FORMAT_JSON, $sensor = false) {
      parent::__construct(self::URL_PATH, self::URL_HTTP, self::URL_HTTPS, $format, $sensor);
      $this->setAddress($address)
      ->setFormat($format)
      ->setSensor($sensor);
    }

    /**
     * Set the address to geocode.
     *
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
     * @return string
     */
    public function getAddress() {
      return $this->address;
    }

    /**
     * Set the latitude/longitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
     * @return float|string latitude to reverse geocode
     */
    public function getLatitude() {
      return $this->latitude;
    }

    /**
     * Set the longitude to reverse geocode to the closest address.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
     * @return float|string longitude to reverse geocode
     */
    public function getLongitude() {
      return $this->longitude;
    }

    /**
     * Set the bounding box coordinates within which to bias geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
     * @return float|string southwest latitude boundary
     */
    public function getBoundsSouthwestLatitude() {
      return $this->boundsSouthwestLatitude;
    }

    /**
     * Get the southwest longitude of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
     * @return float|string southwest longitude boundary
     */
    public function getBoundsSouthwestLongitude() {
      return $this->boundsSouthwestLongitude;
    }

    /**
     * Set the northeast coordinates of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
     * @return float|string northeast latitude boundary
     */
    public function getBoundsNortheastLatitude() {
      return $this->boundsNortheastLatitude;
    }

    /**
     * Get the northeast longitude of the bounding box within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#Viewports
     * @return float|string northeast longitude boundary
     */
    public function getBoundsNortheastLongitude() {
      return $this->boundsNortheastLongitude;
    }

    /**
     * Set the two-character, top-level domain (ccTLD) within which to bias
     * geocode results.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#RegionCodes
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
     * @link   https://developers.google.com/maps/documentation/geocoding/#RegionCodes
     * @return string two-character, top-level domain (ccTLD)
     */
    public function getRegion() {
      return $this->region;
    }

    /**
     * Build the query string with all set parameters of the geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/#GeocodingRequests
     * @return string encoded query string of the geocode request
     */
    function geocodeQueryStringOverride($queryString) {
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

      return $queryString;
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

  }

?>