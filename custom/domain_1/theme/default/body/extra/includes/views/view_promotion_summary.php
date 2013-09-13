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
	# * FILE: /includes/views/view_promotion_summary.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# DEFINES
	# ----------------------------------------------------------------------------------------------------
	$includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
	
	$deal_icon_navbar = "";
	include($includeUrl."/includes/views/icon_promotion.php");
	$deal_icon_navbar = $icon_navbar;
	
	$friendly_url = $promotion->getString("friendly_url");
	
	if ((string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false) && GOOGLE_MAPS_ENABLED == "on" && $mapObj->getString("value") == "on") { 
        if ($listings[0]){
            if ($listings[0]->getString("latitude") && $listings[0]->getString("longitude")) {
                $show_map = true;
            } else {
                $show_map = false;
            }
        }
	}
    $promotionDistance = "";
    if ($listings[0]){
        if (zipproximity_getDistanceLabel($zip, "listing", htmlspecialchars($listings[0]->getNumber("id")), $distance_label, true, $listings[0]->data_in_array)) {
            $promotionDistance .= " (".$distance_label.")";
        }
        
	    if(((isset($location_3)&& $location_3!="")||(isset($location_4) && $location_4!="")) && (isset($dist_loc) &&$dist_loc!=""))
		{ 
			if((isset($location_3)&& $location_3!=""))
			{
				unset($locLevel);
				unset($locObj);
				$locObj = new Location3($location_3);
				$lat = $locObj->getNumber('latitude'); 
				$long = $locObj->getNumber('longitude'); 
			}
			if((isset($location_4)&& $location_4!=""))
			{
				
				unset($locLevel);
				unset($locObj);
				$locObj = new Location4($location_4);
				$lat = $locObj->getNumber('latitude'); 
				$long = $locObj->getNumber('longitude'); 
			}
			if ((!(($location_3!="" && $location_3==$listings[0]->getNumber("location_3"))||($location_4!="" && $location_4==$listings[0]->getNumber("location_4")))) && zipproximity_getLocationDistanceLabel($lat, $long,  "listing", htmlspecialchars($listings[0]->getNumber("id")), $distance_label, true, $listings[0]->data_in_array)) {
				$promotionDistance .= " (".$distance_label.")";
			}
		}
    }

	$deal_price = string_substr($promotion->dealvalue,0,(string_strpos($promotion->dealvalue,".")));
	$deal_cents = string_substr($promotion->dealvalue,(string_strpos($promotion->dealvalue,".")),3);
	if ($deal_cents == ".00") $deal_cents = "";

	if ($promotion->realvalue>0)
		$offer = round(100-(($promotion->dealvalue*100)/$promotion->realvalue)).'%';
	else $offer = system_showText(LANG_NA);
	
	$promotionDeals = $promotion->getDealInfo();

	$sold_out = "";
	if ($promotionDeals['doneByAmount']||$promotionDeals['doneByendDate'])
		$sold_out = system_showTruncatedText(system_showText(DEAL_SOLDOUT),10);
	
	
	$contactObj = new Contact($promotion->account_id);
	
	$listing = db_getFromDB("listing", "promotion_id", db_formatNumber($promotion->id), 1, "", "object", SELECTED_DOMAIN_ID);
	
	$listingTitle = "";
	
	if ($listing->getString("title")){
		$listingTitle = $listing->getString("title");
	}
	
	$listing_link = "";
        $level = new ListingLevel();
	
	if ($user) {
        if ($level->getDetail($listing->getNumber("level")) == "y") {
            $listing_link = DEFAULT_URL."/".ALIAS_LISTING_MODULE."/".$listing->getString("friendly_url").".html";
        } else {
            $listing_link = "".LISTING_DEFAULT_URL."/results.php?id=".$listing->getNumber("id");
        }
	} else {
		$listing_link = "javascript: void(0);";
	}
	
	$imageObj = new Image($promotion->getNumber("image_id"));
	
	$promotionLink = !$user ? "javascript:void(0);" : (PROMOTION_DEFAULT_URL."/".$promotion->getString('friendly_url').".html");
	$promotionStyle = !$user ? "style=\"cursor:default\"": "";
	
	if ($imageObj->imageExists()){
		
		if ($user){
			$imageTag =  "<a href=\"".$promotionLink."\" class=\"image\">";
			$imageTag .= $imageObj->getTag(true, IMAGE_FRONT_PROMOTION_WIDTH, IMAGE_FRONT_PROMOTION_HEIGHT, $promotion->getString("name", false), true);
			$imageTag .= "</a>";
		} else {
			$imageTag .= "<div class=\"no-link\">";
			$imageTag .= $imageObj->getTag(true, IMAGE_FRONT_PROMOTION_WIDTH, IMAGE_FRONT_PROMOTION_HEIGHT, $promotion->getString("name", false), true);
			$imageTag .= "</div>";
		}
	} else {
		$imageTag = "<a href=\"".$promotionLink."\" class=\"image\">";
		$imageTag .= "<span class=\"no-image\"".(!$user ? "style=\"cursor:default\"" : "")."></span>";
		$imageTag .= "</a>";
	}
	
	if(strlen($promotion->description)>200){
			 $promotion->description = substr($promotion->description,0,200).'...';	
	} 
	$promotion_desc = nl2br($promotion->getString("description"));
	
	$promotion_review = "";
    if ($review_enabled == "on" && $commenting_edir) {
        $item_type = 'promotion';
        $item_id   = htmlspecialchars($promotion->id);
        $itemObj   = $promotion;
        include(INCLUDES_DIR."includes/views/view_review.php");
        $promotion_review .= $item_review;
        $item_review = "";
    }
    
    $summaryFileName = $includeUrl."includes/views/view_promotion_summary_code.php";
    $themeSummaryFileName = INCLUDES_DIR."/views/view_promotion_summary_code_".EDIR_THEME.".php";

    if (file_exists($themeSummaryFileName)){
        include($themeSummaryFileName);
    } else {
        include($summaryFileName);
    }


?>
