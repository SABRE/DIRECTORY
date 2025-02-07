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
	# * FILE: /includes/views/icon_promotion.php
	# ----------------------------------------------------------------------------------------------------

	// SOCIAL BOOKMARKING
	if (SOCIAL_BOOKMARKING == "on") {
        if (string_strpos($_SERVER['REQUEST_URI'], ALIAS_PROMOTION_MODULE."/".ALIAS_REVIEW_URL_DIVISOR."/") !== false) {
            $sbmLink = PROMOTION_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR."/".$promotion->getString("friendly_url");
            $sbmLinkShare = PROMOTION_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR."/".ALIAS_SHARE_URL_DIVISOR."/".$promotion->getString("friendly_url").".html";
        } else {
            $sbmLink = PROMOTION_DEFAULT_URL."/".$promotion->getString("friendly_url").".html";
            $sbmLinkShare = PROMOTION_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$promotion->getString("friendly_url").".html";
        }
		 
		if ($user){ 
			$facebook = "href=\"http://www.facebook.com/sharer.php?u=".$sbmLinkShare."&amp;t=".urlencode(htmlspecialchars($promotion->getString("name")))."\" target=\"_blank\"";
			$twitter = "href=\"http://twitter.com/?status=".$sbmLink."\" target=\"_blank\"";
		} else {
			$facebook = "href=\"javascript: void(0);\" style=\"cursor:default\"";
			$twitter = "href=\"javascript: void(0);\" style=\"cursor:default\"";
		}
		
		$facebook_imgE    	= "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-share-facebook.png\" alt=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Facebook\"/>";
        $twitter_imgE     	= "<img src=\"".DEFAULT_URL."/theme/".EDIR_THEME."/images/iconography/icon-share-twitter.png\" alt=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\" title=\"".system_showText(LANG_ADDTO_SOCIALBOOKMARKING)." Twitter\"/>";
	}
	
	if (SOCIAL_BOOKMARKING == "on"){
		$icon_navbar = "<ul class=\"share-social\">";
		$icon_navbar .= "<li class=\"icon\"><a ".$twitter." >".$twitter_imgE."</a></li>";
		$icon_navbar .= "<li class=\"icon\"><a ".$facebook." >".$facebook_imgE."</a></li>";
		
		$twitterL = "<a ".$twitter." >".$twitter_imgE."</a>";
		
		$facebookL = "<a ".$facebook." >".$facebook_imgE."</a>";
		
		$icon_navbar .= "</ul>";
	}
    
    $likeObj = share_getFacebookButton(true, "", "", "", $sbmLinkShare);
	
	if ($listing){
		
		$location_map = false;

		if ($listing->getNumber("latitude") && $listing->getNumber("longitude")) {
			$location_map = $listing->getNumber("latitude").",".$listing->getNumber("longitude");
		}
        
		if ($location_map) $location_map = urlencode("$location_map");
        
        $listingLevelObj = new ListingLevel();
		if ($listingLevelObj->getDetail($listing->getNumber("level")) == "y") {
			if (htmlspecialchars($listing->getNumber("latitude")) && htmlspecialchars($listing->getNumber("longitude"))) {
				if ($user){
					$map_link = "onclick='javascript:window.open(\"http://maps.google.com/maps?q=".$location_map."\",\"popup\",\"\")'";
					$map_style = "";
				} else {
					$map_link = "";
					$map_style = "style=\"cursor:default\"";
				}
			}
		}
	}
?>