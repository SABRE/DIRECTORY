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
	header("Content-type: text/xml");
	$section = "listing";
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_LISTINGHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";


	unset($objCountry);
	//$objCountry = new LocationCountry();
	$objCountry = new Location1();
	$countries = $objCountry->retrieveAllLocation();

	$xml_output  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	if ((count($countries) > 0) && ($countries)) {

		$numberOfResultsPage = count($countries);
		$item_amount = count($countries);

		$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"1\"  object=\"Listing\" >\n";
		$xml_output  .= "<ObjectData>\n";

		foreach ($countries as $country) {
			//echo "<li><a href=\"".EDIRECTORYM_HTTPHOST."/listing/results.php?category_id=".$category["id"]."\">".$category["title".$langIndex]."</a></li>";
			$xml_output  .= "<entry>";

			$xml_output  .= "<listingID>".$country["id"]."</listingID>";
			$xml_output  .= "<listingTitle><![CDATA[".$country["name"]."]]></listingTitle>";

			$xml_output  .= "</entry>\n";
		}

	} else {
		$xml_output  .= "<eDirectoryData amount=\"0\">\n";
		$xml_output  .= "<ObjectData>\n";

	}
	$xml_output  .= "</ObjectData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";
	unset($objCountry);

	echo $xml_output;


?>