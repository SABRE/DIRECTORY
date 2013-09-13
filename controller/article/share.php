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
	# * FILE: /controller/article/share.php
	# ----------------------------------------------------------------------------------------------------

	extract($_GET);

	$aFrom = explode("/", $from);

	if ($friendly_url) {

        $articleObj = db_getFromDB("article", "friendly_url", db_formatString($friendly_url));
		$id = $articleObj->getNumber("id");
		$friendly_url = $articleObj->getString("friendly_url");
        $levelObj = new ArticleLevel();
        $levelDetail = $levelObj->getDetail($articleObj->getNumber("level"));

		if (($aFrom[0] == ALIAS_SHARE_URL_DIVISOR || $aFrom[0] == ALIAS_REVIEW_URL_DIVISOR) && $levelDetail == "y") {
            if (string_strtolower($aFrom[0]) == ALIAS_REVIEW_URL_DIVISOR) {
                $sbmLink = ARTICLE_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR."/".$friendly_url;
            } else {
                $sbmLink = ARTICLE_DEFAULT_URL."/".$friendly_url.".html";
            }
		} else {
			$sbmLink = ARTICLE_DEFAULT_URL."/results.php?id=".$id;
		}

		if (string_strpos($_SERVER["HTTP_USER_AGENT"], "http://www.facebook.com/externalhit_uatext.php") === false) {
			header("Location: ".$sbmLink);
			exit;
		}

		$description = $articleObj->getString("abstract");
		$title = $articleObj->getString("title");
		$dbObj = db_getDBObject();

		$sqlGI = "SELECT `gallery_id` FROM `Gallery_Item` WHERE `item_id` = ".$articleObj->getNumber("id")." AND `item_type` = 'article' LIMIT 1";
		$resGI = $dbObj->Query($sqlGI);

		$rowGI = mysql_fetch_assoc($resGI);

		$galObj = new Gallery();

		$images = $galObj->getAllImages($rowGI["gallery_id"]);
        
        $fromURL = ARTICLE_DEFAULT_URL."/".str_replace(EDIRECTORY_FOLDER, "", $from);
        
        front_shareContent($title, $description, true, $images, $fromURL);
        
    } else {
		header("Location: ".ARTICLE_DEFAULT_URL);
		exit;
	}
?>