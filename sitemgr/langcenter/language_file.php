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
	# * FILE: /sitemgr/langcenter/language_file.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

    if (!DEMO_LIVE_MODE) {
        $filenamePath = language_getFilePath($_GET["language_id"], false, false, ($_GET["language_area"] == "sitemgr" ? true : false), $_GET["domain_id"], false);
        $fileName = $_GET["language_id"].($_GET["language_area"] == "sitemgr" ? "_sitemgr" : "").".php";

        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: ".gmdate("D,d M YH:i:s")." GMT");
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: text/comma-separated-values");
        header("Content-Type: text/comma-separated-values; charset=utf-8", TRUE);
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Description: PHP Generated XLS Generator");
        @readfile("$filenamePath");
    }

?>