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
	# * FILE: /controller/deal/share.php
	# ----------------------------------------------------------------------------------------------------

    extract($_GET);

	$aFrom = explode("/", $from);

	if ($friendly_url) {

		$promotionObj = db_getFromDB("promotion", "friendly_url", db_formatString($friendly_url));

		$id = $promotionObj->getNumber("id");
		$friendly_url = $promotionObj->getString("friendly_url");

		if ($aFrom[0] == ALIAS_SHARE_URL_DIVISOR || $aFrom[0] == ALIAS_REVIEW_URL_DIVISOR) {
            if (string_strtolower($aFrom[0]) == ALIAS_REVIEW_URL_DIVISOR) {
                $sbmLink = PROMOTION_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR."/".$friendly_url;
            } else {
                $sbmLink = PROMOTION_DEFAULT_URL."/".$friendly_url.".html";
            }
		} else {
			$sbmLink = PROMOTION_DEFAULT_URL."/results.php?id=".$id;
		}

		if (string_strpos($_SERVER["HTTP_USER_AGENT"], "http://www.facebook.com/externalhit_uatext.php") === false) {
			header("Location: ".$sbmLink);
			exit;
		}

		$description = $promotionObj->getString("description");
		$title = $promotionObj->getString("name", false);
        
        $images[0]["image_id"] = $promotionObj->getNumber("image_id");
        
        $fromURL = PROMOTION_DEFAULT_URL."/".str_replace(EDIRECTORY_FOLDER, "", $from);
        
        front_shareContent($title, $description, true, $images, $fromURL);
        
    } else {
		header("Location: ".PROMOTION_DEFAULT_URL);
		exit;
	}
?>