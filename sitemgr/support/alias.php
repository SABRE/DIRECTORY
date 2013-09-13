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
	# * FILE: /sitemgr/support/alias.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# THIS PAGE IS ONLY USED BY THE SUPPORT
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();

	if (!sess_getSMIdFromSession()){
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
        exit;
	} else {
		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$sql = "SELECT username FROM SMAccount WHERE id = ".sess_getSMIdFromSession();
		$row = mysql_fetch_assoc($dbMain->query($sql));
		if ($row["username"] != "arcalogin"){
			header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
            exit;
		} 
	}
    
    $url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/support/reset.php";
    extract($_GET);
    extract($_POST);
    
    # ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
    $aliasModules = array();
    $aliasModules[0]["name"] = "ALIAS_LISTING_MODULE";
    $aliasModules[0]["label"] = "Listing";
    $aliasModules[0]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_listing_module : ALIAS_LISTING_MODULE);
    $aliasModules[0]["tip"] = NON_SECURE_URL."/<b>".ALIAS_LISTING_MODULE."</b>";
    
    $aliasModules[1]["name"] = "ALIAS_EVENT_MODULE";
    $aliasModules[1]["label"] = "Event";
    $aliasModules[1]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_event_module : ALIAS_EVENT_MODULE);
    $aliasModules[1]["tip"] = NON_SECURE_URL."/<b>".ALIAS_EVENT_MODULE."</b>";
    
    $aliasModules[2]["name"] = "ALIAS_CLASSIFIED_MODULE";
    $aliasModules[2]["label"] = "Classified";
    $aliasModules[2]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_classified_module : ALIAS_CLASSIFIED_MODULE);
    $aliasModules[2]["tip"] = NON_SECURE_URL."/<b>".ALIAS_CLASSIFIED_MODULE."</b>";
    
    $aliasModules[3]["name"] = "ALIAS_ARTICLE_MODULE";
    $aliasModules[3]["label"] = "Article";
    $aliasModules[3]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_article_module : ALIAS_ARTICLE_MODULE);
    $aliasModules[3]["tip"] = NON_SECURE_URL."/<b>".ALIAS_ARTICLE_MODULE."</b>";
    
    $aliasModules[4]["name"] = "ALIAS_PROMOTION_MODULE";
    $aliasModules[4]["label"] = "Promotion";
    $aliasModules[4]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_promotion_module : ALIAS_PROMOTION_MODULE);
    $aliasModules[4]["tip"] = NON_SECURE_URL."/<b>".ALIAS_PROMOTION_MODULE."</b>";
    
    $aliasModules[5]["name"] = "ALIAS_BLOG_MODULE";
    $aliasModules[5]["label"] = "Blog";
    $aliasModules[5]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_blog_module : ALIAS_BLOG_MODULE);
    $aliasModules[5]["tip"] = NON_SECURE_URL."/<b>".ALIAS_BLOG_MODULE."</b>";
    
    $aliasDivisors = array();
    $aliasDivisors[0]["name"] = "ALIAS_CATEGORY_URL_DIVISOR";
    $aliasDivisors[0]["label"] = "Browse by Category";
    $aliasDivisors[0]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_category_url_divisor : ALIAS_CATEGORY_URL_DIVISOR);
    $aliasDivisors[0]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_CATEGORY_URL_DIVISOR."/...</b>";
    
    $aliasDivisors[1]["name"] = "ALIAS_LOCATION_URL_DIVISOR";
    $aliasDivisors[1]["label"] = "Browse by Location";
    $aliasDivisors[1]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_location_url_divisor : ALIAS_LOCATION_URL_DIVISOR);
    $aliasDivisors[1]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_LOCATION_URL_DIVISOR."/...</b>";
    
    $aliasDivisors[2]["name"] = "ALIAS_SHARE_URL_DIVISOR";
    $aliasDivisors[2]["label"] = "Share page";
    $aliasDivisors[2]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_share_url_divisor : ALIAS_SHARE_URL_DIVISOR);
    $aliasDivisors[2]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_SHARE_URL_DIVISOR."</b>/...";
    
    $aliasDivisors[3]["name"] = "ALIAS_CLAIM_URL_DIVISOR";
    $aliasDivisors[3]["label"] = "Claim page";
    $aliasDivisors[3]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_claim_url_divisor : ALIAS_CLAIM_URL_DIVISOR);
    $aliasDivisors[3]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_CLAIM_URL_DIVISOR."</b>/...";
    
    $aliasDivisors[4]["name"] = "ALIAS_REVIEW_URL_DIVISOR";
    $aliasDivisors[4]["label"] = "Reviews page";
    $aliasDivisors[4]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_review_url_divisor : ALIAS_REVIEW_URL_DIVISOR);
    $aliasDivisors[4]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_REVIEW_URL_DIVISOR."</b>/...";
    
    $aliasDivisors[5]["name"] = "ALIAS_CHECKIN_URL_DIVISOR";
    $aliasDivisors[5]["label"] = "Checkins page";
    $aliasDivisors[5]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_checkin_url_divisor : ALIAS_CHECKIN_URL_DIVISOR);
    $aliasDivisors[5]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_CHECKIN_URL_DIVISOR."</b>/...";

    $aliasDivisors[6]["name"] = "ALIAS_BACKLINK_URL_DIVISOR";
    $aliasDivisors[6]["label"] = "Backlink return URL";
    $aliasDivisors[6]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_backlink_url_divisor : ALIAS_BACKLINK_URL_DIVISOR);
    $aliasDivisors[6]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_BACKLINK_URL_DIVISOR."</b>/...";
    
    $aliasDivisors[7]["name"] = "ALIAS_ARCHIVE_URL_DIVISOR";
    $aliasDivisors[7]["label"] = "Blog Archives";
    $aliasDivisors[7]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_archive_url_divisor : ALIAS_ARCHIVE_URL_DIVISOR);
    $aliasDivisors[7]["tip"] = NON_SECURE_URL."/".ALIAS_BLOG_MODULE."/<b>".ALIAS_ARCHIVE_URL_DIVISOR."</b>/...";

    $aliasPages = array();
    $aliasPages[0]["name"] = "ALIAS_ALLCATEGORIES_URL_DIVISOR";
    $aliasPages[0]["label"] = "All categories page";
    $aliasPages[0]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_allcategories_url_divisor : ALIAS_ALLCATEGORIES_URL_DIVISOR);
    $aliasPages[0]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_ALLCATEGORIES_URL_DIVISOR."</b>.php";
    
    $aliasPages[1]["name"] = "ALIAS_ALLLOCATIONS_URL_DIVISOR";
    $aliasPages[1]["label"] = "All locations page";
    $aliasPages[1]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_alllocations_url_divisor : ALIAS_ALLLOCATIONS_URL_DIVISOR);
    $aliasPages[1]["tip"] = NON_SECURE_URL."/".ALIAS_LISTING_MODULE."/<b>".ALIAS_ALLLOCATIONS_URL_DIVISOR."</b>.php";
    
    $aliasPages[2]["name"] = "ALIAS_ADVERTISE_URL_DIVISOR";
    $aliasPages[2]["label"] = "Advertise Page";
    $aliasPages[2]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_advertise_url_divisor : ALIAS_ADVERTISE_URL_DIVISOR);
    $aliasPages[2]["tip"] = NON_SECURE_URL."/<b>".ALIAS_ADVERTISE_URL_DIVISOR."</b>.php";
    
    $aliasPages[3]["name"] = "ALIAS_CONTACTUS_URL_DIVISOR";
    $aliasPages[3]["label"] = "Contact Us Page";
    $aliasPages[3]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_contactus_url_divisor : ALIAS_CONTACTUS_URL_DIVISOR);
    $aliasPages[3]["tip"] = NON_SECURE_URL."/<b>".ALIAS_CONTACTUS_URL_DIVISOR."</b>.php";
    
    $aliasPages[4]["name"] = "ALIAS_FAQ_URL_DIVISOR";
    $aliasPages[4]["label"] = "FAQ Page";
    $aliasPages[4]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_faq_url_divisor : ALIAS_FAQ_URL_DIVISOR);
    $aliasPages[4]["tip"] = NON_SECURE_URL."/<b>".ALIAS_FAQ_URL_DIVISOR."</b>.php";
    
    $aliasPages[5]["name"] = "ALIAS_SITEMAP_URL_DIVISOR";
    $aliasPages[5]["label"] = "Sitemap Page";
    $aliasPages[5]["value"] = ($_SERVER["REQUEST_METHOD"] == "POST" ? $alias_sitemap_url_divisor : ALIAS_SITEMAP_URL_DIVISOR);
    $aliasPages[5]["tip"] = NON_SECURE_URL."/<b>".ALIAS_SITEMAP_URL_DIVISOR."</b>.php";

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        unset($errorArray, $errorMessage);
        
        //Validation
        foreach($aliasModules as $module) {
            if (!${strtolower($module["name"])}) {
                $errorArray[] = "&#149;&nbsp;".$module["label"];
            }
        }
        
        foreach($aliasDivisors as $divisor) {
            if (!${strtolower($divisor["name"])}) {
                $errorArray[] = "&#149;&nbsp;".$divisor["label"];
            }
        }
        
        foreach($aliasPages as $page) {
            if (!${strtolower($page["name"])}) {
                $errorArray[] = "&#149;&nbsp;".$page["label"];
            }
        }
        
        if (is_array($errorArray) && $errorArray[0]) {
            $errorMessage = "<b>".system_showText(LANG_MSG_FIELDS_CONTAIN_ERRORS)."</b><br />".implode("<br />", $errorArray);
        }
        
        if (!$errorMessage) {
            $fileConstPath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/conf/constants.inc.php";
            system_writeConstantsFile($fileConstPath, SELECTED_DOMAIN_ID, $_POST);
            header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/support/alias.php?message=ok");
            exit;
        }
        
    }
    
	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <div id="main-right">
        <div id="top-content">
            <div id="header-content">
                <h1>Config Checker - Alias Options</h1>
            </div>
        </div>

        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

                <? include(INCLUDES_DIR."/tables/table_support_submenu.php"); ?>
                
                <? if ($errorMessage) { ?>
                    <p class="errorMessage"><?=$errorMessage?></p>
                <? } elseif ($_GET["message"] == "ok") { ?>
                    <p class="successMessage">Settings changed!</p>
                <? } ?>
                    
                <form name="configChecker" id="configChecker" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
                    
                    <? include(INCLUDES_DIR."/forms/form_support_alias.php"); ?>
                    
                </form>

            </div>
        </div>

        <div id="bottom-content">
            &nbsp;
        </div>
    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>