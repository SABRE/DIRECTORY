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
	# * FILE: /includes/code/featured_classified.php
	# ----------------------------------------------------------------------------------------------------

	$numberOfClassifieds = FEATURED_CLASSIFIED_MAXITEMS;
	$lastItemStyle = 0;
	$specialItem = FEATURED_CLASSIFIED_MAXITEMS_SPECIAL;

    $level = implode(",", system_getLevelDetail("ClassifiedLevel"));

    if ($level) {
		unset($searchReturn);
		$searchReturn = search_frontClassifiedSearch($_GET, "random");
		$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." WHERE ".(($searchReturn["where_clause"])?($searchReturn["where_clause"]." AND"):(""))." (Classified.level IN (".$level.")) ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ORDER BY `random_number` LIMIT ".$numberOfClassifieds."";
		$highlight_classifieds = db_getFromDBBySQL("classified", $sql);
	}

	if ($highlight_classifieds) {

		if (CLASSIFIED_SCALABILITY_OPTIMIZATION != "on"){
			$seeAllText = system_showText(LANG_LABEL_VIEW_ALL_CLASSIFIEDS);
			$seeAllTextLink = CLASSIFIED_DEFAULT_URL."/results.php"; 
        }
        
        $count = 0;
        $countSpecialItem = 0;
        $ids_report_lote = "";
        unset($array_show_classifieds);
        
        foreach ($highlight_classifieds as $classified) {
			
            $ids_report_lote .= $classified->getString("id").",";
				
            $lastItemStyle++;
            
            $array_show_classifieds[$count]["detailLink"] = "".CLASSIFIED_DEFAULT_URL."/".$classified->getString("friendly_url").".html";
            

            unset($imageObj);
            
            if ($countSpecialItem < $specialItem) {

                $imageObj = new Image($classified->getNumber("thumb_id"));
                if ($imageObj->imageExists()) {
                    $array_show_classifieds[$count]["image_tag"] = $imageObj->getTag(true, IMAGE_FEATURED_CLASSIFIED_WIDTH, IMAGE_FEATURED_CLASSIFIED_HEIGHT, $classified->getString("title", false), true);                    
                } else {
                    $array_show_classifieds[$count]["image_tag"] = "";
                }
                $countSpecialItem++;
                
            }
            
            $array_show_classifieds[$count]["id"]           = htmlspecialchars($classified->getNumber("id"));
            $array_show_classifieds[$count]["account_id"]   = $classified->getNumber("account_id");
            $array_show_classifieds[$count]["title"]        = $classified->getString("title", true);
            
            if (CLASSIFIED_SCALABILITY_OPTIMIZATION != "on") {
                $array_show_classifieds[$count]["categories"] = system_itemRelatedCategories($classified->getNumber("id"), "classified", true);
                $name = socialnetwork_writeLink($classified->getNumber("account_id"), "profile", "general_see_profile");
                if ($name) {
                    $array_show_classifieds[$count]["author_string"] = " ".system_showText(LANG_BY)." ".$name;
                }
            }

            if ($lastItemStyle == $numberOfClassifieds) {
                $itemStyle = "last";
            } elseif ($lastItemStyle == ($specialItem+1)) {
                $itemStyle = "first";
            } else {
                $itemStyle = "";
            }
            $array_show_classifieds[$count]["itemStyle"] = $itemStyle;
            
            $count++;
        }
        
        $ids_report_lote = string_substr($ids_report_lote, 0, -1);
        report_newRecord("classified", $ids_report_lote, CLASSIFIED_REPORT_SUMMARY_VIEW, true);
	}
?>