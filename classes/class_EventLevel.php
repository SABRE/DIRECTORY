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
	# * FILE: /classes/class_EventLevel.php
	# ----------------------------------------------------------------------------------------------------

	class EventLevel {

		##################################################
		# PRIVATE
		##################################################

		var $default;
		var $value;
		var $name;
		var $detail;
		var $images;
		var $price;
		var $content;
        var $active;

		function EventLevel($listAll = false, $domain_id = false) {
			
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id) {
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			} else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
            
			unset($dbMain);
            
		    $sql = "";
            
            if (!defined("ALL_EVENTLEVEL_INFORMATION") || !defined("ACTIVE_EVENTLEVEL_INFORMATION")) {
               $sql = "SELECT * FROM EventLevel WHERE theme = ".db_formatString(EDIR_THEME ? EDIR_THEME : "default")." ORDER BY value DESC";
            }
            
            if ($sql != "") {
                $result = $dbObj->query($sql);
                unset($eventLevelAux);
                unset($eventLevelAuxAll);
                $i = 0;
                $j = 0;
                while ($row = mysql_fetch_assoc($result)) {
                    foreach ($row as $key => $value) {
                        if ($row["active"] == "y"){
                            if ($key == "defaultlevel" && $value == "y") $eventLevelAuxAll[$j]["default"] = $row["value"];
                            $eventLevelAuxAll[$j][$key] = $value;
                            
                        } 
                        if ($key == "defaultlevel" && $value == "y") $eventLevelAux[$i]["default"] = $row["value"];
                        $eventLevelAux[$i][$key] = $value; 
                    }
                    $i++;
                    $j++;
                }
            }

            if (is_array($eventLevelAux)) {
                if (!defined("ALL_EVENTLEVEL_INFORMATION")) {
                    define("ALL_EVENTLEVEL_INFORMATION", serialize($eventLevelAux));
                }
            }
            
            if (is_array($eventLevelAuxAll)) {
                if (!defined("ACTIVE_EVENTLEVEL_INFORMATION")) {
                    define("ACTIVE_EVENTLEVEL_INFORMATION", serialize($eventLevelAuxAll));
                }
            }

            if ($listAll) {
                $eventLevelAux = unserialize(ALL_EVENTLEVEL_INFORMATION);
            } else {
                $eventLevelAux = unserialize(ACTIVE_EVENTLEVEL_INFORMATION);
            }

            if (is_array($eventLevelAux)) {
                foreach ($eventLevelAux as $eventLevel) {
                    if ($eventLevel["defaultlevel"] == "y") $this->default = $eventLevel["value"];
                    $this->value[] = $eventLevel["value"];
                    $this->name[] = $eventLevel["name"];
                    $this->detail[] = $eventLevel["detail"];
                    $this->images[] = $eventLevel["images"];
                    $this->price[] = $eventLevel["price"];
                    $this->content[] = $eventLevel["content"];
                    $this->active[] = $eventLevel["active"];

                }
            }

		}

		function getValues() {
			return $this->value;
		}

		function getNames() {
			return $this->name;
		}

		function union($key, $value) {
			for ($i=0; $i<count($key); $i++) {
				$aux[$key[$i]] = $value[$i];
			}
			return $aux;
		}

		function getValueName() {
			return $this->union($this->getValues(), $this->getNames());
		}

        function getDefault() {
            $activeArray =  array_filter($this->union($this->value, $this->active), 'validateActive');
            if(array_key_exists($this->default, $activeArray)) {
                return $this->default;
            } else {
                krsort($activeArray);
                $newActiveArray = array_keys($activeArray);
                return $newActiveArray[0];
            }
        }

		function getName($value) {
			if (is_numeric($value)){
				$value_name = $this->getValueName();
				return $value_name[$value];
			}
		}

		##################################################
		# PRIVATE
		##################################################

		##################################################
		# PUBLIC
		##################################################

		function getLevel($value) {
			if ($this->getName($value)) return $this->getName($value);
			else return $this->getLevel($this->getDefaultLevel());
		}

		function getDetail($value) {
			$detailArray = $this->union($this->value, $this->detail);
			if (isset($detailArray[$value])) return $detailArray[$value];
			else return $detailArray[$this->default];
		}

		function getImages($value) {
			$imagesArray = $this->union($this->value, $this->images);
			if (isset($imagesArray[$value])) return $imagesArray[$value];
			else return $imagesArray[$this->default];
		}

		function getPrice($value) {
			$priceArray = $this->union($this->value, $this->price);
			if (isset($priceArray[$value])) return $priceArray[$value];
			else return $priceArray[$this->default];
		}

		function getContent($value) {
			
			$contentArray = $this->union($this->value, $this->content);
    		if (isset($contentArray[$value])) return $contentArray[$value];
			else return $contentArray[$this->default];
			
		}

		function getDefaultLevel() {
			return $this->getDefault();
		}

		function getLevelValues() {
			return $this->getValues();
		}

		function getLevelNames() {
			return $this->getNames();
		}

		function showLevel($value) {
			if ($this->getName($value)) return string_ucwords($this->getName($value));
			else return string_ucwords($this->getLevel($this->getDefaultLevel()));
		}

		function showLevelNames() {
			$names = $this->getNames();
			foreach ($names as $name) {
				$array[] = string_ucwords($name);
			}
			return $array;
		}

        function getActive($value) {
            $activeArray = $this->union($this->value, $this->active);            
            return $activeArray[$value];            
        }
        
        function getLevelActive($value) {
            if ($this->getActive($value) == 'y') return $value;
            else return $this->getDefaultLevel();
        }
        
        function getLevelOrdering($value) {
            switch ( $value ) {
                case 10:
                    return system_showText(LANG_SITEMGR_FIRST);
                    break;
                case 30:
                    return system_showText(LANG_SITEMGR_SECOND);
                    break;
                case 50:
                    return system_showText(LANG_SITEMGR_THIRD);
                    break;
            }
        }
        
        function updateValues($name, $active, $levelValue){
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);
            $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
            
            $sql = "UPDATE EventLevel SET name = '".$name."', active = '".$active."' WHERE value = ".$levelValue." AND theme = '".(EDIR_THEME ? EDIR_THEME : "default")."'";
            $dbObj->query($sql);
            
        }

		function updatePricing($field, $fieldValue, $level){
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);
            $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
            
            $sql = "UPDATE EventLevel SET $field = ".$fieldValue." WHERE value = ".$level." AND theme = '".(EDIR_THEME ? EDIR_THEME : "default")."'";
            $dbObj->query($sql);
        }
		
		##################################################
		# PUBLIC
		##################################################

	}

?>
