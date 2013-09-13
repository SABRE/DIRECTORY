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
	# * FILE: /controller/listing/share.php
	# ----------------------------------------------------------------------------------------------------
    extract($_GET);

	$aFrom = explode("/", $from);

	if ($friendly_url) {

        $listingObj = db_getFromDB("listing", "friendly_url", db_formatString($friendly_url));     
		$id = $listingObj->getNumber("id");
		$friendly_url = $listingObj->getString("friendly_url");
        $levelObj = new ListingLevel();
        $levelDetail = $levelObj->getDetail($listingObj->getNumber("level"));

		if (($aFrom[0] == ALIAS_SHARE_URL_DIVISOR || $aFrom[0] == ALIAS_REVIEW_URL_DIVISOR || $aFrom[0] == ALIAS_CHECKIN_URL_DIVISOR) && $levelDetail == "y") {
            if (string_strtolower($aFrom[0]) == ALIAS_REVIEW_URL_DIVISOR ) {
                $sbmLink = LISTING_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR."/".$friendly_url;
            } elseif (string_strtolower($aFrom[0]) == ALIAS_CHECKIN_URL_DIVISOR) {
                $sbmLink = LISTING_DEFAULT_URL."/".ALIAS_CHECKIN_URL_DIVISOR."/".$friendly_url;
            } else {	
                $sbmLink = LISTING_DEFAULT_URL."/".$friendly_url.".html";
            }
		} else {
			$sbmLink = LISTING_DEFAULT_URL."/results.php?id=".$id;
		}
        
		if (string_strpos($_SERVER["HTTP_USER_AGENT"], "http://www.facebook.com/externalhit_uatext.php") === false) {
			header("Location: ".$sbmLink);
			exit;
		}
        
		$description = $listingObj->getString("description");
		$title = $listingObj->getString("title");
        $level = $listingObj->getNumber("level");
		$dbObj = db_getDBObject();

		$sqlGI = "SELECT `gallery_id` FROM `Gallery_Item` WHERE `item_id` = ".$listingObj->getNumber("id")." AND `item_type` = 'listing' LIMIT 1";
		$resGI = $dbObj->Query($sqlGI);

		$rowGI = mysql_fetch_assoc($resGI);

		$galObj = new Gallery();

		$images = $galObj->getAllImages($rowGI["gallery_id"]);
        
        $levelObj = new ListingLevel();
        //Get fields according to level
        unset($array_fields);
        $array_fields = system_getFormFields("Listing", $level);
        $levelMaxImages = $levelObj->getImages($level);
        $hasImage = false;
        if ((is_array($array_fields) && in_array("main_image", $array_fields)) || $levelMaxImages > 0){
            $hasImage = true;
        }
        
        $fromURL = LISTING_DEFAULT_URL."/".str_replace(EDIRECTORY_FOLDER, "", $from);

        front_shareContent($title, $description, $hasImage, $images, $fromURL);
        
    } else {
		header("Location: ".LISTING_DEFAULT_URL);
		exit;
	}
?>