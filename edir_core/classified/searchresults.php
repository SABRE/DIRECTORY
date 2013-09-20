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
	# * FILE: /edir_core/classified/searchresults.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	if (sess_validateSessionItens("classified", "see_results")) {
		
		$show_results = true;
		
		$mapObj = new GoogleSettings(GOOGLE_MAPS_STATUS);
		
		$searchConditionDivisor = " / ";
		
		$levelObj = new ClassifiedLevel(true);
		
		$user = true;
		$str_search = "";
		
		if ($keyword){ 
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $keyword;
		}
		
		if ($where && !$dist_loc) {
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $where;
		}
		if ($where && $dist_loc) {
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $where.(($dist_loc)?(" (".$dist_loc." ".ZIPCODE_UNIT_LABEL_PLURAL.")"):(""));
		}
                 /*Code is Add on 20-09-2013 For friendly URL*/
		if(empty($where) && empty($dist_loc) && !empty($location_3)){
                   $search_category = new Location3($location_3);
                   if($search_category->getString("name"))
                    {
                        if(!empty($str_search))
                            $str_search .= $searchConditionDivisor;
                        $str_search .= $search_category->getString("name");
                    }
                   
                }
                /*Code End on 20-09-2013*/
                
		if ($category_id) {
			$search_category = new ClassifiedCategory($category_id);
			/*Code is added on 26-06-2013*/
				$_GET['category_main_id'] = $search_category->category_id;
				if(!empty($_GET['category_main_id'])){
					$search_main_category = new ClassifiedCategory($_GET['category_main_id']);
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
				$str_search .= $search_category->getString("title", true, 60);
			}
		}
		if ($zip) {
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $zip.(($dist)?(" (".$dist." ".ZIPCODE_UNIT_LABEL_PLURAL.")"):(""));
		}
	} else {
		$hideResults = true;
	}
?>