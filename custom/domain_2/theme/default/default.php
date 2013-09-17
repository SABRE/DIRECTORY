<?

	/*==================================================================*\
	######################################################################
	#                                                                    #
	# Copyright 2005 Arca Solutions, Inc. All Rights Reserved.           #
	#                                                                    #
	# This file may not be redistributed in whole or part.               #
	# edirectory is licensed on a per-domain basis.                      #
	#                                                                    #
	# ---------------- edirectory IS NOT FREE SOFTWARE ----------------- #
	#                                                                    #
	# http://www.edirectory.com | http://www.edirectory.com/license.html #
	######################################################################
	\*==================================================================*/

    # ----------------------------------------------------------------------------------------------------
	# * FILE: /theme/default/default.php
	# ----------------------------------------------------------------------------------------------------

	$scheme_path = (EDIR_SCHEME != "custom" ? EDIR_SCHEME : "default");

?>

    <? if ($aux_modal_box != "profileLogin") { ?>
        <link href="<?=system_getStylePath("structure.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/structure.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) { ?>
        <link rel="stylesheet" href="<?=DEFAULT_URL?>/scripts/jquery/countdown/jquery.countdown.css?v=1.0" type="text/css" />
    <? } ?>
    
    <? if ((string_strpos($_SERVER['PHP_SELF'], "/".MEMBERS_ALIAS) !== false) && 
        ((string_strpos($_SERVER['PHP_SELF'], "preview.php") === false) || 
        (string_strpos($_SERVER['PHP_SELF'], "invoice.php") === false)) || 
        $loadMembersCss) { ?>
        <link href="<?=system_getStylePath("members.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/members.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?> 
    
    <? if ((string_strpos($_SERVER['PHP_SELF'], "profile/add.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "profile/edit.php") !== false)) { ?>
        <link href="<?=system_getStylePath("members.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/members.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?> 
    
    <? if ((string_strpos($_SERVER['PHP_SELF'], "popup.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "category_detail.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "delete_image.php") !== false)) { ?>
        <link href="<?=system_getStylePath("popup.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/popup.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if (((LOAD_MODULE_CSS_HOME == "on") || 
        (string_strpos($_SERVER['REQUEST_URI'], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) || 
        (string_strpos($_SERVER['REQUEST_URI'], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false)) && (ACTUAL_MODULE_FOLDER != BLOG_FEATURE_FOLDER)) { ?>
        <link href="<?=system_getStylePath("front.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/front.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER || 
        string_strpos($_SERVER['PHP_SELF'], "/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER."/preview.php") !== false) { ?>
        <link href="<?=system_getStylePath("blog.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/blog.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if ((string_strpos($_SERVER['PHP_SELF'], "order_listing.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "order_event.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "order_classified.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "order_article.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "order_banner.php") !== false) || 
        (string_strpos($_SERVER['REQUEST_URI'], ALIAS_CLAIM_URL_DIVISOR."/") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], MEMBERS_ALIAS."/claim/") !== false)) { ?>
        <link href="<?=system_getStylePath("order.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/order.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if (((string_strpos($_SERVER['PHP_SELF'], "favorites") !== false) || 
         (string_strpos($_SERVER['REQUEST_URI'], "results.php") !== false) || 
         (string_strpos($_SERVER['REQUEST_URI'], ALIAS_CATEGORY_URL_DIVISOR."/") !== false) || 
         (string_strpos($_SERVER['REQUEST_URI'], ALIAS_LOCATION_URL_DIVISOR."/") !== false) || 
         (string_strpos($_SERVER['PHP_SELF'], "quicklists.php") !== false) || 
         (string_strpos($_SERVER['REQUEST_URI'], ALIAS_REVIEW_URL_DIVISOR."/") !== false) || 
         (string_strpos($_SERVER['REQUEST_URI'], ALIAS_CHECKIN_URL_DIVISOR."/") !== false) || 
         (string_strpos($_SERVER['REQUEST_URI'], ALIAS_CLAIM_URL_DIVISOR."/") !== false) || 
         (string_strpos($_SERVER['PHP_SELF'], "profile/index.php") !== false)|| 
         (string_strpos($_SERVER['REQUEST_URI'], ACTUAL_MODULE_FOLDER) !== false)) && 
        (ACTUAL_MODULE_FOLDER != BLOG_FEATURE_FOLDER)) { ?>
        <link href="<?=system_getStylePath("results.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/results.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if ((string_strpos($_SERVER['REQUEST_URI'], ".html") !== false) && 
        (defined("ACTUAL_MODULE_FOLDER") && ACTUAL_MODULE_FOLDER != "") &&
        (ACTUAL_MODULE_FOLDER != BLOG_FEATURE_FOLDER)) { ?>
        <link href="<?=system_getStylePath("detail.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/detail.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if ((string_strpos($_SERVER['REQUEST_URI'], ALIAS_ADVERTISE_URL_DIVISOR.".php") !== false || 
         string_strpos($_SERVER['PHP_SELF'], "preview.php")) && 
        ((ACTUAL_MODULE_FOLDER != BLOG_FEATURE_FOLDER))) { ?>
        <link href="<?=system_getStylePath("advertise.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/advertise.css?v=1.0" rel="stylesheet" type="text/css" media="all" />

        <link href="<?=system_getStylePath("results.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/results.css?v=1.0" rel="stylesheet" type="text/css" media="all" />

        <link href="<?=system_getStylePath("detail.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/detail.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>
    
    <? if ((string_strpos($_SERVER['PHP_SELF'], "profile/index.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "profile/edit.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "profile/add.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "account/quicklists.php") !== false) || 
        (string_strpos($_SERVER['PHP_SELF'], "account/reviews.php") !== false)) { ?>
        <link href="<?=system_getStylePath("profile.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
        <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/profile.css?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <? } ?>

    <link href="<?=system_getStylePath("content_custom.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="all" />
    <link href="<?=THEMEFILE_URL;?>/default/schemes/<?=$scheme_path?>/content_custom.css?v=1.0" rel="stylesheet" type="text/css" media="all" />

    <link href="<?=system_getStylePath("print.css", "default");?>?v=1.0" rel="stylesheet" type="text/css" media="print" />

    <?
    setting_get("scheme_custom", $scheme_custom);
    ?>

    <?=($scheme_custom == "on" && !DEMO_LIVE_MODE ? colorscheme_generateDynamicCSS() : "")?>