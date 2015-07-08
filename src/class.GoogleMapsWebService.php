<?php

/**
 * A PHP wrapper for the Google Maps Geocoding API v3.
*
* @author    Justin Stayton
* @author    Stephan Watermeyer
* @copyright Copyright 2015 by Justin Stayton 
* @license   https://github.com/jstayton/Miner/blob/master/LICENSE-MIT MIT
* @link      https://developers.google.com/maps/documentation/geocoding/
* @package   GoogleMapsWebService
* @version   0.0.1
*/
abstract class GoogleMapsWebService {

	/**
	 * Domain portion of the Google Geocoding API URL.
	 */
	const URL_DOMAIN = "maps.googleapis.com";

	/**
	 * Path portion of the Google Geocoding API URL.
	 */
	private $mServiceUrlPath = "";

	/**
	 * HTTP URL of the Google Geocoding API.
	 */
	private $mServiceHttpUrl = "";

	/**
	 * HTTPS URL of the Google Geocoding API.
	 */
	private $mServiceHttpsURL = "";

	/**
	 * JSON response format.
	 */
	const FORMAT_JSON = "json";

	/**
	 * XML response format.
	 */
	const FORMAT_XML = "xml";

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
	public function __construct($pServiceUrlPath, $pServiceHttpsUrl, $pServiceHttpUrl, $format = self::FORMAT_JSON, $sensor = false) {
		$this->mServiceUrlPath = $pServiceUrlPath;
		$this->mServiceHttpUrl = $pServiceHttpUrl;
		$this->mServiceHttpsURL = $pServiceHttpsUrl;
		$this->setFormat($format)->setSensor($sensor);
	}

	/**
	 * Set the response format.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#GeocodingResponses
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
	 * @link   https://developers.google.com/maps/documentation/geocoding/#GeocodingResponses
	 * @return string response format
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * Whether the response format is JSON.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#JSON
	 * @return bool whether JSON
	 */
	public function isFormatJson() {
		return $this->getFormat() == self::FORMAT_JSON;
	}

	/**
	 * Whether the response format is XML.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#XML
	 * @return bool whether XML
	 */
	public function isFormatXml() {
		return $this->getFormat() == self::FORMAT_XML;
	}

	/**
	 * Set the language code in which to return results.
	 *
	 * @link   https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
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
	 * @link   https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
	 * @return string language code
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Set whether the request is from a device with a location sensor.
	 *
	 * @param  bool|string $sensor boolean or 'true'/'false' string
	 * @return GoogleMapsGeocoder
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
	 * @return string 'true' or 'false'
	 */
	public function getSensor() {
		return $this->sensor;
	}

	/**
	 * Set the API key to authenticate with.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#api_key
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
	 * @link   https://developers.google.com/maps/documentation/geocoding/#api_key
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

	abstract function geocodeQueryStringOverride($queryString);
	
	/**
	 * Build the query string with all set parameters of the geocode request.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#GeocodingRequests
	 * @return string encoded query string of the geocode request
	 */
	function geocodeQueryString() {
		$queryString = array();
	    $queryString = $this->geocodeQueryStringOverride($queryString);
  	    $queryString['language'] = $this->getLanguage();
  
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
        return $this->override_http_build_query($queryString);
	}

	/**
	 * Hook to be able to override the building of the query. <br/>
	 * This hook was necessary as it *seems* that the DirectionService is not able to handle the ampersands in the URL correctly.<br/> 
	 * Geocoding works fine with encoded ampersands.<br/>
	 * 
	 * @param array $pQuery
	 * @return string
	 */
	function override_http_build_query($pQuery) {
		return http_build_query($pQuery);
	}
	
	/**
	 * Build the URL (with query string) of the geocode request.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#GeocodingRequests
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

		$pathQueryString = $this->mServiceUrlPath . $this->getFormat() . "?" . $this->geocodeQueryString();

		if ($this->isBusinessClient()) {
			$pathQueryString .= "&signature=" . $this->generateSignature($pathQueryString);
		}
		return $scheme . "://" . self::URL_DOMAIN . $pathQueryString;
	}


	/**
	 * Execute the geocoding request. The return type is based on the requested
	 * format: associative array if JSON, SimpleXMLElement object if XML.
	 *
	 * @link   https://developers.google.com/maps/documentation/geocoding/#GeocodingResponses
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