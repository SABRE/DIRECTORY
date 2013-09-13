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
	# * FILE: /controller/article/detail.php
	# ----------------------------------------------------------------------------------------------------

    ##################################################
	# ARTICLE
	##################################################
	if (string_strpos($aux_array_url[$searchPos_2], ".html") !== false) {
        $browsebyitem = true;
        $article_url = string_replace_once(".html", "", $aux_array_url[$searchPos_2]);
		$sql = "SELECT Article.id as id FROM Article WHERE Article.friendly_url = ".db_formatString($article_url)." LIMIT 1";
		$result = $dbObj->query($sql);
		$aux = mysql_fetch_assoc($result);
		$_GET["id"] = $aux["id"];
        $_GET["article_id"] = $aux["id"];
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
	# ARTICLE
	# ----------------------------------------------------------------------------------------------------
	if (($_GET["id"]) || ($_POST["id"])) {
		$id = $_GET["id"] ? $_GET["id"] : $_POST["id"];
		$article = new Article($id);
		$level = new ArticleLevel(true);
		unset($articleMsg);
		if ((!$article->getNumber("id")) || ($article->getNumber("id") <= 0)) {
			$articleMsg = system_showText(LANG_MSG_NOTFOUND);
		} elseif ($article->getString("status") != "A") {
			$articleMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} elseif ($article->getString("publication_date") > date("Y-m-d")) {
			$articleMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} elseif ($level->getDetail($article->getNumber("level")) != "y") {
			$articleMsg = system_showText(LANG_MSG_NOTAVAILABLE);
		} else {
			report_newRecord("article", $id, ARTICLE_REPORT_DETAIL_VIEW);
			$article->setNumberViews($id);
		}
	} else {
		header("Location: ".ARTICLE_DEFAULT_URL."/");
		exit;
	}
	
	# ----------------------------------------------------------------------------------------------------
	# REVIEWS
	# ----------------------------------------------------------------------------------------------------
	if ($id)  $sql_where[] = " item_type = 'article' AND item_id = ".db_formatNumber($id)." ";
	if (true) $sql_where[] = " review IS NOT NULL AND review != '' ";
	if (true) $sql_where[] = " approved = '1' ";
	if ($sql_where) $sqlwhere .= " ".implode(" AND ", $sql_where)." ";
	$pageObj  = new pageBrowsing("Review", $screen, 3, "added DESC", "", "", $sqlwhere);
	$reviewsArr = $pageObj->retrievePage("object");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	if (($article->getNumber("id")) && ($article->getNumber("id") > 0)) {
		$artCategs = $article->getCategories();
		if ($artCategs) {
			foreach ($artCategs as $artCateg) {
				$category_id[] = $artCateg->getNumber("id");
			}
		}
	}
    $_POST["category_id"] = $category_id;
    
    # ----------------------------------------------------------------------------------------------------
	# Previous Page Link And Next Page Link
	# ----------------------------------------------------------------------------------------------------
    if (($article->getNumber("id")) && ($article->getNumber("id") > 0)) {

    	$prevPageLink = search_pagePrevNextLink('Article',$article->getNumber("id"),'prev');
    	$nextPageLink = search_pagePrevNextLink('Article',$article->getNumber("id"),'next');
    	
    }
?>