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
	# * FILE: /controller/deal/detail.php
	# ----------------------------------------------------------------------------------------------------

    ##################################################
	# PROMOTION
	##################################################
	if (string_strpos($aux_array_url[$searchPos_2], ".html") !== false) {
        $deal_url = string_replace_once(".html", "", $aux_array_url[$searchPos_2]);
		$browsebyitem = true;
		/*
		 * Force Connection with main DB
		 */
		$db = db_getDBObject();
		$sql = "SELECT Promotion.id as id FROM Promotion WHERE Promotion.friendly_url = ".db_formatString($deal_url)." LIMIT 1";
		$result = $db->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["id"] = $aux["id"];
		$_GET["item_id"] = $aux["id"];
		if (!$_GET["id"]) $failure = true;
	}

    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
    
    # ----------------------------------------------------------------------------------------------------
	# PROMOTION
	# ----------------------------------------------------------------------------------------------------
    if (($_GET["id"]) || ($_POST["id"])) {
        $id = $_GET["id"] ? $_GET["id"] : $_POST["id"];
        $promotion = new Promotion($id);
        $listingObj = new Listing();
        $listings = $listingObj->retrieveListingsbyPromotion_id($id);
		unset($promotionMsg);
        if ((!$promotion->getNumber("id")) || ($promotion->getNumber("id") <= 0)) {
			$promotionMsg = system_showText(LANG_MSG_NOTFOUND);
		} elseif ((!validate_date_deal($promotion->getDate("start_date"), $promotion->getDate("end_date"))) || (!validate_period_deal($promotion->getNumber("visibility_start"),$promotion->getNumber("visibility_end")))) {
			$promotionMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} elseif ($listings[0]->getString("status") != "A") {
			$promotionMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} else {
			report_newRecord("promotion", $id, PROMOTION_REPORT_DETAIL_VIEW);
			$promotion->setNumberViews($id);
		}
    } else {
		header("Location: ".PROMOTION_DEFAULT_URL."/");
		exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# REVIEWS
	# ----------------------------------------------------------------------------------------------------
	if ($id)  $sql_where[] = " item_type = 'promotion' AND item_id = ".db_formatNumber($id)." ";
	if (true) $sql_where[] = " review IS NOT NULL AND review != '' ";
	if (true) $sql_where[] = " approved = '1' ";
	if ($sql_where) $sqlwhere .= " ".implode(" AND ", $sql_where)." ";
	$pageObj  = new pageBrowsing("Review", $screen, 3, "added DESC", "", "", $sqlwhere);
	$reviewsArr = $pageObj->retrievePage("object");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	if (($listings[0]->getNumber("id")) && ($listings[0]->getNumber("id") > 0)) {
		$listCategs = $listings[0]->getCategories(false, false, false, true, true);
		if ($listCategs) {
			foreach ($listCategs as $listCateg) {
				$category_id[] = $listCateg->getNumber("id");
			}
		}
	}
    $_POST["category_id"] = $category_id;
    
    # ----------------------------------------------------------------------------------------------------
    # Previous Page Link And Next Page Link
    # ----------------------------------------------------------------------------------------------------
    if (($listings[0]->getNumber("id")) && ($listings[0]->getNumber("id") > 0)) {

    	$prevPageLink = search_pagePrevNextLink('Promotion',$listings[0]->getNumber("id"),'prev');
    	$nextPageLink = search_pagePrevNextLink('Promotion',$listings[0]->getNumber("id"),'next');
    	
    }
?>