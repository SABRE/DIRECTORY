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
	# * FILE: /controller/event/share.php
	# ----------------------------------------------------------------------------------------------------

	extract($_GET);

	$aFrom = explode("/", $from);

	if ($friendly_url) {

        $eventObj = db_getFromDB("event", "friendly_url", db_formatString($friendly_url));
        $id = $eventObj->getNumber("id");
        $friendly_url = $eventObj->getString("friendly_url");
        $levelObj = new EventLevel();
        $levelDetail = $levelObj->getDetail($eventObj->getNumber("level"));

		if (($aFrom[0] == ALIAS_SHARE_URL_DIVISOR) && $levelDetail == "y") {
			$sbmLink = EVENT_DEFAULT_URL."/".$friendly_url.".html";
		} else {
			$sbmLink = EVENT_DEFAULT_URL."/results.php?id=".$id;
		}

		if (string_strpos($_SERVER["HTTP_USER_AGENT"], "http://www.facebook.com/externalhit_uatext.php") === false) {
			header("Location: ".$sbmLink);
			exit;
		}
	
		$description = $eventObj->getString("description");
		$title = $eventObj->getString("title");
        $level = $eventObj->getNumber("level");
		$dbObj = db_getDBObject();

		$sqlGI = "SELECT `gallery_id` FROM `Gallery_Item` WHERE `item_id` = ".$eventObj->getNumber("id")." AND `item_type` = 'event' LIMIT 1";
        $resGI = $dbObj->Query($sqlGI);

		$rowGI = mysql_fetch_assoc($resGI);

		$galObj = new Gallery();

		$images = $galObj->getAllImages($rowGI["gallery_id"]);
        
        $levelObj = new EventLevel();
        //Get fields according to level
        unset($array_fields);
        $array_fields = system_getFormFields("Event", $level);
        $levelMaxImages = $levelObj->getImages($level);
        $hasImage = false;
        if ((is_array($array_fields) && in_array("main_image", $array_fields)) || $levelMaxImages > 0){
           $hasImage = true;
        }
        
        $fromURL = EVENT_DEFAULT_URL."/".str_replace(EDIRECTORY_FOLDER, "", $from);
        
        front_shareContent($title, $description, $hasImage, $images, $fromURL);
        
    } else {
		header("Location: ".EVENT_DEFAULT_URL);
		exit;
	}
?>