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
	# * FILE: /frontend/detail_info.php
	# ----------------------------------------------------------------------------------------------------

    if ((ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) || $signUpListing) {
        $moduleMessage = $listingMsg;
        $module_default_url = LISTING_DEFAULT_URL;
        $backtoLabel = system_showText(LANG_LISTING_FEATURE_NAME_PLURAL);
        $signUpListing = false;
    } elseif ((ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) || $signUpClassified) {
        $moduleMessage = $classifiedMsg;
        $module_default_url = CLASSIFIED_DEFAULT_URL;
        $backtoLabel = system_showText(LANG_CLASSIFIED_FEATURE_NAME_PLURAL);
        $signUpClassified = false;
    } elseif ((ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) || $signUpEvent) {
        $moduleMessage = $eventMsg;
        $module_default_url = EVENT_DEFAULT_URL;
        $backtoLabel = system_showText(LANG_EVENT_FEATURE_NAME_PLURAL);
        $signUpEvent = false;
    } elseif ((ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) || $signUpArticle) {
        $moduleMessage = $articleMsg;
        $module_default_url = ARTICLE_DEFAULT_URL;
        $backtoLabel = system_showText(LANG_ARTICLE_FEATURE_NAME_PLURAL);
        $signUpArticle = false;
        $extraClass = "button-back-fix";
    } elseif ((ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER)) {
        $moduleMessage = $promotionMsg;
        $module_default_url = PROMOTION_DEFAULT_URL;
        $backtoLabel = system_showText(LANG_PROMOTION_FEATURE_NAME_PLURAL);
    } elseif ((ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER)) {
        $moduleMessage = $postMsg;
        $module_default_url = BLOG_DEFAULT_URL;
        $backtoLabel = system_showText(LANG_BLOG_FEATURE_NAME_PLURAL);
    }

    if (EXTRA_FIELDS_SIDEBAR && !$moduleMessage && !$hideDetail){ ?>

        <p class="button-back <?=$extraClass?>">
            <a href="<?=$tPreview || !$user ? "javascript: void(0);" : $module_default_url."/"?>" <?=($tPreview || !$user ? "style=\"cursor:default\"" : "")?>><?=system_showText(LANG_BACKTO.$backtoLabel);?></a>
        </p>
    
        <br clear="all" />

    <? } ?>