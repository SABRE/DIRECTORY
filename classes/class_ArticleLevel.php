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
	# * FILE: /classes/class_ArticleLevel.php
	# ----------------------------------------------------------------------------------------------------

	class ArticleLevel {

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

		function ArticleLevel($listAll = false, $domain_id = false) {
				
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			} else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
            
            $sql = "";
            
            if (!defined("ALL_ARTICLELEVEL_INFORMATION") || !defined("ACTIVE_ARTICLELEVEL_INFORMATION")) {
               $sql = "SELECT * FROM ArticleLevel WHERE theme = ".db_formatString(EDIR_THEME ? EDIR_THEME : "default")." ORDER BY value DESC";
            }
            
            if ($sql != "") {
                $result = $dbObj->query($sql);
                unset($articleLevelAux);
                unset($articleLevelAuxAll);
                $i = 0;
                $j = 0;
                while ($row = mysql_fetch_assoc($result)) {
                    foreach ($row as $key => $value) {
                        if ($row["active"] == "y"){
                            if ($key == "defaultlevel" && $value == "y") $articleLevelAuxAll[$j]["default"] = $row["value"];
                            $articleLevelAuxAll[$j][$key] = $value;
                            
                        } 
                        if ($key == "defaultlevel" && $value == "y") $articleLevelAux[$i]["default"] = $row["value"];
                        $articleLevelAux[$i][$key] = $value; 
                    }
                    $i++;
                    $j++;
                }
            }

            if (is_array($articleLevelAux)) {
                if (!defined("ALL_ARTICLELEVEL_INFORMATION")) {
                    define("ALL_ARTICLELEVEL_INFORMATION", serialize($articleLevelAux));
                }
            }
            
            if (is_array($articleLevelAuxAll)) {
                if (!defined("ACTIVE_ARTICLELEVEL_INFORMATION")) {
                    define("ACTIVE_ARTICLELEVEL_INFORMATION", serialize($articleLevelAuxAll));
                }
            }

            if ($listAll) {
                $articleLevelAux = unserialize(ALL_ARTICLELEVEL_INFORMATION);
            } else {
                $articleLevelAux = unserialize(ACTIVE_ARTICLELEVEL_INFORMATION);
            }

            if (is_array($articleLevelAux)) {
                foreach ($articleLevelAux as $articleLevel) {
                    if ($articleLevel["defaultlevel"] == "y") $this->default = $articleLevel["value"];
                    $this->value[] = $articleLevel["value"];
                    $this->name[] = $articleLevel["name"];
                    $this->detail[] = $articleLevel["detail"];
                    $this->images[] = $articleLevel["images"];
                    $this->price[] = $articleLevel["price"];
                    $this->content[] = $articleLevel["content"];
                    $this->active[] = $articleLevel["active"];

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
                case 50:
                    return system_showText(LANG_SITEMGR_FIRST);
                    break;
                case 30:
                    return system_showText(LANG_SITEMGR_SECOND);
                    break;
                case 10:
                    return system_showText(LANG_SITEMGR_THIRD);
                    break;
            }
        }
        
        function updateValues($name, $active, $levelValue){
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);
            $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
            
            $sql = "UPDATE ArticleLevel SET name = '".$name."', active = '".$active."' WHERE value = ".$levelValue." AND theme = '".(EDIR_THEME ? EDIR_THEME : "default")."'";
            $dbObj->query($sql);
            
        }
        
        function updatePricing($field, $fieldValue, $level){
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);
            $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
            
            $sql = "UPDATE ArticleLevel SET $field = ".$fieldValue." WHERE value = ".$level." AND theme = '".(EDIR_THEME ? EDIR_THEME : "default")."'";
            $dbObj->query($sql);
        }

		##################################################
		# PUBLIC
		##################################################

	}

?>
