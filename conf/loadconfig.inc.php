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
	# * FILE: /conf/loadconfig.inc.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# DEMONSTRATION MODE
	# ----------------------------------------------------------------------------------------------------
	
	/*if (strpos($_SERVER["HTTP_HOST"], "demodirectory") === false) {
		define("DEMO_MODE", 0);
	} else {
		define("DEMO_MODE", 1);
	}
	if (strpos($_SERVER["HTTP_HOST"], "demodirectory.com") === false) {
	        define("DEMO_LIVE_MODE", 0);
	} else {
		define("DEMO_LIVE_MODE", 1);
	}
	if ((strpos($_SERVER["HTTP_HOST"], "arcasolutions.com") === false) && (strpos($_SERVER["HTTP_HOST"], "intranet") === false)) {
		define("DEMO_DEV_MODE", 0);
	} else {
		define("DEMO_DEV_MODE", 1);
	}*/
	
	/* if ($_SERVER["HTTP_HOST"] === '192.168.1.85'){
		define("DEMO_MODE", 1);
		define("DEMO_DEV_MODE", 0);
		define("DEMO_LIVE_MODE", 0);
	} else {
		define("DEMO_MODE", 0);
		define("DEMO_DEV_MODE", 0);
		define("DEMO_LIVE_MODE", 0);
	} */
	
	define("DEMO_DEV_MODE", 0);
	
    # ----------------------------------------------------------------------------------------------------
	# DEFINE EDIRECTORY FOLDER
	# ----------------------------------------------------------------------------------------------------
	if (!defined("EDIRECTORY_FOLDER")) define("EDIRECTORY_FOLDER", "/edirectory");

	# ----------------------------------------------------------------------------------------------------
	# TMP FOLDER PATH DEFINITION
	# ----------------------------------------------------------------------------------------------------
	define("TMP_FOLDER", $_SERVER["DOCUMENT_ROOT"].EDIRECTORY_FOLDER."/custom/tmp");

    
    # ----------------------------------------------------------------------------------------------------
	# LOGS 
	# ----------------------------------------------------------------------------------------------------
    define("ENABLE_LOG", false);
    define("LOG_PATH", $_SERVER["DOCUMENT_ROOT"].EDIRECTORY_FOLDER."/custom/log");
    define("SHOW_REGISTRATION_LOG", true);
    define("ACTIVATION_DEBUG", false);
    define("QUERY_LOG_DB", false); // Save log of queries on DB - SQL_Log
    define("QUERY_LOG_FILE", true);
    define("LOG_SIZE_ROTATE", 1); // Value in MB
    define("ENABLE_CRON_LOG", true);
    define("CRON_LOG_CLEAR_INTERVAL", 7); //days
    
	# ----------------------------------------------------------------------------------------------------
	# DEFINE EDIRECTORY ROOT
	# ----------------------------------------------------------------------------------------------------
	if (!defined("EDIRECTORY_ROOT")) define("EDIRECTORY_ROOT", $_SERVER["DOCUMENT_ROOT"].EDIRECTORY_FOLDER);

	# ----------------------------------------------------------------------------------------------------
	# PHPINI
	# ----------------------------------------------------------------------------------------------------
	include("phpini.inc.php");
    
    # ----------------------------------------------------------------------------------------------------
	# DIRECTORY ALIAS DEFINITIONS
	# ----------------------------------------------------------------------------------------------------
	define("MEMBERS_ALIAS", "members");
	define("SITEMGR_ALIAS", "sitemgr");

	# ----------------------------------------------------------------------------------------------------
	# DOMAIN CONSTANT
	# ----------------------------------------------------------------------------------------------------
	
	include(EDIRECTORY_ROOT."/custom/domain/domain.inc.php");
	
	if (!$_inCron){  
		if ($_SERVER["HTTP_HOST"]){
			session_start();
		}
		
		if(function_exists('mb_strtoupper')){ 
			$host = mb_strtoupper(str_replace("www.", "", $_SERVER["HTTP_HOST"]));
			$host_cookie = str_replace(".", "_", $host);
		}else{ 
			$host = strtoupper(str_replace("www.", "", $_SERVER["HTTP_HOST"]));
			$host_cookie = str_replace(".", "_", $host);
		}
		
		if ($_SERVER["HTTP_HOST"] && !$domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])]) {
			echo "Domain unavailable! Please contact the administrator.";
			exit;
		} else {
			
			if (strpos($_SERVER["PHP_SELF"], SITEMGR_ALIAS)){ 
				if (!in_array($_SESSION[$host."_DOMAIN_ID_SITEMGR"], $domainInfo) || $resetDomainSession) {
					if (!in_array($_COOKIE[$host_cookie."_DOMAIN_ID_SITEMGR"], $domainInfo) || $resetDomainSession) {
						$_SESSION[$host."_DOMAIN_ID_SITEMGR"] = $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])];
						setcookie($host."_DOMAIN_ID_SITEMGR", $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])], time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
					} else {
						$_SESSION[$host."_DOMAIN_ID_SITEMGR"] = $_COOKIE[$host_cookie."_DOMAIN_ID_SITEMGR"];
					}
					define("SELECTED_DOMAIN_ID", $_SESSION[$host."_DOMAIN_ID_SITEMGR"]);
				}
			} else if (strpos ($_SERVER["PHP_SELF"], MEMBERS_ALIAS)){ 
				if (!in_array($_SESSION[$host."_DOMAIN_ID_MEMBERS"], $domainInfo) || $resetDomainSession) {
					if (!in_array($_COOKIE[$host_cookie."_DOMAIN_ID_MEMBERS"], $domainInfo) || $resetDomainSession) {
						$_SESSION[$host."_DOMAIN_ID_MEMBERS"] = $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])];
						setcookie($host."_DOMAIN_ID_MEMBERS", $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])], time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
					} else {
						$_SESSION[$host."_DOMAIN_ID_MEMBERS"] = $_COOKIE[$host_cookie."_DOMAIN_ID_MEMBERS"];
					}
					define("SELECTED_DOMAIN_ID", $_SESSION[$host."_DOMAIN_ID_MEMBERS"]);
					define("URL_DOMAIN_ID", $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])]);
					
				}
			}
			
		}

		if ($_SERVER["HTTP_HOST"]) {
			if (strpos($_SERVER["PHP_SELF"], SITEMGR_ALIAS)){  
				if (!$_SESSION[$host."_DOMAIN_ID_SITEMGR"] || $resetDomainSession) {
					if (!$_COOKIE[$host_cookie."_DOMAIN_ID_SITEMGR"] || $resetDomainSession) {
						$_SESSION[$host."_DOMAIN_ID_SITEMGR"] = $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])];
						setcookie($host."_DOMAIN_ID_SITEMGR", $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])], time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
					} else {
						$_SESSION[$host."_DOMAIN_ID_SITEMGR"] = $_COOKIE[$host_cookie."_DOMAIN_ID_SITEMGR"];
					}
				}
				define("SELECTED_DOMAIN_ID", $_SESSION[$host."_DOMAIN_ID_SITEMGR"]);
			} else if (strpos($_SERVER["PHP_SELF"], MEMBERS_ALIAS)){
				if (!$_SESSION[$host."_DOMAIN_ID_MEMBERS"] || $resetDomainSession) {
					if (!$_COOKIE[$host_cookie."_DOMAIN_ID_MEMBERS"] || $resetDomainSession) {
						$_SESSION[$host."_DOMAIN_ID_MEMBERS"] = $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])];
						setcookie($host."_DOMAIN_ID_MEMBERS", $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])], time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
					} else {
						$_SESSION[$host."_DOMAIN_ID_MEMBERS"] = $_COOKIE[$host_cookie."_DOMAIN_ID_MEMBERS"];
					}
				}
				define("SELECTED_DOMAIN_ID", $_SESSION[$host."_DOMAIN_ID_MEMBERS"]);
				define("URL_DOMAIN_ID", $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])]);
				
			} else { 
				if (!$_SESSION[$host."_DOMAIN_ID"] || $_SESSION[$host."_DOMAIN_ID"] != $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])]) {
					$_SESSION[$host."_DOMAIN_ID"] = $domainInfo[str_replace("www.","",$_SERVER["HTTP_HOST"])];
				}
				define("SELECTED_DOMAIN_ID", $_SESSION[$host."_DOMAIN_ID"]);
			}

		}
		if (strpos($_SERVER["PHP_SELF"], SITEMGR_ALIAS)) {
			setcookie($host."_DOMAIN_ID_TINYMCE_SITEMGR", SELECTED_DOMAIN_ID, time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
			setcookie("SECTION_SITEMGR", "true", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
			setcookie("SECTION_MEMBERS", "", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
		} else if (strpos($_SERVER["PHP_SELF"], MEMBERS_ALIAS)) {
			setcookie($host."_DOMAIN_ID_TINYMCE_MEMBERS", SELECTED_DOMAIN_ID, time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
			setcookie("SECTION_MEMBERS", "true", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
			setcookie("SECTION_SITEMGR", "", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
		}
		unset($domainInfo);
	}
  
    if (file_exists(EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/timezone.inc.php")) {
        include(EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/timezone.inc.php");
    }
	
	
	# ----------------------------------------------------------------------------------------------------
	# INCLUDE GENERAL CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("config.inc.php");
	 
	# ----------------------------------------------------------------------------------------------------
	# PREPARE CONSTANT WITH DOMAIN INFORMATION
	# ----------------------------------------------------------------------------------------------------
	db_ArrayDomainInfo();

	# ----------------------------------------------------------------------------------------------------
	# PREPARE CONSTANT WITH LANGUAGE INFORMATION
	# ----------------------------------------------------------------------------------------------------
	language_constants();

	# ----------------------------------------------------------------------------------------------------
	# PREPARE CONSTANT WITH LEVELS INFORMATION
	# ----------------------------------------------------------------------------------------------------
    if (!$upgradeScript){ 
        system_ListingLevel_Constant();
    }
	# ----------------------------------------------------------------------------------------------------
	# PREPARE CONSTANT WITH SETTING INFORMATION
	# ----------------------------------------------------------------------------------------------------
	setting_constants();
    
    # ----------------------------------------------------------------------------------------------------
	# PREPARE CONSTANT WITH THEME TEMPLATE ID
	# ----------------------------------------------------------------------------------------------------
    if (!$upgradeScript){
        system_getThemeTemplate();
    }
	
	# ----------------------------------------------------------------------------------------------------
	# AUTOMATIC FEATURE
	# MOBILE FEATURE
	# ----------------------------------------------------------------------------------------------------
	// *** AUTOMATIC FEATURE *** (DONT CHANGE THESE LINES)
	if ((strpos($_SERVER["PHP_SELF"], "/".MEMBERS_ALIAS) === false) && (strpos($_SERVER["PHP_SELF"], "/".SITEMGR_ALIAS) === false)) {

		$autoMobileDetect = mobile_enableAutoDetect();
		if ($autoMobileDetect == "y") {

			$isiapp = "n";
			if (strpos($_SERVER["PHP_SELF"], "iapp") !== false) $isiapp = "y";
			$isMacMobile = mobile_isMacMobile();
			if (($isiapp == "y") && ($isMacMobile != "y")) {
				header("Location: ".DEFAULT_URL."");
				exit;
			}

			if ($isiapp != "y") {
				$isMobile = mobile_isMobile();
				if ((MOBILE_FEATURE == "on") && ($isMobile == "y") && !defined("EDIRECTORY_MOBILE")) {
					
                    if (strpos($_SERVER["PHP_SELF"], LISTING_FEATURE_FOLDER."/detail.php") !== false){
                        include(EDIRECTORY_ROOT."/conf/mobile.inc.php");
                        $detailLink = MOBILE_DEFAULT_URL.$_SERVER["REDIRECT_URL"];
                        header("Location: ".$detailLink);
                        exit;
                    } else {
                        include(EDIRECTORY_ROOT."/conf/mobile.inc.php");
                        header("Location: ".DEFAULT_URL."/".EDIRECTORY_MOBILE_LABEL."");
                        exit;
                    }
                    
				} elseif (defined("EDIRECTORY_MOBILE") && (EDIRECTORY_MOBILE == "on") && ((MOBILE_FEATURE != "on") || ($isMobile != "y"))) {
					
					if(string_strpos($_SERVER["SCRIPT_FILENAME"], EDIRECTORY_MOBILE_LABEL."/listingdetail.php") !== false){
                        unset($aux_new_url);
                        $aux_new_url = str_replace("id=","",$_SERVER["QUERY_STRING"]).".html";
						header("Location: ".LISTING_DEFAULT_URL."/".$aux_new_url."");
					}else{
						header("Location: ".DEFAULT_URL."");
					}

					exit;
				}
			}
		}
	}
?>
