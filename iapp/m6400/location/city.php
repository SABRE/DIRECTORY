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

	$state_id = (isset($_GET["state_id"]) && $_GET["state_id"]!="") ? $_GET["state_id"] : 1;

	unset($objCity);
	//$objCity = new LocationRegion();
	$objCity = new Location4();
	$objCity->setString("location_3", $state_id);
	$objCity->state_id = $state_id;
	$cities = $objCity->retrieveLocationByLocation(3);

	$xml_output  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	if ((count($cities) > 0) && ($cities)) {

		$numberOfResultsPage = count($cities);
		$item_amount = count($cities);

		$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"1\" object=\"Listing\">\n";
		$xml_output  .= "<ObjectData>\n";

		unset($xmlcount);
		$xmlcount = 1;
		foreach ($cities as $city) {

			if($xmlcount == 2000) break;

			if (isset($_GET['letter']) && strtolower($city["name"]{0})!=strtolower($_GET['letter'])) continue;

			$xml_output  .= "<entry>";
			$xml_output  .= "<listingID>".$city["id"]."</listingID>";
			$xml_output  .= "<listingTitle><![CDATA[".$city["name"]."]]></listingTitle>";
			$xml_output  .= "</entry>\n";
			$xmlcount++;
		}

	} else {
		$xml_output  .= "<eDirectoryData amount=\"0\">\n";
		$xml_output  .= "<ObjectData>\n";

	}
	$xml_output  .= "</ObjectData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";
	unset($objCity);

	echo $xml_output;


?>