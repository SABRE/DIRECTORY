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
	# * FILE: /controller/listing/rss.php
	# ----------------------------------------------------------------------------------------------------

    if ($_GET["qs"]) {
        $fromRSS = false;

        $qs = explode("_", $_GET["qs"]);

        if (!in_array(ALIAS_CATEGORY_URL_DIVISOR, $qs) && !in_array(ALIAS_LOCATION_URL_DIVISOR, $qs) && (count($qs) == 1)) {

            $_GET["listing"] = $qs[0];

        } elseif ((in_array(ALIAS_CATEGORY_URL_DIVISOR, $qs) || in_array(ALIAS_LOCATION_URL_DIVISOR, $qs)) && (count($qs) > 1)) {

            $guidepos = array_search(ALIAS_CATEGORY_URL_DIVISOR, $qs);
            if ($guidepos !== false) $guideposbegin = $guidepos+1;
            $locationpos = array_search(ALIAS_LOCATION_URL_DIVISOR, $qs);
            if ($locationpos !== false) $locationposbegin = $locationpos+1;

            if ($guidepos === false) {
                $locationposend = count($qs)-1;
            } elseif ($locationpos === false) {
                $guideposend = count($qs)-1;
            } else {
                if ($guideposbegin > $locationposbegin) {
                    $guideposend = count($qs)-1;
                    $locationposend = $guidepos-1;
                } elseif ($locationposbegin > $guideposbegin) {
                    $locationposend = count($qs)-1;
                    $guideposend = $locationpos-1;
                }
            }

            if ($guidepos !== false) {
                $fromRSS = true;
                $get_urlFull = EDIRECTORY_FOLDER.'/'.ALIAS_LISTING_MODULE.'/'.ALIAS_CATEGORY_URL_DIVISOR;
                for ($i=$guideposbegin; $i<=$guideposend; $i++) {
                    $get_urlFull .= '/'.$qs[$i];
                }
            }

            $_GET ["url_full"] = $get_urlFull;

            $_locations = explode(",", EDIR_LOCATIONS);
            if ($locationpos !== false) {
                for ($i=$locationposbegin; $i<=$locationposend; $i++) {
                    if ($i == ($locationposbegin))   $_GET["friendLoc1"] = $qs[$i];
                    if ($i == ($locationposbegin+1)) $_GET["friendLoc2"] = $qs[$i];
                    if ($i == ($locationposbegin+2)) $_GET["friendLoc3"] = $qs[$i];
                    if ($i == ($locationposbegin+3)) $_GET["friendLoc4"] = $qs[$i];
                    if ($i == ($locationposbegin+4)) $_GET["friendLoc5"] = $qs[$i];
                }
            }
            unset ($_locations);

        }

        unset($_GET["qs"], $qs);

    }
    
    # ----------------------------------------------------------------------------------------------------
	# MOD-REWRITE
	# ----------------------------------------------------------------------------------------------------
	include(EDIR_CONTROLER_FOLDER."/".LISTING_FEATURE_FOLDER."/rewrite.php");
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	$levelObj = new ListingLevel();
	$levelvalues = $levelObj->getLevelValues();
	$levels_str = implode(",",$levelvalues);

	$dbObj = db_getDBObJect();

	unset($searchReturn);
	$searchReturn = search_frontListingSearch($_GET, "listing_results");
	$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." WHERE ".(($searchReturn["where_clause"])?($searchReturn["where_clause"]." AND"):(""))." Listing_Summary.level IN (".$levels_str.") ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT 100";
	$result = $dbObj->query($sql);

	if (mysql_num_rows($result) <= 0) {
		unset($searchReturn);
		$searchReturn = search_frontListingSearch($_GET, "listing_results");
		$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." ".(($searchReturn["where_clause"])?("WHERE ".$searchReturn["where_clause"]):(""))." ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT 100";
		$result = $dbObj->query($sql);
	}

	while ($row = mysql_fetch_assoc($result)) {
		$listings[] = new Listing($row["id"]);
	}

	if ($listings) {
        
		$rssWriter = new RSSWriter();

		unset($channel_properties);
		if ($id) {
			$channel_properties["title"]			= EDIRECTORY_TITLE." ".string_ucwords(LANG_LISTING)." - ".$listings[0]->getString("title");
			$channel_properties["link"]				= DEFAULT_URL;
			$channel_properties["description"]		= EDIRECTORY_TITLE." ".string_ucwords(LANG_LISTING)." - ".$listings[0]->getString("title");
		} elseif (($category_id || $location_1 || $location_2 || $location_3 || $location_4 || $location_5 || $keyword || $where) && !$zip && !$dist) {
			if ($category_id) $rss_category = new ListingCategory($category_id);
			if ($location_1)  $rss_location1  = new Location1($location_1);
			if ($location_2)  $rss_location2  = new Location2($location_2);
			if ($location_3)  $rss_location3  = new Location3($location_3);
			if ($location_4)  $rss_location4  = new Location4($location_4);
			if ($location_5)  $rss_location5  = new Location5($location_5);
			if ($location_1 || $location_2 || $location_3 || $location_4 || $location_5) {
				if ($location_1) $rsslocationstr[] = $rss_location1->getString("name");
				if ($location_2) $rsslocationstr[] = $rss_location2->getString("name");
				if ($location_3) $rsslocationstr[] = $rss_location3->getString("name");
				if ($location_4) $rsslocationstr[] = $rss_location4->getString("name");
				if ($location_5) $rsslocationstr[] = $rss_location5->getString("name");
				$rss_location_str = implode(" / ", $rsslocationstr);
			}
			if ( $where ) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".$where;
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".$where;
			} elseif ( $keyword ) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".$keyword;
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".$keyword;
			} elseif ($category_id && !$location_1 && !$location_2 && !$location_3 && !$location_4 && !$location_5) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".system_showText(LANG_LABEL_GUIDE)." ".$rss_category->getString("title");
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".system_showText(LANG_LABEL_GUIDE)." ".$rss_category->getString("title");
			} elseif (($location_1 || $location_2 || $location_3 || $location_4 || $location_5) && !$category_id) {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".system_showText(LANG_SEARCH_LABELLOCATION)." ".$rss_location_str;
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".system_showText(LANG_SEARCH_LABELLOCATION)." ".$rss_location_str;
			} else {
				$channel_properties["title"]		= EDIRECTORY_TITLE." - ".system_showText(LANG_LABEL_GUIDE)." ".$rss_category->getString("title")." - ".system_showText(LANG_SEARCH_LABELLOCATION)." ".$rss_location_str;
				$channel_properties["link"]			= DEFAULT_URL;
				$channel_properties["description"]	= EDIRECTORY_TITLE." - ".system_showText(LANG_LABEL_GUIDE)." ".$rss_category->getString("title")." - ".system_showText(LANG_SEARCH_LABELLOCATION)." ".$rss_location_str;
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

		foreach ($listings as $each_listing) {

			unset($itens_properties);
			$itens_properties["title"]			= $each_listing->getString("title");
			if ($levelObj->getDetail($each_listing->getNumber("level")) == "y") {
				$itens_properties["link"]       = LISTING_DEFAULT_URL."/".$each_listing->getString("friendly_url").".html";
			} else {
				$itens_properties["link"]		= LISTING_DEFAULT_URL."/results.php?id=".$each_listing->getNumber("id");
			}
			$itens_properties["description"]	= $each_listing->getString("description");
			$itens_properties["guid"]			= $itens_properties["link"];
			$itens_properties["phone"]			= $each_listing->getString("phone");
			$itens_properties["email"]			= $each_listing->getString("email");
			$itens_properties["url"]			= $each_listing->getString("url");
			$itens_properties["address"]		= $each_listing->getString("address");
			$itens_properties["pubDate"]		= date(DATE_RSS,strtotime($each_listing->updated));
			
			if ($each_listing->getNumber("thumb_id")) {
				$imageObj = new Image($each_listing->getNumber("thumb_id"));
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