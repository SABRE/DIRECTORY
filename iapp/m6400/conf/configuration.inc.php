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
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	if (file_exists("../../conf/loadconfig.inc.php")){
        include("../../conf/loadconfig.inc.php");
    }else{
        include("../../../conf/loadconfig.inc.php");
    }
//die(3);
	# ----------------------------------------------------------------------------------------------------
	# CONSTANTS
	# ----------------------------------------------------------------------------------------------------
	define(EDIRECTORYM_VERSION, "m.6.4.00");
	define(EDIRECTORYM_DOCUMENTROOT, EDIRECTORY_ROOT."/iapp/".str_replace(".", "", EDIRECTORYM_VERSION));
	define(EDIRECTORYM_HTTPHOST, DEFAULT_URL."/iapp/".str_replace(".", "", EDIRECTORYM_VERSION));

	
	if (file_exists(EDIRECTORYM_DOCUMENTROOT."/lang/".$_COOKIE["edir_language"].".php")) {
		include(EDIRECTORYM_DOCUMENTROOT."/lang/".$_COOKIE["edir_language"].".php");
	} else {
		include(EDIRECTORYM_DOCUMENTROOT."/lang/en_us.php");
	}
	

?>
