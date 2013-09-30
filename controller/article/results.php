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
	# * FILE: /controller/article/results.php
	# ----------------------------------------------------------------------------------------------------
    
        # ----------------------------------------------------------------------------------------------------
        # MODULE REWRITE
        # ----------------------------------------------------------------------------------------------------
        include(EDIR_CONTROLER_FOLDER."/".ARTICLE_FEATURE_FOLDER."/rewrite.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	if ($category_id) {
		$catObj = new ArticleCategory($category_id);
		if (!$catObj->getString("title")) {
			header("Location: ".ARTICLE_DEFAULT_URL."/");
			exit;
		}
	}

	# ----------------------------------------------------------------------------------------------------
	# RESULTS
	# ----------------------------------------------------------------------------------------------------
	$search_lock = false;
	if (ARTICLE_SCALABILITY_OPTIMIZATION == "on") {
		if (!$_GET["keyword"] && !$_GET["category_id"] && !$_GET["id"]) {
			$_GET["id"] = 0;
			$search_lock = true;
		}
	}

	unset($searchReturn);
	$searchReturn = search_frontArticleSearch($_GET, "article");
	$aux_items_per_page = ($_COOKIE["article_results_per_page"] ? $_COOKIE["article_results_per_page"] : 10);
	$pageObj = new pageBrowsing($searchReturn["from_tables"], (string_strpos($_GET["url_full"],'results.php') ? $screen : $page), $aux_items_per_page, $searchReturn["order_by"], "Article.title", $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Article", $searchReturn["group_by"]);
	if (!$search_lock) {
		$articles = $pageObj->retrievePage();
	} else {
		$articles = false;
	}
	$searchReturn['total_listings'] = $pageObj->record_amount; 
	/*
	 * Will be used on:
	 * /frontend/results_info.php
	 * /frontend/results_filter.php
	 * /frontend/results_maps.php
     * functions/script_funct.php
	 */
	$aux_module_per_page			= "article";
	$aux_module_items				= $articles; 
	$aux_module_itemRSSSection		= "article";
	
	/*
	 * Will be used on
	 * /frontend/browsebycategory.php
	 */
	$aux_CategoryObj				= "ArticleCategory";
	$aux_CategoryModuleURL			= ARTICLE_DEFAULT_URL;
	$aux_CategoryNumColumn			= 3;
	$aux_CategoryActiveField		= 'active_article';
	
	$array_search_params = array();

	if ($_GET["url_full"]  && string_strpos($_GET["url_full"],'results.php') == false ) {
		if ($browsebycategory) {
			$paging_url = ARTICLE_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR;
			$aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_ARTICLE_MODULE."/".ALIAS_CATEGORY_URL_DIVISOR."/", "", $_GET["url_full"]);
		}else if($friendlyurl){
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
		$paging_url = ARTICLE_DEFAULT_URL."/results.php";

		foreach ($_GET as $name => $value) {
			if ($name != "screen" && $name != "letter" && $name != "url_full") {
				if ( $name == "keyword" ) $array_search_params[] = $name."=".urlencode($value);
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
        	$letters_menu		= system_prepareLetterToPagination_Search($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, ARTICLE_SCALABILITY_OPTIMIZATION);
        	$array_pages_code	= system_preparePaginationForListing($paging_url, $url_search_params, $pageObj, $letter, (string_strpos($_GET["url_full"],'results.php') ? $screen : $page), $aux_items_per_page, (string_strpos($_GET["url_full"],'results.php') ? true : false));
     }else{
        	$letters_menu		= system_prepareLetterToPagination($pageObj, $searchReturn, $paging_url, $url_search_params, $letter, "title", false, false, false, ARTICLE_SCALABILITY_OPTIMIZATION);
        	$array_pages_code	= system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, ($_GET["url_full"] ? $page  : $screen), $aux_items_per_page, ($_GET["url_full"] ? false : true));
    }
	
	
	$user = true;

	setting_get('commenting_edir', $commenting_edir);
	setting_get("review_article_enabled", $review_article_enabled);
	$db = db_getDBObject();			
	$sql = "SELECT count(*) as nunberOfReviews FROM Review WHERE item_type = 'article'";
	$result = $db->query($sql);
	$result = mysql_fetch_assoc($result);
	$numberOfReviews = $result['nunberOfReviews'];	
	
	if ($review_article_enabled && $commenting_edir && $numberOfReviews) {
		$orderBy =  array(LANG_PAGING_ORDERBYPAGE_CHARACTERS,LANG_PAGING_ORDERBYPAGE_LASTUPDATE,LANG_PAGING_ORDERBYPAGE_DATECREATED,LANG_PAGING_ORDERBYPAGE_POPULAR,LANG_PAGING_ORDERBYPAGE_RATING);	
	} else {
		$orderBy =  array(LANG_PAGING_ORDERBYPAGE_CHARACTERS,LANG_PAGING_ORDERBYPAGE_LASTUPDATE,LANG_PAGING_ORDERBYPAGE_DATECREATED,LANG_PAGING_ORDERBYPAGE_POPULAR);	
	}
	 if(SELECTED_DOMAIN_ID > 0)
	   	$orderbyDropDown = search_getSortingLinks($_GET, $paging_url,$parts,$friendlyurl,$url_search_params);
	 else 
	 	$orderbyDropDown = search_getOrderbyDropDown($_GET, $paging_url, $orderBy, system_showText(LANG_PAGING_ORDERBYPAGE) . " ", "this.form.submit();", $parts, false, false);
	
	# --------------------------------------------------------------------------------------------------------------

	$showLetter = true;
	if (!$articles && !$letter) $showLetter = false;

?>