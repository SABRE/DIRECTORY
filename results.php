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
	# * FILE: /results.php
	# ----------------------------------------------------------------------------------------------------
	
	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("./conf/loadconfig.inc.php");
	
	# ----------------------------------------------------------------------------------------------------
	# CACHE
	# ----------------------------------------------------------------------------------------------------
	cachefull_header();

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSessionFront();

	# ----------------------------------------------------------------------------------------------------
	# MAINTENANCE MODE
	# ----------------------------------------------------------------------------------------------------
	verify_maintenanceMode();

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# SITE CONTENT
	# ----------------------------------------------------------------------------------------------------
	$sitecontentSection = "Directory Results";
    $array_HeaderContent = front_getSiteContent($sitecontentSection);
    extract($array_HeaderContent);

	# ----------------------------------------------------------------------------------------------------
	# RESULTS
	# ----------------------------------------------------------------------------------------------------

	$search_lock = false;
	if (LISTING_SCALABILITY_OPTIMIZATION == "on") {
		if (!$_GET["keyword"] && !$_GET["where"] && !$_GET["category_id"] && !$_GET["zip"] && !$_GET["id"]) {
			$_GET["id"] = 0;
			$search_lock = true;
		} else {
			if ($_GET["keyword"] && string_strlen($_GET["keyword"]) < (int)FT_MIN_WORD_LEN && !$_GET["where"]) {
				$_GET["id"] = 0;
				$search_lock = true;
			} else if ($_GET["keyword"] && !$_GET["where"]) {
				$aux = explode(" ", $_GET["keyword"]);
				$search_lock = true;
				for ($i = 0; $i < count($aux); $i++) {
					if (string_strlen($aux[$i]) >= (int)FT_MIN_WORD_LEN) {
						$search_lock = false;
					}
				}
				if ($search_lock) {
					$_GET["id"] = 0;
				}
			}

			if ($_GET["where"] && string_strlen($_GET["where"]) < (int)FT_MIN_WORD_LEN && !$_GET["keyword"]) {
				$_GET["id"] = 0;
				$search_lock = true;
			} else if ($_GET["where"] && !$_GET["keyword"]) {
				$aux = explode(" ", $_GET["where"]);
				$search_lock = true;
				for ($i = 0; $i < count($aux); $i++) {
					if (string_strlen($aux[$i]) >= (int)FT_MIN_WORD_LEN) {
						$search_lock = false;
					}
				}
				if ($search_lock) {
					$_GET["id"] = 0;
				}
			}

			if ($_GET["keyword"] && string_strlen($_GET["keyword"]) < (int)FT_MIN_WORD_LEN && $_GET["where"] && string_strlen($_GET["where"]) < (int)FT_MIN_WORD_LEN) {
				$_GET["id"] = 0;
				$search_lock = true;
			}
		}
	}

	// replacing useless spaces in search by "where"
	if ($_GET["where"]) {
		while (string_strpos($_GET["where"], "  ") !== false) {
			str_replace("  ", " ", $_GET["where"]);
		}
		if ((string_strpos($_GET["where"], ",") !== false) && (string_strpos($_GET["where"], ", ") === false)) {
			str_replace(",", ", ", $_GET["where"]);
		}
	}

	// replacing useless spaces in search by "where"
	if ($_GET["where"]) {
		while (string_strpos($_GET["where"], "  ") !== false) {
			str_replace("  ", " ", $_GET["where"]);
		}
		if ((string_strpos($_GET["where"], ",") !== false) && (string_strpos($_GET["where"], ", ") === false)) {
			str_replace(",", ", ", $_GET["where"]);
		}
	}

	unset($searchReturn);
    if(!$search_lock){
        $searchReturn = search_frontListingSearch($_GET, "listing_results");
        $aux_items_per_page = ($_COOKIE["listing_results_per_page"] ? $_COOKIE["listing_results_per_page"] : 10);
        $pageObj = new pageBrowsing($searchReturn["from_tables"], $screen, $aux_items_per_page, $searchReturn["order_by"], "Listing_Summary.title", $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Listing_Summary", $searchReturn["group_by"]);
        $listings = $pageObj->retrievePage("array",$searchReturn["total_listings"]);
        $paging_url = LISTING_DEFAULT_URL."/results.php";

        /*
         * Will be used on:
         * /frontend/results_info.php
         * /frontend/results_filter.php
         * /frontend/results_maps.php
         * functions/script_funct.php
         */
        $aux_module_per_page			= "listing";
        $aux_module_items				= $listings; 
        $aux_module_itemRSSSection		= "listing";

        $levelsWithReview = system_retrieveLevelsWithInfoEnabled("has_review");

        $array_search_params = array();

        $paging_url = DEFAULT_URL."/results.php";
        foreach ($_GET as $name => $value){
            if ($name != "screen" && $name != "letter"){
                if ( $name == "keyword" || $name == "where" ) $array_search_params[] = $name."=".urlencode($value);
                else $array_search_params[] = $name."=".$value;
            }
        }
        $url_search_params = implode("&amp;", $array_search_params);

        /*
         * Preparing Pagination
         */
        unset($letters_menu);
        $letters_menu		= system_prepareLetterToPagination($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, LISTING_SCALABILITY_OPTIMIZATION);
        $array_pages_code	= system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, $screen, $aux_items_per_page, true);

        $user = true;

        setting_get('commenting_edir', $commenting_edir);
        setting_get("review_listing_enabled", $review_enabled);
        $db = db_getDBObject();			
        $sql = "SELECT count(*) as nunberOfReviews FROM Review WHERE item_type = 'listing'";
        $result = $db->query($sql);
        $result = mysql_fetch_assoc($result);
        $numberOfReviews = $result['nunberOfReviews'];

        if ($review_enabled && $commenting_edir && $numberOfReviews) {
            $orderBy =  array(LANG_PAGING_ORDERBYPAGE_CHARACTERS,LANG_PAGING_ORDERBYPAGE_LASTUPDATE,LANG_PAGING_ORDERBYPAGE_DATECREATED,LANG_PAGING_ORDERBYPAGE_POPULAR,LANG_PAGING_ORDERBYPAGE_RATING);	
        } else {
            $orderBy =  array(LANG_PAGING_ORDERBYPAGE_CHARACTERS,LANG_PAGING_ORDERBYPAGE_LASTUPDATE,LANG_PAGING_ORDERBYPAGE_DATECREATED,LANG_PAGING_ORDERBYPAGE_POPULAR);
        }
        
        if (LISTING_ORDERBY_PRICE){
            array_unshift($orderBy, LANG_PAGING_ORDERBYPAGE_PRICE);
        }

        $orderbyDropDown = search_getOrderbyDropDown($_GET, $paging_url, $orderBy, system_showText(LANG_PAGING_ORDERBYPAGE)." ", "this.form.submit();", $parts);
    }
    
    # --------------------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	$headertag_title = $headertagtitle;
	$headertag_description = $headertagdescription;
	$headertag_keywords = $headertagkeywords;
    $activeMenuHome = true;
	include(system_getFrontendPath("header.php", "layout"));

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	require(EDIRECTORY_ROOT."/frontend/checkregbin.php");
	$showLetter = true;
	if (!$listings && !$letter) $showLetter = false;

    # ----------------------------------------------------------------------------------------------------
	# BODY
	# ----------------------------------------------------------------------------------------------------
	include(THEMEFILE_DIR."/".EDIR_THEME."/body/results.php");
	
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(system_getFrontendPath("footer.php", "layout"));
	
	# ----------------------------------------------------------------------------------------------------
	# CACHE
	# ----------------------------------------------------------------------------------------------------
	cachefull_footer();
?>