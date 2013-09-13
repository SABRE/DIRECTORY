<?php

	class GeoCoder extends CURL_handle {

		private $location_string = ""; // Address, City, State, Country Zip
		private $point = null; //lat,long

		// encapsuling

		public function set_point($point) {
			$this->point = $point;
		}
		public function get_point() {
			if($this->point) return $this->point;
			else             return $this->_point_by_string();
		}

		public function set_location_string($location_string) {
			$this->location_string = $location_string;
		}
		public function get_location_string() {
			if($this->location_string) return $this->location_string;
			else             return $this->_string_by_point();
		}

		// privates
		private function _point_by_string() {
			$dbObj_main = db_getDBObject(DEFAULT_DB, true);
			$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj_main);
			$dbObj_main->close();
			$googleSettingObj = new GoogleSettings(GOOGLE_MAPS_SETTING);
			$googleMapsKey = ($googleSettingObj->getString("value"));

			//$url_query = "http://tinygeocoder.com/create-api.php?q=".urlencode($location_string);
			$url_query = "http://maps.google.com/maps/geo?key=$googleMapsKey&sensor=false&output=csv&q=".urlencode($this->get_location_string());

			$this->setUrl($url_query);
			$latlng = $this->exec();


			list($s, $r, $lat, $lng) = explode(",",$latlng);
#print_r($latlng);
			if(is_numeric($lat) && is_numeric($lng) && $lat && $lng) {
				$return = array('lat'=>$lat, 'lng'=>$lng, "point"=>"$lat,$lng");
#print_r($return);
				$this->point = $return;
				return $return;
			} else {
#echo "no return";
				return false;
			}
		}
		private function _string_by_point() {
			$dbObj_main = db_getDBObject(DEFAULT_DB, true);
			$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj_main);
			$dbObj_main->close();
			$googleSettingObj = new GoogleSettings(GOOGLE_MAPS_SETTING);
			$googleMapsKey = ($googleSettingObj->getString("value"));

			//$url_query = "http://maps.google.com/maps/api/geocode/xml?sensor=false&latlng=".urlencode($this->point);
			$url_query = "http://maps.google.com/maps/geo?key=$googleMapsKey&sensor=false&output=xml&q=".urlencode($this->point);

			$this->setUrl($url_query);
			$gresp = $this->exec();

			$edir_locs = explode(',', EDIR_LOCATIONS);
			$xml = new SimpleXMLElement(html_entity_decode($gresp));

			$full_location  = "";
			if(in_array('4', $edir_locs)) {
				if($xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea) {
					$full_location .= $xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->LocalityName.",";
				} else {
					$full_location .= $xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->Locality->LocalityName.",";
				}
			}
			if(in_array('3', $edir_locs)) {
				$full_location .= $xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName.",";
			}
			if(in_array('1', $edir_locs)) {
				$full_location .= $xml->Response->Placemark->AddressDetails->Country->CountryNameCode;
			}

			$full_location = trim($full_location, ',');
			$this->location_string = $full_location;

			//die;

			/*$xml = new SimpleXMLIterator(html_entity_decode($gresp));
			for($xml->rewind(); $xml->valid(); $xml->next()) {
				if($xml->hasChildren()) {
					$result_i = $xml->current();
					$result = new SimpleXMLElement($result_i->asXML());

					$country = "";
					$state = "";

					foreach($result_i->address_component as $component) {
						$component = new SimpleXMLElement($component->asXML());
						$types = (array) $component->type;
						if(in_array("country",$types) && in_array('1', $edir_locs)) {
							$country = (string) $component->long_name;
						} else
						if(in_array("administrative_area_level_1",$types) && in_array('3', $edir_locs)) {
							$state = (string) $component->long_name;
						} else
						if(in_array("locality",$types) && in_array('4', $edir_locs)) {
							$city = (string) $component->long_name;
						}

					} // result
					$full_location = "$city,$state,$country";
					$full_location = trim($full_location, ',');
					$this->location_string = $full_location;
					if($full_location) break;

				} // xml - has child
			}//xml*/
			return $full_location;
		}



	}


?>
