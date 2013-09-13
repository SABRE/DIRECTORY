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
	# * FILE: /classes/class_Navigation.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$navigationObj = new Navigation($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 9.7.00
	 * @package Classes
	 * @name Navigation
	 * @method Navigation
	 * @method makeFromRow
	 * @method Save
	 * @method ClearNavigation
	 * @method WriteNavBar
	 * @method getNavbar
	 * @method ResetNavbar
	 * @access Public
	 */
	class Navigation extends Handle {

		/**
		 * @var integer
		 * @access Private
		 */
		var $order;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $label;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $link;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $area;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $custom;
		
		
		/**
		 * <code>
		 *		$navigationObj = new Navigation($id);
		 *		//OR
		 *		$navigationObj = new Navigation($row);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name Navigation
		 * @access Public
		 * @param mixed $var
		 */
		function Navigation($var='', $domain_id = false) {

			if (is_numeric($var) && ($var)) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if ($domain_id) {
					$this->domain_id = $domain_id;
					$db = db_getDBObjectByDomainID($domain_id, $dbMain);
				} else if (defined("SELECTED_DOMAIN_ID")) {
					$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$db = db_getDBObject();
				}
				unset($dbMain);
				$sql = "SELECT * FROM Navigation WHERE order = $var";

				$row = mysql_fetch_array($db->query($sql));

				unset($db);

				$this->makeFromRow($row);
			} else {
                if (!is_array($var)) {
                    $var = array();
                }
				$this->makeFromRow($var);
			}
		}

		/**
		 * <code>
		 *		$this->makeFromRow($row);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name makeFromRow
		 * @access Public
		 * @param array $row
		 */
		function makeFromRow($row='') {

			$this->order    = ($row["order"])       ? $row["order"]     : ($this->order     ? $this->order  : 0);
			$this->label    = ($row["label"])       ? $row["label"]     : ($this->label     ? $this->label  : "");
			$this->link     = ($row["link"])        ? $row["link"]      : ($this->link      ? $this->link   : "");
			$this->area     = ($row["area"])        ? $row["area"]      : ($this->area      ? $this->area   : "");
			$this->custom   = ($row["custom"])      ? $row["custom"]    : ($this->custom    ? $this->custom : "");
			
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$navigationObj->Save();
		 * <br /><br />
		 *		//Using this in Navigation() class.
		 *		$this->Save();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name Save
		 * @access Public
		 */
		function Save() {
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);

            if ($this->domain_id) {
                $dbObj = db_getDBObjectByDomainID($this->domain_id, $dbMain);
                $aux_log_domain_id = $this->domain_id;
            } else	if (defined("SELECTED_DOMAIN_ID")) {
                $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
                $aux_log_domain_id = SELECTED_DOMAIN_ID;
            } else {
                $dbObj = db_getDBObject();
            }

            unset($dbMain);

            $this->prepareToSave();

            $sql = "INSERT INTO Setting_Navigation (`order`, `label`, `link`, `area`, `custom`, `theme`) VALUES (".$this->order.", ".$this->label.", ".$this->link.", ".$this->area.", ".$this->custom.", '".EDIR_THEME."')";
            $result = $dbObj->query($sql);
            
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$navigationObj->ClearNavigation();
		 * <br /><br />
		 *		//Using this in Navigation() class.
		 *		$this->ClearNavigation();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name ClearNavigation
		 * @access Public
         * @param string $area
		 */
        function ClearNavigation($area) {
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);

            if ($this->domain_id) {
                $dbObj = db_getDBObjectByDomainID($this->domain_id, $dbMain);
                $aux_log_domain_id = $this->domain_id;
            } else	if (defined("SELECTED_DOMAIN_ID")) {
                $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
                $aux_log_domain_id = SELECTED_DOMAIN_ID;
            } else {
                $dbObj = db_getDBObject();
            }

            unset($dbMain);
            $sql = "DELETE FROM Setting_Navigation WHERE `area` = '".$area."' AND theme = '".EDIR_THEME."'";
            $result = $dbObj->query($sql);
            
        }
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$navigationObj->WriteNavBar();
		 * <br /><br />
		 *		//Using this in Navigation() class.
		 *		$this->WriteNavBar();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name WriteNavBar
		 * @access Public
         * @param string $area
		 */
        function WriteNavBar($area) {
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);

            if ($this->domain_id) {
                $dbObj = db_getDBObjectByDomainID($this->domain_id, $dbMain);
                $aux_log_domain_id = $this->domain_id;
            } else	if (defined("SELECTED_DOMAIN_ID")) {
                $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
                $aux_log_domain_id = SELECTED_DOMAIN_ID;
            } else {
                $dbObj = db_getDBObject();
            }
            
            unset($dbMain, $navbar, $classActive, $itemLink, $validation, $closeValidation);
             
            $sql = "SELECT `link`, `label`, `custom` FROM Setting_Navigation WHERE area = '".$area."' AND theme = '".EDIR_THEME."' ORDER BY `order`";
            $result = $dbObj->query($sql);

            if (mysql_num_rows($result)) {
                while ($row = mysql_fetch_assoc($result)) {
                    if ($row["custom"] == "y") {
                        
                        if ($area == "header") {
                            $classActive = "<?=((string_strpos(\$_SERVER[\"REQUEST_URI\"], \"".str_replace(NON_SECURE_URL.EDIRECTORY_FOLDER."/", "", $row["link"])."\") !== false) ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                        }
                        
                        $itemLink = $row["link"];

                    } else {
                                                   
                        //Home
                        if ($row["link"] == "NON_SECURE_URL") {
                            $classActive = "<?=(\$activeMenuHome ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=NON_SECURE_URL?>";
                        //Advertise
                        } elseif ($row["link"] == "ALIAS_ADVERTISE_URL_DIVISOR") {
                            $classActive = "<?=((string_strpos(\$_SERVER[\"REQUEST_URI\"], \"/\".ALIAS_ADVERTISE_URL_DIVISOR.\".php\") !== false) ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=NON_SECURE_URL?>/<?=ALIAS_ADVERTISE_URL_DIVISOR?>.php";
                        //Contact Us
                        } elseif ($row["link"] == "ALIAS_CONTACTUS_URL_DIVISOR") {
                            $classActive = "<?=((string_strpos(\$_SERVER[\"REQUEST_URI\"], \"/\".ALIAS_CONTACTUS_URL_DIVISOR.\".php\") !== false) ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=NON_SECURE_URL?>/<?=ALIAS_CONTACTUS_URL_DIVISOR?>.php";
                        //Listing
                        } elseif ($row["link"] == "LISTING_DEFAULT_URL") {
                            $classActive = "<?=(ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=LISTING_DEFAULT_URL?>/";
                        //Event
                        } elseif ($row["link"] == "EVENT_DEFAULT_URL") {
                            $classActive = "<?=(ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=EVENT_DEFAULT_URL?>/";
                            $validation = "<? if (EVENT_FEATURE == \"on\" && CUSTOM_EVENT_FEATURE == \"on\") { ?>";
                        //Classified
                        } elseif ($row["link"] == "CLASSIFIED_DEFAULT_URL") {
                            $classActive = "<?=(ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=CLASSIFIED_DEFAULT_URL?>/";
                            $validation = "<? if (CLASSIFIED_FEATURE == \"on\" && CUSTOM_CLASSIFIED_FEATURE == \"on\") { ?>";
                        //Article
                        } elseif ($row["link"] == "ARTICLE_DEFAULT_URL") {
                            $classActive = "<?=(ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=ARTICLE_DEFAULT_URL?>/";
                            $validation = "<? if (ARTICLE_FEATURE == \"on\" && CUSTOM_ARTICLE_FEATURE == \"on\") { ?>";
                        //Deal
                        } elseif ($row["link"] == "PROMOTION_DEFAULT_URL") {
                            $classActive = "<?=(ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=PROMOTION_DEFAULT_URL?>/";
                            $validation = "<? if (PROMOTION_FEATURE == \"on\" && CUSTOM_HAS_PROMOTION == \"on\" && CUSTOM_PROMOTION_FEATURE == \"on\") { ?>";
                        //Blog
                        } elseif ($row["link"] == "BLOG_DEFAULT_URL") {
                            $classActive = "<?=(ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER ? \"class=\\\"menuActived\\\"\" : \"\")?>";
                            $itemLink = "<?=BLOG_DEFAULT_URL?>/";
                            $validation = "<? if (BLOG_FEATURE == \"on\" && CUSTOM_BLOG_FEATURE == \"on\") { ?>";
                        }
                        
                        if ($validation) {
                            $closeValidation = "<? } ?>";
                        }
                        if ($area != "header") {
                            unset($classActive);
                        }

                    }
                    $navbar .= $validation.PHP_EOL;
                    $navbar .= "<li $classActive><a href=\"".$itemLink."\">".string_htmlentities($row["label"])."</a></li>".PHP_EOL;
                    $navbar .= $closeValidation.PHP_EOL;
                    
                    unset($classActive, $itemLink, $validation, $closeValidation);
                }
            }
            
            $filePath = HTMLEDITOR_FOLDER."/".EDIR_THEME."/".$area."_menu.php";
            
            if (!is_dir(HTMLEDITOR_FOLDER)) {
                //create folder custom/domain_x/editor
                mkdir(HTMLEDITOR_FOLDER);
            }
            if (!is_dir(HTMLEDITOR_FOLDER."/".EDIR_THEME)) {
                mkdir(HTMLEDITOR_FOLDER."/".EDIR_THEME);
            }
            
            $file = fopen($filePath, "w+");
            if ($file) {
                fwrite($file,$navbar);
            }
            fclose($file);       
        }
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$navigationObj->getNavbar();
		 * <br /><br />
		 *		//Using this in Navigation() class.
		 *		$this->getNavbar();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name getNavbar
		 * @access Public
         * @param array $navBarOptions
         * @param string $area
		 */
        function getNavbar(&$navBarOptions, $area) {
            
            $dbMain = db_getDBObject(DEFAULT_DB, true);
            $dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
            
            unset($dbMain, $navbar);

            $sql = "SELECT * FROM Setting_Navigation WHERE `area` = '".$area."' AND theme = '".EDIR_THEME."' ORDER BY `order`";
            $result = $dbObj->query($sql);
            
            if (mysql_num_rows($result)) {
                $i = 0;
                while($row = mysql_fetch_assoc($result)) {
                    foreach ($row as $key => $value) {
                        $navBarOptions[$i][$key] = $value;
                    }
                    $i++;
                }
            }
        }
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$navigationObj->ResetNavbar();
		 * <br /><br />
		 *		//Using this in Navigation() class.
		 *		$this->ResetNavbar();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 9.7.00
		 * @name ResetNavbar
		 * @access Public
         * @param string $area
		 */
        function ResetNavbar($area) {
            
            $this->ClearNavigation($area);
            
            /**
             * Array with Modules and URL
             */
            if ($area == "header") {
                $array_modules[] = array("name" => LANG_MENU_HOME, "url" => "NON_SECURE_URL");
            }

            $array_modules[] = array("name"=>LANG_MENU_LISTING, "url" => "LISTING_DEFAULT_URL");

            if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { 
                $array_modules[] = array("name" => LANG_MENU_EVENT, "url" => "EVENT_DEFAULT_URL");
            }

            if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") {
                $array_modules[] = array("name" => LANG_MENU_CLASSIFIED, "url" => "CLASSIFIED_DEFAULT_URL");
            }

            if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") {
                $array_modules[] = array("name" => LANG_MENU_ARTICLE, "url" => "ARTICLE_DEFAULT_URL");
            }

            if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") {
                $array_modules[] = array("name" => LANG_MENU_PROMOTION, "url" => "PROMOTION_DEFAULT_URL");
            }

            if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") {
                $array_modules[] = array("name" => LANG_MENU_BLOG, "url" => "BLOG_DEFAULT_URL");
            }
            
            if ($area == "header"){
                $array_modules[] = array("name" => LANG_MENU_ADVERTISE, "url" => "ALIAS_ADVERTISE_URL_DIVISOR");
                $array_modules[] = array("name" => LANG_MENU_CONTACT, "url" => "ALIAS_CONTACTUS_URL_DIVISOR");
            }

            for ($i = 0; $i < count($array_modules); $i++) {
                unset($aux_array);
                $aux_array["order"]     = $i;
                $aux_array["label"]     = $array_modules[$i]["name"];
                $aux_array["link"]      = $array_modules[$i]["url"];
                $aux_array["area"]      = $area;
                $aux_array["custom"]    = "n";
                $this->makeFromRow($aux_array);
                $this->Save();
            }
            
            $filePath = HTMLEDITOR_FOLDER."/".EDIR_THEME."/".$area."_menu.php";
            @unlink($filePath);
        }
    }
?>