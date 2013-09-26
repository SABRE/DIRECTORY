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
	# * FILE: /controller/blog/prepare_results.php
	# ----------------------------------------------------------------------------------------------------
 
	# ----------------------------------------------------------------------------------------------------
	# RESULTS
	# ----------------------------------------------------------------------------------------------------
	$search_lock = false;
	if (BLOG_SCALABILITY_OPTIMIZATION == "on" && string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false) {
		if (!$_GET["keyword"] && !$_GET["category_id"] && !$_GET["id"]) {
			$_GET["id"] = 0;
			$search_lock = true;
		}
	}
	
	unset($searchReturn);
	$okY = 1;
	$okM = 1;
	if ($_GET["archive_year"]) {
		if (is_numeric($_GET["archive_year"])) {
			$okY = 1;
		} else {
			$okY = 0;
		}
	}

	if ($_GET["archive_month"]) {
		if (is_numeric($_GET["archive_month"])) {
            $okM = 1;
        } else {
            $okM = 0;
        }
	}

	if ($okY && $okM) {
		
		/*
		 * Aux to pages
		 */
		$page = ($page ? $page : $pn);
		
		if ($pn) {
			$screen = $pn;
		}
		
		$searchReturn = search_frontBlogSearch($_GET, "blog");
		
		if ($aux_results_number_index) {
			$aux_items_per_page = $aux_results_number_index;
		} else {
			if ($_COOKIE["blog_results_per_page"]) {
				$aux_items_per_page = $_COOKIE["blog_results_per_page"];
			} else {
				$aux_items_per_page = 10;
			}
		}

		$aux_items_per_page = ($_COOKIE["blog_results_per_page"] ? $_COOKIE["blog_results_per_page"] : ($aux_results_number_index ? $aux_results_number_index : 10));
		$pageObj = new pageBrowsing($searchReturn["from_tables"], ($page ? $page : $screen), $aux_items_per_page, $searchReturn["order_by"], "Post.title", $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], "Post", $searchReturn["group_by"]);

		$posts = $pageObj->retrievePage();
		
		/*
		 * Will be used on:
		 * /frontend/results_info.php
		 * /frontend/results_filter.php
		 * /frontend/results_maps.php
		 * /frontend/breadcrumb.php
         * functions/script_funct.php
		 */
		$aux_module_per_page			= "blog";
		$aux_module_items				= $posts; 
		$aux_module_itemRSSSection		= "blog";
        
        /*
		 * Will be used on
		 * /frontend/results_browsebycategory.php
		 */
		$aux_CategoryObj				= "BlogCategory";
		$aux_CategoryModuleURL			= BLOG_DEFAULT_URL;
		$aux_CategoryNumColumn			= 3;
		$aux_CategoryActiveField		= 'active_post';
		
		$array_search_params = array();
		$_GET["advsearch"] = false;
		
        if ($_GET["url_full"]) {

            if ($browsebycategory) {
                $paging_url = BLOG_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR;
                if ($_GET["url_full"]) {
                    $aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_BLOG_MODULE."/".ALIAS_CATEGORY_URL_DIVISOR."/", "", $_GET["url_full"]);
                }
            } else if ($browsebydate) {
                $paging_url = BLOG_DEFAULT_URL."/".ALIAS_ARCHIVE_URL_DIVISOR;
                if ($_GET["url_full"]) {
                    $aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_BLOG_MODULE."/".ALIAS_ARCHIVE_URL_DIVISOR."/", "", $_GET["url_full"]);
                }
            } else if($friendlyurl){
                if(EDIRECTORY_FOLDER){
                    $paging_url = str_replace(EDIRECTORY_FOLDER,'',DEFAULT_URL);
                }else{
                    $paging_url = DEFAULT_URL;
                }
                $aux = $_GET["url_full"];
            }else {
                if($blogHome){
                    $paging_url = BLOG_DEFAULT_URL;
                }else{
                    $paging_url = BLOG_DEFAULT_URL."/results.php";
                }
                
            }

            $parts = explode("/", $aux);

            for ($i=0; $i < count($parts); $i++ ) {
                if ($parts[$i]) {
                    if ($parts[$i] != "page" && $parts[$i] != "orderby") {
                        $array_search_params[] = "/".urlencode($parts[$i]);
                    } else {
                        if ($parts[$i] != "page") {
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
            if (string_strpos($_SERVER["REQUEST_URI"], "results.php")) {
                $paging_url = BLOG_DEFAULT_URL."/results.php";
            } else {
                $paging_url = BLOG_DEFAULT_URL;
            }
        }
		
		/*
		 * Preparing Pagination
		 */
        $array_pages_code	= system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, ($page ? $page : $screen), $aux_items_per_page, (((($_GET["url_full"]) || $blogHome) && (string_strpos($_SERVER["REQUEST_URI"], "results.php") === false)) ? false : true));
        $user = true;

		# PAGES DROP DOWN ----------------------------------------------------------------------------------------------
		setting_get("review_post_enabled", $review_post_enabled);

		$db = db_getDBObject();
		$sql = "SELECT count(0) as numberOfReviews FROM Review WHERE item_type = 'post'";
		$result = $db->query($sql);
		$result = mysql_fetch_assoc($result);
		$numberOfReviews = $result['numberOfReviews'];

		if ($review_post_enabled && $numberOfReviews) {
			$orderBy =  array(LANG_PAGING_ORDERBYPAGE_CHARACTERS,LANG_PAGING_ORDERBYPAGE_LASTUPDATE,LANG_PAGING_ORDERBYPAGE_DATECREATED,LANG_PAGING_ORDERBYPAGE_POPULAR,LANG_PAGING_ORDERBYPAGE_RATING);
        } else {
			$orderBy =  array(LANG_PAGING_ORDERBYPAGE_CHARACTERS,LANG_PAGING_ORDERBYPAGE_LASTUPDATE,LANG_PAGING_ORDERBYPAGE_DATECREATED,LANG_PAGING_ORDERBYPAGE_POPULAR);
        }
        $orderbyDropDown = search_getOrderbyDropDown($_GET, $paging_url, $orderBy, system_showText(LANG_PAGING_ORDERBYPAGE)." ", "this.form.submit();", $parts, false, true);

        $showLetter = true;
        if (!$posts && !$letter){
            $showLetter = false;
        }
        
       /**
        * Change path to breadcrumb
        */
        $breadcrumbScriptPath = "/".ALIAS_BLOG_MODULE;
        
	}
?>