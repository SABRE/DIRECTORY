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
	# * FILE: /controller/event/detail.php
	# ----------------------------------------------------------------------------------------------------

    ##################################################
	# EVENT
	##################################################
    if (string_strpos($aux_array_url[$searchPos_2], ".html") !== false) {
        $browsebyitem = true;
        $event_url = string_replace_once(".html", "", $aux_array_url[$searchPos_2]);
		$sql = "SELECT Event.id as id FROM Event WHERE Event.friendly_url = ".db_formatString($event_url)." LIMIT 1";
		$result = $dbObj->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["id"] = $aux["id"];
        $_GET["event_id"] = $aux["id"];
        if (!$_GET["id"]) {
            $failure = true;
        }
    }
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# EVENT
	# ----------------------------------------------------------------------------------------------------
	if (($_GET["id"]) || ($_POST["id"])) {
		$id = $_GET["id"] ? $_GET["id"] : $_POST["id"];
		$event = new Event($id);
		$level = new EventLevel(true);
		unset($eventMsg);
		if ((!$event->getNumber("id")) || ($event->getNumber("id") <= 0)) {
			$eventMsg = system_showText(LANG_MSG_NOTFOUND);
		} elseif ($event->getString("status") != "A") {
			$eventMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} elseif ($level->getDetail($event->getNumber("level")) != "y" && $level->getActive($event->getNumber("level")) == 'y') {
			$eventMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} else {
			report_newRecord("event", $id, EVENT_REPORT_DETAIL_VIEW);
			$event->setNumberViews($id);
		}
	} else {
		header("Location: ".EVENT_DEFAULT_URL."/");
		exit;
	}
    
    # ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	if (($event->getNumber("id")) && ($event->getNumber("id") > 0)) {
		$evCategs = $event->getCategories();
		if ($evCategs) {
			foreach ($evCategs as $evCateg) {
				$category_id[] = $evCateg->getNumber("id");
			}
		}
	}
	$_POST["category_id"] = $category_id;
	
	# ----------------------------------------------------------------------------------------------------
	# Previous Page Link And Next Page Link
	# ----------------------------------------------------------------------------------------------------
    if (($event->getNumber("id")) && ($event->getNumber("id") > 0)) {

    	$prevPageLink = search_pagePrevNextLink('Event',$event->getNumber("id"),'prev');
    	$nextPageLink = search_pagePrevNextLink('Event',$event->getNumber("id"),'next');
    	
    }
?>