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
	header("Content-type: text/xml");
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_LISTINGHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";



	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	$xml_output  .= "<eDirectoryData>\n";
	$xml_output  .= "<ListingData>\n";

	$xml_output  .= "<entry>";



	if (sess_authenticateAccount($_POST["username"], $_POST["password"], $authmessage))
	{
		$xml_output  .= "<authenticateAccount>true</authenticateAccount>";
		$Account = db_getFromDB("account", "username", db_formatString($_POST["username"]));

		//$Profile = new Profile($Account->id);

		$Contact = new Contact($Account->id);

		$xml_output  .= "<id>".$Account->id."</id>";
		$xml_output  .= "<username>".$Account->username."</username>";

		$xml_output  .= "<name>".$Contact->first_name." ".$Contact->last_name."</name>";
		$xml_output  .= "<first_name>".$Contact->first_name."</first_name>";
		$xml_output  .= "<last_name>".$Contact->last_name."</last_name>";
		$xml_output  .= "<email>".$Contact->email."</email>";
		$xml_output  .= "<location>".$Contact->city.", ".$Contact->state."</location>";
		$xml_output  .= "<ip>".$_SERVER["REMOTE_ADDR"]."</ip>";
	}
	else
	{
		$xml_output  .= "<authenticateAccount>false</authenticateAccount>";
		$xml_output  .= "<authmessage>".$authmessage."</authmessage>";
	}


	$xml_output  .= "</entry>\n";

	$xml_output  .= "</ListingData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";


	echo $xml_output;


?>