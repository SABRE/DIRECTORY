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
	define('MAX_ITEM_PER_PAGE', 20);

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
	
	//Gets the number of the page for generate the results
	$page = $_GET["page"];
	//gets the category id
	$CategoryID = $_GET["catid"] ? $_GET["catid"] : '0';


	//avoid errors if the value for page is zero
	if ((int)$page == 0){
		$page = 1;
	}

	$letter = isset($_GET["pageletter"]) && $_GET["pageletter"]!="" ? $_GET["pageletter"] : "";

	$whereLetter = $letter!="" ? " AND lower(substring(title, 1,1))=lower('".$letter."') " : "";

	$viewAll = 0;//($letter=='') ? 1 : 0;

	unset($item_amount);
	unset($categories);

	$dbCatObj = db_getDBObJect();
	$sql = "SELECT ListingCategory.* FROM ListingCategory WHERE category_id = '{$CategoryID}' ";
	$sql .= $whereLetter . " ORDER BY active_listing DESC LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE;
	//$sql .= "AND lang LIKE ".db_formatString("%".EDIR_LANGUAGE."%") . $whereLetter . " ORDER BY active_listing DESC LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE;
	$result = $dbCatObj->query($sql);

	/* BEGIN FOUNDROWS SECTION */
	$sqlFoundRows = "SELECT count(0) as row_amount FROM ListingCategory WHERE category_id = '{$CategoryID}' ";
	$sqlFoundRows .= $whereLetter;
	//$sqlFoundRows .= " AND lang LIKE ".db_formatString("%".EDIR_LANGUAGE."%") . $whereLetter;
	$resultFoundRows = $dbCatObj->query($sqlFoundRows);
	$foundRows = mysql_fetch_assoc($resultFoundRows);
	$item_total_amount = $foundRows["row_amount"] + $viewAll;


	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
    //$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	if ($result) {
		$numberOfResultsPage = ceil($item_total_amount/MAX_ITEM_PER_PAGE);
		$item_amount = mysql_num_rows($result) + $viewAll;
	}
	else {
		$numberOfResultsPage = $viewAll;
		$item_amount = $viewAll;
	}
	$xml_output_header  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";
	//$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\">\n";
	$xml_output_header  .= "<ObjectData>\n";

	if ($viewAll) {
		unset($catObj);
		$catObj = new ListingCategory($CategoryID);
		$categoryTitle = LANG_LABEL_VIEW_ALL;
		$categoryTitle .= ' - ' . (($catObj->getString("title") <> '') ? $catObj->getString("title", false) : $headerTitle);
		$xml_output_body .= "<entry><subCat>0</subCat><rootCat>{$CategoryID}</rootCat><listingID>{$CategoryID}</listingID><listingTitle><![CDATA[{$categoryTitle}]]></listingTitle></entry>\n";
	}

	if ($result) {

		unset($catObj);
		$catObj = new ListingCategory();

		while ($category = mysql_fetch_assoc($result)) {
			$xml_output_body  .= "<entry>";


			unset($arraySubCat);

			$xml_output_body  .= "<subCat>";

			$arraySubCat = $catObj->retrieveAllSubCatById($category["id"]);
			if($arraySubCat){
				$xml_output_body  .= count($arraySubCat);
			}else{
				$xml_output_body  .= 0;
		}
			$xml_output_body  .= "</subCat>";

			if($CategoryID){
				$xml_output_body  .= "<rootCat>".$catObj->findRootCategoryId($CategoryID)."</rootCat>";;
			}else{
				$xml_output_body  .= "<rootCat>0</rootCat>";;
			}

			$xml_output_body  .= "<listingID>".$category["id"]."</listingID>";
			$xml_output_body  .= "<listingTitle><![CDATA[".$category["title"]."]]></listingTitle>";
			$xml_output_body  .= "</entry>\n";
		}

	}
	$xml_output_footer  .= "</ObjectData>\n";
	$xml_output_footer  .= "</eDirectoryData>\n";
	//$xml_output_footer  .="</feed>";


	$xml_output .= $xml_output_header;
	$xml_output .= $xml_output_rootCat;
	$xml_output .= $xml_output_body;
	$xml_output .= $xml_output_footer;

	echo $xml_output;
?>