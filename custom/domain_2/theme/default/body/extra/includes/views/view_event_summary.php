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
	# * FILE: /includes/views/view_event_summary.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# DEFINES
	# ----------------------------------------------------------------------------------------------------
	$includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
        //Get fields according to level
        unset($array_fields);
        $array_fields = system_getFormFields("Event", $event->getNumber("level"));
	$event_icon_navbar = "";
	include($includeUrl."/includes/views/icon_event.php");
	$event_icon_navbar = $icon_navbar;
	$icon_navbar = "";

	$detailLink = "".EVENT_DEFAULT_URL."/".$event->getString("friendly_url").".html";
	$str_date = "";
	$str_date = $event->getDateString();
        $str_recurring = "";
	if ($event->getString("recurring")=="Y"){
		$str_recurring = $event->getDateStringRecurring();
	}
	
        $str_end = "";
        $str_end = $event->getDateStringEnd();
    
        $str_time = "";
        if (is_array($array_fields) && (in_array("start_time", $array_fields) || in_array("end_time", $array_fields))){
            $str_time = $event->getTimeString();
        }
    
	$friendly_url = $event->getString('friendly_url');
	
	if ((string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false) && GOOGLE_MAPS_ENABLED == "on" && $mapObj->getString("value") == "on") { 
		if ($event->getString("latitude") && $event->getString("longitude")) {
			$show_map = true;
		}else{
			$show_map = false;
		}
	}
	
	if (($user) && ($level->getDetail($event->getNumber("level")) == "y")) { 
		$show_detailLink = true;
	}else{
		$show_detailLink = false;
	}
	
	$distance_label = "";
	if (zipproximity_getDistanceLabel($zip, "event", $event->getNumber("id"), $distance_label)) {
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
		if ((!(($location_3!="" && $location_3==$event->getNumber("location_3"))||($location_4!="" && $location_4==$event->getNumber("location_4")))) &&  zipproximity_getLocationDistanceLabel($lat, $long, "event", $event->getNumber("id"), $distance_label)) {
			$distance_label = " (".$distance_label.")";
		}
	}
	
	unset($title);
	
	if(strlen($event->title)>20){
			$event->title = substr($event->title,0,20).'...';	
	}
	
	if($show_detailLink){
		$title	= "<a href=\"".$detailLink."\">";
		$title .= $event->getString("title");
		$title .= "</a>";
		$title .= $distance_label;
	}else{
		$title = $event->getString("title").$distance_label;
	}
	if ($tPreview) {
		$complementary_info = system_showText(LANG_IN)." "; 
		$complementary_info .= "<a href=\"javascript:void(0);\" style=\"cursor: default;\">".system_showText(LANG_LABEL_ADVERTISE_CATEGORY)."</a>";
		$complementary_info .= " ".LANG_BY." "; 
		if (SOCIALNETWORK_FEATURE == "on") {
			$complementary_info .= "<a href=\"javascript:void(0);\" title=\"".system_showText(LANG_LABEL_ADVERTISE_EVENT_OWNER)."\" style=\"cursor: default;\">".system_showText(LANG_LABEL_ADVERTISE_EVENT_OWNER)."</a>";
		} else {
			$complementary_info .= "<strong>".system_showText(LANG_LABEL_ADVERTISE_EVENT_OWNER)."</strong>";
		}
	} else {
		if(EVENT_SCALABILITY_OPTIMIZATION == "on"){
			$complementary_info = "<a href=\"javascript: void(0);\" ".($user ? "onclick=\"showCategory(".htmlspecialchars($event->getNumber("id")).", 'event', ".($user ? true : false).", ".$event->getNumber("account_id").")\"" : "style=\"cursor: default;\"").">".system_showText(LANG_VIEWCATEGORY)."</a>";
		} else {
			$complementary_info = system_itemRelatedCategories($event->getNumber("id"), "event", $user);
			$complementary_info .= " ".($event->getNumber("account_id") ? LANG_BY." ".socialnetwork_writeLink($event->getNumber("account_id"), "profile", "general_see_profile", false, false, false, "", $user) : "");
		}	
	}
	
	$when = ($event->getString("recurring") != "Y" ? $str_date : $str_recurring);
	
	if ($tPreview){
        $event_location = system_showText(LANG_LABEL_LOCATION_NAME);
		$locationsToShow = explode (",", EDIR_LOCATIONS);
		$locationsToShow = array_reverse ($locationsToShow);
		$locationsParam = "";
		foreach ($locationsToShow as $locationToShow) {
			$locationsParam .= system_showText(constant("LANG_LABEL_".constant("LOCATION".$locationToShow."_SYSTEM"))).", ";
		}
		$location = string_substr("$locationsParam", 0, -2).', '.$event->getString("zip_code");
	} else {
        $event_location = $event->getString("location", true);
		$locationsToshow = system_retrieveLocationsToShow();
		$locationsParam = $locationsToshow." z";
		$location = $event->getLocationString($locationsParam, true);
	}
	
	$address1 = $event->getString("address");
	$address2 = $event->getString("address2");
	$phone = $event->getString("phone");
	
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
            if($event->getNumber("image_id")){
                $imageObj = new Image($event->getNumber("image_id"));
                if ($imageObj->imageExists()) {
                    if ($show_detailLink){
                        $imageTag  = "<a href=\"".$detailLink."\">";
                        $imageTag .= $imageObj->getTag(true, IMAGE_EVENT_THUMB_WIDTH, IMAGE_EVENT_THUMB_HEIGHT, $event->getString("title", false), true);
                        $imageTag .= "</a>";
                    } else {
                        $imageTag .= "<div class=\"no-link\">";
                        $imageTag .= $imageObj->getTag(true, IMAGE_EVENT_THUMB_WIDTH, IMAGE_EVENT_THUMB_HEIGHT, $event->getString("title", false), true);
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
    
    $description = "";
    if(strlen($event->description)>200){
			 $event->description = substr($event->description,0,200).'...';	
	} 
	if (is_array($array_fields) && in_array("summary_description", $array_fields)){
		$description = $event->getString("description", true);
	}
    
    $summaryFileName = $includeUrl."includes/views/view_event_summary_code.php";
    $themeSummaryFileName = INCLUDES_DIR."/views/view_event_summary_code_".EDIR_THEME.".php";

    if (file_exists($themeSummaryFileName)){
        include($themeSummaryFileName);
    } else {
        include($summaryFileName);
    }
	
?>
