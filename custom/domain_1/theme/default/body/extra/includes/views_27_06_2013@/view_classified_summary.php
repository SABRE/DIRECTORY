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
	# * FILE: /includes/views/view_classified_summary.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# DEFINES
	# ----------------------------------------------------------------------------------------------------
    //
    //Get fields according to level
    
	$includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
	
    unset($array_fields);
    $array_fields = system_getFormFields("Classified", $classified->getNumber("level"));

	$classified_icon_navbar = "";
	include($includeUrl."/includes/views/icon_classified.php");
	
	$classified_icon_navbar = $icon_navbar;
	$icon_navbar = "";
    $detailLink = "".CLASSIFIED_DEFAULT_URL."/".$classified->getString("friendly_url").".html";
	$friendly_url = $classified->getString('friendly_url');
	
	if ((string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false) && GOOGLE_MAPS_ENABLED == "on" && $mapObj->getString("value") == "on") { 
		if ($classified->getString("latitude") && $classified->getString("longitude")) {
			$show_map = true;
		}else{
			$show_map = false;
		}
	}
	
	if (($user) && ($level->getDetail($classified->getNumber("level")) == "y")) { 
		$show_detailLink = true;
	}else{
		$show_detailLink = false;
	}
	
	unset($distance_label);
	if (zipproximity_getDistanceLabel($zip, "classified", $classified->getNumber("id"), $distance_label)) {
		$distance_label = " (".$distance_label.")";
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
		if ((!(($location_3!="" && $location_3==$classified->getNumber("location_3"))||($location_4!="" && $location_4==$classified->getNumber("location_4")))) &&  zipproximity_getLocationDistanceLabel($lat, $long, "classified", $classified->getNumber("id"), $distance_label)) {
			$distance_label = " (".$distance_label.")";
		}
	}
	
	unset($title);
	if($show_detailLink){
		$title = "<a href=\"".$detailLink."\">";
		$title .= $classified->getString("title").$distance_label;
		$title .= "</a>";
	}else{
		$title = $classified->getString("title").$distance_label;
	}
	
	if ($tPreview) {
		$complementary_info = system_showText(LANG_IN)." "; 
		$complementary_info .= "<a href=\"javascript:void(0);\" style=\"cursor: default;\">".system_showText(LANG_LABEL_ADVERTISE_CATEGORY)."</a>";
		$complementary_info .= " ".LANG_BY." "; 
		if (SOCIALNETWORK_FEATURE == "on") {
			$complementary_info .= "<a href=\"javascript:void(0);\" title=\"".system_showText(LANG_LABEL_ADVERTISE_CLASSIFIED_OWNER)."\" style=\"cursor: default;\">".system_showText(LANG_LABEL_ADVERTISE_CLASSIFIED_OWNER)."</a>";
		} else {
			$complementary_info .= "<strong>".system_showText(LANG_LABEL_ADVERTISE_CLASSIFIED_OWNER)."</strong>";
		}
	} else {
		if(CLASSIFIED_SCALABILITY_OPTIMIZATION == "on"){
			$complementary_info = "<a href=\"javascript: void(0);\" ".($user ? "onclick=\"showCategory(".htmlspecialchars($classified->getNumber("id")).", 'classified', ".($user ? true : false).", ".$classified->getNumber("account_id").")\"" : "style=\"cursor: default;\"").">".system_showText(LANG_VIEWCATEGORY)."</a>";
		} else {
			$complementary_info = system_itemRelatedCategories($classified->getNumber("id"), "classified", $user);
			$complementary_info .= " ".($classified->getNumber("account_id") ? LANG_BY." ".socialnetwork_writeLink($classified->getNumber("account_id"), "profile", "general_see_profile", false, false, false, "", $user) : "");
		}	
	}
    
    if ($tPreview){
		$locationsToShow = explode (",", EDIR_LOCATIONS);
		$locationsToShow = array_reverse ($locationsToShow);
		$locationsParam = "";
		foreach ($locationsToShow as $locationToShow) {
			$locationsParam .= system_showText(constant("LANG_LABEL_".constant("LOCATION".$locationToShow."_SYSTEM"))).", ";
		}
		$location = string_substr("$locationsParam", 0, -2).', '.$classified->getString("zip_code");
	} else {
		$locationsToshow = system_retrieveLocationsToShow();
		$locationsParam = $locationsToshow." z";
		$location = $classified->getLocationString($locationsParam, true);
	}
	
	$address1 = $classified->getString("address");
	$address2 = $classified->getString("address2");
	
	if($location){
		$location = "<span>".$location."</span>";
	}
	if($address1){
		$address1 = "<span>".$address1."</span>";
	}
	if($address2){
		$address2 = "<span>".$address2."</span>";
	}    
	
	unset($imageTag);
    if (is_array($array_fields) && in_array("main_image", $array_fields)){
        if ($tPreview) {
            $imageTag = "<span class=\"no-image\" style=\"cursor: default;\"></span>";
        } else {
            if($classified->getNumber("image_id")){
                $imageObj = new Image($classified->getNumber("image_id"));
                if ($imageObj->imageExists()) {
                    if ($show_detailLink){
                        $imageTag  = "<a href=\"".$detailLink."\">";
                        $imageTag .= $imageObj->getTag(true, IMAGE_CLASSIFIED_THUMB_WIDTH, IMAGE_CLASSIFIED_THUMB_HEIGHT, $classified->getString("title", false), true);
                        $imageTag .= "</a>";
                    } else {
                        $imageTag .= "<div class=\"no-link\">";
                        $imageTag .= $imageObj->getTag(true, IMAGE_CLASSIFIED_THUMB_WIDTH, IMAGE_CLASSIFIED_THUMB_HEIGHT, $classified->getString("title", false), true);
                        $imageTag .= "</div>";
                    }
                }else{
                    if ($show_detailLink){
                        $imageTag =  "<a href=\"".$detailLink."\" class=\"image\">";
                        $imageTag .=  "<span class=\"no-image\"></span>";
                        $imageTag .=  "</a>";
                    } else {
                        $imageTag = "<span class=\"no-image no-link\"></span>";
                    }
                }
            } else {
                if ($show_detailLink){
                    $imageTag =  "<a href=\"".$detailLink."\" class=\"image\">";
                    $imageTag .=  "<span class=\"no-image\"></span>";
                    $imageTag .=  "</a>";
                } else {
                    $imageTag = "<span class=\"no-image no-link\"></span>";
                }
            }
        }
    }
		
	unset($summaryDescription);
	$summaryDescription = nl2br($classified->getString("summarydesc", true));
	
	unset($phone);
	$phone = $classified->getString("phone");
	
	$contact_email_style = "";
	if ($classified->getString("email")) {
		if ($user){ 
			$contact_email = DEFAULT_URL."/popup/popup.php?pop_type=classified_emailform&amp;id=".$classified->getNumber("id")."&amp;receiver=owner";
		} else { 
			$contact_email = "javascript:void(0);"; 
			$contact_email_style = "cursor:default";  
		}
	}
	
	unset($display_url);
	if ($classified->getString("url", true, 30) && (is_array($array_fields) && in_array("url", $array_fields))){
		$display_urlStr = $classified->getString("url", true, 30);
		if ($user){
			$display_url = $classified->getString("url", true, 30);
			$target = "target=\"_blank\"";
			$style = "";
		} else {
			$display_url = "javascript:void(0);";
			$target = "";
			$style = "style=\"cursor:default\"";
		}
	}
	
	unset($price);
	if ($classified->getString("classified_price") != "NULL"){
		$price = CURRENCY_SYMBOL." ".($classified->getString("classified_price"));
	}
	
	unset($description);
	$description = $classified->getString("description", true);
    
    $summaryFileName = $includeUrl."includes/views/view_classified_summary_code.php";
    $themeSummaryFileName = INCLUDES_DIR."/views/view_classified_summary_code_".EDIR_THEME.".php";

    if (file_exists($themeSummaryFileName)){
        include($themeSummaryFileName);
    } else {
        include($summaryFileName);
    }
?>