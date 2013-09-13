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
	# DEFINE
	# ----------------------------------------------------------------------------------------------------
	define(MAX_ITEM_PER_PAGE, 20);

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	header("Content-type: application/json");
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_LISTINGHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";



	$xml_output  = "{\n";

	if (sess_authenticateAccount($_POST["username"], $_POST["password"], $authmessage))
	{
		$xml_output  .= "\"authenticateAccount\": \"true\",\n";
		$Account = db_getFromDB("account", "username", db_formatString($_POST["username"]));

		//$Profile = new Profile($Account->id);

		$Contact = new Contact($Account->id);

		$xml_output  .= '"id":"'.$Account->id."\",\n";
		$xml_output  .= '"username":"'.$Account->username."\",\n";
		$xml_output  .= '"name":"'.$Contact->first_name." ".$Contact->last_name."\",\n";
		$xml_output  .= '"first_name":"'.$Contact->first_name."\",\n";
		$xml_output  .= '"last_name":"'.$Contact->last_name."\",\n";
		$xml_output  .= '"email":"'.$Contact->email."\",\n";
		$xml_output  .= '"location":"'.$Contact->city.", ".$Contact->state."\",\n";
		$xml_output  .= '"ip":"'.$_SERVER["REMOTE_ADDR"]."\"\n";
	}
	else
	{
		$xml_output  .= '"authenticateAccount":"false",';
		$xml_output  .= '"authmessage":"'.$authmessage."\"\n";
	}


	$xml_output  .= "\n}";


	echo $xml_output;


?>