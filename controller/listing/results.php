<?
    include(EDIR_CONTROLER_FOLDER . "/" . LISTING_FEATURE_FOLDER . "/rewrite.php");
    include(EDIRECTORY_ROOT . "/includes/code/validate_querystring.php");
    include(EDIRECTORY_ROOT . "/includes/code/validate_frontrequest.php");
    if ($category_id) {
        $catObj = new ListingCategory($category_id);
        if (!$catObj->getString("title")) {
            header("Location: " . LISTING_DEFAULT_URL . "/index.php");
            exit;
        }
    }
    # ----------------------------------------------------------------------------------------------------------------------
    # RESULTS
    # ----------------------------------------------------------------------------------------------------------------------
    $search_lock = false;
    
    if (LISTING_SCALABILITY_OPTIMIZATION == "on") 
    {
       if (!$_GET["keyword"] && !$_GET["where"] && !$_GET["category_id"] && !$mod_rewrite_have_location && !$_GET["zip"] && !$_GET["template_id"] && !$_GET["id"]) {
            $_GET["id"] = 0;
            $search_lock = true;
        } else {
            if ($_GET["keyword"] && string_strlen($_GET["keyword"]) < (int) FT_MIN_WORD_LEN && !$_GET["where"]) {
                $_GET["id"] = 0;
                $search_lock = true;
            } else if ($_GET["keyword"] && !$_GET["where"]) {
                $aux = explode(" ", $_GET["keyword"]);
                $search_lock = true;
                for ($i = 0; $i < count($aux); $i++) {
                    if (string_strlen($aux[$i]) >= (int) FT_MIN_WORD_LEN) {
                        $search_lock = false;
                    }
                }
                if ($search_lock) {
                    $_GET["id"] = 0;
                }
            }

            if ($_GET["where"] && string_strlen($_GET["where"]) < (int) FT_MIN_WORD_LEN && !$_GET["keyword"]) {
                $_GET["id"] = 0;
                $search_lock = true;
            } else if ($_GET["where"] && !$_GET["keyword"]) {
                $aux = explode(" ", $_GET["where"]);
                $search_lock = true;
                for ($i = 0; $i < count($aux); $i++) {
                    if (string_strlen($aux[$i]) >= (int) FT_MIN_WORD_LEN) {
                        $search_lock = false;
                    }
                }
                if ($search_lock) {
                    $_GET["id"] = 0;
                }
            }

            if ($_GET["keyword"] && string_strlen($_GET["keyword"]) < (int) FT_MIN_WORD_LEN && $_GET["where"] && string_strlen($_GET["where"]) < (int) FT_MIN_WORD_LEN) {
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

    unset($searchReturn);

    if (!$search_lock) 
    {

        $searchReturn = search_frontListingSearch($_GET, "listing_results");
        $aux_items_per_page = ($_COOKIE["listing_results_per_page"] ? $_COOKIE["listing_results_per_page"] : 10);
        $pageObj = new pageBrowsing($searchReturn["from_tables"], ($_GET["url_full"] ? $page : $screen), $aux_items_per_page, $searchReturn["order_by"], "Listing_Summary.title", $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Listing_Summary", $searchReturn["group_by"]);
        $listings = $pageObj->retrievePage("array");
        $searchReturn['total_listings'] = $pageObj->record_amount;
        $paging_url = LISTING_DEFAULT_URL . "/results.php";
        /*
         * Will be used on:
         * /frontend/results_info.php
         * /frontend/results_filter.php
         * /frontend/results_maps.php
         * functions/script_funct.php
         */
        $aux_module_per_page = "listing";
        $aux_module_items = $listings;
        $aux_module_itemRSSSection = "listing";
        /*
         * Will be used on
         * /frontend/browsebycategory.php
         */
        $aux_CategoryObj = "ListingCategory";
        $aux_CategoryModuleURL = LISTING_DEFAULT_URL;
        $aux_CategoryNumColumn = 3;
        $aux_CategoryActiveField = 'active_listing';

        $levelsWithReview = system_retrieveLevelsWithInfoEnabled("has_review");

        $array_search_params = array();
    
        if ($_GET["url_full"]) {
            if ($browsebycategory) {
                $paging_url = LISTING_DEFAULT_URL . "/" . ALIAS_CATEGORY_URL_DIVISOR;
                $aux = str_replace(EDIRECTORY_FOLDER . "/" . ALIAS_LISTING_MODULE . "/" . ALIAS_CATEGORY_URL_DIVISOR . "/", "", $_GET["url_full"]);
            } else if ($browsebylocation) {
                $paging_url = LISTING_DEFAULT_URL . "/" . ALIAS_LOCATION_URL_DIVISOR;
                $aux = str_replace(EDIRECTORY_FOLDER . "/" . ALIAS_LISTING_MODULE . "/" . ALIAS_LOCATION_URL_DIVISOR . "/", "", $_GET["url_full"]);
            }else if($friendlyurl){
                if(EDIRECTORY_FOLDER){
                    $paging_url = str_replace(EDIRECTORY_FOLDER,'',DEFAULT_URL);
                }else{
                    $paging_url = DEFAULT_URL;
                }
                $aux = $_GET["url_full"];
            }
            $parts = explode("/", $aux);
            for ($i = 0; $i < count($parts); $i++) {
                if ($parts[$i]) {
                    if ($parts[$i] != "page" && $parts[$i] != "letter" && $parts[$i] != "orderby") {
                        $array_search_params[] = "/" . urlencode($parts[$i]);
                    } else {
                        if ($parts[$i] != "page" && $parts[$i] != "letter") {
                            $array_search_params[] = "/" . $parts[$i] . "/" . $parts[$i + 1];
                            $i++;
                        } else {
                            $i++;
                        }
                    }
                }
            }

            $url_search_params = implode("/", $array_search_params);

            if (string_substr($url_search_params, -1) == "/") {
                $url_search_params = string_substr($url_search_params, 0, -1);
            }
            $url_search_params = str_replace("//", "/", $url_search_params);
        } else {

            $paging_url = LISTING_DEFAULT_URL . "/results.php";

            foreach ($_GET as $name => $value) {
                if ($name != "screen" && $name != "letter" && $name != "url_full") {
                    if ($name == "keyword" || $name == "where")
                        $array_search_params[] = $name . "=" . urlencode($value);
                    else
                        $array_search_params[] = $name . "=" . $value;
                }
            }

            $url_search_params = implode("&amp;", $array_search_params);
        }
        /*
         * Preparing Pagination
         */
        unset($letters_menu);

        if (SELECTED_DOMAIN_ID == 1) {
            $letters_menu = system_prepareLetterToPagination_Search($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, LISTING_SCALABILITY_OPTIMIZATION);
            $array_pages_code = system_preparePaginationForListing($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
        } elseif (SELECTED_DOMAIN_ID == 2) {
            $letters_menu = system_prepareLetterToPagination_Search($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, LISTING_SCALABILITY_OPTIMIZATION);
            $array_pages_code = system_preparePaginationForListing($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
        } elseif (SELECTED_DOMAIN_ID == 4) {
            $letters_menu = system_prepareLetterToPagination_Search($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, LISTING_SCALABILITY_OPTIMIZATION);
            $array_pages_code = system_preparePaginationForListing($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
        } else {
            $letters_menu = system_prepareLetterToPagination($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, LISTING_SCALABILITY_OPTIMIZATION);
            $array_pages_code = system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
        }
        $user = true;
        $showLetter = true;

        setting_get('commenting_edir', $commenting_edir);
        setting_get("review_listing_enabled", $review_enabled);
        $db = db_getDBObject();
        $sql = "SELECT count(*) as nunberOfReviews FROM Review WHERE item_type = 'listing'";
        //$result = $db->query($sql);
        $result = $db->unbuffered_query($sql);
        $result = mysql_fetch_assoc($result);
        $numberOfReviews = $result['nunberOfReviews'];

        if ($review_enabled && $commenting_edir && $numberOfReviews) {
            $orderBy = array(LANG_PAGING_ORDERBYPAGE_CHARACTERS, LANG_PAGING_ORDERBYPAGE_LASTUPDATE, LANG_PAGING_ORDERBYPAGE_DATECREATED, LANG_PAGING_ORDERBYPAGE_POPULAR, LANG_PAGING_ORDERBYPAGE_RATING);
        } else {
            $orderBy = array(LANG_PAGING_ORDERBYPAGE_CHARACTERS, LANG_PAGING_ORDERBYPAGE_LASTUPDATE, LANG_PAGING_ORDERBYPAGE_DATECREATED, LANG_PAGING_ORDERBYPAGE_POPULAR);
        }

        if (LISTING_ORDERBY_PRICE) {
            array_unshift($orderBy, LANG_PAGING_ORDERBYPAGE_PRICE);
        }

        if (SELECTED_DOMAIN_ID > 0)
            $orderbyDropDown = search_getSortingLinks($_GET, $paging_url, $parts,$friendlyurl);
        else
            $orderbyDropDown = search_getOrderbyDropDown($_GET, $paging_url, $orderBy, system_showText(LANG_PAGING_ORDERBYPAGE) . " ", "this.form.submit();", $parts, false, false);
    }

    if (!$listings && !$letter) {
        $showLetter = false;
    }
?>