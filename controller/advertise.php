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
	# * FILE: /controller/advertise.php
	# ----------------------------------------------------------------------------------------------------

	setting_get('commenting_edir', $commenting_edir);
	setting_get("review_listing_enabled", $review_enabled);
	customtext_get("payment_tax_label", $payment_tax_label);
	setting_get("payment_tax_status", $payment_tax_status);
	setting_get("payment_tax_value", $payment_tax_value);

	$levelsWithReview = system_retrieveLevelsWithInfoEnabled("has_review");
	
	$locationsToShow = explode (",", EDIR_LOCATIONS);
	$locationsToShow = array_reverse ($locationsToShow);
	foreach ($locationsToShow as $locationToShow) {
		$reviewer_location .= system_showText(constant("LANG_LABEL_".constant("LOCATION".$locationToShow."_SYSTEM"))).", ";
	}
	$reviewer_location = string_substr("$reviewer_location", 0, -2);
	unset($locationsToShow);
	
	$arrReviewAux["review_title"] = system_showText(LANG_LABEL_ADVERTISE_REVIEW_TITLE);
	$arrReviewAux["reviewer_name"] = system_showText(LANG_LABEL_ADVERTISE_VISITOR);
    $arrReviewAux["reviewer_location"] = $reviewer_location;
    $arrReviewAux["added"] = date("Y-m-d")." ".date("H:m:s");
    $arrReviewAux["approved"] = "1";
	$arrReviewAux["review"] = "Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica formas.";
	
    $arrReviewAux["rating"] = "1";
    $arrReviewAux["response"] = "Lorem ipsum dolor sit amet, consectetur. Pellentesque luctus enim ac diam tortor.";
    $arrReviewAux["responseapproved"] = "1";
	$reviewsArr[] = new Review($arrReviewAux);
	
	$arrReviewAux["rating"] = "3";
    $arrReviewAux["response"] = "";
    $arrReviewAux["responseapproved"] = "0";
	$reviewsArr[] = new Review($arrReviewAux);
	
	$arrReviewAux["rating"] = "5";
	$reviewsArr[] = new Review($arrReviewAux);
	unset($arrReviewAux);
	
	unset($activeTab);
	if (isset($_GET["event"]) && EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") $activeTab = "event";
	elseif (isset($_GET["banner"]) && BANNER_FEATURE == "on" && CUSTOM_BANNER_FEATURE == "on") $activeTab = "banner";
	elseif (isset($_GET["classified"]) && CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") $activeTab = "classified";
	elseif (isset($_GET["article"]) && ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") $activeTab = "article";
	elseif (isset($_GET["listing"])) $activeTab = "listing";
	else  $activeTab = "listing";
?>