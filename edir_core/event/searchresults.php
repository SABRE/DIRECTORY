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
	# * FILE: /edir_core/event/searchresults.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	if (sess_validateSessionItens("event", "see_results")) {

		$show_results = true;
		
		$mapObj = new GoogleSettings(GOOGLE_MAPS_STATUS);
		$searchConditionDivisor = " / ";
		$user = true;
		$str_search = "";
		if ($_GET["this_date"]) {
			$ts_time = mktime(0,0,0,(int)string_substr($_GET["this_date"],4,2),(int)string_substr($_GET["this_date"],6,2),(int)string_substr($_GET["this_date"],0,4));
		} else {
			$ts_time = mktime("0,0,0,".date("m,d,Y"));
		}
		if (!$_GET["month"]){
			$str_search = system_showText(LANG_SEARCHRESULTS_DATE)." <strong>".system_showDate(LANG_STRINGDATE_YEARANDMONTHANDDAY, $ts_time)."</strong>";
		}else{
			$str_search = system_showText(LANG_SEARCHRESULTS_DATE)." <strong>".system_showDate(LANG_STRINGDATE_YEARANDMONTH, $ts_time)."</strong>";
		}
		if (!$_GET["month"] && !$_GET["this_date"]) {
			$str_search = "";
		}
		if ($keyword){
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $keyword;
		}
		if ($where && !$dist_loc){
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $where;
		}
		if ($where && $dist_loc){
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $where.(($dist_loc)?(" (".$dist_loc." ".ZIPCODE_UNIT_LABEL_PLURAL.")"):(""));
		}
		if ($category_id) {
			$search_category = new EventCategory($category_id);
			/*Code is added on 26-06-2013*/
				$_GET['category_main_id'] = $search_category->category_id;
				if(!empty($_GET['category_main_id'])){
					$search_main_category = new EventCategory($_GET['category_main_id']);
					if($search_main_category->getString("title")){
						if(!empty($str_search))
							$str_search .= $searchConditionDivisor;
						$str_search .= $search_main_category->getString("title", true, 60);
					}
				}
			/*Code End on 26-06-2013*/
			if ($search_category->getString("title")) {
				if(!empty($str_search))
					$str_search .= $searchConditionDivisor;
				$str_search .= $search_category->getString("title", true, 55);
			}
		}
		if ($zip) {
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $zip;
		}
	} else {
		$hideResults = true;
	}
?>