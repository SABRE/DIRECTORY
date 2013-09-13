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
	# * FILE: /includes/code/join.php
	# ----------------------------------------------------------------------------------------------------

    if (string_strpos(ACTUAL_MODULE_FOLDER, LISTING_FEATURE_FOLDER) !== false) {
        $advertiseLabel = system_showText(LANG_BUTTON_ADDLISTING);
        $advertisePath = "?listing";
    } elseif (string_strpos(ACTUAL_MODULE_FOLDER, CLASSIFIED_FEATURE_FOLDER) !== false) {
        $advertiseLabel = system_showText(LANG_BUTTON_ADDCLASSIFIED);
        $advertisePath = "?classified";
    } elseif (string_strpos(ACTUAL_MODULE_FOLDER, EVENT_FEATURE_FOLDER) !== false) {
        $advertiseLabel = system_showText(LANG_BUTTON_ADDEVENT);
        $advertisePath = "?event";
    } elseif (string_strpos(ACTUAL_MODULE_FOLDER, ARTICLE_FEATURE_FOLDER) !== false) {
        $advertiseLabel = system_showText(LANG_BUTTON_ADDARTICLE);
        $advertisePath = "?article";
    } elseif (string_strpos(ACTUAL_MODULE_FOLDER, PROMOTION_FEATURE_FOLDER) !== false) {
        $advertiseLabel = system_showText(LANG_BUTTON_ADDPROMOTION);
        $advertisePath = "?listing";
    } elseif (string_strpos(ACTUAL_MODULE_FOLDER, BLOG_FEATURE_FOLDER) !== false) {
        $advertiseLabel = "";
        $advertisePath = "";
    } else {
        $advertiseLabel = system_showText(LANG_MENU_ADVERTISE);
        $advertisePath = "";
    }