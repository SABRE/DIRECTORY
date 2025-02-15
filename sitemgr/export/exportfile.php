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
	# * FILE: /sitemgr/export/exportfile.php
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
	
	if ($_GET["export_type"] == "listing"){
		$item_scalability = LISTING_SCALABILITY_OPTIMIZATION;
	} elseif($_GET["export_type"] == "event"){
		$item_scalability = EVENT_SCALABILITY_OPTIMIZATION;
	} else {
		$item_scalability = LISTING_SCALABILITY_OPTIMIZATION;
	}

	/*
	 * Zip file to download
	 */
	if (($item_scalability == "on") && ($_GET["type"]=="csv")){
		$zip_filename = str_replace(".csv",".zip",$_GET["filename"]);

		$zipObj = new zipGenerator();

		$file_name = $_GET["filename"];

		if (file_exists(EDIRECTORY_ROOT.IMPORT_FOLDER_RELATIVE_PATH."/".$file_name)) {
			$fileContents = file_get_contents(EDIRECTORY_ROOT.IMPORT_FOLDER_RELATIVE_PATH."/".$file_name);
			$zipObj->addFile($fileContents, $file_name);
		} else {
			$error = "cannot open <$file_name>";
		}

		$fileName = EDIRECTORY_ROOT.IMPORT_FOLDER_RELATIVE_PATH."/".$zip_filename;
		$fd = fopen ($fileName, "wb");
		$out = fwrite ($fd, $zipObj->getZippedfile());
		fclose ($fd);

		$zipObj->forceDownload($fileName);

	}else{
		$zip_filename = $_GET["filename"];
		$zipObj = new zipGenerator();
		$zipObj->forceDownload(EDIRECTORY_ROOT.IMPORT_FOLDER_RELATIVE_PATH."/".$zip_filename);
	}
?>