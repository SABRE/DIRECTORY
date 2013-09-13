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
	# * FILE: /frontend/detailview.php
	# ----------------------------------------------------------------------------------------------------
    if(ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER){
        $module = "listing";
        $moduleMsg = $listingMsg;
    } elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
        $module = "classified";
        $moduleMsg = $classifiedMsg;
    } elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
        $module = "event";
        $moduleMsg = $eventMsg;
    } elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
        $module = "article";
        $moduleMsg = $articleMsg;
    } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
        $module = "promotion";
        $moduleMsg = $promotionMsg;
    } elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
        $module = "blog";
        $moduleMsg = $postMsg;
    }
    $includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	if (sess_validateSessionItens($module, "see_detail")) {
		if (!$moduleMsg) {
			$user = true;
		                //include(INCLUDES_DIR."/views/view_".$module."_detail.php");
                        include($includeUrl."includes/views/view_".$module."_detail.php");
                        
		} else {
			echo "<p class=\"errorMessage mg-0\">".$moduleMsg."</p>";
		}
	} else {
		$hideDetail = true;
	}
?>