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
	# * FILE: /includes/code/featured_listing.php
	# ----------------------------------------------------------------------------------------------------

	$numberOfListings = FEATURED_LISTING_MAXITEMS;
	$lastItemStyle = 0;
	$specialItem = FEATURED_LISTING_MAXITEMS_SPECIAL;

    $level = implode(",", system_getLevelDetail("ListingLevel"));

	if ($level) {
		unset($searchReturn);
		$searchReturn = search_frontListingSearch($_GET, "random");
		$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." WHERE ".(($searchReturn["where_clause"])?($searchReturn["where_clause"]." AND"):(""))." (Listing_Summary.level IN (".$level.")) ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ORDER BY ".($searchReturn["order_by"] ? $searchReturn["order_by"] : " `Listing_FeaturedTemp`.`random_number` ")." LIMIT ".$numberOfListings."";
		
		$random_listings = db_getFromDBBySQL("listing", $sql);
	}

	if ($random_listings) {

		if (LISTING_SCALABILITY_OPTIMIZATION != "on"){
			$seeAllText = system_showText(LANG_LABEL_VIEW_ALL_LISTINGS);
			$seeAllTextLink = LISTING_DEFAULT_URL."/results.php"; 
        }
        
        $count = 0;
        $countSpecialItem = 0;
        $ids_report_lote = "";
        unset($array_show_listings);
        
        foreach ($random_listings as $listing) {
			
            $ids_report_lote .= $listing->getString("id").",";
				
            $lastItemStyle++;
            
            $array_show_listings[$count]["detailLink"] = "".LISTING_DEFAULT_URL."/".$listing->getString("friendly_url").".html";
            
            unset($imageObj);
            
            if ($countSpecialItem < $specialItem) {

                $imageObj = new Image($listing->getNumber("thumb_id"));
                if ($imageObj->imageExists()) {
                    $array_show_listings[$count]["image_tag"] = $imageObj->getTag(true, IMAGE_FEATURED_LISTING_WIDTH, IMAGE_FEATURED_LISTING_HEIGHT, $listing->getString("title", false), true);                    
                } else {
                    $array_show_listings[$count]["image_tag"] = "";
                }
                $countSpecialItem++;
                
            }
            
            $array_show_listings[$count]["id"]              = htmlspecialchars($listing->getNumber("id"));
            $array_show_listings[$count]["account_id"]      = $listing->getNumber("account_id");
            $array_show_listings[$count]["title"]           = $listing->getString("title");
            $array_show_listings[$count]["title_truncated"] = $listing->getString("title", true, 30);
            
            if (LISTING_SCALABILITY_OPTIMIZATION != "on") {
                $array_show_listings[$count]["categories"] = system_itemRelatedCategories($listing->getNumber("id"), "listing", true);
                $name = socialnetwork_writeLink($listing->getNumber("account_id"), "profile", "general_see_profile");
                if ($name) {
                    $array_show_listings[$count]["author_string"] = " ".system_showText(LANG_BY)." ".$name;
                }
            }

            if ($lastItemStyle == $numberOfListings) {
                $itemStyle = "last";
            } elseif ($lastItemStyle == ($specialItem+1)) {
                $itemStyle = "first";
            } else {
                $itemStyle = "";
            }
            $array_show_listings[$count]["itemStyle"] = $itemStyle;
            
            $count++;
        }
        $ids_report_lote = string_substr($ids_report_lote, 0, -1);
		report_newRecord("listing", $ids_report_lote, LISTING_REPORT_SUMMARY_VIEW, true);
	}
?>