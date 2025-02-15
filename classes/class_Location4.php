<?

	/*==================================================================*\
	######################################################################
	#                                                                    #
	# Copyright 2005 Arca Solutions, Inc. All Rights Reserved.           #
	#                                                                    #
	# This file may not be redistributed in whole or part.               #
	# eDirectory is licensed on a per-domain basis.                      #
	#                                                                    #
	# ---------------- eDirectory IS NOT FREE SOFTWARE ----------------- #
	#                                                                    #
	# http://www.edirectory.com | http://www.edirectory.com/license.html #
	######################################################################
	\*==================================================================*/

	# ----------------------------------------------------------------------------------------------------
	# * FILE: /classes/class_Location4.php
	# ----------------------------------------------------------------------------------------------------

	class Location4 extends Handle {

		var $id;
		var $location_3;
		var $location_2;
		var $location_1;
		var $name;
		var $abbreviation;
		var $friendly_url;
		var $seo_description;
		var $seo_keywords;
		var $latitude;
        var $longitude;


		function Location4($var='') {
			if (is_numeric($var) && ($var)) {
				$db = db_getDBObject(DEFAULT_DB,true);
				$sql = "SELECT * FROM Location_4 WHERE id = $var";
				$row = mysql_fetch_array($db->query($sql));
				$this->makeFromRow($row);
			} else {
                if (!is_array($var)) {
                    $var = array();
                }
				$this->makeFromRow($var);
			}
		}

		function makeFromRow($row='') {

			if ($row['id']) $this->id = $row['id'];
			else if (!$this->id) $this->id = 0;

			if ($row['location_3']) $this->location_3 = $row['location_3'];
			else if (!$this->location_3) $this->location_3 = 0;

			if ($row['location_2']) $this->location_2 = $row['location_2'];
			else if (!$this->location_2) $this->location_2 = 0;

			if ($row['location_1']) $this->location_1 = $row['location_1'];
			else if (!$this->location_1) $this->location_1 = 0;

			if ($row['name']) $this->name = $row['name'];
			else if (!$this->name) $this->name = "";

			$this->abbreviation = $row['abbreviation'];

			if ($row['friendly_url']) $this->friendly_url = $row['friendly_url'];
			else if (!$this->friendly_url) $this->friendly_url = "";

			$this->seo_description = $row['seo_description'];
			$this->seo_keywords = $row['seo_keywords'];
			
			$this->latitude  = $row['latitude']  ? $row['latitude']  : ($this->latitude  ? $this->latitude  : 0);
            $this->longitude = $row['longitude'] ? $row['longitude'] : ($this->longitude ? $this->longitude : 0);
		}

		function Save($updFullText = false) {

			$this->prepareToSave();

			$dbObj = db_getDBObject(DEFAULT_DB,true);

			$this->friendly_url = string_strtolower($this->friendly_url);

			if ($this->id) {
				system_logLocationChanges($this->id, 4, $this->location_1, 1);
				system_logLocationChanges($this->id, 4, $this->location_2, 2);
				system_logLocationChanges($this->id, 4, $this->location_3, 3);

				$sql  = "UPDATE Location_4 SET"
					. " location_1 = $this->location_1,"
					. " location_2 = $this->location_2,"
					. " location_3 = $this->location_3,"
					. " name = $this->name,"
					. " abbreviation = $this->abbreviation,"
					. " friendly_url = $this->friendly_url,"
					. " seo_description = $this->seo_description,"
					. " seo_keywords = $this->seo_keywords"
					. " WHERE id = $this->id";
				$dbObj->query($sql);
			} else {
				$sql = "INSERT INTO Location_4"
					. " (location_3, location_2, location_1, name, abbreviation, friendly_url, seo_description, seo_keywords)"
					. " VALUES"
					. " ($this->location_3, $this->location_2, $this->location_1, $this->name, $this->abbreviation, $this->friendly_url, $this->seo_description, $this->seo_keywords)";
				$dbObj->query($sql);
				$this->id = mysql_insert_id($dbObj->link_id);
			}

			$this->prepareToUse();

			if ($updFullText) $this->updateFullTextItems();

		}

		function Delete($updFullText = false) {

			if ($this->id) {
				$dbObjMain = db_getDBObject(DEFAULT_DB,true);

				$sqlDomain = "SELECT `id` FROM `Domain` WHERE `status` = 'A'";
				$resDomain = $dbObjMain->Query($sqlDomain);
				while ($row = mysql_fetch_assoc($resDomain)) {
					unset($dbObj);
					$dbObj = db_getDBObjectByDomainID($row["id"], $dbObjMain);

					//Listing
					$sql = "UPDATE Listing SET location_4 = 0, location_5 = 0 WHERE location_4 = $this->id";
					$result = $dbObj->query($sql);
                    
                    //Promotion
					$sql = "UPDATE Promotion SET    listing_location4 = 0, 
                                                    listing_location5 = 0 
                            WHERE listing_location4 = $this->id";
                    $result = $dbObj->query($sql);

					//Listing_Summary
					$sql = "UPDATE Listing_Summary SET location_4 = 0,
													   location_4_title = '',
													   location_4_abbreviation = '',
													   location_4_friendly_url = '',
													   location_5 = 0,
													   location_5_title = '',
													   location_5_abbreviation = '',
													   location_5_friendly_url = ''
							WHERE location_4 = $this->id";
					$result = $dbObj->query($sql);

					//Event
					$sql = "UPDATE Event SET location_4 = 0, location_5 = 0 WHERE location_4 = $this->id";
					$result = $dbObj->query($sql);
					//Classified
					$sql = "UPDATE Classified SET location_4 = 0, location_5 = 0 WHERE location_4 = $this->id";
					$result = $dbObj->query($sql);
				}
				unset($rowDomain);

				$_locations = explode(",", EDIR_LOCATIONS);
				system_retrieveLocationRelationship ($_locations, 4, $_location_father_level, $_location_child_level);
				if ($_location_child_level) {
					$sql = "SELECT id FROM Location_".$_location_child_level." WHERE location_4=".$this->id;
					$result = $dbObjMain->query($sql);
					if(mysql_num_rows($result) > 0){
						while($row = mysql_fetch_assoc($result)){
							$objLocationLabel = "Location".$_location_child_level;
							unset(${"Location".$_location_child_level});
							${"Location".$_location_child_level} = new $objLocationLabel;
							${"Location".$_location_child_level}->SetNumber("id", $row["id"]);
							${"Location".$_location_child_level}->Delete();
						}
					}
				}
				$sql = "DELETE FROM Location_4 WHERE id = $this->id";
				$dbObjMain->query($sql);
			}
		}

		function updateFullTextItemsLoc($listings_ids=false) {

			if (!$listings_ids) {

				if ($this->id) {

					$dbObj = db_getDBObject(DEFAULT_DB,true);
					$sql = "SELECT id FROM Listing WHERE Location_4 = $this->id";
					$result = $dbObj->query($sql);

					while ($row = mysql_fetch_array($result)) {
						 if ($row['id']) {
							$listingObj = new Listing($row['id']);
							$listingObj->setFullTextSearch(true,4);
							unset($listingObj);
						 }
					}
					return true;
				}
				return false;
			}
			else {
				foreach ($listings_ids as $listing_id) {
					 if ($listing_id) {
						$listingObj = new Listing($listing_id);
						$listingObj->setFullTextSearch(true,4);
						unset($listingObj);
					 }
				}
				return true;
			}
		}

		function retrieveFeatureds($_locations, $default=false, $last_default_level=false, $last_default_id=false) {

			$locationFeatObj = new LocationFeatured();
			$sql = "";

			$searchReturn["select"]		= false;
			$searchReturn["leftjoin"]	= false;
			$searchReturn["on"]			= false;
			$searchReturn["orderby"]	= false;

			foreach($_locations as $each_location) {

				if ($each_location == 4) {
					$searchReturn["select"][] = "Location_4.* ";
					$searchReturn["orderby"][] = "Location_4.name ";
				} else {
					if ($each_location < 4) {
						$searchReturn["select"][] = "Location_".$each_location.".friendly_url AS location".$each_location."_friendly_url ";
						$searchReturn["leftjoin"][] = "Location_".$each_location." ";
						$searchReturn["on"][] = "Location_4.location_".$each_location."=Location_".$each_location.".id ";
						if ($_locations == $_locations[0])
							$searchReturn["orderby"][] = "Location_4.location_".$each_location." ";
					}
				}
			}

			$sql .= "SELECT ";
			$sql .= implode(" , ", $searchReturn["select"]);
			$sql .= " FROM Location_4 ";
			if ($searchReturn["leftjoin"]) {
				$sql .= " LEFT JOIN ( ";
				$sql .= implode(" , ", $searchReturn["leftjoin"]);
				$sql .= " ) ON ( ";
				$sql .= implode(" AND ", $searchReturn["on"]);
				$sql .= " ) ";
			}
			$sql .= " WHERE Location_4.".($default==false?"id IN (".$locationFeatObj->getFeatureds(SELECTED_DOMAIN_ID, 4).")":"id = $default");

			if ($last_default_id)
				$sql .= " AND Location_4.location_".$last_default_level." = ".$last_default_id." ";

			$sql .= " GROUP BY Location_4.id ";
			$sql .= " ORDER BY ";
			$sql .= implode(" , ", $searchReturn["orderby"]);

			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$result =  $dbObj->query($sql);

			$rows = array();
			while ($row = mysql_fetch_array($result)) $rows[] = $row;

			return $rows;

		}



		function isRepeated($father_level, &$error_message){
			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$sql = "SELECT * FROM Location_4 WHERE name = ".db_formatString($this->name);
			if ($father_level !== false) {
				$father_level_value = "location_".$father_level;
				if ($this->$father_level_value) {
					$father_level_value = $this->$father_level_value;
					$sql .= " AND location_".$father_level." = ".$father_level_value;
				}
			}
			if($this->id) $sql .= " AND id != $this->id";
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if($row) {
				$error_message = string_ucwords(LOCATION_TITLE)." ".$this->name." ".system_showText(LANG_SITEMGR_LOCATION_ALREADYEXISTS);
				return true;
			}
			return false;
		}

		function retrievedIfRepeated($_locations) {

			$sql = "SELECT * FROM Location_4 WHERE (friendly_url = ".db_formatString($this->friendly_url)." OR name = ".db_formatString($this->name).") ";

			foreach ($_locations as $each_location) {
				if ($each_location < 4) {
					$attribute = "location_".$each_location;
					$sql.= " AND location_".$each_location." = ".$this->$attribute;
				}
			}

			if ($this->id) $sql .= " AND id != $this->id";

			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);

			if ($row["id"]) {
				return $row["id"];
			} else {
				return false;
			}
		}

		function changeDefault() {
			$sql = "UPDATE Location_4 SET Location_4.default='n' WHERE Location_4.default='y'";
			$db = db_getDBObject(DEFAULT_DB,true);
			$db->query($sql);
			$this->Save();
		}

		function retrieveAllLocation(){
			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$sql = "SELECT * FROM Location_4 ORDER BY name";
			$result = $dbObj->query($sql);
			while($row = mysql_fetch_assoc($result)) $data[] = $row;
			if($data) return $data; else return false;
		}

		function retrieveLocationById(){
			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$sql = "SELECT * FROM Location_4 WHERE id = $this->id";
			$result = $dbObj->query($sql);
			$row = mysql_fetch_assoc($result);
			if($row) return $row; else return false;
		}

		function retrieveLocationByLocation($father_level){
			$father_level_value = "location_".$father_level;
			$father_level_value = $this->$father_level_value;
			if(!$father_level_value) return false;
			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$sql = "SELECT * FROM Location_4 WHERE location_".$father_level." = ".$father_level_value." ORDER BY name";
			$result = $dbObj->query($sql);
			if(mysql_num_rows($result)){
				unset($data);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				if($data){
					return $data;
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}

		function isValidFriendlyUrl($father_level, &$error_message) {

			if(!$this->getString("friendly_url")){
				$error_message = "&#149;&nbsp; Friendly Title is required, please do not leave it blank.";
				return false;
			}
			$dbObj = db_getDBObject(DEFAULT_DB,true);
			$sql = "SELECT friendly_url FROM Location_4 WHERE friendly_url = '".$this->getString("friendly_url")."'";
			if ($father_level !== false) {
				$father_level_value = "location_".$father_level;
				if ($this->$father_level_value) {
					$father_level_value = $this->$father_level_value;
					$sql .= " AND location_".$father_level." = ".$father_level_value;
				}
			}
			if($this->getString("id"))
				$sql .= " AND id != ".$this->getString("id");
			$sql .= " LIMIT 1";
			$rs = $dbObj->query($sql);
			if(mysql_num_rows($rs) > 0){
				$error_message = "&#149;&nbsp; Friendly Title already in use, please choose another Friendly Title";
				return false;
			}
			if(!preg_match(FRIENDLYURL_REGULAREXPRESSION, $this->getString("friendly_url"))){
				$error_message = "&#149;&nbsp; Friendly Url contain invalid chars";
				return false;
			}
			return true;
		}

		function updateFullTextItems() {

			if ($this->id) {

				$dbObj = db_getDBObject(DEFAULT_DB,true);

				//Listing
				if (LISTING_SCALABILITY_OPTIMIZATION != 'on') {
					$sql = "SELECT * FROM Listing WHERE location_4_id = $this->id";
					$result = $dbObj->query($sql);
					while ($row = mysql_fetch_array($result)) {
						$itemObj = new Listing($row['id']);
						$itemObj->setFullTextSearch();
						unset($itemObj);
					}
				}

				//Event
				if (EVENT_FEATURE == 'on' && EVENT_SCALABILITY_OPTIMIZATION != 'on') {
					$sql = "SELECT * FROM Event WHERE location_4_id = $this->id";
					$result = $dbObj->query($sql);
					while ($row = mysql_fetch_array($result)) {
						$itemObj = new Event($row['id']);
						$itemObj->setFullTextSearch();
						unset($itemObj);
					}
				}

				//Classified
				if (CLASSIFIED_FEATURE == 'on' && CLASSIFIED_SCALABILITY_OPTIMIZATION != 'on') {
					$sql = "SELECT * FROM Classified WHERE location_4_id = $this->id";
					$result = $dbObj->query($sql);
					while ($row = mysql_fetch_array($result)) {
						$itemObj = new Classified($row['id']);
						$itemObj->setFullTextSearch();
						unset($itemObj);
					}
				}

				return true;

			}

			return false;

		}
		
		function setCoordinates() {
		
if($this->latitude!=0 && $this->longitude!=0)
				return true;
            $location_string = array();
            $_locations = explode(",", EDIR_LOCATIONS);
            $_locations = array_reverse($_locations);
            foreach($_locations as $_location_level) {
                if($_location_level == 4){
                    $location_string[] = $this->getString('name');
                } else {
                    $varname = "location_".$_location_level;
                    $classname = "Location".$_location_level;
                    $locObj = new $classname();
                    if ($this->$varname) {
                        $locObj->setString('id', $this->$varname);
                        $location_data = $locObj->retrieveLocationById();
                        $location_string[] = $location_data['name'];
                    }
                }
            }
            $location_string = implode(', ', $location_string);

            $geoCoder = new GeoCoder();
            $geoCoder->set_location_string($location_string);
            $point = $geoCoder->get_point();

            $this->latitude = $point['lat'];
            $this->longitude = $point['lng'];

            $dbObj = db_getDBObject(DEFAULT_DB, true);
            $sql = "UPDATE Location_4 SET latitude = '$this->latitude', longitude = '$this->longitude' WHERE id = '$this->id'";
#echo $sql . "\n";
            $dbObj->query($sql);
        }

	}
?>