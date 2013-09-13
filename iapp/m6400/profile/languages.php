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

	$objLang = new Lang();
	$langs = $objLang->getAll();

	foreach ($langs as $lang) {
 		if ($lang["lang_enabled"] == "y") {
			$xml_output  .= "<entry_language>";
			$xml_output  .= "<language_id>".$lang["id"]."</language_id>";
			$xml_output  .= "<language_name><![CDATA[".$lang["name"]."]]></language_name>";
			//$xml_output	 .= "<language_enabled>".$lang["lang_enabled"]."</language_enabled>";
			$xml_output  .= "</entry_language>\n";
 		}
	}

	$xml_output  .= "</ListingData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";


	echo $xml_output;
?>