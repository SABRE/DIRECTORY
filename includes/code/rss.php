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
	# * FILE: /includes/code/rss.php
	# ----------------------------------------------------------------------------------------------------

	if (!function_exists("getRSSTreeCategory")) {
		function getRSSTreeCategory($categoryID, $itemRSSSection) {
			unset($strRet);
			$dbMain = db_getDBObject(DEFAULT_DB, true);
			$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			if ($itemRSSSection == "listing" || $itemRSSSection == "deal"){
				$categoryObj = new ListingCategory($categoryID);
			}elseif ($itemRSSSection == "event"){
				$categoryObj = new EventCategory($categoryID);
			}elseif ($itemRSSSection == "classified"){
				$categoryObj = new ClassifiedCategory($categoryID);
			}elseif ($itemRSSSection == "article"){
				$categoryObj = new ArticleCategory($categoryID);
			}elseif ($itemRSSSection == "blog"){
				$categoryObj = new BlogCategory($categoryID);
			} 

			if($categoryObj && $itemRSSSection){
                if($itemRSSSection == "deal"){
                    $sql = "SELECT id FROM ListingCategory WHERE id = ".$categoryObj->getNumber("category_id")."";
                }else{
                    $sql = "SELECT id FROM ".string_ucwords($itemRSSSection)."Category WHERE id = ".$categoryObj->getNumber("category_id")."";
                }
				$result = $dbObj->query($sql);
				if (mysql_num_rows($result) > 0) {
					while ($row = mysql_fetch_assoc($result)) {
						$strRet = getRSSTreeCategory($row["id"], $itemRSSSection);
					}
				}
				$strRet[] = $categoryObj->getString("friendly_url");
				return $strRet;
			} else {
				return false;
			}
		}
	}
    
	unset($rss_querystring, $rss_friendlyurl);

	if($itemRSSSection){

		if ($_GET["category_id"]) {
			$rss_querystring[] = "category_id=".$_GET["category_id"];
			$rss_treecategory = getRSSTreeCategory($_GET["category_id"], $itemRSSSection);
			if(is_array($rss_treecategory)){
				$rss_friendlyurl["category_id"] = ALIAS_CATEGORY_URL_DIVISOR."_".implode("_", $rss_treecategory);
			}
			unset($rss_treecategory);
		}

		$_locations = explode(",", EDIR_LOCATIONS);
		$rss_flagLoc = false;
		foreach ($_locations as $_location_level) {
			if ($_GET["location_".$_location_level]) {
				$location_id = $_GET["location_".$_location_level];
				$rss_querystring[] = "location_".$_location_level."=".$location_id;
				if (!$rss_flagLoc)
					$rss_flagLoc = true;
				$location_array = db_getFromDB('location'.$_location_level, 'id', $location_id, 1, '', 'array');
				$rss_location["location_".$_location_level] = $location_array["friendly_url"];
			}
		}
		if ($rss_flagLoc)
			$rss_friendlyurl["location"] .= ALIAS_LOCATION_URL_DIVISOR."_".implode ("_", $rss_location);


		if ($_GET["keyword"]) {
			$rss_querystring[] = "keyword=".urlencode($_GET["keyword"]);
			$rss_friendlyurl["keyword"] = "";
		}

		if ($_GET["where"]) {
			$rss_querystring[] = "where=".urlencode($_GET["where"]);
			$rss_friendlyurl["where"] = "";
		}

		if ($_GET["zip"]) {
			$rss_querystring[] = "zip=".$_GET["zip"];
			$rss_friendlyurl["zip"] = "";
		}

		if ($_GET["dist"]) {
			$rss_querystring[] = "dist=".$_GET["dist"];
			$rss_friendlyurl["dist"] = "";
		}

		if ($_GET["month"]) {
			$rss_querystring[] = "month=".$_GET["month"];
			$rss_friendlyurl["month"] = "month_".$_GET["month"];
		} elseif ($_GET["this_date"]) {
			$rss_querystring[] = "this_date=".$_GET["this_date"];
			$rss_friendlyurl["this_date"] = "day_".$_GET["this_date"];    
		}
        
        if ($_GET["archive_year"] && !$_GET["archive_month"]) {
            $rss_querystring[] = "year=".$_GET["archive_year"];
			$rss_friendlyurl["month"] = "year_".$_GET["archive_year"];
        } elseif ($_GET["archive_year"] && $_GET["archive_month"]) {
            $rss_querystring[] = "month=".$_GET["archive_year"].$_GET["archive_month"];
			$rss_friendlyurl["month"] = "month_".$_GET["archive_year"].$_GET["archive_month"];
        }

		if ($_GET["id"]) {
			$rss_querystring[] = "id=".$_GET["id"];
			if ($itemRSSSection == "listing") ${"rss_".$itemRSSSection} = new Listing($_GET["id"]);
			elseif ($itemRSSSection == "event") ${"rss_".$itemRSSSection} = new Event($_GET["id"]);
			elseif ($itemRSSSection == "classified") ${"rss_".$itemRSSSection} = new Classified($_GET["id"]);
			elseif ($itemRSSSection == "article") ${"rss_".$itemRSSSection} = new Article($_GET["id"]);
            elseif ($itemRSSSection == "blog") ${"rss_".$itemRSSSection} = new Post($_GET["id"]);
            elseif ($itemRSSSection == "deal") ${"rss_".$itemRSSSection} = new Promotion($_GET["id"]);
			$rss_friendlyurl["id"] = ${"rss_".$itemRSSSection}->getString("friendly_url");
			unset(${"rss_".$itemRSSSection});
		}

        if ($itemRSSSection == "deal"){
            $aux_url_rss_link = "PROMOTION_DEFAULT_URL";
        } else {
            $aux_url_rss_link = string_strtoupper($itemRSSSection."_DEFAULT_URL");
        }
        
		if ($rss_querystring) {
			$rssLink = "".constant($aux_url_rss_link)."/rss/index.php?".implode("&amp;", $rss_querystring);
		} else {
			$rssLink = "".constant($aux_url_rss_link)."/rss/index.php";
		}

        if ($rss_friendlyurl) {
            
            if ($itemRSSSection == "listing") $itemRSSSection_name = ALIAS_LISTING_MODULE;
			elseif ($itemRSSSection == "event") $itemRSSSection_name = ALIAS_EVENT_MODULE;
			elseif ($itemRSSSection == "classified") $itemRSSSection_name = ALIAS_CLASSIFIED_MODULE;
			elseif ($itemRSSSection == "article") $itemRSSSection_name = ALIAS_ARTICLE_MODULE;
            elseif ($itemRSSSection == "blog") $itemRSSSection_name = ALIAS_BLOG_MODULE;
            elseif ($itemRSSSection == "deal") $itemRSSSection_name = ALIAS_PROMOTION_MODULE;
            
            if (!array_key_exists("keyword", $rss_friendlyurl) && !array_key_exists("where", $rss_friendlyurl) && !array_key_exists("zip", $rss_friendlyurl) && !array_key_exists("dist", $rss_friendlyurl)) {
                if (array_key_exists("id", $rss_friendlyurl) && (count($rss_friendlyurl) == 1)) {
                    $rssLink = "".constant($aux_url_rss_link)."/rss/".$itemRSSSection_name."_".$rss_friendlyurl["id"].".xml";
                } elseif (!array_key_exists("id", $rss_friendlyurl)) {
                    $rssLink = "".constant($aux_url_rss_link)."/rss/".$itemRSSSection_name."_".implode("_", $rss_friendlyurl).".xml";
                }
            }
        } else {
            $rssLink = "".constant($aux_url_rss_link)."/rss/";
        }
		unset($aux_array_rss);
		$aux_array_rss = array();
		$aux_array_rss["link"]	= $rssLink;
		$aux_array_rss["title"] = system_showText(LANG_LABEL_SUBSCRIBERSS);
		$aux_array_rss["alt"]	= system_showText(LANG_LABEL_SUBSCRIBERSS);

		unset($rss_querystring, $rss_friendlyurl, $rssLink, $_locations);
	}
?>