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
	# * FILE: /includes/code/featured_event.php
	# ----------------------------------------------------------------------------------------------------

	$numberOfEvents = FEATURED_EVENT_MAXITEMS;
	$lastItemStyle = 0;
	$specialItem = FEATURED_EVENT_MAXITEMS_SPECIAL;

	$level = implode(",", system_getLevelDetail("EventLevel"));

    if ($level) {
		unset($searchReturn);
		$searchReturn = search_frontEventSearch($_GET, "random");
		$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." WHERE ".(($searchReturn["where_clause"])?($searchReturn["where_clause"]." AND"):(""))." (Event.level IN (".$level.")) ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ORDER BY `start_date` ASC, `start_time` ASC LIMIT ".$numberOfEvents."";
		$highlight_events = db_getFromDBBySQL("event", $sql);
	}

	if ($highlight_events) {

		if (EVENT_SCALABILITY_OPTIMIZATION != "on") {
			$seeAllText = system_showText(LANG_LABEL_VIEW_ALL_EVENTS);
			$seeAllTextLink = EVENT_DEFAULT_URL."/results.php"; 
        }
        
        $count = 0;
        $countSpecialItem = 0;
        $ids_report_lote = "";
        unset($array_show_events);
        
        foreach ($highlight_events as $event) {
			
            $ids_report_lote .= $event->getString("id").",";
				
            $lastItemStyle++;
            
            $array_show_events[$count]["detailLink"] = "".EVENT_DEFAULT_URL."/".$event->getString("friendly_url").".html";
            
            unset($imageObj);
            
            if ($countSpecialItem < $specialItem) {

                $imageObj = new Image($event->getNumber("thumb_id"));
                if ($imageObj->imageExists()) {
                    $array_show_events[$count]["image_tag"] = $imageObj->getTag(true, IMAGE_FRONT_EVENT_WIDTH, IMAGE_FRONT_EVENT_HEIGHT, $event->getString("title", false), true);                    
                } else {
                    $array_show_events[$count]["image_tag"] = "";
                }
                
                if ($event->checkStartDate()){
                    $str_monthAbbr = $event->getMonthAbbr();
                    $str_day = $event->getDayStr();
                    $array_show_events[$count]["calendar_month"] = $str_monthAbbr;
                    $array_show_events[$count]["calendar_day"] = $str_day;
                } else {
                    $array_show_events[$count]["calendar_month"] = "";
                    $array_show_events[$count]["calendar_day"] = "";
                }
                
                $str_date = $event->getDateString(true);
                $str_recurring = $event->getDateStringRecurring();
                
                if ($event->getString("recurring") != "Y") {
                    $array_show_events[$count]["date_string"] = $str_date;
                    $array_show_events[$count]["date_recurring"] = "";
                } else {
                    $array_show_events[$count]["date_string"] = "";
                    $array_show_events[$count]["date_recurring"] = system_showTruncatedText($str_recurring, 45);
                }
                
                $countSpecialItem++;
                
            } else {
                $str_date = $event->getDateString();
                $str_recurring = $event->getDateStringRecurring();
                $array_show_events[$count]["date_string"] = ($event->getString("recurring") != "Y" ? $str_date : $str_recurring);
            }
            
            $array_show_events[$count]["id"]           = htmlspecialchars($event->getNumber("id"));
            $array_show_events[$count]["account_id"]   = $event->getNumber("account_id");
            $array_show_events[$count]["title"]        = $event->getString("title", true);
            
            if (EVENT_SCALABILITY_OPTIMIZATION != "on") {
                $array_show_events[$count]["categories"] = system_itemRelatedCategories($event->getNumber("id"), "event", true);
                $name = socialnetwork_writeLink($event->getNumber("account_id"), "profile", "general_see_profile");
                if ($name) {
                    $array_show_events[$count]["author_string"] = " ".system_showText(LANG_BY)." ".$name;
                }
            }

            if ($lastItemStyle == $numberOfEvents) {
                $itemStyle = "last";
            } elseif ($lastItemStyle == ($specialItem+1)) {
                $itemStyle = "first";
            } else {
                $itemStyle = "";
            }
            $array_show_events[$count]["itemStyle"] = $itemStyle;
            
            $count++;
        }
        
        $ids_report_lote = string_substr($ids_report_lote, 0, -1);
        report_newRecord("event", $ids_report_lote, EVENT_REPORT_SUMMARY_VIEW, true);
	}
?>