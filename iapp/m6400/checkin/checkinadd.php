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
	include("../conf/configuration.inc.php");


	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------

	

	unset($checkInObj);
	$checkInObj = new CheckIn();
	
	$checkInObj->setString("item_id", $_POST["item_id"]);
	$checkInObj->setString("member_id", $_POST["account_id"]);
	$checkInObj->setString("ip", $_SERVER["REMOTE_ADDR"]);
	$checkInObj->setString("quick_tip", $_POST["quick_tip"]);
	$checkInObj->setString("checkin_name", $_POST["checkin_name"]);

	$checkInObj->Save();
	

	header("Location:checkin.php?id=".$checkInObj->id);

	
	
?>