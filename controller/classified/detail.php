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
	# * FILE: /controller/classified/detail.php
	# ----------------------------------------------------------------------------------------------------

    ##################################################
	# CLASSIFIED
	##################################################
	if (string_strpos($aux_array_url[$searchPos_2], ".html") !== false) {
        $browsebyitem = true;
        $classified_url = string_replace_once(".html", "", $aux_array_url[$searchPos_2]);
		$sql = "SELECT Classified.id as id FROM Classified WHERE Classified.friendly_url = ".db_formatString($classified_url)." LIMIT 1";
		$result = $dbObj->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["id"] = $aux["id"];
        $_GET["classified_"] = $aux["id"];
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
	# CLASSIFIED
	# ----------------------------------------------------------------------------------------------------
	if (($_GET["id"]) || ($_POST["id"])) {
		$id = $_GET["id"] ? $_GET["id"] : $_POST["id"];
		$classified = new Classified($id);
		$level = new ClassifiedLevel(true);
		unset($classifiedMsg);
		if ((!$classified->getNumber("id")) || ($classified->getNumber("id") <= 0)) {
			$classifiedMsg = system_showText(LANG_MSG_NOTFOUND);
		} elseif ($classified->getString("status") != "A") {
			$classifiedMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} elseif ($level->getDetail($classified->getNumber("level")) != "y" && $level->getActive($classified->getNumber("level")) == 'y') {
			$classifiedMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} else {
			report_newRecord("classified", $id, CLASSIFIED_REPORT_DETAIL_VIEW);
			$classified->setNumberViews($id);
		}
	} else {
		header("Location: ".CLASSIFIED_DEFAULT_URL."/");
		exit;
	}
    
    # ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
    if (($classified->getNumber("id")) && ($classified->getNumber("id") > 0)) {
        $claCategs = $classified->getCategories();
        if ($claCategs) {
            foreach ($claCategs as $claCateg) {
                $category_id[] = $claCateg->getNumber("id");
            }
        }
    }
    $_POST["category_id"] = $category_id;
    
    
    # ----------------------------------------------------------------------------------------------------
	# Previous Page Link And Next Page Link
	# ----------------------------------------------------------------------------------------------------
    if (($classified->getNumber("id")) && ($classified->getNumber("id") > 0)) {

    	$prevPageLink = search_pagePrevNextLink('Classified',$classified->getNumber("id"),'prev');
    	$nextPageLink = search_pagePrevNextLink('Classified',$classified->getNumber("id"),'next');
    	
    }
?>