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
	# * FILE: /classes/class_Classified.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$classifiedObj = new Classified($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @package Classes
	 * @name Classified
	 * @method Classified
	 * @method makeFromRow
	 * @method Save
	 * @method Delete
	 * @method updateImage
	 * @method getCategories
	 * @method setCategories
	 * @method getPrice
	 * @method hasRenewalDate
	 * @method needToCheckOut
	 * @method getNextRenewalDate
	 * @method setLocationManager
	 * @method getLocationManager
	 * @method getLocationString
	 * @method setFullTextSearch
	 * @method getGalleries
	 * @method setGalleries
	 * @method setMapTuning
	 * @method hasDetail
	 * @method setNumberViews
	 * @method deletePerAccount
	 * @method getFriendlyURL
	 * @method getClassifiedToApp
	 * @method GetInfoToApp
	 * @access Public
	 */

	class Classified extends Handle {

		/**
		 * @var integer
		 * @access Private
		 */
		var $id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $account_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $location_1;
		/**
		 * @var integer
		 * @access Private
		 */
		var $location_2;
		/**
		 * @var integer
		 * @access Private
		 */
		var $location_3;
		/**
		 * @var integer
		 * @access Private
		 */
		var $location_4;
		/**
		 * @var integer
		 * @access Private
		 */
		var $location_5;
		/**
		 * @var date
		 * @access Private
		 */
		var $entered;
		/**
		 * @var date
		 * @access Private
		 */
		var $updated;
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
		 * @var string
		 * @access Private
		 */
		var $title;
		/**
		 * @var string
		 * @access Private
		 */
		var $seo_title;
		/**
		 * @var string
		 * @access Private
		 */
		var $friendly_url;
		/**
		 * @var string
		 * @access Private
		 */
		var $email;
		/**
		 * @var string
		 * @access Private
		 */
		var $url;
		/**
		 * @var string
		 * @access Private
		 */
		var $contactname;
		/**
		 * @var string
		 * @access Private
		 */
		var $address;
		/**
		 * @var string
		 * @access Private
		 */
		var $address2;
		/**
		 * @var string
		 * @access Private
		 */
		var $phone;
		/**
		 * @var string
		 * @access Private
		 */
		var $fax;
		/**
		 * @var string
		 * @access Private
		 */
		var $summarydesc;
		/**
		 * @var string
		 * @access Private
		 */
		var $seo_summarydesc;
		/**
		 * @var string
		 * @access Private
		 */
		var $detaildesc;
		/**
		 * @var string
		 * @access Private
		 */
		var $keywords;
		/**
		 * @var string
		 * @access Private
		 */
		var $seo_keywords;
		/**
		 * @var integer
		 * @access Private
		 */
		var $image_id;
		/**
		 * @var integer
		 * @access Private
		 */
		var $thumb_id;
		/**
		 * @var string
		 * @access Private
		 */
		var $zip_code;
		/**
		 * @var integer
		 * @access Private
		 */
		var $level;
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
        /*
         * @var real
         * @access Private
         */
        var $latitude;
        /*
         * @var real
         * @access Private
         */
        var $longitude;
		/**
		 * @var integer
		 * @access Private
		 */
		var $map_zoom;
		/**
		 * @var array
		 * @access Private
		 */
		var $locationManager;
		/**
		 * @var real
		 * @access Private
		 */
		var $classified_price;
		/**
		 * @var integer
		 * @access Private
		 */
		var $number_views;
		/**
		 * @var array
		 * @access Private
		 */
		var $data_in_array;
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
		 * <code>
		 *		$classifiedObj = new Classified($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Classified
		 * @access Public
		 * @param integer $var
		 */
		function Classified($var='', $domain_id = false) {
			if (is_numeric($var) && ($var)) {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				if ($domain_id){
					$this->domain_id = $domain_id;
					$db = db_getDBObjectByDomainID($domain_id, $dbMain);
				}else if (defined("SELECTED_DOMAIN_ID")) {
					$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				} else {
					$db = db_getDBObject();
				}
				unset($dbMain);
				$sql = "SELECT * FROM Classified WHERE id = $var";
				$row = mysql_fetch_array($db->query($sql));

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
		function makeFromRow($row='') {

			$status = new ItemStatus();
			$level = new ClassifiedLevel();

			$this->id					= ($row["id"])					? $row["id"]					: ($this->id					? $this->id					: 0);
			$this->account_id			= ($row["account_id"])			? $row["account_id"]			: 0;
			$this->location_1			= ($row["location_1"])			? $row["location_1"]			: 0;
			$this->location_2			= ($row["location_2"])			? $row["location_2"]			: 0;
			$this->location_3			= ($row["location_3"])			? $row["location_3"]			: 0;
			$this->location_4			= ($row["location_4"])			? $row["location_4"]			: 0;
			$this->location_5			= ($row["location_5"])			? $row["location_5"]			: 0;
			$this->entered				= ($row["entered"])				? $row["entered"]				: ($this->entered				? $this->entered			: "");
			$this->updated				= ($row["updated"])				? $row["updated"]				: ($this->updated				? $this->updated			: "");
			$this->renewal_date			= ($row["renewal_date"])		? $row["renewal_date"]			: ($this->renewal_date			? $this->renewal_date		: 0);
			$this->discount_id			= ($row["discount_id"])			? $row["discount_id"]			: "";
			$this->title				= ($row["title"])				? $row["title"]					: ($this->title					? $this->title				: "");
			$this->seo_title			= ($row["seo_title"])			? $row["seo_title"]				: ($this->seo_title				? $this->seo_title			: "");
			$this->friendly_url			= ($row["friendly_url"])		? $row["friendly_url"]			: "";
			$this->email				= ($row["email"])				? $row["email"]					: "";
			$this->url					= ($row["url"])					? $row["url"]					: "";
			$this->contactname			= ($row["contactname"])			? $row["contactname"]			: "";
			$this->address				= ($row["address"])				? $row["address"]				: "";
			$this->address2				= ($row["address2"])			? $row["address2"]				: "";
			$this->phone				= ($row["phone"])				? $row["phone"]					: "";
			$this->fax					= ($row["fax"])					? $row["fax"]					: "";
			$this->summarydesc			= ($row["summarydesc"])         ? $row["summarydesc"]			: "";
			$this->seo_summarydesc		= ($row["seo_summarydesc"])     ? $row["seo_summarydesc"]		: ($this->seo_summarydesc		? $this->seo_summarydesc	: "");
			$this->detaildesc			= ($row["detaildesc"])			? $row["detaildesc"]			: "";
			$this->keywords             = ($row["keywords"])			? $row["keywords"]				: "";
			$this->seo_keywords         = ($row["seo_keywords"])		? $row["seo_keywords"]			: ($this->seo_keywords			? $this->seo_keywords		: "");
			$this->image_id				= ($row["image_id"])			? $row["image_id"]				: ($this->image_id				? $this->image_id			: 0);
			$this->thumb_id				= ($row["thumb_id"])			? $row["thumb_id"]				: ($this->thumb_id				? $this->thumb_id			: 0);
			$this->zip_code				= ($row["zip_code"])			? $row["zip_code"]				: "";
			$this->level				= ($row["level"])				? $row["level"]					: ($this->level					? $this->level				: $level->getDefaultLevel());
			$this->status				= ($row["status"])				? $row["status"]				: $status->getDefaultStatus();
			$this->suspended_sitemgr	= ($row["suspended_sitemgr"])   ? $row["suspended_sitemgr"]		: ($this->suspended_sitemgr		? $this->suspended_sitemgr  : "n");
            $this->latitude             = ($row["latitude"])			? $row["latitude"]				: ($this->latitude              ? $this->latitude           : "");
			$this->longitude			= ($row["longitude"])			? $row["longitude"]				: ($this->longitude             ? $this->longitude          : "");
            $this->map_zoom				= ($row["map_zoom"])            ? $row["map_zoom"]              : 0;
			$this->classified_price     = ($row["classified_price"])    ? $row["classified_price"]      : "NULL";
			$this->number_views			= ($row["number_views"])		? $row["number_views"]			: ($this->number_views			? $this->number_views		: 0);
			$this->data_in_array		= $row;
			$this->package_id			= ($row["package_id"])			? $row["package_id"]			: ($this->package_id            ? $this->package_id			: 0);
			$this->package_price		= ($row["package_price"])		? $row["package_price"]			: ($this->package_price         ? $this->package_price		: 0);

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->Save();
		 * <br /><br />
		 *		//Using this in Classified() class.
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

			$this->friendly_url = string_strtolower($this->friendly_url);

			if ($this->id) {

				$sql = "SELECT status FROM Classified WHERE id = $this->id";
				$result = $dbObj->query($sql);
				if ($row = mysql_fetch_assoc($result)) $last_status = $row["status"];
				$this_status = $this->status;
				$this_id = $this->id;

				$sql = "UPDATE Classified SET"
					. " account_id         = $this->account_id,"
					. " location_1         = $this->location_1,"
					. " location_2         = $this->location_2,"
					. " location_3         = $this->location_3,"
					. " location_4         = $this->location_4,"
					. " location_5         = $this->location_5,"
					. " updated            = NOW(),"
					. " renewal_date       = $this->renewal_date,"
					. " discount_id        = $this->discount_id,"
					. " title              = $this->title,"
					. " seo_title          = $this->seo_title,"
					. " friendly_url       = $this->friendly_url,"
					. " email              = $this->email,"
					. " url                = $this->url,"
					. " contactname        = $this->contactname,"
					. " address            = $this->address,"
					. " address2           = $this->address2,"
					. " phone              = $this->phone,"
					. " fax                = $this->fax,"
					. " summarydesc        = $this->summarydesc,"
					. " seo_summarydesc    = $this->seo_summarydesc,"
					. " detaildesc         = $this->detaildesc,"
					. " keywords           = $this->keywords,"
					. " seo_keywords       = $this->seo_keywords,"
					. " image_id           = $this->image_id,"
					. " thumb_id           = $this->thumb_id,"
					. " zip_code           = $this->zip_code,"
					. " level              = $this->level,"
					. " status             = $this->status,"
                    . " suspended_sitemgr = $this->suspended_sitemgr,"
                    . " latitude           = $this->latitude,"
					. " longitude          = $this->longitude,"
					. " map_zoom           = $this->map_zoom,"
					. " classified_price   = $this->classified_price,"
					. " number_views	   = $this->number_views,"
					. " package_id         = $this->package_id,"
					. " package_price	   = $this->package_price"
					. " WHERE id           = $this->id";
				$dbObj->query($sql);

				$last_status = str_replace("\"", "", $last_status);
				$last_status = str_replace("'", "", $last_status);
				$this_status = str_replace("\"", "", $this_status);
				$this_status = str_replace("'", "", $this_status);
				$this_id = str_replace("\"", "", $this_id);
				$this_id = str_replace("'", "", $this_id);
				if (($this_status == "A") && ($last_status != "A")) system_countActiveItemByCategory("classified", $this_id, "inc");
				elseif (($last_status == "A") && ($this_status != "A")) system_countActiveItemByCategory("classified", $this_id, "dec");

				if ($aux_old_account != $aux_account && $aux_account != 0) {
					domain_SaveAccountInfoDomain($aux_account, $this);
				}

				if ($last_status != "P" && $this_status == "P"){
					activity_newToApproved($aux_log_domain_id, $this->id, "classified", $this->title);
				} else if ($last_status == "P" && $this_status != "P") {
					activity_deleteRecord($aux_log_domain_id, $this->id, "classified");
				} else if ($last_status == $this_status){
					activity_updateRecord($aux_log_domain_id, $this->id, $this->title, "item", "classified");
				}

			} else {

				$sql = "INSERT INTO Classified"
					. " (account_id,"
					. " location_1,"
					. " location_2,"
					. " location_3,"
					. " location_4,"
					. " location_5,"
					. " updated,"
					. " entered,"
					. " renewal_date,"
					. " discount_id,"
					. " title,"
					. " seo_title,"
					. " friendly_url,"
					. " email,"
					. " url,"
					. " contactname,"
					. " address,"
					. " address2,"
					. " phone,"
					. " fax,"
					. " summarydesc,"
					. " seo_summarydesc,"
					. " detaildesc,"
					. " keywords,"
					. " seo_keywords,"
					. " fulltextsearch_keyword,"
					. " fulltextsearch_where,"
					. " image_id,"
					. " thumb_id,"
					. " zip_code,"
					. " level,"
					. " status,"
					. " latitude,"
					. " longitude,"
					. " map_zoom,"
					. " classified_price,"
					. " number_views,"
					. " package_id,"
					. " package_price)"
					. " VALUES"
					. " ($this->account_id,"
					. " $this->location_1,"
					. " $this->location_2,"
					. " $this->location_3,"
					. " $this->location_4,"
					. " $this->location_5,"
					. " NOW(),"
					. " NOW(),"
					. " $this->renewal_date,"
					. " $this->discount_id,"
					. " $this->title,"
					. " $this->title,"
					. " $this->friendly_url,"
					. " $this->email,"
					. " $this->url,"
					. " $this->contactname,"
					. " $this->address,"
					. " $this->address2,"
					. " $this->phone,"
					. " $this->fax,"
					. " $this->summarydesc,"
					. " $this->summarydesc,"
					. " $this->detaildesc,"
					. " $this->keywords,"
					. " ".str_replace(" || ", ", ", $this->keywords).","
					. " '',"
					. " '',"
					. " $this->image_id,"
					. " $this->thumb_id,"
					. " $this->zip_code,"
					. " $this->level,"
					. " $this->status,"
					. " $this->latitude,"
					. " $this->longitude,"
					. " $this->map_zoom,"
					. " $this->classified_price,"
					. " $this->number_views,"
					. " $this->package_id,"
					. " $this->package_price)";
				$dbObj->query($sql);
				$this->id = mysql_insert_id($dbObj->link_id);

				if (sess_getAccountIdFromSession() || string_strpos($_SERVER["PHP_SELF"],"order_") !== false){
					activity_newActivity($aux_log_domain_id, $this->account_id, 0, "newitem", "classified", $this->title);
				}

				if ($this->status == "'P'"){
					activity_newToApproved($aux_log_domain_id, $this->id, "classified", $this->title);
				}

				domain_updateDashboard("number_content","inc",0,$aux_log_domain_id);

				$this_status = $this->status;
				$this_id = $this->id;
				$this_status = str_replace("\"", "", $this_status);
				$this_status = str_replace("'", "", $this_status);
				$this_id = str_replace("\"", "", $this_id);
				$this_id = str_replace("'", "", $this_id);
				if ($this_status == "A") system_countActiveItemByCategory("classified", $this_id, "inc");

				if ($aux_account != 0) {
					domain_SaveAccountInfoDomain($aux_account, $this);
				}

			}

			$this->prepareToUse();

			$this->setFullTextSearch();

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->Delete();
		 * <code>
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
			
			### CLASSIFIED CATEGORY
			if ($this->status == "A") system_countActiveItemByCategory("classified", $this->id, "dec", false, $domain_id);

			### GALERY
			$sql = "DELETE FROM Gallery_Item WHERE item_type = 'classified' AND item_id = $this->id";
			$dbObj->query($sql);

			### IMAGES
			if ($this->image_id) {
				$image = new Image($this->image_id);
				if ($image) $image->Delete($domain_id);
			}
			if ($this->thumb_id) {
				$image = new Image($this->thumb_id);
				if ($image) $image->Delete($domain_id);
			}

			### INVOICE
			$sql = "UPDATE Invoice_Classified SET classified_id = '0' WHERE classified_id = $this->id";
			$dbObj->query($sql);

			### PAYMENT
			$sql = "UPDATE Payment_Classified_Log SET classified_id = '0' WHERE classified_id = $this->id";
			$dbObj->query($sql);

			### CLASSIFIED
			$sql = "DELETE FROM Classified WHERE id = $this->id";
			$dbObj->query($sql);

			if ($domain_id){
				$domain_idDash = $domain_id;
			} else {
				$domain_idDash = SELECTED_DOMAIN_ID;
			}

			domain_updateDashboard("number_content","dec", 0, $domain_idDash);

			activity_deleteRecord($domain_idDash, $this->id, "classified");

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->updateImage($imageArray);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->updateImage($imageArray);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name updateImage
		 * @access Public
		 * @param array $imageArray
		 */
		function updateImage($imageArray) {
			unset($imageObj);
			if ($this->image_id) {
				$imageobj = new Image($this->image_id);
				if ($imageobj) $imageobj->delete();
			}
			$this->image_id = $imageArray["image_id"];
			unset($imageObj);
			if ($this->thumb_id) {
				$imageObj = new Image($this->thumb_id);
				if ($imageObj) $imageObj->delete();
			}
			$this->thumb_id = $imageArray["thumb_id"];
			unset($imageObj);
		}
		
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->getCategories();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getCategories();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getCategories
		 * @access Public
		 * @return array
		 */
		function getCategories() {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT cat_1_id, cat_2_id, cat_3_id, cat_4_id, cat_5_id FROM Classified WHERE id = $this->id";
			$r = $dbObj->query($sql);
			while ($row = mysql_fetch_array($r)) {
				if ($row["cat_1_id"]) $categories[] = new ClassifiedCategory($row["cat_1_id"]);
				if ($row["cat_2_id"]) $categories[] = new ClassifiedCategory($row["cat_2_id"]);
				if ($row["cat_3_id"]) $categories[] = new ClassifiedCategory($row["cat_3_id"]);
				if ($row["cat_4_id"]) $categories[] = new ClassifiedCategory($row["cat_4_id"]);
				if ($row["cat_5_id"]) $categories[] = new ClassifiedCategory($row["cat_5_id"]);
			}
			return $categories;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->setCategories();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->setCategories();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setCategories
		 * @access Public
		 */
		function setCategories($array) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			
			if ($this->status == "A") system_countActiveItemByCategory("classified", $this->id, "dec");

			$cat_1_id = 0;
			$parcat_1_level1_id = 0;
			$parcat_1_level2_id = 0;
			$parcat_1_level3_id = 0;
			$parcat_1_level4_id = 0;
			$cat_2_id = 0;
			$parcat_2_level1_id = 0;
			$parcat_2_level2_id = 0;
			$parcat_2_level3_id = 0;
			$parcat_2_level4_id = 0;
			$cat_3_id = 0;
			$parcat_3_level1_id = 0;
			$parcat_3_level2_id = 0;
			$parcat_3_level3_id = 0;
			$parcat_3_level4_id = 0;
			$cat_4_id = 0;
			$parcat_4_level1_id = 0;
			$parcat_4_level2_id = 0;
			$parcat_4_level3_id = 0;
			$parcat_4_level4_id = 0;
			$cat_5_id = 0;
			$parcat_5_level1_id = 0;
			$parcat_5_level2_id = 0;
			$parcat_5_level3_id = 0;
			$parcat_5_level4_id = 0;
			if ($array) {
				$count_category_aux = 1;
				foreach ($array as $category) {
					if ($category) {
						unset($parents);
						$cat_id = $category;
						$i = 0;
						while ($cat_id != 0) {
							$sql = "SELECT * FROM ClassifiedCategory WHERE id = $cat_id";
							$rs1 = $dbObj->query($sql);
							if (mysql_num_rows($rs1) > 0) {
								$cat_info = mysql_fetch_assoc($rs1);
								$cat_id = $cat_info["category_id"];
								$parents[$i++] = $cat_id;
							} else {
								$cat_id = 0;
							}
						}
						for ($j=count($parents)-1; $j < 4; $j++) { $parents[$j] = 0; }
						${"cat_".$count_category_aux."_id"} = $category;
						${"parcat_".$count_category_aux."_level1_id"} = $parents[0];
						${"parcat_".$count_category_aux."_level2_id"} = $parents[1];
						${"parcat_".$count_category_aux."_level3_id"} = $parents[2];
						${"parcat_".$count_category_aux."_level4_id"} = $parents[3];
						$count_category_aux++;
					}
				}
			}
			$sql = "UPDATE Classified SET cat_1_id = ".$cat_1_id.", parcat_1_level1_id = ".$parcat_1_level1_id.", parcat_1_level2_id = ".$parcat_1_level2_id.", parcat_1_level3_id = ".$parcat_1_level3_id.", parcat_1_level4_id = ".$parcat_1_level4_id.", cat_2_id = ".$cat_2_id.", parcat_2_level1_id = ".$parcat_2_level1_id.", parcat_2_level2_id = ".$parcat_2_level2_id.", parcat_2_level3_id = ".$parcat_2_level3_id.", parcat_2_level4_id = ".$parcat_2_level4_id.", cat_3_id = ".$cat_3_id.", parcat_3_level1_id = ".$parcat_3_level1_id.", parcat_3_level2_id = ".$parcat_3_level2_id.", parcat_3_level3_id = ".$parcat_3_level3_id.", parcat_3_level4_id = ".$parcat_3_level4_id.", cat_4_id = ".$cat_4_id.", parcat_4_level1_id = ".$parcat_4_level1_id.", parcat_4_level2_id = ".$parcat_4_level2_id.", parcat_4_level3_id = ".$parcat_4_level3_id.", parcat_4_level4_id = ".$parcat_4_level4_id.", cat_5_id = ".$cat_5_id.", parcat_5_level1_id = ".$parcat_5_level1_id.", parcat_5_level2_id = ".$parcat_5_level2_id.", parcat_5_level3_id = ".$parcat_5_level3_id.", parcat_5_level4_id = ".$parcat_5_level4_id." WHERE id = $this->id";
			$dbObj->query($sql);
			$this->setFullTextSearch();

			if ($this->status == "A") system_countActiveItemByCategory("classified", $this->id, "inc");
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->getCategories();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getCategories();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getCategories
		 * @access Public
		 * @return real
		 */
		function getPrice() {

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

			$levelObj = new ClassifiedLevel();
			if($this->package_id){
				$price = $this->package_price;
			}else{
				$price = $price + $levelObj->getPrice($this->level);
			}

			if ($this->discount_id) {

				$discountCodeObj = new DiscountCode($this->discount_id);

				if (is_valid_discount_code($this->discount_id, "classified", $this->id, $discount_message, $discount_error)) {

					if ($discountCodeObj->getString("id") && $discountCodeObj->expire_date >= date('Y-m-d')) {

						if ($discountCodeObj->getString("type") == "percentage") {
							$price = $price * (1 - $discountCodeObj->getString("amount")/100);
						} elseif ($discountCodeObj->getString("type") == "monetary value") {
							$price = $price - $discountCodeObj->getString("amount");
						}

					} elseif ( ($discountCodeObj->type == 'percentage' && $discountCodeObj->amount == '100.00') || ($discountCodeObj->type == 'monetary value' && $discountCodeObj->amount > $price) ) {
                        $this->status = 'E';
                        $this->renewal_date = $discountCodeObj->expire_date;

						$sql = "UPDATE Classified SET status = 'E', renewal_date = '".$discountCodeObj->expire_date."', discount_id = '' WHERE id = ".$this->id;
                        $result = $dbObj->query($sql);
                    }

				} else {

					if ( ($discountCodeObj->type == 'percentage' && $discountCodeObj->amount == '100.00') || ($discountCodeObj->type == 'monetary value' && $discountCodeObj->amount > $price) ) {
                        $this->status = 'E';
                        $this->renewal_date = $discountCodeObj->expire_date;
                        $sql = "UPDATE Classified SET status = 'E', renewal_date = '".$discountCodeObj->expire_date."', discount_id = '' WHERE id = ".$this->id;
					} else {
						$sql = "UPDATE Classified SET discount_id = '' WHERE id = ".$this->id;
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
		 *		$classifiedObj->hasRenewalDate();
		 * <br /><br />
		 *		//Using this in Classified() class.
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
			if ($this->getPrice() <= 0) return false;
			return true;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->needToCheckOut();
		 * <br /><br />
		 *		//Using this in Classified() class.
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

			return false;

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->getNextRenewalDate($times);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getNextRenewalDate($times);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getNextRenewalDate
		 * @access Public
		 * @param integer $times
		 * @return date
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

				$renewalcycle = payment_getRenewalCycle("classified");
				$renewalunit = payment_getRenewalUnit("classified");

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

		/**
		 * <code>
		 *		//Using this in Classified() class.
		 *		$this->setLocationManager(&$locationManager);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setLocationManager
		 * @access Public
		 * @param string $locationManager
		 */
		function setLocationManager(&$locationManager) {
			$this->locationManager =& $locationManager;
		}

		/**
		 * <code>
		 *		//Using this in Classified() class.
		 *		$this->getLocationManager();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getLocationManager
		 * @access Public
		 * @return array
		 */
		function &getLocationManager() {
			return $this->locationManager; /* NEVER auto-instantiate this*/
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->getLocationString($format,$forceManagerCreation);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getLocationString();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getLocationString
		 * @access Public
		 * @param string $format, boolean $forceManagerCreation
		 * @return array
		 */
		function getLocationString($format, $forceManagerCreation = false) {
			if($forceManagerCreation && !$this->locationManager) $this->locationManager = new LocationManager();
			return db_getLocationString($this, $format);
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->setFullTextSearch();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->setFullTextSearch();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setFullTextSearch
		 * @access Public
		 */
		function setFullTextSearch() {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);

			if ($this->title) {
				$string=str_replace(" || ", " ", $this->title);
                $fulltextsearch_keyword[] = $string;
                $addkeyword=format_addApostWords($string);
                if ($addkeyword!='')  $fulltextsearch_keyword[] =$addkeyword;
                unset($addkeyword);
			}
             
            if ($this->keywords) {
                $string=str_replace(" || ", " ", $this->keywords);
                $fulltextsearch_keyword[] = $string;
                $addkeyword=format_addApostWords($string);
                if ($addkeyword!='')  $fulltextsearch_keyword[] =$addkeyword;
                unset($addkeyword);
            }

            if ($this->summarydes) {
                $fulltextsearch_keyword[] = string_substr($this->summarydesc, 0, 100);
            }

			if ($this->address) {
				$fulltextsearch_where[] = $this->address;
			}

			if ($this->zip_code) {
				$fulltextsearch_where[] = $this->zip_code;
			}

			$_locations = explode(",", EDIR_LOCATIONS);
			foreach ($_locations as $each_location) {
				unset ($objLocation);
				$objLocationLabel = "Location".$each_location;
				$attributeLocation = 'location_'.$each_location;
				$objLocation = new $objLocationLabel;
				$objLocation->SetString("id", $this->$attributeLocation);
				$locationsInfo = $objLocation->retrieveLocationById();
				if ($locationsInfo["id"]) {
					$fulltextsearch_where[] = $locationsInfo["name"];
					if ($locationsInfo["abbreviation"]) {
						$fulltextsearch_where[] = $locationsInfo["abbreviation"];
					}
				}
			}

			$categories = $this->getCategories();
			if ($categories) {
				foreach ($categories as $category) {
					unset($parents);
					$category_id = $category->getNumber("id");
					while ($category_id != 0) {
						$sql = "SELECT * FROM ClassifiedCategory WHERE id = $category_id";
						$result = $dbObj->query($sql);
						if (mysql_num_rows($result) > 0) {
							$category_info = mysql_fetch_assoc($result);

                            if ($category_info["enabled"] == "y") {
                                if ($category_info["title"]) {
                                    $fulltextsearch_keyword[] = $category_info["title"];
                                }

                                if ($category_info["keywords"]) {
                                    $fulltextsearch_keyword[] = $category_info["keywords"];
                                }
                            }

							$category_id = $category_info["category_id"];
						} else {
							$category_id = 0;
						}
					}
				}
			}

			if (is_array($fulltextsearch_keyword)) {
				$fulltextsearch_keyword_sql = db_formatString(implode(" ", $fulltextsearch_keyword));
				$sql = "UPDATE Classified SET fulltextsearch_keyword = $fulltextsearch_keyword_sql WHERE id = $this->id";
				$result = $dbObj->query($sql);
			}
			if (is_array($fulltextsearch_where)) {
				$fulltextsearch_where_sql = db_formatString(implode(" ", $fulltextsearch_where));
				$sql = "UPDATE Classified SET fulltextsearch_where = $fulltextsearch_where_sql WHERE id = $this->id";
				$result = $dbObj->query($sql);
			}

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->getGalleries();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getGalleries();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getGalleries
		 * @access Public
		 * @return array
		 */
		function getGalleries() {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "SELECT * FROM Gallery_Item WHERE item_type='classified' AND item_id = $this->id ORDER BY gallery_id";
			$r = $dbObj->query($sql);
			if ($this->id > 0) while ($row = mysql_fetch_array($r)) $galleries[] = $row["gallery_id"];
			return $galleries;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->setGalleries($gallery);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->setGalleries($gallery);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setGalleries
		 * @access Public
		 * @param integer $gallery
		 */
		function setGalleries($gallery = false) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "DELETE FROM Gallery_Item WHERE item_type='classified' AND item_id = $this->id";
			$dbObj->query($sql);
				if ($gallery) {
					$sql = "INSERT INTO Gallery_Item (item_id, gallery_id, item_type) VALUES ($this->id, $gallery, 'classified')";
					$rs3 = $dbObj->query($sql);
				}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->setMapTuning($latitude_longitude,$map_zoom);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->setMapTuning($gallery);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setMapTuning
		 * @access Public
		 * @param string $latitude_longitude, integer $map_zoom
		 */
		function setMapTuning($latitude_longitude="",$map_zoom) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}
			unset($dbMain);
            
            $auxCoord = explode(",", $latitude_longitude);
            $latitude = $auxCoord[0];
            $longitude = $auxCoord[1];
            
			$sql = "UPDATE Classified SET latitude = ".db_formatString($latitude).", longitude = ".db_formatString($longitude).", map_zoom = ".db_formatNumber($map_zoom)." WHERE id = ".$this->id."";
			$dbObj->query($sql);
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->hasDetail();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->hasDetail();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name hasDetail
		 * @access Public
		 * @return char
		 */
        function hasDetail() {
            $classifiedLevel = new ClassifiedLevel();
            $detail = $classifiedLevel->getDetail($this->level);
            unset($classifiedLevel);
            return $detail;
        }

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->setNumberViews($id);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->setNumberViews($id);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name setNumberViews
		 * @access Public
		 * @param integer $id
		 */
		function setNumberViews($id) {
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			if (defined("SELECTED_DOMAIN_ID")) {
				$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			} else {
				$dbObj = db_getDBObject();
			}

			unset($dbMain);
			$sql = "UPDATE Classified SET number_views = ".$this->number_views." + 1 WHERE Classified.id = ".$id;
			$dbObj->query($sql);

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->deletePerAccount($account_id);
		 * <br /><br />
		 *		//Using this in Classified() class.
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
				$sql = "SELECT * FROM Classified WHERE account_id = $account_id";
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
		 *		$classifiedObj->getFriendlyURL();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getFriendlyURL();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getFriendlyURL
		 * @access Public
		 * @param boolean $mobile
		 */
		function getFriendlyURL($mobile = false) {
        	if ($mobile) {
        		$aux_url = DEFAULT_URL."/mobile/".CLASSIFIED_FEATURE_FOLDER;
        	} else {
        		$aux_url = CLASSIFIED_DEFAULT_URL;
        	}
        
            return $aux_url."/".$this->friendly_url.".html";

		}
        
       /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->getClassifiedToApp();
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->getClassifiedToApp();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getClassifiedToApp
		 * @access Public
		 */
        function getClassifiedToApp() {
            
            if ($this->id > 0 && $this->status == 'A' && $this->suspended_sitemgr == 'n') {
                
                /**
                 * Fields to detail page
                 */
                unset($aux_detail_fields);
                
                $aux_detail_fields[] = "id";
                $aux_detail_fields[] = "title";
                $aux_detail_fields[] = "email";
                $aux_detail_fields[] = "phone";
                $aux_detail_fields[] = "url";
                $aux_detail_fields[] = "latitude";
                $aux_detail_fields[] = "longitude";
                $aux_detail_fields[] = "summarydesc";
                $aux_detail_fields[] = "detaildesc";
                $aux_detail_fields[] = "status";
                $aux_detail_fields[] = "suspended_sitemgr";
                $aux_detail_fields[] = "level";
                $aux_detail_fields[] = "classified_price";
                
                /*
                 * Number fields
                 */
                unset($number_fields);
                $number_fields[] = "latitude";
                $number_fields[] = "longitude";
                $number_fields[] = "level";
                $number_fields[] = "classified_price";
                
                unset($add_info);
                //$this->data_in_array["location_information"] = $this->getLocationString("A, 4, 3, 1", true);
                $add_info["location_information"] = $this->getLocationString("A, 4, 3, 1", true);
                
                foreach ($this->data_in_array as $key => $value) {
                
                    if ($key == "image_id" && $value > 0) {
                        unset($imageObj);
                        $imageObj = new Image($value);
                        if ($imageObj->imageExists()) {
                            $add_info["imageurl"] = $imageObj->getPath();
                        } else {
                            $add_info["imageurl"] = NULL;
                        }
                    }
                    
                    /**
                     * Get just fields to show on detail App
                     */
                    if (!is_numeric($key) && in_array($key, $aux_detail_fields)) {
                        
                        if ($key != "image_id") {
                            if (is_array($aux_fields)) {
                                $add_info[array_search($key, $aux_fields)] = ((is_numeric($value) && in_array($key,$number_fields)) ? (float)$value : $value);
                            } else {
                                $add_info[$key] = ((is_numeric($value)  && in_array($key,$number_fields)) ? (float)$value : $value);
                            }
                        }
                    }
                }
               
                
                /**
                 * Get galleries
                 */
                unset($aux_galleries);
                
                $aux_galleries = $this->getGalleries();
                if (is_array($aux_galleries)) {
                    
                    $galleryObj = new Gallery();
                    
                    for ($i=0;$i < count($aux_galleries);$i++) {
                        
                        $images = $galleryObj->getAllImages($aux_galleries[$i]);
                        
                        if (is_array($images)) {
                            
                            for ($j=0; $j<count($images); $j++) {
                        
                                unset($imageObj);
                                $imageObj = new Image($images[$j]["image_id"]);
                                if ($imageObj->imageExists()) {
                                    $add_info["gallery"][$j]["imageurl"] = $imageObj->getPath();
                                } else {
                                    $add_info["gallery"][$j]["imageurl"] = NULL;
                                }    

                                $add_info["gallery"][$j]["caption"] = $images[$j]["image_caption"];

                            }
                        }
                    }
                }
                
                /**
                 * Preparing friendly URL
                 */
                $add_info["friendly_url"] = $this->getFriendlyURL(false);
                
                if (is_array($add_info)) {
                    return $add_info;
                } else {
                    return false;
                }
                
            } else {
                return false;
            }
        }
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$classifiedObj->GetInfoToApp($array_get, $aux_returnArray, $aux_fields, $items, $auxTable, $aux_Where);
		 * <br /><br />
		 *		//Using this in Classified() class.
		 *		$this->GetInfoToApp($array_get, $aux_returnArray, $aux_fields, $items, $auxTable, $aux_Where);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name GetInfoToApp
         * @param array $array_get
         * @param array $aux_returnArray
         * @param array $aux_fields
         * @param array $items
         * @param array $auxTable
         * @param array $aux_Where
		 * @access Public
		 */
        function GetInfoToApp($array_get,&$aux_returnArray,&$aux_fields,&$items,&$auxTable,&$aux_Where) {
            
            extract($array_get);
            
            /**
             * Prepare columns with alias
             */
            if (is_array($aux_fields)) {

                unset($fields_to_map);

                foreach ($aux_fields as $key => $value) {
                    $fields_to_map[] = $value." as `".$key."`";
                }
            }
            
            if ($id) {
                    
                /*
                 * Get Classified
                 */
                unset($classifiedObj,$classifiedInfo);
                $classifiedObj = new Classified($id);

                $classifiedInfo = $classifiedObj->getClassifiedToApp();

                if (!is_array($classifiedInfo)) {

                    $aux_returnArray["error"]           = "No results found.";
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = 0; 
                    $aux_returnArray["total_pages"]     = 0; 
                    $aux_returnArray["results_per_page"]= 0; 
                    
                } else {
                    $items[] = $classifiedInfo;
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = 1; 
                    $aux_returnArray["total_pages"]     = 1; 
                    $aux_returnArray["results_per_page"]= 1;
                }

            } else {

                $auxTable = "Classified";

                $aux_orderBy[] = "level";
                $aux_orderBy[] = "title";

                $aux_Where[] = "status = 'A'";

            }

            if ($searchBy) {
                if ($searchBy == "keyword" && $keyword) {

                    unset($searchReturn);
                    $searchReturn["from_tables"]    = "Classified";
                    $searchReturn["order_by"]       = "Classified.level, Classified.title";
                    $searchReturn["where_clause"]   = "Classified.status = 'A' ";
                    $searchReturn["select_columns"] = implode(", ",$aux_fields);
                    $searchReturn["group_by"]       = false;

                    $letterField = "title";
                    search_frontAppKeyword($array_get, $searchReturn,"Classified");

                    $pageObj = new pageBrowsing($searchReturn["from_tables"], $page, $aux_results_per_page, $searchReturn["order_by"], $letterField, $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Classified", $searchReturn["group_by"]);

                    $items = $pageObj->retrievePage("array");

                    if (!is_array($items)) {
                        $aux_returnArray["error"]           = "No results found.";
                    }
                    
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = $pageObj->record_amount; 
                    $aux_returnArray["total_pages"]     = $pageObj->pages; 
                    $aux_returnArray["results_per_page"]= $pageObj->limit; 


                } elseif ($searchBy == "map" && ($drawLat0 && $drawLat1 && $drawLong0 && $drawLong1)) {
                    
                    /**
                     * Search on map with coordinates and / or keyword
                     */
                    $letterField = "title";
                    $searchReturn = search_frontDrawMap($array_get, $fields_to_map,"Classified");
                    $pageObj = new pageBrowsing($searchReturn["from_tables"], $page, $aux_results_per_page, $searchReturn["order_by"], $letterField, $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Classified", $searchReturn["group_by"]);

                    $items = $pageObj->retrievePage("array");
                    
                    if (!is_array($items)) {
                        $aux_returnArray["error"]           = "No results found.";
                    }
                    

                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = $pageObj->record_amount; 
                    $aux_returnArray["total_pages"]     = $pageObj->pages; 
                    $aux_returnArray["results_per_page"]= $pageObj->limit; 
                   

                } elseif ($searchBy == "category" && $category_id) {

                    /*
                     * Get classifieds by category_id
                     */
                    $search_for["category_id"] = $category_id;
                    $searchReturn = search_frontClassifiedSearch($search_for, "classified");
                    
                    if ($searchReturn) {
                        $aux_Where[] = $searchReturn["where_clause"];
                    } else {
                        $aux_returnArray["error"] = "No results found.";
                    }
                    
                } else {
                    echo "Wrong Search, check the parameters";
                    exit;
                }
            }
        }
	}
?>