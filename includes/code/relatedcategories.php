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
	# * FILE: /includes/code/relatedcategories.php
	# ----------------------------------------------------------------------------------------------------

    if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) {
        $module = "listing";
        $categTable = "ListingCategory";
        $moduleScalability = LISTINGCATEGORY_SCALABILITY_OPTIMIZATION;
    } elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
        $module = "classified";
        $categTable = "ClassifiedCategory";
        $moduleScalability = CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION;
    } elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
        $module = "event";
        $categTable = "EventCategory";
        $moduleScalability = EVENTCATEGORY_SCALABILITY_OPTIMIZATION;
    } elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
        $module = "article";
        $categTable = "ArticleCategory";
        $moduleScalability = ARTICLECATEGORY_SCALABILITY_OPTIMIZATION;
    } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
        $module = "promotion";
        $categTable = "ListingCategory";
        $moduleScalability = LISTINGCATEGORY_SCALABILITY_OPTIMIZATION;
    } elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
        $module = "blog";
        $categTable = "BlogCategory";
        $moduleScalability = BLOGCATEGORY_SCALABILITY_OPTIMIZATION;
    }

	unset($related_categories);
	unset($category_tree);

	if ($keyword) {

		$search_for_keyword = str_replace("\\", "", $keyword);
		$search_for_keyword_fields[] = "title";
		$search_for_keyword_fields[] = "keywords";
		$where_clause = search_getSQLFullTextSearch($search_for_keyword, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, "anyword", $order_by_keyword_score2, "keyword_score2");

		if ($moduleScalability == "on") {
			$sql = "SELECT * FROM $categTable WHERE category_id = 0 AND enabled = 'y' AND ".$where_clause."";
		} else {
			$sql = "SELECT * FROM $categTable WHERE enabled = 'y' AND ".$where_clause."";
		}

		unset($search_for_keyword);
		unset($search_for_keyword_fields);
		unset($order_by_keyword_score);
		unset($order_by_keyword_score2);
		unset($where_clause);

		$dbObj = db_getDBObject();
		$rs = $dbObj->query($sql);
		while ($row = mysql_fetch_assoc($rs)) {
			$related_categories[] = new $categTable($row["id"]);
		}

	}

	if ($related_categories) {
		foreach ($related_categories as $categoryObj) $arr_full_path[] = $categoryObj->getFullPath();
		$category_tree = system_generateCategoryTree($related_categories, $arr_full_path, $module, $user);
	}
?>