<?php

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
	# * FILE: /classes/class_banner.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$bannerObj = new Banner($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @package Classes
	 * @name Banner
	 * @method Banner
	 * @method makeFromRow
	 * @method Save
	 * @method Delete
	 * @method getNextRenewalDate
	 * @method getPrice
	 * @method getTimeString
	 * @method hasImpressions
	 * @method hasRenewalDate
	 * @method makeBanner
	 * @method needToCheckOut
	 * @method randomRetrieve
	 * @method retrieve
	 * @method retrieveHumanReadableType
	 * @method retrieveType
	 * @access Public
	 */
	class Banner extends Handle {

		/**
		 * @var integer
		 * @access Private
		 */
		var $id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $image_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $category_id;
		/**
		 * @var date
		 * @access Private
		 */
		var $renewal_date;
		/**
		 * @var integer
		 * @access Private
		 */
		var $discount_id;
		/**
		 * @var date
		 * @access Private
		 */
		var $updated;
		/**
		 * @var date
		 * @access Private
		 */
		var $entered;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $destination_protocol;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $destination_url;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $display_url;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $caption;
		/**
		 * @var integer
		 * @access Private
		 */
		var $account_id;
		/**
		 * @var char
		 * @access Private
		 */
		var $status;
        /**
		 * @var char
		 * @access Private
		 */
		var $suspended_sitemgr;
		/**
		 * @var integer
		 * @access Private
		 */
		var $type;
		/**
		 * @var varchar
		 * @access Private
		 */
        var $section;
		/**
		 * @var array
		 * @access Private
		 */
		var $banner_types;
		/**
		 * 1 - _SELF
		 * 2 - _BLANK
		 * @var integer
		 * @access Private
		 */
		var $target_window;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $content_line1;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $content_line2;
		/**
		 * @var integer
		 * @access Private
		 */
		var $unpaid_impressions;
		/**
		 * @var integer
		 * @access Private
		 */
		var $impressions;
		/**
		 * @var char
		 * @access Private
		 */
		var $unlimited_impressions;
		/**
		 * @var char
		 * @access Private
		 */
		var $expiration_setting;
		/**
		 * @var int
		 * @access Private
		 */
		var $show_type;
		/**
		 * @var varchar
		 * @access Private
		 */
		var $script;
		/**
		 * @var integer
		 * @access Private
		 */
		var $domain_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $package_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $package_price;
		/**
		 * @var integer
		 * @access Private
		 */
		var $feature_onlocation1;
		/**
		 * @var integer
		 * @access Private
		 */
		var $feature_onlocation2;
		/**
		 * @var integer
		 * @access Private
		 */
		var $feature_onlocation3;
		/**
		 * @var char
		 * @access Private
		 */
		var $approve_feature;
		
		/**
		 * <code>
		 *		$bannerObj = new Banner($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Banner
		 * @access Public
		 * @param mixed $var
		 */
		function Banner($var="", $domain_id = false) {

			$bannerLevelObj = new BannerLevel(true);
			$bannerLevelValue = $bannerLevelObj->getValues();
            if($bannerLevelValue){
                foreach ($bannerLevelValue as $value) {
                    if ($bannerLevelObj->showLevel($value)){
                        $this->banner_types[$bannerLevelObj->showLevel($value)] = $value;
                    }
                }
            }
			if (is_numeric($var) && ($var)) { 
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if ($domain_id){
					$this->domain_id = $domain_id;
					$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
				}else if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}
				unset($dbMain);

				$sql = "SELECT * FROM Banner WHERE id = $var";
				
				$row = mysql_fetch_array($dbObj->query($sql));

				$this->old_account_id = $row["account_id"];

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
		 * @version 8.0.00
		 * @name makeFromRow
		 * @access Public
		 * @param array $row
		 */
		function makeFromRow($row="") {

			$status = new ItemStatus();

			$this->discount_id				= ($row["discount_id"])				? $row["discount_id"]			: "";
			$this->entered					= ($row["entered"])					? $row["entered"]				: ($this->entered				? $this->entered				: "");
			$this->updated					= ($row["updated"])					? $row["updated"]				: ($this->updated				? $this->updated				: "");
			$this->id						= ($row["id"])						? $row["id"]					: ($this->id					? $this->id						: 0);
			$this->account_id				= ($row["account_id"])				? $row["account_id"]			: 0;
			$this->image_id                 = ($row["image_id"])				? $row["image_id"]				: ($this->image_id				? $this->image_id				: 0);
			$this->category_id				= ($row["category_id"])				? $row["category_id"]			: 0;
			$this->renewal_date				= ($row["renewal_date"])			? $row["renewal_date"]			: ($this->renewal_date			? $this->renewal_date			: 0);
			$this->destination_url			= ($row["destination_url"])			? $row["destination_url"]		: "";
			$this->display_url				= ($row["display_url"])				? $row["display_url"]			: "";
			$this->destination_protocol		= ($row["destination_protocol"])	? $row["destination_protocol"]	: "";
			$this->caption					= ($row["caption"])                 ? $row["caption"]				: "";
			$this->status					= ($row["status"])					? $row["status"]				: ($this->status				? $this->status					: $status->getDefaultStatus());
			$this->suspended_sitemgr        = ($row["suspended_sitemgr"])       ? $row["suspended_sitemgr"]		: ($this->suspended_sitemgr		? $this->suspended_sitemgr		: "n");
			$this->type						= ($row["type"])					? $row["type"]					: ($this->type					? $this->type					: 0);
            $this->section                  = ($row["section"])                 ? $row["section"]               : ($this->section               ? $this->section                : "general");
			$this->target_window			= ($row["target_window"])			? $row["target_window"]			: 2;
			$this->content_line1			= ($row["content_line1"])			? $row["content_line1"]         : "";
			$this->content_line2			= ($row["content_line2"])			? $row["content_line2"]         : "";
			$this->unpaid_impressions		= ($row["unpaid_impressions"])		? $row["unpaid_impressions"]	: ($this->unpaid_impressions	? $this->unpaid_impressions		: 0);
			$this->impressions				= ($row["impressions"])				? $row["impressions"]			: ($this->impressions			? $this->impressions			: 0);
			$this->unlimited_impressions	= ($row["unlimited_impressions"])	? $row["unlimited_impressions"]	: ($this->unlimited_impressions	? $this->unlimited_impressions	: "n");
			$this->expiration_setting		= ($row["expiration_setting"])		? $row["expiration_setting"]	: ($this->expiration_setting	? $this->expiration_setting		: 0);
			$this->show_type				= ($row["show_type"])				? $row["show_type"]				: 0;
			$this->script					= ($row["script"])					? $row["script"]				: "";
			$this->package_id				= ($row["package_id"])				? $row["package_id"]			: ($this->package_id			? $this->package_id				: 0);
			$this->package_price			= ($row["package_price"])			? $row["package_price"]			: ($this->package_price			? $this->package_price			: 0);
			$this->feature_onlocation1		= ($row["feature_onlocation1"])		? $row["feature_onlocation1"]	: 0;
			$this->feature_onlocation2		= ($row["feature_onlocation2"])		? $row["feature_onlocation2"]	: 0;
			$this->feature_onlocation3		= ($row["feature_onlocation3"])		? $row["feature_onlocation3"]	: 0;
			$this->approve_feature			= ($row["approve_feature"])			? $row["approve_feature"]		: ($this->approve_feature		? $this->approve_feature		: "n");
		}	

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->Save();
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->Save();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Save
		 * @access Public
		 */
		function Save() {
			
			$dbMain = db_getDBObject(DEFAULT_DB, true);

			if ($this->domain_id){
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
			
			$aux_old_account = str_replace("'", "", $this->old_account_id);
			$aux_account = str_replace("'", "", $this->account_id);

			if ($this->id) {

				$sql = "SELECT status FROM Banner WHERE id = $this->id";
				$result = $dbObj->query($sql);
				if ($row = mysql_fetch_assoc($result)) {
					$last_status = $row["status"];
				}
				$this_status = $this->status;

				unset($dbMain);
				$sql  = "UPDATE Banner SET"
					. " account_id				= $this->account_id,"
					. " image_id				= $this->image_id,"
					. " category_id				= $this->category_id,"
					. " renewal_date			= $this->renewal_date,"
					. " discount_id				= $this->discount_id,"
					. " updated                 = NOW(),"
					. " destination_url			= $this->destination_url,"
					. " display_url				= $this->display_url,"
					. " destination_protocol	= $this->destination_protocol,"
					. " caption                 = $this->caption,"
					. " status					= $this->status,"
					. " suspended_sitemgr		= $this->suspended_sitemgr,"
					. " type					= $this->type,"
                    . " section                 = $this->section,"
					. " target_window			= $this->target_window,"
					. " content_line1			= $this->content_line1,"
					. " content_line2			= $this->content_line2,"
					. " unpaid_impressions		= $this->unpaid_impressions,"
					. " impressions				= $this->impressions,"
					. " unlimited_impressions	= $this->unlimited_impressions,"
					. " show_type				= $this->show_type,"
					. " script					= $this->script,"
					. " expiration_setting		= $this->expiration_setting,"
					. " package_id				= $this->package_id,"
					. " package_price			= $this->package_price,"
					. " feature_onlocation1		= $this->feature_onlocation1,"
					. " feature_onlocation2		= $this->feature_onlocation2,"
					. " feature_onlocation3		= $this->feature_onlocation3,"
					. " approve_feature			= $this->approve_feature"
					. " WHERE id				= $this->id";
				$dbObj->query($sql);

				if ($aux_old_account != $aux_account && $aux_account != 0) {
					domain_SaveAccountInfoDomain($aux_account, $this);
				}

				$last_status = str_replace("\"", "", $last_status);
				$last_status = str_replace("'", "", $last_status);
				$this_status = str_replace("\"", "", $this_status);
				$this_status = str_replace("'", "", $this_status);

				if ($last_status != "P" && $this_status == "P"){
					activity_newToApproved($aux_log_domain_id, $this->id, "banner", $this->caption);
				} else if ($last_status == "P" && $this_status != "P") {
					activity_deleteRecord($aux_log_domain_id, $this->id, "banner");
				} else if ($last_status == $this_status){
					activity_updateRecord($aux_log_domain_id, $this->id, $this->caption, "item", "banner");
				}

			} else {

				$sql = "INSERT INTO Banner"
					. " (account_id,"
					. " image_id,"
					. " category_id,"
					. " renewal_date,"
					. " discount_id,"
					. " updated,"
					. " entered,"
					. " destination_url, "
					. " display_url, "
					. " destination_protocol, "
					. " caption,"
					. " status,"
					. " target_window,"
					. " content_line1,"
					. " content_line2,"
					. " unpaid_impressions,"
					. " impressions,"
					. " unlimited_impressions,"
					. " expiration_setting,"
                    . " section,"
					. " show_type,"
					. " script,"
					. " type,"
					. " package_id,"
					. " package_price,"
					. " feature_onlocation1,"
					. " feature_onlocation2,"
					. " feature_onlocation3,"
					. " approve_feature)"
					. " VALUES"
					. " ($this->account_id,"
					. " $this->image_id,"
					. " $this->category_id,"
					. " $this->renewal_date,"
					. " $this->discount_id,"
					. " NOW(),"
					. " NOW(),"
					. " $this->destination_url,"
					. " $this->display_url,"
					. " $this->destination_protocol,"
					. " $this->caption,"
					. " $this->status,"
					. " $this->target_window,"
					. " $this->content_line1,"
					. " $this->content_line2,"
					. " $this->unpaid_impressions,"
					. " $this->impressions,"
					. " $this->unlimited_impressions,"
					. " $this->expiration_setting,"
                    . " $this->section,"
					. " $this->show_type,"
					. " $this->script,"
					. " $this->type,"
					. " $this->package_id,"
					. " $this->package_price,"
					. " $this->feature_onlocation1,"
					. " $this->feature_onlocation2,"
					. " $this->feature_onlocation3,"
					. " $this->approve_feature)";
				$dbObj->query($sql);
				$this->id = mysql_insert_id($dbObj->link_id);
				
				if (sess_getAccountIdFromSession() || string_strpos($_SERVER["PHP_SELF"],"order_") !== false){
					activity_newActivity($aux_log_domain_id, $this->account_id, 0, "newitem", "banner", $this->caption);
				}

				domain_updateDashboard("number_content", "inc", 0 ,$aux_log_domain_id);

				if ($this->status == "'P'"){
					activity_newToApproved($aux_log_domain_id, $this->id, "banner", $this->caption);
				}

				if ($aux_account != 0) {
					domain_SaveAccountInfoDomain($aux_account, $this);
				}
			}
			$this->prepareToUse();
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->Delete();
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->Delete();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Delete
		 * @access Public
		 */
		function Delete($domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id) {
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			} else {
				if (defined("SELECTED_DOMAIN_ID")) {
					$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$dbObj = db_getDBObject();
				}
				unset($dbMain);
			}

			### IMAGE
			$imageObj = new Image($this->image_id);
			$imageObj->Delete($domain_id);

			### INVOICE
			$sql = "UPDATE Invoice_Banner SET banner_id = '0' WHERE banner_id = $this->id";
			$dbObj->query($sql);

			### PAYMENT
			$sql = "UPDATE Payment_Banner_Log SET banner_id = '0' WHERE banner_id = $this->id";
			$dbObj->query($sql);

			### BANNER
			$sql = "DELETE FROM Banner WHERE id = $this->id";
			$dbObj->query($sql);

			if ($domain_id){
				$domain_idDash = $domain_id;
			} else {
				$domain_idDash = SELECTED_DOMAIN_ID;
			}

			domain_updateDashboard("number_content","dec", 0, $domain_idDash);

			activity_deleteRecord($domain_idDash, $this->id, "banner");

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->deletePerAccount($account_id);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->deletePerAccount($account_id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name deletePerAccount
		 * @access Public
		 * @param integer $account_id
		 * @param integer $domain_id
		 */
		function deletePerAccount($account_id = 0, $domain_id = false) {
			if (is_numeric($account_id) && $account_id > 0) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if ($domain_id) {
					$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
				} else {
					if (defined("SELECTED_DOMAIN_ID")) {
						$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
					} else {
						$dbObj = db_getDBObject();
					}
					unset($dbMain);
				}
				$sql = "SELECT * FROM Banner WHERE account_id = $account_id";
				$result = $dbObj->query($sql);
				while ($row = mysql_fetch_array($result)) {
					$this->makeFromRow($row);
					$this->Delete($domain_id);
				}
			}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->retrieveHumanReadableType($type);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->retrieveHumanReadableType($type);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveHumanReadableType
		 * @access Public
		 * @param varchar $type
		 * @return varchar $hrtype
		 */
		function retrieveHumanReadableType($type){
            $levelObj = new BannerLevel(true);
			foreach($this->banner_types as $key => $value){
				if($value == $type)
				    $hrtype = string_ucwords($levelObj->getDisplayName($value));
			}
            unset($levelObj);            
			return $hrtype;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->retrieveType($type);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->retrieveType($type);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieveType
		 * @access Public
		 * @param varchar $type
		 * @return mixed $key
		 */
		function retrieveType($type){
			foreach($this->banner_types as $key => $value){
				if($value == $type) return $key;
			}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->retrieve($id);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->retrieve($id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name retrieve
		 * @access Public
		 * @param integer $id
		 * @return array $data
		 */
		function retrieve($id){
			$sql = "SELECT * FROM Banner WHERE id = $id";
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$result = $dbObj->query($sql);
			$data = mysql_fetch_assoc($result);
			return $data;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->randomRetrieve(...);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->randomRetrieve(...);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name randomRetrieve
		 * @access Public
		 * @param integer $type
		 * @param integer $category_id
		 * @param varchar $banner_section
		 * @param integer $amount
		 * @return array $data
		 */
		function randomRetrieve($type = false, $category_id = false, $banner_section = "general", $amount = 1) {
            
			if (!$banner_section) $banner_section = "general";

			if (!$type) return false;

			$sql = "SELECT * FROM Banner WHERE status = 'A'";

			$sql .= "AND (expiration_setting = '".BANNER_EXPIRATION_RENEWAL_DATE."' OR (expiration_setting = '".BANNER_EXPIRATION_IMPRESSION."' AND impressions > 0) OR (expiration_setting = '".BANNER_EXPIRATION_IMPRESSION."' AND unlimited_impressions = 'y'))";

			if ($type && is_numeric($type)) $sql .= " AND type = $type";
            
			$sql .= " AND (section = ".db_formatString($banner_section)." OR section = 'global')";
            
			if ($banner_section != "general") {
				if ((string_strpos($category_id, "x") === false) && (string_strpos($category_id, ".") === false)) {
					if ($category_id && is_numeric($category_id)) {
						$catList = string_substr(getTreePath($category_id, (($banner_section == "promotion" ||$banner_section == "deal") ? "listing" : $banner_section)), 1);
						$catList .= $catList.",".string_substr(getSubTree($category_id, (($banner_section == "promotion" ||$banner_section == "deal") ? "listing" : $banner_section)), 1);
						$sql .= " AND (category_id IN (".$catList.") OR section = 'global')";
					} elseif ($category_id && is_array($category_id)) {
						$sql .= " AND (category_id IN (".implode(",", $category_id).") OR section = 'global')";
					} else {
						$sql .= " AND (category_id = 0 OR category_id IS NULL OR section = 'global')";
					}
				}
			}

			$sql.= " ORDER BY ".((BANNER_SCALABILITY_OPTIMIZATION == "on")?("random_number DESC"):("RAND()"))." LIMIT $amount";

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
                        
			unset($dbMain);
                        
                        
			$result = $dbObj->query($sql);
                       
                        
                        
			while ($row = mysql_fetch_assoc($result)) {
                            
                            
                            $data[] = $row;
			}
                        
			return $data;

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->makeBanner(...);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->makeBanner(...);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name makeBanner
		 * @access Public
		 * @param mixed $banner
		 * @return varchar $html
		 */
		/*function makeBanner($banner) {
            
    		if (!$banner) return "";

			// if calling only one banner by id it needs to be a multidimensional array.
			if (array_key_exists("type", $banner)) {
				$aux = $banner;
				unset($banner);
				$banner[0] = $aux;
			}

			foreach ($banner as $each_banner) {

            	$bannerLevelObj = new BannerLevel(true);
				$each_banner["width"] = $bannerLevelObj->getWidth($each_banner["type"]);
				$each_banner["height"] = $bannerLevelObj->getHeight($each_banner["type"]);

				if ($each_banner["show_type"] == 1) {

					$banner_content .= "<div style=\"width: {$each_banner["width"]}px; height: {$each_banner["height"]}px; overflow: auto;\">".$each_banner["script"]."</div>";

				} else {

					if ($each_banner["image_id"]) $imageObj = new Image($each_banner["image_id"]);
					else $imageObj = new Image($each_banner["image_id"]);
					$image_name = $imageObj->GetString("prefix")."photo_".$imageObj->GetString("id").".".string_strtolower($imageObj->GetString("type"));

					$full_destination_url = (!$each_banner["destination_url"] || (sess_isAccountLogged(true) || sess_isSitemgrLogged(true))) ? "javascript:void(0);" : NON_SECURE_URL."/banner_reports.php?id=".$each_banner["id"];

					if ($each_banner["target_window"] == 1 || !$each_banner["destination_url"]) {
						$target_window = "_self";
					} else if ($each_banner["target_window"] == 2) {
						$target_window = "_blank";
					}

					if ($each_banner["type"] >= 50) { // text ads

						$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" class=\"sponsored\" ".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "style=\"cursor: pointer;\"" : "style=\"cursor: default;\"").">"
								."<span class=\"title\">".string_htmlentities($each_banner["caption"])."</span>\n"
								."<span class=\"text\">".string_htmlentities($each_banner["content_line1"])."</span>\n"
								."<span class=\"text\">".string_htmlentities($each_banner["content_line2"])."</span>\n";

						if ((string_strlen($each_banner["destination_url"]) > 1) && (string_strlen($each_banner["display_url"]) > 1)){
							$banner_content .= "<span class=\"url\">".system_showTruncatedText($each_banner["display_url"], 27)."</span>\n";
						} elseif (string_strlen($each_banner["destination_url"]) > 1) {
							$banner_content .= "<span class=\"url\">".system_showTruncatedText($each_banner["destination_url"], 27)."</span>\n";
						}

						$banner_content .= "</a>";

					} else if ($each_banner["type"] < 50) { // image ads

						if(string_strtolower($imageObj->getString("type")) != "swf") { // jpg, gif or png ad

							if ($imageObj->imageExists()) {

								$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" style=\"".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "cursor: pointer;" : "cursor: default;")."\"><img src=\"".DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/image_files/$image_name\" border=\"0\" width=\"{$each_banner["width"]}\" height=\"{$each_banner["height"]}\" title=\"".string_htmlentities($each_banner["caption"])."\" alt=\"".string_htmlentities($each_banner["caption"])."\" /></a>";

							} else {

								$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" class=\"no-image\" style=\"text-decoration: none; display: inline-block; width: {$each_banner["width"]}px; height: {$each_banner["height"]}px; ".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "cursor: pointer;" : "cursor: default;")."\" title=\"".string_htmlentities($each_banner["caption"])."\">&nbsp;</a>";

							}

						} else { // flash ad

							if ($imageObj->imageExists()) {

								$banner_content .= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\""
										."codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\""
										."width=\"{$each_banner["width"]}\" height=\"{$each_banner["height"]}\">\n"
										."<param name=Flashvars value=\"selection=0\">\n"
										."<param name=\"menu\" value=\"false\">\n"
										."<param name=\"movie\" value=\"".IMAGE_URL."/$image_name\">\n"
										."<param name=\"quality\" value=\"high\">\n"
										."<embed src=\"".IMAGE_URL."/$image_name\" menu=\"false\" value=\"selection=0\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"{$each_banner["width"]}\" height=\"{$each_banner["height"]}\"></embed>\n"
										."</object>\n";

							} else {
								$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" class=\"noimage\" style=\"text-decoration: none; display: block; width: {$each_banner["width"]}px; height: {$each_banner["height"]}px; ".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "cursor: pointer;" : "cursor: default;")."\" title=\"".string_htmlentities($each_banner["caption"])."\">&nbsp;</a>";
							}

						}

					}

				}

			}

			$html = "";
			if ($banner_content) {
				$html = $banner_content;
			}

			return $html;

		}*/

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->getPrice($disable_unpaid);
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->getPrice($disable_unpaid);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getPrice
		 * @access Public
		 * @param boolean $disable_unpaid
		 * @return double $price
		 */
		function getPrice($disable_unpaid = false) {

			$price = 0;

			$dbMain = db_getDBObject(DEFAULT_DB, true);

			if ($this->domain_id){
				$dbObj = db_getDBObjectByDomainID($this->domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			$levelObj = new BannerLevel(true);
			if($this->package_id){
				$price = $this->package_price;
			}else{
				$price = $price + $levelObj->getPrice($this->type, $this->expiration_setting);
			}

			if (!$disable_unpaid || $this->discount_id) {
				if ($this->expiration_setting == BANNER_EXPIRATION_IMPRESSION) {
					if ( $this->unpaid_impressions ) $price *= $this->unpaid_impressions / $levelObj->getImpressionBlock($this->type);
					elseif ( $this->impressions ) $price *= $this->impressions / $levelObj->getImpressionBlock($this->type);
				}
			}

			if ($this->discount_id) {
			
				$discountCodeObj = new DiscountCode($this->discount_id);
				
				if (is_valid_discount_code($this->discount_id, "banner", $this->id, $discount_message, $discount_error)) {

					if ($discountCodeObj->getString("id") && $discountCodeObj->expire_date >= date('Y-m-d')) {

						if ($discountCodeObj->getString("type") == "percentage") {
							$price = $price * (1 - $discountCodeObj->getString("amount")/100);
						} elseif ($discountCodeObj->getString("type") == "monetary value") {
							$price = $price - $discountCodeObj->getString("amount");
						}

					} elseif ( ($discountCodeObj->type == 'percentage' && $discountCodeObj->amount == '100.00') || ($discountCodeObj->type == 'monetary value' && $discountCodeObj->amount > $price) ) {
                        $this->status = 'E';
                        $this->renewal_date = $discountCodeObj->expire_date;
						
                        $sql = "UPDATE Banner SET status = 'E', renewal_date = '".$discountCodeObj->expire_date."', discount_id = '', expiration_setting = 2 WHERE id = ".$this->id;
                        $result = $dbObj->query($sql);
                    }

				} else {
					
					if ( ($discountCodeObj->type == 'percentage' && $discountCodeObj->amount == '100.00') || ($discountCodeObj->type == 'monetary value' && $discountCodeObj->amount > $price) ) {
                        $this->status = 'E';
                        $this->renewal_date = $discountCodeObj->expire_date; 
                        $sql = "UPDATE Banner SET status = 'E', renewal_date = '".$discountCodeObj->expire_date."', discount_id = '', expiration_setting = 2 WHERE id = ".$this->id;
					} else {
						$sql = "UPDATE Banner SET discount_id = '' WHERE id = ".$this->id;
					}
                    $result = $dbObj->query($sql);
                    
				}

			}

			if ($price <= 0) $price = 0;

			return $price;

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->hasRenewalDate();
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->hasRenewalDate();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name hasRenewalDate
		 * @access Public
		 * @return boolean
		 */
		function hasRenewalDate() {
			if (PAYMENT_FEATURE != "on") return false;
			if ((CREDITCARDPAYMENT_FEATURE != "on") && (INVOICEPAYMENT_FEATURE != "on") && (MANUALPAYMENT_FEATURE != "on")) return false;
			if ($this->expiration_setting != BANNER_EXPIRATION_RENEWAL_DATE) return false;
			if ($this->getPrice() <= 0) return false;
			return true;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->hasImpressions();
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->hasImpressions();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name hasImpressions
		 * @access Public
		 * @return boolean
		 */
		function hasImpressions() {
			if (PAYMENT_FEATURE != "on") return false; 
			elseif ((CREDITCARDPAYMENT_FEATURE != "on") && (INVOICEPAYMENT_FEATURE != "on") && (MANUALPAYMENT_FEATURE != "on")) return false;
			elseif ($this->expiration_setting != BANNER_EXPIRATION_IMPRESSION) return false;
			elseif ($this->getPrice($disable_unpaid = true) <= 0) return false;
			return true;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->needToCheckOut();
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->needToCheckOut();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name needToCheckOut
		 * @access Public
		 * @return boolean
		 */
		function needToCheckOut() {

			if ($this->hasRenewalDate()) {

				$today = date("Y-m-d");
				$today = explode("-", $today);
				$today_year = $today[0];
				$today_month = $today[1];
				$today_day = $today[2];
				$timestamp_today = mktime(0, 0, 0, $today_month, $today_day, $today_year);

				$this_renewaldate = $this->renewal_date;
				$renewaldate = explode("-", $this_renewaldate);
				$renewaldate_year = $renewaldate[0];
				$renewaldate_month = $renewaldate[1];
				$renewaldate_day = $renewaldate[2];
				$timestamp_renewaldate = mktime(0, 0, 0, $renewaldate_month, $renewaldate_day, $renewaldate_year);

				if (($this->status == "E") || ($this_renewaldate == "0000-00-00") || ($timestamp_today > $timestamp_renewaldate)) {
					return true;
				}

			}

			if ($this->hasImpressions()) {

				if ($this->unpaid_impressions > 0) {
					return true;
				}

			}

			return false;

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$bannerObj->getNextRenewalDate();
		 * <br /><br />
		 *		//Using this in Banner() class.
		 *		$this->getNextRenewalDate();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getNextRenewalDate
		 * @access Public
		 * @param integer $times
		 * @return date $nextrenewaldate
		 */
		function getNextRenewalDate($times = 1) {

			$nextrenewaldate = "0000-00-00";

			if ($this->hasRenewalDate()) {

				if ($this->needToCheckOut()) {

					$today = date("Y-m-d");
					$today = explode("-", $today);
					$start_year = $today[0];
					$start_month = $today[1];
					$start_day = $today[2];

				} else {

					$this_renewaldate = $this->renewal_date;
					$renewaldate = explode("-", $this_renewaldate);
					$start_year = $renewaldate[0];
					$start_month = $renewaldate[1];
					$start_day = $renewaldate[2];

				}

				$renewalcycle = payment_getRenewalCycle("banner");
				$renewalunit = payment_getRenewalUnit("banner");

				if ($renewalunit == "Y") {
					$nextrenewaldate = date("Y-m-d", mktime(0, 0, 0, (int)$start_month, (int)$start_day, (int)$start_year+($renewalcycle*$times)));
				} elseif ($renewalunit == "M") {
					$nextrenewaldate = date("Y-m-d", mktime(0, 0, 0, (int)$start_month+($renewalcycle*$times), (int)$start_day, (int)$start_year));
				} elseif ($renewalunit == "D") {
					$nextrenewaldate = date("Y-m-d", mktime(0, 0, 0, (int)$start_month, (int)$start_day+($renewalcycle*$times), (int)$start_year));
				}

			}

			return $nextrenewaldate;

		}
		
		function randomRetrieveByCategoryLocation($type = 4, $category_id = false, $banner_section = "listing", $amount = 1, $banner_location = "" ) {
            
			if (!$banner_section) $banner_section = "listing";

			if (!$type) return false;

			$sql = "SELECT * FROM Banner WHERE status = 'A'";
			
			if ($banner_location!="") $sql .= " AND ".$banner_location;

			$sql .= " AND (expiration_setting = '".BANNER_EXPIRATION_RENEWAL_DATE."' OR (expiration_setting = '".BANNER_EXPIRATION_IMPRESSION."' AND impressions > 0) OR (expiration_setting = '".BANNER_EXPIRATION_IMPRESSION."' AND unlimited_impressions = 'y'))";

			if ($type && is_numeric($type)) $sql .= " AND type = $type";
			
			if ($banner_location!="") $sql .= " AND approve_feature = 'O'";
            
			$sql .= " AND (section = ".db_formatString($banner_section).")";
            
			if ((string_strpos($category_id, "x") === false) && (string_strpos($category_id, ".") === false)) {
					if ($category_id && is_numeric($category_id)) {
						$catList = string_substr(getTreePath($category_id, (($banner_section == "promotion" ||$banner_section == "deal") ? "listing" : $banner_section)), 1);
						$catList .= $catList.",".string_substr(getSubTree($category_id, (($banner_section == "promotion" ||$banner_section == "deal") ? "listing" : $banner_section)), 1);
						$sql .= " AND (category_id IN (".$catList."))";
					} elseif ($category_id && is_array($category_id)) {
						$sql .= " AND (category_id IN (".implode(",", $category_id)."))";
					}
				
			}
                        
			//$sql.= " ORDER BY ".((BANNER_SCALABILITY_OPTIMIZATION == "on")?("random_number DESC"):("RAND()"))." LIMIT $amount";

                        $sql.= " ORDER BY ".((BANNER_SCALABILITY_OPTIMIZATION == "on")?("random_number DESC"):("RAND()"));
                        
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
                        
			unset($dbMain);
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}
                        return $data;

		}
		
		function getAllWithCategoryLocation($domain_id = false) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "SELECT * FROM Banner WHERE type=4";
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}

			return $data;
		}
		
		function getAllByCategoryLocation($category_id=0, $location="", $domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "SELECT * FROM Banner WHERE category_id = ".$category_id." AND ".$location;
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}

			return $data;
		}
		
		function getAllByCategory($category_id=0, $domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "SELECT * FROM Banner WHERE category_id = ".$category_id;
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}

			return $data;

		}
		
		function getAllByLocation($category_id="", $domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "SELECT * FROM Banner WHERE ".$location;
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}

			return $data;
		}
		
		function denyAllByCategoryLocation($section="listing", $category_id=0, $location="", $exception=false, $domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "UPDATE Banner SET approve_feature='D' WHERE section = '".$section."' AND category_id = ".$category_id." AND ".$location;
			if($exception)	
				$sql .= " AND approve_feature!='O'";
			$dbObj->query($sql);
		}
		
		function resetAllByCategoryLocation($section="listing", $category_id=0, $location="", $domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "UPDATE Banner SET approve_feature='P' WHERE section = '".$section."' AND category_id = ".$category_id." AND ".$location;
			$dbObj->query($sql);
		}
		
		function getAllApprovedCategoryLocation($domain_id = false) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if ($domain_id){
				$this->domain_id = $domain_id;
				$dbObj = db_getDBObjectByDomainID($domain_id, $dbMain);
			}else if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);

			$sql = "SELECT * FROM Banner WHERE approve_feature='O'";
			$result = $dbObj->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}

			return $data;
		}
                
                function makeBanner($banner) {
            
    		if (!$banner) return "";

			// if calling only one banner by id it needs to be a multidimensional array.
			if (array_key_exists("type", $banner)) {
				$aux = $banner;
				unset($banner);
				$banner[0] = $aux;
			}
                        
                        
                       $banner_content .= "<script type=\"text/javascript\" src=\"".DEFAULT_URL."/scripts/jcarousellite.js\"></script>";
//                       $banner_content .= "<button class=\"prev\"><<</button>";
//                       $banner_content .= "<button class=\"next\">>></button>";
                       $banner_content .= "<div class=\"anyClass\"><ul>";
                       
			foreach ($banner as $each_banner) {
                                $banner_content .= "<li>";
                        $bannerLevelObj = new BannerLevel(true);
				$each_banner["width"] = $bannerLevelObj->getWidth($each_banner["type"]);
				$each_banner["height"] = $bannerLevelObj->getHeight($each_banner["type"]);

				if ($each_banner["show_type"] == 1) {

					$banner_content .= "<div style=\"width: {$each_banner["width"]}px; height: {$each_banner["height"]}px; overflow: auto;\">".$each_banner["script"]."</div>";

				} else {

					if ($each_banner["image_id"]) $imageObj = new Image($each_banner["image_id"]);
					else $imageObj = new Image($each_banner["image_id"]);
					$image_name = $imageObj->GetString("prefix")."photo_".$imageObj->GetString("id").".".string_strtolower($imageObj->GetString("type"));

					$full_destination_url = (!$each_banner["destination_url"] || (sess_isAccountLogged(true) || sess_isSitemgrLogged(true))) ? "javascript:void(0);" : NON_SECURE_URL."/banner_reports.php?id=".$each_banner["id"];

					if ($each_banner["target_window"] == 1 || !$each_banner["destination_url"]) {
						$target_window = "_self";
					} else if ($each_banner["target_window"] == 2) {
						$target_window = "_blank";
					}

					if ($each_banner["type"] >= 50) { // text ads

						$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" class=\"sponsored\" ".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "style=\"cursor: pointer;\"" : "style=\"cursor: default;\"").">"
								."<span class=\"title\">".string_htmlentities($each_banner["caption"])."</span>\n"
								."<span class=\"text\">".string_htmlentities($each_banner["content_line1"])."</span>\n"
								."<span class=\"text\">".string_htmlentities($each_banner["content_line2"])."</span>\n";

						if ((string_strlen($each_banner["destination_url"]) > 1) && (string_strlen($each_banner["display_url"]) > 1)){
							$banner_content .= "<span class=\"url\">".system_showTruncatedText($each_banner["display_url"], 27)."</span>\n";
						} elseif (string_strlen($each_banner["destination_url"]) > 1) {
							$banner_content .= "<span class=\"url\">".system_showTruncatedText($each_banner["destination_url"], 27)."</span>\n";
						}

						$banner_content .= "</a>";

					} else if ($each_banner["type"] < 50) { // image ads

						if(string_strtolower($imageObj->getString("type")) != "swf") { // jpg, gif or png ad

							if ($imageObj->imageExists()) {

								$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" style=\"".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "cursor: pointer;" : "cursor: default;")."\"><img src=\"".DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/image_files/$image_name\" border=\"0\" width=\"{$each_banner["width"]}\" height=\"{$each_banner["height"]}\" title=\"".string_htmlentities($each_banner["caption"])."\" alt=\"".string_htmlentities($each_banner["caption"])."\" /></a>";

							} else {

								$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" class=\"no-image\" style=\"text-decoration: none; display: inline-block; width: {$each_banner["width"]}px; height: {$each_banner["height"]}px; ".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "cursor: pointer;" : "cursor: default;")."\" title=\"".string_htmlentities($each_banner["caption"])."\">&nbsp;</a>";

							}

						} else { // flash ad

							if ($imageObj->imageExists()) {

								$banner_content .= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\""
										."codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\""
										."width=\"{$each_banner["width"]}\" height=\"{$each_banner["height"]}\">\n"
										."<param name=Flashvars value=\"selection=0\">\n"
										."<param name=\"menu\" value=\"false\">\n"
										."<param name=\"movie\" value=\"".IMAGE_URL."/$image_name\">\n"
										."<param name=\"quality\" value=\"high\">\n"
										."<embed src=\"".IMAGE_URL."/$image_name\" menu=\"false\" value=\"selection=0\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"{$each_banner["width"]}\" height=\"{$each_banner["height"]}\"></embed>\n"
										."</object>\n";

							} else {
								$banner_content .= "<a href=\"".$full_destination_url."\" target=\"$target_window\" class=\"noimage\" style=\"text-decoration: none; display: block; width: {$each_banner["width"]}px; height: {$each_banner["height"]}px; ".((string_strpos($full_destination_url,"banner_reports.php")!==false) ? "cursor: pointer;" : "cursor: default;")."\" title=\"".string_htmlentities($each_banner["caption"])."\">&nbsp;</a>";
							}

						}

					}

				}
                                 $banner_content .= "</li>";
			}
                       
                        $banner_content .= "</ul></div>";
                        $banner_content .= "<script>
                                            $(document).ready(function() {
                                                 $(\".listing_banner\").find(\".anyClass\").jCarouselLite({
                                                    auto: 3000,
                                                    speed: 3000,
													visible:1,
													circular: false
                                                });
                                            });</script>";
                      
			$html = "";
			if ($banner_content) {
				$html = $banner_content;
			}

			return $html;

		}

	}

?>