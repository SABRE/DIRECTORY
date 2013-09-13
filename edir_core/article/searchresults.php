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
	# * FILE: /edir_core/article/searchresults.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	if (sess_validateSessionItens("article", "see_results")) {
		$user = true;
		$show_results = true;
		
		$searchConditionDivisor = " / ";
		
		$str_search = "";
		
		if ($keyword) {
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $keyword;
		}

		if ($where){
			if(!empty($str_search))
				$str_search .= $searchConditionDivisor;
			$str_search .= $where;
		}

		if ($category_id) {
			$search_category = new ArticleCategory($category_id);
			/*Code is added on 26-06-2013*/
				$_GET['category_main_id'] = $search_category->category_id;
				if(!empty($_GET['category_main_id'])){
					$search_main_category = new ArticleCategory($_GET['category_main_id']);
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
	} else {
		$hideResults = true;
	}
?>