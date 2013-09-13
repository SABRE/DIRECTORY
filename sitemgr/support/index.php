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
	# * FILE: /sitemgr/support/index.php
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
    
    $url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/support/index.php";
    extract($_GET);
    extract($_POST);

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if ($rewriteFile == "constants") {
            
            $fileConstPath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/conf/constants.inc.php";
            
            $constValues = array();
            $constValues["event_feature"] = EVENT_FEATURE;
            $constValues["banner_feature"] = BANNER_FEATURE;
            $constValues["classified_feature"] = CLASSIFIED_FEATURE;
            $constValues["article_feature"] = ARTICLE_FEATURE;
            $constValues["promotion_feature"] = PROMOTION_FEATURE;
            $constValues["blog_feature"] = BLOG_FEATURE;
            $constValues["zipproximity_feature"] = ZIPCODE_PROXIMITY;
            $constValues["custominvoice_feature"] = CUSTOM_INVOICE_FEATURE;
            $constValues["claim_feature"] = CLAIM_FEATURE;
            $constValues["listingtemplate_feature"] = LISTINGTEMPLATE_FEATURE;
            $constValues["mobile_feature"] = MOBILE_FEATURE;
            $constValues["multilanguage_feature"] = MULTILANGUAGE_FEATURE;
            $constValues["maintenance_feature"] = MAINTENANCE_FEATURE;
            $constValues["sitemap_feature"] = SITEMAP_FEATURE;
            $constValues["branded_print"] = BRANDED_PRINT;
            $constValues["paymentsystem_feature"] = PAYMENTSYSTEM_FEATURE;
            $constValues["name"] = EDIRECTORY_TITLE;
            $constValues["geoip_feature"] = $const_geoip == "y" ? "on" : "off";
            $constValues["inactive_banner"] = SHOW_INACTIVE_BANNER;
            $constValues["cachefull_feature"] = $const_cache_full == "y" ? "on" : "off";
            $constValues["cachefull_zlib"] = "on";
            $constValues["cachefull_verbose"] = "off";
            $constValues["cachefull_queries"] = "off";
            $constValues["cachefull_comments"] = "off";
            $constValues["members"] = "on";
            $constValues["disabled"] = "on";
            $constValues["cachefull_refreshL"] = "on";
            $constValues["cachefull_refreshP"] = "on";
            $constValues["cachefull_refreshC"] = "on";
            $constValues["cachefull_refreshE"] = "on";
            $constValues["cachefull_refreshA"] = "on";
            $constValues["cachepartial_feature"] = $const_cache_partial == "y" ? "on" : "off";
            $constValues["search_booleanmode"] = $const_front_search == "y" ? "on" : "off";
            $constValues["free_ratio"] = $const_free_ratio == "y" ? "on" : "off";
            $constValues["jpg_as_png"] = $const_jpg_as_png == "y" ? "on" : "off";;
            $constValues["resize_images"] = $const_free_ratio == "y" ? "off" : "on";
            $constValues["sitemap_www"] = "off";
            
            if (CACHE_FULL_FEATURE == "on"){
                cachefull_forceExpiration();
            }
            
            if (!system_writeConstantsFile($fileConstPath, SELECTED_DOMAIN_ID, $constValues)) {
                $errorFolder = true;
            }
            
        } elseif ($rewriteFile == "scalability") {
            
            $fileScalPath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/conf/scalability.inc.php";
            
            $scalValues = array();
            $scalValues["listing_scalability"] = $scalability_listing == "y" ? "on" : "off";
            $scalValues["promotion_scalability"] = $scalability_promotion == "y" ? "on" : "off";
            $scalValues["promotion_auto_complete"] = $scalability_promotion_autocomplete? "on" : "off";
            $scalValues["event_scalability"] = $scalability_event == "y" ? "on" : "off";
            $scalValues["banner_scalability"] = $scalability_banner == "y" ? "on" : "off";
            $scalValues["classified_scalability"] = $scalability_classified == "y" ? "on" : "off";
            $scalValues["article_scalability"] = $scalability_article == "y" ? "on" : "off";
            $scalValues["blog_scalability"] = $scalability_blog == "y" ? "on" : "off";
            $scalValues["listingcateg_scalability"] = $scalability_listingcateg == "y" ? "on" : "off";
            $scalValues["eventcateg_scalability"] = $scalability_eventcateg == "y" ? "on" : "off";
            $scalValues["classifiedcateg_scalability"] = $scalability_classifiedcateg == "y" ? "on" : "off";
            $scalValues["articlecateg_scalability"] = $scalability_articlecateg == "y" ? "on" : "off";
            $scalValues["blogcateg_scalability"] = $scalability_blogcateg == "y" ? "on" : "off";
            
            if (CACHE_FULL_FEATURE == "on"){
                cachefull_forceExpiration();
            }
            
			if (!system_writeScalabilityFile($fileScalPath, SELECTED_DOMAIN_ID, $scalValues)) {
				$errorFolder = true;
			}
            
        } elseif ($rewriteFile == "timezone") {
            
            if ($opt_timezone != "Default Time Zone") {
                $fileConfigPath = EDIRECTORY_ROOT.'/custom/domain_'.SELECTED_DOMAIN_ID.'/timezone.inc.php';
                if (!$fileConfig = fopen($fileConfigPath, 'w+')) {
                    $errorFolder = 'error';

                } else {
                    $buffer  = "<?php".PHP_EOL."ini_set('date.timezone', '$opt_timezone');".PHP_EOL;
                    if (!fwrite($fileConfig, $buffer, strlen($buffer))) {
                        $errorFolder = 'error';
                    }
                }
            } else {
                @unlink(EDIRECTORY_ROOT.'/custom/domain_'.SELECTED_DOMAIN_ID.'/timezone.inc.php');
            }
        } elseif ($rewriteFile == "defaultsearch") {
            if (!setting_set("default_search_option", $default_search_option))
				if (!setting_new("default_search_option", $default_search_option))
					$error = true;
        }
        
        if ($errorFolder) {
            $errorMessage = "Error trying to rewrite file. Please, check the permissions from /custom folder.";
        } else {
            header("Location: ".$url_redirect."?message=ok");
            exit;
        }
        
    }
    
    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    $myUID = getmyuid();
    if (function_exists("posix_getuid")) {
        $ownerUID = posix_getuid();
    }
    $rightPerm = "777";
    if ($myUID == $ownerUID) { //suPHP
        $rightPerm = " < 777";
    }
    
    $customPerm = (int)system_checkPerm(EDIRECTORY_ROOT."/custom");
    $binPerm = (int)system_checkPerm(EDIRECTORY_ROOT."/bin");
    
    if (($rightPerm == "777" && $customPerm == 777) || ($rightPerm == " < 777" && $customPerm < 777)) {
        $styleCustom = "style = \"color: green\"";
    } else{
        $styleCustom = "style = \"color: red\"";
    }
    
    if (($rightPerm == "777" && $binPerm == 777) || ($rightPerm == " < 777" && $binPerm < 777)) {
        $styleBin = "style = \"color: green\"";
    } else{
        $styleBin = "style = \"color: red\"";
    }
    
    $arrayHtacces = array();
    $arrayHtaccesMissing = array();
    $arrayHtacces[] = EDIRECTORY_ROOT."/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/classes/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/conf/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/content/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/cron/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/functions/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/includes/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/isapirewrite/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/mobile/.htaccess";
    $arrayHtacces[] = EDIRECTORY_ROOT."/profile/.htaccess";
    
    foreach ($arrayHtacces as $htFile) {
        if (!file_exists($htFile)) {
            $arrayHtaccesMissing[] = $htFile;
        }
    }

    //Timezone
    $zones = timezone_identifiers_list();
    $timeZone = ini_get("date.timezone");
    if (!$timeZone) { 
        $timeZone = "Default Time Zone";
    }
    array_unshift($zones, "Default Time Zone");
    $timeZoneDropdown = html_selectBox("opt_timezone", $zones, $zones, $timeZone);
    
    //Default search
    setting_get("default_search_option", $default_search_option);
    if (!$default_search_option) {
        $default_search_option = "anyword";
    }
    
    $searchOptionsName = array();
    $searchOptionsValue = array();
    
    $searchOptionsName[] = "Exact Match";
    $searchOptionsName[] = "Any Word";
    $searchOptionsName[] = "All Words";
    
    $searchOptionsValue[] = "exactmatch";
    $searchOptionsValue[] = "anyword";
    $searchOptionsValue[] = "allwords";
    
    $defaultSearchDropdown = html_selectBox("default_search_option", $searchOptionsName, $searchOptionsValue, $default_search_option);

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <script type="text/javascript">
        function JS_submit(value) {
            $("#rewriteFile").attr("value", value);
            document.configChecker.submit();
        }
    </script>

    <div id="main-right">
        <div id="top-content">
            <div id="header-content">
                <h1>Config Checker - System Settings</h1>
            </div>
        </div>

        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

                <? include(INCLUDES_DIR."/tables/table_support_submenu.php"); ?>

                <br class="clear" />
                
                <? if ($errorMessage) { ?>
                    <p class="errorMessage"><?=$errorMessage?></p>
                <? } elseif ($_GET["message"] == "ok") { ?>
                    <p class="successMessage">Settings changed!</p>
                <? } ?>

                <? include(INCLUDES_DIR."/forms/form_support_system.php"); ?>

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