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
	# * FILE: /controller/deal/review.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # MODULE REWRITE
    # ----------------------------------------------------------------------------------------------------
    include(EDIR_CONTROLER_FOLDER."/".PROMOTION_FEATURE_FOLDER."/rewrite.php");

    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	setting_get('commenting_edir', $commenting_edir);
	setting_get("review_promotion_enabled", $review_enabled);
	if ($review_enabled != "on" || !$commenting_edir) {
		$error_message = system_showText(LANG_REVIEWDISABLE);
	}

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	$item_type = "promotion";
	$item_id   = $_GET['item_id'];
	if (!$item_id && $_GET['id']) $item_id = $_GET['id'];

	include(INCLUDES_DIR."/code/review.php");
	$promotionObj = $itemObj;

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	// Page Browsing /////////////////////////////////////////
	if ($item_id) 	 $sql_where[] = " item_type = 'promotion' AND item_id = $item_id ";
	if (true)        $sql_where[] = " review IS NOT NULL AND review != '' ";
	if (true)        $sql_where[] = " approved = '1' ";
	if ($sql_where)  $sqlwhere .= " ".implode(" AND ", $sql_where)." ";

	$aux_items_per_page = ($_COOKIE["dealreviews_results_per_page"] ? $_COOKIE["dealreviews_results_per_page"] : 10);

	$pageObj  = new pageBrowsing("Review", $page, $aux_items_per_page, "added DESC", "", "", $sqlwhere);
	$reviewsArr = $pageObj->retrievePage("object");

	$aux_module_per_page			= "dealreviews";
	$aux_module_items				= $reviewsArr; 

	$array_search_params = array();

    $paging_url = PROMOTION_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR;
    $aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_PROMOTION_MODULE."/".ALIAS_REVIEW_URL_DIVISOR."/", "", $_GET["url_full"]);

    $parts = explode("/", $aux);

    for($i=0; $i < count($parts); $i++ ){
        if ($parts[$i]){
            if ($parts[$i] != "page"){
                $array_search_params[] = "/".urlencode($parts[$i]);
            } else {
                if ($parts[$i] != "page"){
                    $array_search_params[] = "/".$parts[$i]."/".$parts[$i+1];
                    $i++;
                } else {
                    $i++;
                }
            }
        }
    }

    $url_search_params = implode("/", $array_search_params);

	$showLetter = false;
	
	$array_pages_code	= system_preparePagination($paging_url, $url_search_params, $pageObj, $letter, $page, $aux_items_per_page);

?>