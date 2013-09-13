<?php

/**
 * 
 * Class to find latitude and longitude through of the location
 * How use it:
 * $geoLoc = new GeoLocation("Bauru, SP");
 * $bruLatitude = $geoLoc->latitude;
 * $bruLongitude = $geoLoc->longitude;
 * 
 * @author Roberto C‰ndido da Silva
 * @since 2010-09-02
 *
 */
class GeoLocation {
	
	var $url = "http://maps.google.com/maps/api/geocode/json?address=%s&sensor=false";
	
	var $latitude = 0;
	var $longitude = 0;
	
	/**
	 * 
	 * Constructor 
	 * @param $location Location eg.: Bauru, SP
	 */
	public function __construct($location) {
		$location = urlencode($location);
		$this->url = sprintf($this->url, $location);
		$cUrl = curl_init();
		curl_setopt($cUrl, CURLOPT_URL, $this->url);
		curl_setopt($cUrl, CURLOPT_HEADER, FALSE);
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($cUrl);
		curl_close($cUrl);
		$result = json_decode($content);
		$location = $result->results[0]->geometry->location;
		$this->latitude = $location->lat;
		$this->longitude = $location->lng;
	}
	
}

?>