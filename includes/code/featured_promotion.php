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
	# * FILE: /includes/code/featured_promotion.php
	# ----------------------------------------------------------------------------------------------------

    if (THEME_FEATURED_DEAL_BIG){
        $priceClass = "price-tag";
        $contentClass = "deal-feat-big";
        $imageOpenDiv = "<div class=\"deal-image\">";
        $imageCloseDiv = "</div>";
    } else {
        $priceClass = "left";
        $contentClass = "right";
        $imageOpenDiv = "";
        $imageCloseDiv = "";
    }

	$numberOfPromotions = FEATURED_PROMOTION_MAXITEMS;
	$lastItemStyle = 0;
	$specialItem = FEATURED_PROMOTION_MAXITEMS_SPECIAL;

    unset($searchReturn);
	$searchReturn = search_frontPromotionSearch($_GET, "random", true);
    $sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." ".(($searchReturn["where_clause"])?("WHERE ".$searchReturn["where_clause"]):(""))." ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT ".($numberOfPromotions)."";
    $promotions = db_getFromDBBySQL("promotion", $sql);

	if ($promotions) {

		if (PROMOTION_SCALABILITY_OPTIMIZATION != "on"){
			$seeAllText = system_showText(LANG_LABEL_VIEW_ALL_PROMOTIONS);
			$seeAllTextLink = PROMOTION_DEFAULT_URL."/results.php"; 
        }
        
        $level = new ListingLevel();
        $count = 0;
        $countSpecialItem = 0;
        $ids_report_lote = "";
        unset($array_show_promotions);
        
        foreach ($promotions as $promotion) {
			
            $ids_report_lote .= $promotion->getString("id").",";
				
            $lastItemStyle++;
            
            $array_show_promotions[$count]["detailLink"] = "".PROMOTION_DEFAULT_URL."/".$promotion->getString("friendly_url").".html";
            
            $array_show_promotions[$count]["deal_price"] = string_substr($promotion->getNumber("dealvalue"), 0, (string_strpos($promotion->getNumber("dealvalue"), ".")));
            $array_show_promotions[$count]["deal_cents"] = string_substr($promotion->getNumber("dealvalue"), (string_strpos($promotion->getNumber("dealvalue"), ".")), 3);
            if ($array_show_promotions[$count]["deal_cents"] == ".00") {
                $array_show_promotions[$count]["deal_cents"] = "";
            }
            
            unset($imageObj);
            
            if ($countSpecialItem < $specialItem) {
                
                if ($promotion->getNumber("realvalue") > 0) {
                    $array_show_promotions[$count]["offer"] = round(100-(($promotion->getNumber("dealvalue")*100)/$promotion->getNumber("realvalue"))).'%';
                } else {
                    $array_show_promotions[$count]["offer"] = system_showText(LANG_NA);
                }

                $imageObj = new Image($promotion->getNumber("thumb_id"));
                if ($imageObj->imageExists()) {
                    $array_show_promotions[$count]["image_tag"] = $imageObj->getTag(true, IMAGE_FRONT_PROMOTION_WIDTH, IMAGE_FRONT_PROMOTION_HEIGHT, $promotion->getString("name", false), true);                    
                } else {
                    $array_show_promotions[$count]["image_tag"] = "";
                }
                                
                $countSpecialItem++;
                
            }
            
            $array_show_promotions[$count]["id"]           = htmlspecialchars($promotion->getNumber("id"));
            $array_show_promotions[$count]["account_id"]   = $promotion->getNumber("account_id");
            $array_show_promotions[$count]["title"]        = $promotion->getString("name", true);
            
            $listing = db_getFromDB("listing", "promotion_id", db_formatNumber($promotion->getNumber("id")), 1, "", "array");
            if ($listing["title"]) {
                if ($level->getDetail($listing["level"]) == "y") {
                    $array_show_promotions[$count]["listing_link"] = "".LISTING_DEFAULT_URL."/".$listing["friendly_url"].".html";
                } else {
                    $array_show_promotions[$count]["listing_link"] = "".LISTING_DEFAULT_URL."/results.php?id=".$listing["id"];
                }
                $array_show_promotions[$count]["listing_title"] = $listing["title"];
            }
            
            if ($lastItemStyle == $numberOfPromotions) {
                $itemStyle = "last";
            } elseif ($lastItemStyle == ($specialItem+1)) {
                $itemStyle = "first";
            } else {
                $itemStyle = "";
            }
            $array_show_promotions[$count]["itemStyle"] = $itemStyle;
            
            $count++;
        }
        
        $ids_report_lote = string_substr($ids_report_lote, 0, -1);
        report_newRecord("promotion", $ids_report_lote, PROMOTION_REPORT_SUMMARY_VIEW, true);
	}
?>