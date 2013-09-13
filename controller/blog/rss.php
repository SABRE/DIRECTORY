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
	# * FILE: /controller/blog/rss.php
	# ----------------------------------------------------------------------------------------------------

	if ($_GET["qs"]) {

		$qs = explode("_", $_GET["qs"]);

		if (!in_array(ALIAS_CATEGORY_URL_DIVISOR, $qs) && !in_array("month", $qs) && !in_array("year", $qs) && (count($qs) == 1)) {

			$_GET["post"] = $qs[0];

		} elseif ((in_array(ALIAS_CATEGORY_URL_DIVISOR, $qs) || in_array("month", $qs) || in_array("year", $qs)) && (count($qs) > 1)) {

			$guidepos = array_search(ALIAS_CATEGORY_URL_DIVISOR, $qs);
			if ($guidepos !== false) $guideposbegin = guidepos+1;
            if (in_array("month", $qs)) {
                $monthpos = array_search("month", $qs);
                $_GET["month"] = $qs[$monthpos+1];
                $_GET["archive_month"] = string_substr($qs[$daypos+1], 4, 2);
                $_GET["archive_year"] = string_substr($qs[$daypos+1], 0, 4);
            } elseif (in_array("year", $qs))  {
                $daypos = array_search("year", $qs);
                $_GET["year"] = $qs[$daypos+1];
                $_GET["archive_year"] = $qs[$daypos+1];
            }

            $guideposend = count($qs)-1;

			if ($guidepos !== false) {
				for ($i=$guideposbegin; $i<=$guideposend; $i++) {
					if ($i == ($guideposbegin))   $_GET["category1"] = $qs[$i];
					if ($i == ($guideposbegin+1)) $_GET["category2"] = $qs[$i];
					if ($i == ($guideposbegin+2)) $_GET["category3"] = $qs[$i];
					if ($i == ($guideposbegin+3)) $_GET["category4"] = $qs[$i];
					if ($i == ($guideposbegin+4)) $_GET["category5"] = $qs[$i];
				}
			}
		}

		unset($_GET["qs"], $qs);

	}
    
    # ----------------------------------------------------------------------------------------------------
	# MOD-REWRITE
	# ----------------------------------------------------------------------------------------------------
	include(EDIR_CONTROLER_FOLDER."/".BLOG_FEATURE_FOLDER."/rewrite.php");
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	$dbObj = db_getDBObJect();

	unset($searchReturn);
	$searchReturn = search_frontBlogSearch($_GET, "rss");
	$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." WHERE ".(($searchReturn["where_clause"])?($searchReturn["where_clause"]." "):("")).(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT 100";
	$result = $dbObj->query($sql);

	if (mysql_num_rows($result) <= 0) {
		unset($searchReturn);
		$searchReturn = search_frontBlogSearch($_GET, "rss");
		$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." ".(($searchReturn["where_clause"])?("WHERE ".$searchReturn["where_clause"]):(""))." ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT 100";
		$result = $dbObj->query($sql);
	}

	while ($row = mysql_fetch_assoc($result)) {
		$posts[] = new Post($row["id"]);
	}

	if ($posts) {

		$rssWriter = new RSSWriter();

		unset($channel_properties);
		if ($id) {
			$channel_properties["title"]			= EDIRECTORY_TITLE." ".string_ucwords(LANG_BLOG)." - ".$posts[0]->getString("title");
			$channel_properties["link"]				= DEFAULT_URL;
			$channel_properties["description"]		= EDIRECTORY_TITLE." ".string_ucwords(LANG_BLOG)." - ".$posts[0]->getString("title");
		} elseif ($category_id || $month || $year || $keyword) {
			if ($category_id) $rss_category = new BlogCategory($category_id);
            if ($year) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".ucfirst(system_showText(LANG_YEAR))." ".$year;
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".ucfirst(system_showText(LANG_YEAR))." ".$year;	
			} elseif ($month) {
				$ts_time = mktime(0,0,0,(int)(string_substr($month,4,2)+1),null,(int)string_substr($month,0,4));
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".system_showDate(LANG_STRINGDATE_YEARANDMONTH, $ts_time);
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".system_showDate(LANG_STRINGDATE_YEARANDMONTH, $ts_time);	
			} elseif ($keyword) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".$keyword;
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".$keyword;
			} elseif ($category_id) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".system_showText(LANG_LABEL_GUIDE)." ".$rss_category->getString("title");
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." -  ".system_showText(LANG_LABEL_GUIDE)." ".$rss_category->getString("title");
			}
		} else {
			$channel_properties["title"]			= EDIRECTORY_TITLE." - RSS Feed";
			$channel_properties["link"]				= DEFAULT_URL;
			$channel_properties["description"]		= EDIRECTORY_TITLE." - RSS Feed";
		}
		$rssWriter->addChannel($channel_properties);

		unset($image_properties);
		$image_properties["link"]		= DEFAULT_URL;
		if (file_exists(EDIRECTORY_ROOT.RSS_LOGO_PATH)) {
			$image_properties["url"]	= DEFAULT_URL.RSS_LOGO_PATH;
		} else {
			$image_properties["url"]	= DEFAULT_URL."/images/content/img_logo.gif";
		}
		$image_properties["title"]		= EDIRECTORY_TITLE;
		$rssWriter->addChannelImage($image_properties);

		foreach ($posts as $each_post) {

			unset($itens_properties);
			$itens_properties["title"]      = $each_post->getString("title");
			
            $itens_properties["link"]	= BLOG_DEFAULT_URL."/".$each_post->getString("friendly_url").".html";
            $itens_properties["description"]	= $each_post->getString("seo_abstract");
            $itens_properties["guid"]			= $itens_properties["link"];
            $itens_properties["pubDate"]		= date(DATE_RSS,strtotime($each_post->updated));

			if ($each_post->getNumber("thumb_id")) {
				$imageObj = new Image($each_post->getNumber("thumb_id"));
				$itens_properties["img_src"]	= IMAGE_URL."/".$imageObj->getString("prefix")."photo_".$imageObj->getNumber("id").".".string_strtolower($imageObj->getString("type"));
				$itens_properties["img_width"]	= $imageObj->getNumber("width");
				$itens_properties["img_height"]	= $imageObj->getNumber("height");
			}

			$rssWriter->addItem($itens_properties);

			$rssWriter->buildItem();

		}

		$rssWriter->buildChannel();

		$rssWriter->outputRSS();

	}
?>