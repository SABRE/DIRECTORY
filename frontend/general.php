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
	# * FILE: /frontend/general.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------	
	$generalPageItemPath = "";
	if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) { 
		$generalPageItemPath = LISTING_FEATURE_FOLDER."/";
        $moduleFolder = LISTING_FEATURE_FOLDER;
        $module_name = "listing";
        $page = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_LISTING_MODULE."/", "", $_SERVER["REQUEST_URI"]);
	} elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
		$generalPageItemPath = PROMOTION_DEFAULT_URL."/";
        $moduleFolder = PROMOTION_FEATURE_FOLDER;
        $module_name = "promotion";
        $page = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_PROMOTION_MODULE."/", "", $_SERVER["REQUEST_URI"]);
	} elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
		$generalPageItemPath = str_replace(NON_SECURE_URL, "", EVENT_DEFAULT_URL)."/";
        $moduleFolder = EVENT_FEATURE_FOLDER;
        $module_name = "event";
        $page = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_EVENT_MODULE."/", "", $_SERVER["REQUEST_URI"]);
	} elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
		$generalPageItemPath = str_replace(NON_SECURE_URL, "", CLASSIFIED_DEFAULT_URL)."/";
        $moduleFolder = CLASSIFIED_FEATURE_FOLDER;
        $module_name = "classified";
        $page = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_CLASSIFIED_MODULE."/", "", $_SERVER["REQUEST_URI"]);
	} elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
		$generalPageItemPath = str_replace(NON_SECURE_URL, "", ARTICLE_DEFAULT_URL)."/";
        $moduleFolder = ARTICLE_FEATURE_FOLDER;
        $page = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_ARTICLE_MODULE."/", "", $_SERVER["REQUEST_URI"]);
	} elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
		$generalPageItemPath = str_replace(NON_SECURE_URL, "", BLOG_DEFAULT_URL)."/";
        $moduleFolder = BLOG_FEATURE_FOLDER;
        $page = string_replace_once(EDIRECTORY_FOLDER."/".ALIAS_BLOG_MODULE."/", "", $_SERVER["REQUEST_URI"]);
	}	
    
    if (string_strpos($page, ".php") === false) {
        $page = string_substr($page, 0, string_strpos($page, "/"));
    }

    if (!$page) {
        $page = $_SERVER["PHP_SELF"];
        $page = string_substr($page, string_strrpos($page, "/")+1);
        $page = string_substr($page, 0, string_strrpos($page, "."));
    }
   
    if ($page == ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") {	
        $allCategories = true;
        include(system_getFrontendPath("browsebycategory.php", "frontend", false, $moduleFolder));
    } elseif ($page == ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") {
        $allCategories = true;
        include(EDIRECTORY_ROOT."/alllocationscontent.php");
    } elseif ($page == ALIAS_REVIEW_URL_DIVISOR || $page == ALIAS_CHECKIN_URL_DIVISOR) {
         include(system_getFrontendPath("commentscontent.php", "frontend", false, $moduleFolder));
    } elseif ($generalPage == "faq") {
		include(EDIRECTORY_ROOT."/includes/forms/form_faq.php");
    } else { 
			
		if (file_exists(EDIRECTORY_ROOT."/".$generalPageItemPath.$page."content.php")) {
            include(EDIRECTORY_ROOT."/".$generalPageItemPath.$page."content.php");
        } elseif (file_exists(EDIRECTORY_ROOT."/".$generalPage."content.php")) {
            include(EDIRECTORY_ROOT."/".$generalPage."content.php");
        }
    }