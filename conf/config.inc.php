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
	# * FILE: /conf/config.inc.php
	# ----------------------------------------------------------------------------------------------------
	
	if (!defined("DEFAULT_DB")) {
                
		# ----------------------------------------------------------------------------------------------------
		# EDIRECTORY ADMINISTRATOR EMAIL
		# ----------------------------------------------------------------------------------------------------
		define("EDIR_ADMIN_EMAIL", "ashish.agarwal@octalsoftware.net");
		define("EDIR_SUPPORT_EMAIL", "shreyas@octalsoftware.net");

		# ----------------------------------------------------------------------------------------------------
		# DATABASE CONNECTION PARAMETERS
		# ----------------------------------------------------------------------------------------------------
                define("DEFAULT_DB",         "DIRECTORYDB");
		define('_DIRECTORYDB_HOST',  "localhost");
                define('_DIRECTORYDB_USER',  "edirectory");
                define('_DIRECTORYDB_PASS',  "edirectory");
                define('_DIRECTORYDB_NAME',  "edirectory_9700");
		define("_DIRECTORYDB_EMAIL", EDIR_ADMIN_EMAIL);
		if ( (defined('DEMO_DEV_MODE') && DEMO_DEV_MODE) || isset($_SERVER["HTTP_HOST"])) {
			define("_DIRECTORYDB_DEBUG", "display");
		} else {
			define("_DIRECTORYDB_DEBUG", "hide");
		}

		define("DB_NAME_PREFIX", "");

		define("MYSQL_TIMEOUT", 10); // Seconds information to each connection of Connection Pool
        }

	if (!$_inCron || !empty($_inCronCheck)) { 
		# ----------------------------------------------------------------------------------------------------
		# SEARCH WORD LENGTH
		# ----------------------------------------------------------------------------------------------------
		define("FT_MIN_WORD_LEN", "4");

		# ----------------------------------------------------------------------------------------------------
		# DEFINE DEFAULT URL
		# ----------------------------------------------------------------------------------------------------
		if ((!$_SERVER["HTTPS"]) || ($_SERVER["HTTPS"] == "off")) {
			define("HTTPS_MODE", "off");
			if (!defined("DEFAULT_URL")) define("DEFAULT_URL", "http://".$_SERVER["HTTP_HOST"].EDIRECTORY_FOLDER);
		} else {
			define("HTTPS_MODE", "on");
			if (!defined("DEFAULT_URL")) define("DEFAULT_URL", "https://".$_SERVER["HTTP_HOST"].EDIRECTORY_FOLDER);
		}

		# ----------------------------------------------------------------------------------------------------
		# SECURE URL
		# ----------------------------------------------------------------------------------------------------
		define("SECURE_URL", "https://".$_SERVER["HTTP_HOST"].EDIRECTORY_FOLDER);

		# ----------------------------------------------------------------------------------------------------
		# NON_SECURE_URL
		# ----------------------------------------------------------------------------------------------------
		define("NON_SECURE_URL", "http://".$_SERVER["HTTP_HOST"].EDIRECTORY_FOLDER);
        	
		/*
		 * Create session to force second DB
		 */
		define("FORCE_SECOND", true);
		
		# ----------------------------------------------------------------------------------------------------
		# INCLUDE GLOBAL INCLUDES
		# ----------------------------------------------------------------------------------------------------
		
		include(EDIRECTORY_ROOT."/conf/includes.inc.php");
	}
?>