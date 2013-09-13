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
	# * FILE: /classes/class_Event.php
	# ----------------------------------------------------------------------------------------------------

	/**
	 * <code>
	 *		$eventObj = new Event($id);
	 * <code>
	 * @copyright Copyright 2005 Arca Solutions, Inc.
	 * @author Arca Solutions, Inc.
	 * @version 8.0.00
	 * @package Classes
	 * @name Event
	 * @method Event
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
	 * @method getDateString
	 * @method getDateStringEnd
	 * @method getDateStringRecurring
	 * @method getTimeString
	 * @method getMonthAbbr
	 * @method checkStartDate
	 * @method getDayStr
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
	 * @method getEventToApp
	 * @method GetInfoToApp
	 * @method EventsDay
	 * @access Public
	 */
	class Event extends Handle {

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
		var $description;
		/**
		 * @var string
		 * @access Private
		 */
		var $seo_description;
		/**
		 * @var string
		 * @access Private
		 */
		var $long_description;
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
		 * @var date
		 * @access Private
		 */
		var $start_date;
		/**
		 * @var char
		 * @access Private
		 */
		var $has_start_time;
		/**
		 * @var date
		 * @access Private
		 */
		var $start_time;
		/**
		 * @var date
		 * @access Private
		 */
		var $end_date;
		/**
		 * @var char
		 * @access Private
		 */
		var $has_end_time;
		/**
		 * @var date
		 * @access Private
		 */
		var $end_time;
		/**
		 * @var string
		 * @access Private
		 */
		var $location;
		/**
		 * @var string
		 * @access Private
		 */
		var $address;
		/**
		 * @var string
		 * @access Private
		 */
		var $zip_code;
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
		 * @var string
		 * @access Private
		 */
		var $url;
		/**
		 * @var string
		 * @access Private
		 */
		var $contact_name;
		/**
		 * @var string
		 * @access Private
		 */
		var $phone;
		/**
		 * @var string
		 * @access Private
		 */
		var $email;
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
		var $level;
		/**
		 * @var char
		 * @access Private
		 */
		var $recurring;
		/**
		 * @var integer
		 * @access Private
		 */
		var $day;
		/**
		 * @var integer
		 * @access Private
		 */
		var $dayofweek;
		/**
		 * @var integer
		 * @access Private
		 */
		var $week;
		/**
		 * @var integer
		 * @access Private
		 */
		var $month;
		/**
		 * @var date
		 * @access Private
		 */
		var $until_date;
		/**
		 * @var char
		 * @access Private
		 */
		var $repeat_event;
		/**
		 * @var integer
		 * @access Private
		 */
		var $number_views;
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
		 *		$eventObj = new Event($id);
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Event
		 * @access Public
		 * @param integer $var
		 */
		function Event($var='', $domain_id = false) {
		
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
				$sql = "SELECT * FROM Event WHERE id = $var";
				$row = mysql_fetch_array($db->query($sql));

				$this->old_account_id = $row["account_id"];

				$this->makeFromRow($row);
			}
			else {
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

			$statusObj = new ItemStatus();
			$level = new EventLevel();

			$this->id					= ($row["id"])					? $row["id"]				: ($this->id					? $this->id				: 0);
			$this->account_id			= ($row["account_id"])			? $row["account_id"]		: 0;
			$this->title				= ($row["title"])				? $row["title"]				: ($this->title					? $this->title			: "");
			$this->seo_title			= ($row["seo_title"])			? $row["seo_title"]			: ($this->seo_title				? $this->seo_title		: "");
			$this->friendly_url			= ($row["friendly_url"])		? $row["friendly_url"]		: "";
			$this->image_id				= ($row["image_id"])			? $row["image_id"]			: ($this->image_id				? $this->image_id		: 0);
			$this->thumb_id				= ($row["thumb_id"])			? $row["thumb_id"]			: ($this->thumb_id				? $this->thumb_id		: 0);
			$this->description			= ($row["description"])         ? $row["description"]		: "";
			$this->seo_description		= ($row["seo_description"])     ? $row["seo_description"]	: ($this->seo_description       ? $this->seo_description	: "");
			$this->long_description     = ($row["long_description"])	? $row["long_description"]	: "";
			$this->keywords             = ($row["keywords"])			? $row["keywords"]			: "";
			$this->seo_keywords         = ($row["seo_keywords"])		? $row["seo_keywords"]		: ($this->seo_keywords			? $this->seo_keywords	: "");
			$this->updated				= ($row["updated"])				? $row["updated"]			: ($this->updated				? $this->updated		: "");
			$this->entered				= ($row["entered"])				? $row["entered"]			: ($this->entered				? $this->entered		: "");
			$this->setDate("start_date", $row["start_date"]);
			$this->has_start_time       = ($row["has_start_time"])		? $row["has_start_time"]	: "n";
			$this->start_time           = ($row["start_time"])			? $row["start_time"]		: 0;
			$this->setDate("end_date", $row["end_date"]);
			$this->has_end_time         = ($row["has_end_time"])		? $row["has_end_time"]		: "n";
			$this->end_time             = ($row["end_time"])			? $row["end_time"]			: 0;
			$this->location             = ($row["location"])			? $row["location"]			: "";
			$this->address              = ($row["address"])				? $row["address"]			: "";
			$this->zip_code             = ($row["zip_code"])			? $row["zip_code"]			: "";
			$this->location_1           = ($row["location_1"])			? $row["location_1"]		: 0;
			$this->location_2           = ($row["location_2"])			? $row["location_2"]		: 0;
			$this->location_3           = ($row["location_3"])			? $row["location_3"]		: 0;
			$this->location_4           = ($row["location_4"])			? $row["location_4"]		: 0;
			$this->location_5           = ($row["location_5"])			? $row["location_5"]		: 0;
			$this->url                  = ($row["url"])					? $row["url"]				: "";
			$this->contact_name         = ($row["contact_name"])		? $row["contact_name"]		: "";
			$this->phone                = ($row["phone"])				? $row["phone"]				: "";
			$this->email                = ($row["email"])				? $row["email"]				: "";
			$this->renewal_date         = ($row["renewal_date"])		? $row["renewal_date"]		: ($this->renewal_date			? $this->renewal_date	: 0);
			$this->discount_id          = ($row["discount_id"])			? $row["discount_id"]		: "";
			$this->status               = ($row["status"])				? $row["status"]			: $statusObj->getDefaultStatus();
			$this->suspended_sitemgr	= ($row["suspended_sitemgr"])   ? $row["suspended_sitemgr"]		: ($this->suspended_sitemgr		? $this->suspended_sitemgr		: "n");
            $this->level                = ($row["level"])				? $row["level"]				: ($this->level					? $this->level			: $level->getDefaultLevel());
			$this->recurring            = ($row["recurring"])			? $row["recurring"]			: "N";
			$this->day                  = ($row["day"])					? $row["day"]				: 0;
			$this->dayofweek            = ($row["dayofweek"])			? $row["dayofweek"]			: "";
			$this->week                 = ($row["week"])				? $row["week"]				: "";
			$this->month                = ($row["month"])				? $row["month"]				: 0;
			$this->setDate("until_date", $row["until_date"]);
			$this->repeat_event			= ($row["repeat_event"])			? $row["repeat_event"]			: "N";
			if ($this->recurring == "N"){
				$this->day = 0;
				$this->dayofweek = "";
				$this->week = "";
				$this->month = 0;
				$this->until_date = "";
			}
			$this->number_views         = ($row["number_views"])		? $row["number_views"]		: ($this->number_views			? $this->number_views	: 0);
			$this->latitude             = ($row["latitude"])			? $row["latitude"]			: ($this->latitude              ? $this->latitude		: "");
			$this->longitude			= ($row["longitude"])			? $row["longitude"]			: ($this->longitude             ? $this->longitude		: "");
            $this->map_zoom             = ($row["map_zoom"])            ? $row["map_zoom"]			: 0;
			$this->data_in_array        = $row;
			$this->package_id           = ($row["package_id"])			? $row["package_id"]		: ($this->package_id		? $this->package_id			: 0);
			$this->package_price        = ($row["package_price"])		? $row["package_price"]		: ($this->package_price		? $this->package_price		: 0);
		}
	
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->Save();
		 * <br /><br />
		 *		//Using this in Event() class.
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

				$sql = "SELECT status, end_date, until_date FROM Event WHERE id = $this->id";
				$result = $dbObj->query($sql);
				if ($row = mysql_fetch_assoc($result)) {
					$last_status = $row["status"];
					$last_end_date = $row["end_date"];
					$last_until_date = $row["until_date"];
				}
				$this_status = $this->status;
				$this_id = $this->id;

				$sql  = "UPDATE Event SET"
					. " account_id        = $this->account_id,"
					. " title             = $this->title,"
					. " seo_title         = $this->seo_title,"
					. " friendly_url      = $this->friendly_url,"
					. " image_id          = $this->image_id,"
					. " thumb_id          = $this->thumb_id,"
					. " description       = $this->description,"
					. " seo_description   = $this->seo_description,"
					. " long_description  = $this->long_description,"
					. " keywords          = $this->keywords,"
					. " seo_keywords      = $this->seo_keywords,"
					. " updated           = NOW(),"
					. " start_date        = $this->start_date,"
					. " has_start_time    = $this->has_start_time,"
					. " start_time        = $this->start_time,"
					. " end_date          = $this->end_date,"
					. " has_end_time      = $this->has_end_time,"
					. " end_time          = $this->end_time,"
					. " location          = $this->location,"
					. " address           = $this->address,"
					. " zip_code          = $this->zip_code,"
					. " location_1        = $this->location_1,"
					. " location_2        = $this->location_2,"
					. " location_3        = $this->location_3,"
					. " location_4        = $this->location_4,"
					. " location_5        = $this->location_5,"
					. " url               = $this->url,"
					. " contact_name      = $this->contact_name,"
					. " phone             = $this->phone,"
					. " email             = $this->email,"
					. " renewal_date      = $this->renewal_date,"
					. " discount_id       = $this->discount_id,"
					. " status            = $this->status,"
					. " suspended_sitemgr = $this->suspended_sitemgr,"
					. " level             = $this->level,"
					. " recurring         = $this->recurring,"
					. " day 			  = $this->day,"
					. " dayofweek		  = $this->dayofweek,"
				    . " week			  = $this->week,"
				    . " month			  = $this->month,"
					. " until_date        = $this->until_date,"
					. " repeat_event	  = $this->repeat_event,"
					. " number_views      = $this->number_views,"
                    . " latitude          = $this->latitude,"
					. " longitude         = $this->longitude,"
					. " map_zoom          = $this->map_zoom,"
					. " package_id		  = $this->package_id,"
					. " package_price	  = $this->package_price"
					. " WHERE id          = $this->id";

				$dbObj->query($sql);

				$last_status = str_replace("\"", "", $last_status);
				$last_status = str_replace("'", "", $last_status);
				$this_status = str_replace("\"", "", $this_status);
				$this_status = str_replace("'", "", $this_status);
				$this_id = str_replace("\"", "", $this_id);
				$this_id = str_replace("'", "", $this_id);

				/////
				$lastendDateStr = explode("-", $last_end_date);
				$lastuntilDateStr = explode("-", $last_until_date);
				$endDateStr = explode("-", $this->end_date);
				$untilDateStr = explode("-", $this->until_date);


				$lastendDateStr = $lastendDateStr[0].$lastendDateStr[1].$lastendDateStr[2];
				$lastuntilDateStr = $lastuntilDateStr[0].$lastuntilDateStr[1].$lastuntilDateStr[2];
				$endDateStr = $endDateStr[0].$endDateStr[1].$endDateStr[2];
				$untilDateStr = $untilDateStr[0].$untilDateStr[1].$untilDateStr[2];
				$endDateStr = str_replace("'", "", $endDateStr);
				$untilDateStr = str_replace("'", "", $untilDateStr);
				////

				$incCheck = false;
				$decCheck1 = false;
				$decCheck2 = false;
				//if end_date/until_date is in the past and item status = A, category_count doesn't need changes, because daily_maintenance already did.
				//only change the counter if sitemgr/member corrects the date to future
				if (($last_status == "A" && $this_status == "A") && (($lastendDateStr < date("Ymd") && $endDateStr >= date("Ymd") && $this->recurring == "'N'") || ($this->recurring == "'Y'" && $this->repeat == "'N'" && $lastuntilDateStr < date("Ymd") && $untilDateStr >= date("Ymd")))){
					$incCheck = true;
				}

				if (($last_status == "A" && $this_status != "A") && (($lastendDateStr < date("Ymd") && $endDateStr < date("Ymd") && $this->recurring == "'N'") || ($this->recurring == "'Y'" && $this->repeat == "'N'" && $lastuntilDateStr < date("Ymd") && $untilDateStr < date("Ymd")))){
					$decCheck1 = true; //doesn't need any changes
				}

				if (($last_status != "A" && $this_status == "A") && (($lastendDateStr < date("Ymd") && $endDateStr < date("Ymd") && $this->recurring == "'N'") || ($this->recurring == "'Y'" && $this->repeat == "'N'" && $lastuntilDateStr < date("Ymd") && $untilDateStr < date("Ymd")))){
					$decCheck2 = true; //doesn't need any changes
				}

				if ($incCheck) system_countActiveItemByCategory("event", $this_id, "inc");
				if (($this_status == "A") && ($last_status != "A") && !$decCheck2) system_countActiveItemByCategory("event", $this_id, "inc");
				elseif (($last_status == "A") && ($this_status != "A") && !$decCheck1) system_countActiveItemByCategory("event", $this_id, "dec");

				if ($aux_old_account != $aux_account && $aux_account != 0) {
					domain_SaveAccountInfoDomain($aux_account, $this);
				}

				if ($last_status != "P" && $this_status == "P"){
					activity_newToApproved($aux_log_domain_id, $this->id, "event", $this->title);
				} else if ($last_status == "P" && $this_status != "P") {
					activity_deleteRecord($aux_log_domain_id, $this->id, "event");
				} else if ($last_status == $this_status){
					activity_updateRecord($aux_log_domain_id, $this->id, $this->title, "item", "event");
				}

			} else {

				$sql = "INSERT INTO Event"
					. " (account_id,"
					. " title,"
					. " seo_title,"
					. " friendly_url,"
					. " image_id,"
					. " thumb_id,"
					. " description,"
					. " seo_description,"
					. " long_description,"
					. " keywords,"
					. " seo_keywords,"
					. " updated,"
					. " entered,"
					. " start_date,"
					. " has_start_time,"
					. " start_time,"
					. " end_date,"
					. " has_end_time,"
					. " end_time,"
					. " location,"
					. " address,"
					. " zip_code,"
					. " location_1,"
					. " location_2,"
					. " location_3,"
					. " location_4,"
					. " location_5,"
					. " url,"
					. " contact_name,"
					. " phone,"
					. " email,"
					. " renewal_date,"
					. " discount_id,"
					. " status,"
					. " level,"
					. " fulltextsearch_keyword,"
					. " fulltextsearch_where,"
					. " recurring,"
					. " day,"
					. " dayofweek,"
					. " week,"
					. " month,"
					. " until_date,"
					. " repeat_event,"
					. " number_views,"
					. " latitude,"
					. " longitude,"
					. " map_zoom,"
					. " package_id,"
					. " package_price)"
					. " VALUES"
					. " ($this->account_id,"
					. " $this->title,"
					. " $this->title,"
					. " $this->friendly_url,"
					. " $this->image_id,"
					. " $this->thumb_id,"
					. " $this->description,"
					. " $this->description,"
					. " $this->long_description,"
					. " $this->keywords,"
					. " ".str_replace(" || ", ", ", $this->keywords).","
					. " NOW(),"
					. " NOW(),"
					. " $this->start_date,"
					. " $this->has_start_time,"
					. " $this->start_time,"
					. " $this->end_date,"
					. " $this->has_end_time,"
					. " $this->end_time,"
					. " $this->location,"
					. " $this->address,"
					. " $this->zip_code,"
					. " $this->location_1,"
					. " $this->location_2,"
					. " $this->location_3,"
					. " $this->location_4,"
					. " $this->location_5,"
					. " $this->url,"
					. " $this->contact_name,"
					. " $this->phone,"
					. " $this->email,"
					. " $this->renewal_date,"
					. " $this->discount_id,"
					. " $this->status,"
					. " $this->level,"
					. " '',"
					. " '',"
					. " $this->recurring,"
					. " $this->day,"
					. " $this->dayofweek,"
					. " $this->week,"
					. " $this->month,"
					. " $this->until_date,"
					. " $this->repeat_event,"
					. " $this->number_views,"
					. " $this->latitude,"
					. " $this->longitude,"
					. " $this->map_zoom,"
					. " $this->package_id,"
					. " $this->package_price)";

				$dbObj->query($sql);
				$this->id = mysql_insert_id($dbObj->link_id);

				if (sess_getAccountIdFromSession() || string_strpos($_SERVER["PHP_SELF"],"order_") !== false){
					activity_newActivity($aux_log_domain_id, $this->account_id, 0, "newitem", "event", $this->title);
				}

				if ($this->status == "'P'"){
					activity_newToApproved($aux_log_domain_id, $this->id, "event", $this->title);
				}

				domain_updateDashboard("number_content","inc",0,$aux_log_domain_id);

				$this_status = $this->status;
				$this_id = $this->id;
				$this_status = str_replace("\"", "", $this_status);
				$this_status = str_replace("'", "", $this_status);
				$this_id = str_replace("\"", "", $this_id);
				$this_id = str_replace("'", "", $this_id);
				if ($this_status == "A") system_countActiveItemByCategory("event", $this_id, "inc");

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
		 *		$eventObj->Delete();
		 * <code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name Delete
		 * @access Public
		 * @param integer $domain_id
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
			### EVENT CATEGORY
			if ($this->status == "A") system_countActiveItemByCategory("event", $this->id, "dec", false, $domain_id);

			### GALERY
			$sql = "DELETE FROM Gallery_Item WHERE item_type = 'event' AND item_id = $this->id";
			$dbObj->query($sql);

			### IMAGE
			if ($this->image_id) {
				$image = new Image($this->image_id);
				if ($image) $image->Delete($domain_id);
			}
			if ($this->thumb_id) {
				$image = new Image($this->thumb_id);
				if ($image) $image->Delete($domain_id);
			}

			### INVOICE
			$sql = "UPDATE Invoice_Event SET event_id = '0' WHERE event_id = $this->id";
			$dbObj->query($sql);

			### PAYMENT
			$sql = "UPDATE Payment_Event_Log SET event_id = '0' WHERE event_id = $this->id";
			$dbObj->query($sql);

			### GALERY
			$sql = "DELETE FROM Gallery_Item WHERE item_type = 'event' AND item_id = $this->id";
			$dbObj->query($sql);

			### EVENT
			$sql = "DELETE FROM Event WHERE id = $this->id";
			$dbObj->query($sql);

			if ($domain_id){
				$domain_idDash = $domain_id;
			} else {
				$domain_idDash = SELECTED_DOMAIN_ID;
			}

			domain_updateDashboard("number_content","dec", 0, $domain_idDash);

			activity_deleteRecord($domain_idDash, $this->id, "event");

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->updateImage($imageArray);
		 * <br /><br />
		 *		//Using this in Event() class.
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
		 *		$eventObj->getCategories();
		 * <br /><br />
		 *		//Using this in Event() class.
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
			$sql = "SELECT cat_1_id, cat_2_id, cat_3_id, cat_4_id, cat_5_id FROM Event WHERE id = $this->id";
			$r = $dbObj->query($sql);
			while ($row = mysql_fetch_array($r)) {
				if ($row["cat_1_id"]) $categories[] = new EventCategory($row["cat_1_id"]);
				if ($row["cat_2_id"]) $categories[] = new EventCategory($row["cat_2_id"]);
				if ($row["cat_3_id"]) $categories[] = new EventCategory($row["cat_3_id"]);
				if ($row["cat_4_id"]) $categories[] = new EventCategory($row["cat_4_id"]);
				if ($row["cat_5_id"]) $categories[] = new EventCategory($row["cat_5_id"]);
			}
			return $categories;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->setCategories();
		 * <br /><br />
		 *		//Using this in Event() class.
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

			if ($this->status == "A") system_countActiveItemByCategory("event", $this->id, "dec");

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
							$sql = "SELECT * FROM EventCategory WHERE id = $cat_id";
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
			$sql = "UPDATE Event SET cat_1_id = ".$cat_1_id.", parcat_1_level1_id = ".$parcat_1_level1_id.", parcat_1_level2_id = ".$parcat_1_level2_id.", parcat_1_level3_id = ".$parcat_1_level3_id.", parcat_1_level4_id = ".$parcat_1_level4_id.", cat_2_id = ".$cat_2_id.", parcat_2_level1_id = ".$parcat_2_level1_id.", parcat_2_level2_id = ".$parcat_2_level2_id.", parcat_2_level3_id = ".$parcat_2_level3_id.", parcat_2_level4_id = ".$parcat_2_level4_id.", cat_3_id = ".$cat_3_id.", parcat_3_level1_id = ".$parcat_3_level1_id.", parcat_3_level2_id = ".$parcat_3_level2_id.", parcat_3_level3_id = ".$parcat_3_level3_id.", parcat_3_level4_id = ".$parcat_3_level4_id.", cat_4_id = ".$cat_4_id.", parcat_4_level1_id = ".$parcat_4_level1_id.", parcat_4_level2_id = ".$parcat_4_level2_id.", parcat_4_level3_id = ".$parcat_4_level3_id.", parcat_4_level4_id = ".$parcat_4_level4_id.", cat_5_id = ".$cat_5_id.", parcat_5_level1_id = ".$parcat_5_level1_id.", parcat_5_level2_id = ".$parcat_5_level2_id.", parcat_5_level3_id = ".$parcat_5_level3_id.", parcat_5_level4_id = ".$parcat_5_level4_id." WHERE id = $this->id";
			$dbObj->query($sql);
			$this->setFullTextSearch();

			if ($this->status == "A") system_countActiveItemByCategory("event", $this->id, "inc");
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getCategories();
		 * <br /><br />
		 *		//Using this in Event() class.
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

			$levelObj = new EventLevel();
			if($this->package_id){
				$price = $this->package_price;
			}else{
				$price = $price + $levelObj->getPrice($this->level);
			}

			if ($this->discount_id) {
			
				$discountCodeObj = new DiscountCode($this->discount_id);
				
				if (is_valid_discount_code($this->discount_id, "event", $this->id, $discount_message, $discount_error)) {

					if ($discountCodeObj->getString("id") && $discountCodeObj->expire_date >= date('Y-m-d')) {

						if ($discountCodeObj->getString("type") == "percentage") {
							$price = $price * (1 - $discountCodeObj->getString("amount")/100);
						} elseif ($discountCodeObj->getString("type") == "monetary value") {
							$price = $price - $discountCodeObj->getString("amount");
						}

					} elseif ( ($discountCodeObj->type == 'percentage' && $discountCodeObj->amount == '100.00') || ($discountCodeObj->type == 'monetary value' && $discountCodeObj->amount > $price) ) {
                        $this->status = 'E';
                        $this->renewal_date = $discountCodeObj->expire_date;

                        $sql = "UPDATE Event SET status = 'E', renewal_date = '".$discountCodeObj->expire_date."', discount_id = '' WHERE id = ".$this->id;
                        $result = $dbObj->query($sql);
                    }

				} else {
					
					if ( ($discountCodeObj->type == 'percentage' && $discountCodeObj->amount == '100.00') || ($discountCodeObj->type == 'monetary value' && $discountCodeObj->amount > $price) ) {
                        $this->status = 'E';
                        $this->renewal_date = $discountCodeObj->expire_date; 
                        $sql = "UPDATE Event SET status = 'E', renewal_date = '".$discountCodeObj->expire_date."', discount_id = '' WHERE id = ".$this->id;
					} else {
						$sql = "UPDATE Event SET discount_id = '' WHERE id = ".$this->id;
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
		 *		$eventObj->hasRenewalDate();
		 * <br /><br />
		 *		//Using this in Event() class.
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
		 *		$eventObj->needToCheckOut();
		 * <br /><br />
		 *		//Using this in Event() class.
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
		 *		$eventObj->getNextRenewalDate($times);
		 * <br /><br />
		 *		//Using this in Event() class.
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

				$renewalcycle = payment_getRenewalCycle("event");
				$renewalunit = payment_getRenewalUnit("event");

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
		 *		//Using this in forms or other pages.
		 *		$eventObj->getDateString();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getDateString();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getDateString
		 * @access Public
		 * @return string
		 */
		function getDateString($use_text = false) {
			$str_date = "";

			if ($this->getDate("start_date") == $this->getDate("end_date")){
				$str_date = $this->getDate("start_date");
			}elseif ($this->getString("recurring")!="Y"){
				if($use_text){
					$str_date = "<p><strong>".ucfirst(system_showText(LANG_LABEL_FROM)).":</strong>"."<span>".$this->getDate("start_date")."</span></p>"."<p><strong>".ucfirst(system_showText(LANG_LABEL_DATE_TO)).":</strong>"."<span>".$this->getDate("end_date")."</span></p>";
				}else{
					$str_date = $this->getDate("start_date")." - ".$this->getDate("end_date");
				}
			}else{
				$str_date = $this->getDate("start_date");
			}

			return $str_date;
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getDateStringEnd();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getDateStringEnd();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getDateStringEnd
		 * @access Public
		 * @return string
		 */
		function getDateStringEnd() {
			$str_date = "";
			$str_date = $this->getDate("until_date");

			return $str_date;
		}

        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getDateStringRecurring();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getDateStringRecurring();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getDateStringRecurring
		 * @access Public
		 * @return string
		 */
		function getDateStringRecurring() {
			$str_date = "";

			if ($this->getString("recurring")=="Y"){

				$month_names = explode(",", LANG_DATE_MONTHS);
			    $weekday_names = explode(",", LANG_DATE_WEEKDAYS);
				if ($this->getString("dayofweek") and $this->getNumber("week") and $this->getNumber("month")) {
                        $aux = system_getRecurringWeeks($this->getString("week"));
						$checkDays = system_checkDay($this->getString("dayofweek"));
						$str_date .= $checkDays;
						if ($aux)
							$str_date .= " ".system_showText(LANG_OF)." ".$aux.system_showText(LANG_WEEK)." ". system_showText(LANG_OF2) ." ". ucfirst($month_names[$this->getNumber("month")-1]);
						else
							$str_date .= " ". system_showText(LANG_OF2) ." ". ucfirst($month_names[$this->getNumber("month")-1]);

                }elseif($this->getNumber("day")){
					if($this->getNumber("month")){
						if (EDIR_LANGUAGE == "en_us") {
							$str_date .= ucfirst($month_names[$this->getNumber("month")-1])." ".$this->getNumber("day");
                        } else {
							$str_date .= ucfirst(system_showText(LANG_DAY))." ".$this->getNumber("day")." ".system_showText(LANG_OF2)." ".ucfirst($month_names[$this->getNumber("month")-1]);
                        }
					}else{
						$str_date .= system_showText(LANG_EVERY2)." ".system_showText(LANG_DAY)." ".$this->getNumber("day");
					}
				}elseif($this->getString("dayofweek")){

					if($this->getNumber("week")){

						$aux = system_getRecurringWeeks($this->getString("week"));
						$checkDays = system_checkDay($this->getString("dayofweek"));
						$str_date .= $checkDays." ";
						if ($aux)
							$str_date .= LANG_OF3." ".$aux.LANG_WEEK;
					}else{
						$checkDays = system_checkDay($this->getString("dayofweek"));
						$str_date .= $checkDays;
					}

				}else{
					$str_date .= system_showText(LANG_DAILY2);
				}

			}

			return $str_date;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getTimeString();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getTimeString();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getTimeString
		 * @access Public
		 * @return string
		 */
		function getTimeString() { 
			$str_time = "";
			if ($this->getString("has_start_time") == "y") {
				$startTimeStr = explode(":", $this->getString("start_time"));
                if (CLOCK_TYPE == '24') {
                    $start_time_hour = $startTimeStr[0];    
                } elseif (CLOCK_TYPE == '12') {
				    if ($startTimeStr[0] > "12") {
					    $start_time_hour = $startTimeStr[0] - 12;
					    $start_time_am_pm = "pm";
				    } elseif ($startTimeStr[0] == "12") {
					    $start_time_hour = 12;
					    $start_time_am_pm = "pm";
				    } elseif ($startTimeStr[0] == "00") {
					    $start_time_hour = 12;
					    $start_time_am_pm = "am";
				    } else {
					    $start_time_hour = $startTimeStr[0];
					    $start_time_am_pm = "am";
				    }
                }
                if ($start_time_hour < 10) $start_time_hour = "0".($start_time_hour+0);
                $start_time_min = $startTimeStr[1];
                $str_time .= $start_time_hour.":".$start_time_min." ".$start_time_am_pm;
			} else {
				$str_time .= LANG_NA;
			}
			$str_time .= " - ";
			if ($this->getString("has_end_time") == "y") {
				$endTimeStr = explode(":", $this->getString("end_time"));
                if (CLOCK_TYPE == '24') {
                    $end_time_hour = $endTimeStr[0];
                } elseif (CLOCK_TYPE == '12') {
				    if ($endTimeStr[0] > "12") {
					    $end_time_hour = $endTimeStr[0] - 12;
					    $end_time_am_pm = "pm";
				    } elseif ($endTimeStr[0] == "12") {
					    $end_time_hour = 12;
					    $end_time_am_pm = "pm";
				    } elseif ($endTimeStr[0] == "00") {
					    $end_time_hour = 12;
					    $end_time_am_pm = "am";
				    } else {
					    $end_time_hour = $endTimeStr[0];
					    $end_time_am_pm = "am";
				    }
                }
                if ($end_time_hour < 10) $end_time_hour = "0".($end_time_hour+0);
                $end_time_min = $endTimeStr[1];
                $str_time .= $end_time_hour.":".$end_time_min." ".$end_time_am_pm;
			} else {
				$str_time .= LANG_NA;
			}
			if (($this->getString("has_start_time") == "n") && ($this->getString("has_end_time") == "n")) {
				$str_time = "";
			}
			return $str_time;
		}
		
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getMonthAbbr();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getMonthAbbr();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getMonthAbbr
		 * @access Public
		 * @return string
		 */
		function getMonthAbbr() {
			$aux = explode("/", $this->getDate("start_date"));
			$months = explode(",", LANG_DATE_MONTHS);
			if (DEFAULT_DATE_FORMAT == "m/d/Y")
				$month = $aux[0];
			else
				$month = $aux[1];

			switch ($month){
				case "01" : return string_substr($months[0], 0 , 3);
							break;
				case "02" : return string_substr($months[1], 0 , 3);
							break;
				case "03" : return string_substr($months[2], 0 , 3);
							break;
				case "04" : return string_substr($months[3], 0 , 3);
							break;
				case "05" : return string_substr($months[4], 0 , 3);
							break;
				case "06" : return string_substr($months[5], 0 , 3);
							break;
				case "07" : return string_substr($months[6], 0 , 3);
							break;
				case "08" : return string_substr($months[7], 0 , 3);
							break;
				case "09" : return string_substr($months[8], 0 , 3);
							break;
				case "10" : return string_substr($months[9], 0 , 3);
							break;
				case "11" : return string_substr($months[10], 0 , 3);
							break;
				case "12" : return string_substr($months[11], 0 , 3);
							break;
			}
		}
		
		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->checkStartDate();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->checkStartDate();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name checkStartDate
		 * @access Public
		 * @return string
		 */
		function checkStartDate() {
			if($this->getString("recurring") != "Y"){
				$today = date("Y-m-d");
				$auxStartDate = explode("/", $this->getDate("start_date"));
				if (DEFAULT_DATE_FORMAT == "m/d/Y"){
					$startDate = $auxStartDate[2]."-".$auxStartDate[0]."-".$auxStartDate[1];
				} else{
					$startDate = $auxStartDate[2]."-".$auxStartDate[1]."-".$auxStartDate[0];
				}
				if ($today == $startDate){
					return true;
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
		 *		$eventObj->getMonthAbbr();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getMonthAbbr();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getTimeString
		 * @access Public
		 * @return string
		 */
		function getDayStr() {
			$aux = explode("/", $this->getDate("start_date"));
			if (DEFAULT_DATE_FORMAT == "m/d/Y")
				return $aux[1];
			else
				return $aux[0];
			
		}

		/**
		 * <code>
		 *		//Using this in Event() class.
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
		 *		//Using this in Event() class.
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
		 *		$eventObj->getLocationString($format,$forceManagerCreation);
		 * <br /><br />
		 *		//Using this in Event() class.
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
		 *		$eventObj->setFullTextSearch();
		 * <br /><br />
		 *		//Using this in Event() class.
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

            if ($this->description) {
                $fulltextsearch_keyword[] = string_substr($this->description, 0, 100);
            }

			if ($this->address) {
				$fulltextsearch_where[] = $this->address;
			}

			if ($this->location) {
				$fulltextsearch_where[] = $this->location;
			}

			if ($this->zip_code) {
				$fulltextsearch_where[] = $this->zip_code;
			}

			$Location1 = new Location1($this->location_1);
			if ($Location1->getNumber("id")) {
				$fulltextsearch_where[] = $Location1->getString("name", false);
				if ($Location1->getString("abbreviation")) {
					$fulltextsearch_where[] = $Location1->getString("abbreviation", false);
				}
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
						$sql = "SELECT * FROM EventCategory WHERE id = $category_id";
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
				$sql = "UPDATE Event SET fulltextsearch_keyword = $fulltextsearch_keyword_sql WHERE id = $this->id";
				$result = $dbObj->query($sql);
			}
			if (is_array($fulltextsearch_where)) {
				$fulltextsearch_where_sql = db_formatString(implode(" ", $fulltextsearch_where));
				$sql = "UPDATE Event SET fulltextsearch_where = $fulltextsearch_where_sql WHERE id = $this->id";
				$result = $dbObj->query($sql);
			}

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getGalleries();
		 * <br /><br />
		 *		//Using this in Event() class.
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
			$sql = "SELECT * FROM Gallery_Item WHERE item_type='event' AND item_id = $this->id ORDER BY gallery_id";
			$r = $dbObj->query($sql);
			if ($this->id > 0) while ($row = mysql_fetch_array($r)) $galleries[] = $row["gallery_id"];
			return $galleries;
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->setGalleries($gallery);
		 * <br /><br />
		 *		//Using this in Event() class.
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
			$sql = "DELETE FROM Gallery_Item WHERE item_type='event' AND item_id = $this->id";
			$dbObj->query($sql);
				if ($gallery) {
					$sql = "INSERT INTO Gallery_Item (item_id, gallery_id, item_type) VALUES ($this->id, $gallery, 'event')";
					$rs3 = $dbObj->query($sql);
				}
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->setMapTuning($latitude_longitude,$map_zoom);
		 * <br /><br />
		 *		//Using this in Event() class.
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
            
			$sql = "UPDATE Event SET latitude = ".db_formatString($latitude).", longitude = ".db_formatString($longitude).", map_zoom = ".db_formatNumber($map_zoom)." WHERE id = ".$this->id."";
			$dbObj->query($sql);
		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->hasDetail();
		 * <br /><br />
		 *		//Using this in Event() class.
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
            $eventLevel = new EventLevel();
            $detail = $eventLevel->getDetail($this->level);
            unset($eventLevel);
            return $detail;
        }

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->setNumberViews($id);
		 * <br /><br />
		 *		//Using this in Event() class.
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
			$sql = "UPDATE Event SET number_views = ".$this->number_views." + 1 WHERE Event.id = ".$id;
			$dbObj->query($sql);

		}

		/**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->deletePerAccount($account_id);
		 * <br /><br />
		 *		//Using this in Event() class.
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
				$sql = "SELECT * FROM Event WHERE account_id = $account_id";
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
		 *		$eventObj->getFriendlyURL();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getFriendlyURL();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getFriendlyURL
		 * @access Public
         * @param boolean $mobile
		 * @return boolean
		 */
		function getFriendlyURL($mobile = false) {
        	if ($mobile) {
        		$aux_url = DEFAULT_URL."/mobile/".EVENT_FEATURE_FOLDER;
        	} else {
        		$aux_url = EVENT_DEFAULT_URL;
        	}
        
	        return $aux_url."/".$this->friendly_url.".html";
		}
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->getEventToApp();
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->getEventToApp();
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name getEventToApp
		 * @access Public
		 */
        function getEventToApp() {
            
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
                $aux_detail_fields[] = "description";
                $aux_detail_fields[] = "long_description";
                $aux_detail_fields[] = "status";
                $aux_detail_fields[] = "suspended_sitemgr";
                $aux_detail_fields[] = "level";
                $aux_detail_fields[] = "start_date";
                $aux_detail_fields[] = "end_date";
                $aux_detail_fields[] = "start_time";
                $aux_detail_fields[] = "end_time";
                $aux_detail_fields[] = "level";
                $aux_detail_fields[] = "recurring";
                
                /*
                 * Number fields
                 */
                unset($number_fields);
                $number_fields[] = "latitude";
                $number_fields[] = "longitude";
                $number_fields[] = "level";
                
                unset($add_info);
                //$this->data_in_array["location_information"] = $this->getLocationString("A, 4, 3, 1", true);
                $add_info["location_information"] = $this->getLocationString("A, 4, 3, 1", true);
                $add_info["string_time"] = $this->getTimeString();
                
                if ($this->getString("recurring") == "Y") {
                    $add_info["recurring_string"] = $this->getDateStringRecurring();
                } else {
                    $add_info["recurring_string"] = "";
                }
                
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
                    
                    for ($i=0; $i < count($aux_galleries); $i++) {
                        
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
		 *		$eventObj->GetInfoToApp($array_get, $aux_returnArray, $aux_fields, $items, $auxTable, $aux_Where);
		 * <br /><br />
		 *		//Using this in Event() class.
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
        function GetInfoToApp($array_get, &$aux_returnArray, &$aux_fields, &$items, &$auxTable, &$aux_Where) {
            
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
                 * Get Event
                 */
                unset($eventObj,$eventInfo);
                $eventObj = new Event($id);

                $eventInfo = $eventObj->getEventToApp();

                if (!is_array($eventInfo)) {

                    $aux_returnArray["error"]           = "No results found.";
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = 0; 
                    $aux_returnArray["total_pages"]     = 0; 
                    $aux_returnArray["results_per_page"]= 0; 
                    
                } else {
                    $items[] = $eventInfo;
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = 1; 
                    $aux_returnArray["total_pages"]     = 1; 
                    $aux_returnArray["results_per_page"]= 1;
                }

            } else {

                $auxTable = "Event";

                $aux_orderBy[] = "level";
                $aux_orderBy[] = "title";

                $aux_Where[] = "status = 'A'";

            }

            if ($searchBy) {
                if ($searchBy == "keyword" && $keyword) {

                    unset($searchReturn);
                    $searchReturn["from_tables"]    = "Event";
                    $searchReturn["order_by"]       = "Event.level, Event.title";
                    $searchReturn["where_clause"]   = "Event.status = 'A' ";
                    $searchReturn["select_columns"] = implode(", ",$aux_fields);
                    $searchReturn["group_by"]       = false;

                    $letterField = "title";
                    search_frontAppKeyword($array_get, $searchReturn,"Event");

                    $pageObj = new pageBrowsing($searchReturn["from_tables"], $page, $aux_results_per_page, $searchReturn["order_by"], $letterField, $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Event", $searchReturn["group_by"]);
                    $items = $pageObj->retrievePage("array");

                    if (!is_array($items)) {
                        $aux_returnArray["error"]       = "No results found.";
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
                    $searchReturn = search_frontDrawMap($array_get, $fields_to_map,"Event");
                    $pageObj = new pageBrowsing($searchReturn["from_tables"], $page, $aux_results_per_page, $searchReturn["order_by"], $letterField, $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Event", $searchReturn["group_by"]);

                    $items = $pageObj->retrievePage("array");
                    
                    if (!is_array($items)) {
                        $aux_returnArray["error"]       = "No results found.";
                    }
                    
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = $pageObj->record_amount; 
                    $aux_returnArray["total_pages"]     = $pageObj->pages; 
                    $aux_returnArray["results_per_page"]= $pageObj->limit; 
         

                } elseif ($searchBy == "category" && $category_id) {

                    /*
                     * Get Events by category_id
                     */
                    $search_for["category_id"] = $category_id;
                    $searchReturn = search_frontEventSearch($search_for, "event");
                    
                    if ($searchReturn) {
                        $aux_Where[] = $searchReturn["where_clause"];
                    } else {
                        $aux_returnArray["error"] = "No results found.";
                    }
                    
                } elseif ($searchBy == "calendar" && $year) {
                    
                    return $this->EventsDay($year,$month);
                    
                } elseif ($searchBy == "calendarList" && $month && $year) {
                    
                    $search_for["single_month"] = $year.$month;
                    $searchReturn = search_frontEventSearch($search_for, "event");
                    $searchReturn["select_columns"] = implode(", ",$fields_to_map);
                    $searchReturn["order_by"]       = "Event.start_date";
                    
                    $pageObj = new pageBrowsing($searchReturn["from_tables"], $page, $aux_results_per_page, $searchReturn["order_by"], $letterField, $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Event", $searchReturn["group_by"]);
                    $items = $pageObj->retrievePage("array");
                    
                    if (!is_array($items)) {
                        $aux_returnArray["error"]       = "No results found.";
                    }
                    
                    $aux_returnArray["type"]            = $resource;
                    $aux_returnArray["total_results"]   = $pageObj->record_amount; 
                    $aux_returnArray["total_pages"]     = $pageObj->pages; 
                    $aux_returnArray["results_per_page"]= $pageObj->limit; 
                } else {
                    echo "Wrong Search, check the parameters";
                    exit;
                }
            }
        }
        
        /**
		 * <code>
		 *		//Using this in forms or other pages.
		 *		$eventObj->EventsDay($year, $month);
		 * <br /><br />
		 *		//Using this in Event() class.
		 *		$this->EventsDay($year, $month);
		 * </code>
		 * @copyright Copyright 2005 Arca Solutions, Inc.
		 * @author Arca Solutions, Inc.
		 * @version 8.0.00
		 * @name EventsDay
         * @param string $year
         * @param boolean $month
		 * @access Public
		 */
        function EventsDay($year, $month = false) {
            $db = db_getDBObject();
            $sql = "SELECT concat(month(start_date),
                           day(start_date)) as concat_Mon_Day,
                           day(start_date) as day_start_date, 
                           month(start_date) month_start_date 
                      FROM Event 
                     WHERE year(start_date) = ".$year." AND ".($month ? " month(start_date) = ".$month." AND " : "")."
                           status = 'A' 
                  GROUP BY month_start_date, day_start_date
                  ORDER BY month_start_date";
            $result = $db->query($sql);
            
            /**
             * Preparing array to return
             */
            $aux_returnArray["type"]                = "eventCalendar";
            $aux_returnArray["total_results"]       = 12; // months 
            $aux_returnArray["total_pages"]         = 1 ;
            $aux_returnArray["results_per_page"]    = 12; //months 
            if (mysql_num_rows($result)) {
                unset($arrayDayEvents);
                
                
                ;
                
                
                while ($row = mysql_fetch_assoc($result)) {
                    $arrayDayEvents[date('M', mktime(0, 0, 0, $row["month_start_date"], 1, $year))][] = (int)$row["day_start_date"];
                }
                
                for ($i=1; $i <= 12; $i++) {

                    if (!is_array($arrayDayEvents[date('M', mktime(0, 0, 0, $i, 1, $year))])) {
                        //$arrayDayEvents[$year][$i] = array(0);
                        $arrayDayEvents[date('M', mktime(0, 0, 0, $i, 1, $year))] = NULL;
                    }

                }
                /*  
                 */
                ksort($arrayDayEvents);
                    
                if (is_array($arrayDayEvents)) {
                    
                    /**
                     * Add header to JSON return
                     */
                    
                    $aux_returnArray["results"]         = $arrayDayEvents; 
                    
                    return $aux_returnArray;
                } else {
                    $aux_returnArray["error"]           = "No results found.";
                    return false;
                }
            } else {
                return false;
            } 
        }
	}

?>