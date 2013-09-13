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
	# * FILE: /controller/blog/share.php
	# ----------------------------------------------------------------------------------------------------

    extract($_GET);

	$aFrom = explode("/", $from);

	if ($id || $friendly_url) {

		if ($id) {
            $postObj = new Post($id);
        } else {
			$dbObj = db_getDBObject();
			$sql = "SELECT * FROM `Post` WHERE `friendly_url` = '".$friendly_url."'";
			$res = $dbObj->Query($sql);
			if (mysql_num_rows($res)) {
				$row = mysql_fetch_assoc($res);
				$postObj = new Post($row);
			} else {
                header("Location: ".BLOG_DEFAULT_URL);
                exit;
            }
		}

		$id = $postObj->getNumber("id");
		$friendly_url = $postObj->getString("friendly_url");

		$sbmLink = BLOG_DEFAULT_URL."/".$postObj->getString("friendly_url").".html";

            
		if (string_strpos($_SERVER["HTTP_USER_AGENT"], "http://www.facebook.com/externalhit_uatext.php") === false) {
			header("Location: ".$sbmLink);
			exit;
		}
		
		$description = (($postObj->getString("seo_abstract")) ? ($postObj->getString("seo_abstract")):(strip_tags($postObj->getString("content", false, 252))));
		$title = (($postObj->getString("seo_title")) ? ($postObj->getString("seo_title")) : ($postObj->getString("title")));
        
        $images[0]["image_id"] = $postObj->getNumber("image_id");
        
        $fromURL = BLOG_DEFAULT_URL."/".str_replace(EDIRECTORY_FOLDER, "", $from);
        
        front_shareContent($title, $description, true, $images, $fromURL);
        
    } else {
		header("Location: ".BLOG_DEFAULT_URL);
		exit;
	}
?>