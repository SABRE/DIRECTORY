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

	$country_id = (isset($_GET["country_id"]) && $_GET["country_id"]!="") ? $_GET["country_id"] : 1;

	unset($objState);
	//$objState = new LocationState();
	$objState = new Location3();
	$objState->setString('location_1', $country_id);
	$states = $objState->retrieveLocationByLocation(1);

	$xml_output  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	if ((count($states) > 0) && ($states)) {

		$numberOfResultsPage = count($states);
		$item_amount = count($states);

		$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"1\" object=\"Listing\">\n";
		$xml_output  .= "<ObjectData>\n";

		foreach ($states as $state) {
			//echo "<li><a href=\"".EDIRECTORYM_HTTPHOST."/listing/results.php?category_id=".$category["id"]."\">".$category["title".$langIndex]."</a></li>";
			$xml_output  .= "<entry>";
			$xml_output  .= "<listingID>".$state["id"]."</listingID>";
			$xml_output  .= "<listingTitle><![CDATA[".$state["name"]."]]></listingTitle>";

			if ($state["abbreviation"])
				$xml_output  .= "<address><![CDATA[".$state["abbreviation"]."]]></address>";
			else
				$xml_output  .= "<address><![CDATA[".$state["name"]."]]></address>";


			$xml_output  .= "</entry>\n";
		}

	} else {
		$xml_output  .= "<eDirectoryData amount=\"0\">\n";
		$xml_output  .= "<ObjectData>\n";

	}
	$xml_output  .= "</ObjectData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";
	unset($objState);

	echo $xml_output;


?>