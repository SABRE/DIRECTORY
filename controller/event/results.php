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
	# * FILE: /controller/event/results.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # MODULE REWRITE
    # ----------------------------------------------------------------------------------------------------
     
    include(EDIR_CONTROLER_FOLDER."/".EVENT_FEATURE_FOLDER."/rewrite.php");
    
    # ----------------------------------------------------------------------------------------------------
    # VALIDATION
    # ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
    include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
    
    if ($category_id) {
        $catObj = new EventCategory($category_id);
        if (!$catObj->getString("title")) {
            header("Location: ".EVENT_DEFAULT_URL."/index.php");
            exit;
        }
    }
    
    # ----------------------------------------------------------------------------------------------------
	# RESULTS
	# ----------------------------------------------------------------------------------------------------
	$search_lock = false;
	if (EVENT_SCALABILITY_OPTIMIZATION == "on") {
		if (!$_GET["keyword"] && !$_GET["where"] && !$_GET["category_id"] && !$mod_rewrite_have_location && !$_GET["zip"] && !$_GET["id"] && !$_GET["this_date"] && !$_GET["month"]) {
			$_GET["id"] = 0;
			$search_lock = true;
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
	$searchReturn = search_frontEventSearch($_GET, "event");
    
	$aux_items_per_page = ($_COOKIE["event_results_per_page"] ? $_COOKIE["event_results_per_page"] : 10);
	$pageObj = new pageBrowsing($searchReturn["from_tables"], ($_GET["url_full"] ? $page : $screen), $aux_items_per_page, $searchReturn["order_by"], "Event.title", $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Event", $searchReturn["group_by"]);
        
        
      
        
        $events = $pageObj->retrievePage();
	
        $searchReturn['total_listings'] = $pageObj->record_amount; 
	
	if (!$search_lock) {
		$events = $pageObj->retrievePage();
	} else {
		$events = false;
	}
//        echo '<pre>';
//        print_r($events);die;
	/*
	 * Will be used on:
	 * /frontend/results_info.php
	 * /frontend/results_filter.php
	 * /frontend/results_maps.php
     * functions/script_funct.php
	 */
	$aux_module_per_page			= "event";
	$aux_module_items			= $events; 
	$aux_module_itemRSSSection		= "event";
	
	/*
	 * Will be used on
	 * /frontend/browsebycategory.php
	 */
	$aux_CategoryObj				= "EventCategory";
	$aux_CategoryModuleURL			= EVENT_DEFAULT_URL;
	$aux_CategoryNumColumn			= 3;
	$aux_CategoryActiveField		= 'active_event';
	
	$array_search_params = array();

	if ($_GET["url_full"]) {
		if ($browsebycategory) {
			$paging_url = EVENT_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR;
			$aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_EVENT_MODULE."/".ALIAS_CATEGORY_URL_DIVISOR."/", "", $_GET["url_full"]);
		} else if ($browsebylocation) {
			$paging_url = EVENT_DEFAULT_URL."/".ALIAS_LOCATION_URL_DIVISOR;
			$aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_EVENT_MODULE."/".ALIAS_LOCATION_URL_DIVISOR."/", "", $_GET["url_full"]);
		}else if($friendlyurl){
                     /*This else case is write on the 20-09-2013 for friendly url*/
                    
                    if(EDIRECTORY_FOLDER){
                        $paging_url = str_replace(EDIRECTORY_FOLDER,'',DEFAULT_URL);
                    }else{
                        $paging_url = DEFAULT_URL;
                    }
                    $aux = $_GET["url_full"];
                }
                $parts = explode("/", $aux);

		for ($i=0; $i < count($parts); $i++ ) {
			if ($parts[$i]) {
				if ($parts[$i] != "page" && $parts[$i] != "letter" && $parts[$i] != "orderby") {
					$array_search_params[] = "/".urlencode($parts[$i]);
				} else {
					if ($parts[$i] != "page" && $parts[$i] != "letter") {
						$array_search_params[] = "/".$parts[$i]."/".$parts[$i+1];
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
		$paging_url = EVENT_DEFAULT_URL."/results.php";

		foreach ($_GET as $name => $value) {
			if ($name != "screen" && $name != "letter" && $name != "url_full") {
				if ( $name == "keyword" || $name == "where" ) $array_search_params[] = $name."=".urlencode($value);
				else $array_search_params[] = $name."=".$value;
			}
		}
		
		$url_search_params = implode("&amp;", $array_search_params);
	}

	/*
	 * Preparing Pagination
	 */
	 unset($letters_menu);
	 if(SELECTED_DOMAIN_ID > 0){
        	$letters_menu		= system_prepareLetterToPagination_Search($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, EVENT_SCALABILITY_OPTIMIZATION);
        	$array_pages_code	= system_preparePaginationForListing($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page  : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
        }else{
        	$letters_menu = system_prepareLetterToPagination($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, EVENT_SCALABILITY_OPTIMIZATION);
        	$array_pages_code	= system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page  : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
        }
	
	
	
	$user = true;
	
	# ORDER BY DROP DOWN ----------------------------------------------------------------------------------------------
	$orderBy = array(LANG_PAGING_ORDERBYPAGE_STARTDATE,
					 LANG_PAGING_ORDERBYPAGE_CHARACTERS,
					 LANG_PAGING_ORDERBYPAGE_LASTUPDATE,
					 LANG_PAGING_ORDERBYPAGE_DATECREATED,
					 LANG_PAGING_ORDERBYPAGE_POPULAR);
	
	
	# --------------------------------------------------------------------------------------------------------------
	if(SELECTED_DOMAIN_ID > 0)
		$orderbyDropDown = search_getSortingLinks($_GET, $paging_url, $parts,$friendlyurl);
	else
		$orderbyDropDown = search_getOrderbyDropDown($_GET, $paging_url, $orderBy, system_showText(LANG_PAGING_ORDERBYPAGE)." ", "this.form.submit();", $parts);
		
    $showLetter = true;
    if (!$events && !$letter) $showLetter = false;
?>