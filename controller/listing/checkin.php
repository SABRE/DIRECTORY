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
	# * FILE: /controller/listing/checkin.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # MODULE REWRITE
    # ----------------------------------------------------------------------------------------------------
    include(EDIR_CONTROLER_FOLDER."/".LISTING_FEATURE_FOLDER."/rewrite.php");
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
    # ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
    $item_type = "listing";
    $item_id   = $_GET['item_id'];
    if (!$item_id && $_GET['id']) $item_id = $_GET['id'];

    include(INCLUDES_DIR."/code/checkin.php");
    $listingObj = $itemObj;

    if ( $listingObj->getString('status') != 'A' ) exit;

    # ----------------------------------------------------------------------------------------------------
    # AUX
    # ----------------------------------------------------------------------------------------------------  
    // Page Browsing /////////////////////////////////////////
    if ($item_id) $sql_where[] = " item_id = $item_id ";
    if (true)        $sql_where[] = " quick_tip IS NOT NULL AND quick_tip != '' ";
    if ($sql_where)  $sqlwhere .= " ".implode(" AND ", $sql_where)." ";

    $aux_items_per_page = ($_COOKIE["checkin_results_per_page"] ? $_COOKIE["checkin_results_per_page"] : 10);

    $pageObj  = new pageBrowsing("CheckIn", $page, $aux_items_per_page, "added DESC", "", "", $sqlwhere);
    $checkinsArr = $pageObj->retrievePage("object");

    $aux_module_per_page			= "checkin";
    $aux_module_items				= $checkinsArr; 

    $array_search_params = array();

    $paging_url = LISTING_DEFAULT_URL."/".ALIAS_CHECKIN_URL_DIVISOR;
    $aux = str_replace(EDIRECTORY_FOLDER."/".ALIAS_LISTING_MODULE."/".ALIAS_CHECKIN_URL_DIVISOR."/", "", $_GET["url_full"]);

    $parts = explode("/", $aux);

    for ($i=0; $i < count($parts); $i++ ) {
        if ($parts[$i]) {
            if ($parts[$i] != "page") {
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