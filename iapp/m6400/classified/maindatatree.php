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

	define('MAX_ITEM_PER_PAGE', 20);

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	$section = "classified";
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_CLASSIFIEDHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";
	//avoid errors if the value for page is zero
	$page = (isset($_GET["page"]) ? $_GET["page"] : 1);


	unset($catObj);
	$catObj = new ClassifiedCategory();
	unset($classifieds);
	if (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
		$dbClassifiedObj = db_getDBObJect();
		$sql = "SELECT * FROM ClassifiedCategory WHERE category_id = '0' ORDER BY title LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE."";
		$result = $dbClassifiedObj->query($sql);
		$classifieds = false;
		while ($row = mysql_fetch_assoc($result)) $classifieds[] = $row;
		unset($dbClassifiedObj);
	} else {
		$classifieds = $catObj->retrieveAllCategories(EDIR_LANGUAGE);
	}
	
	$dbClassifiedObj = db_getDBObJect();
	$sql= "SELECT count(0) as numberOfResults FROM ClassifiedCategory WHERE category_id = '0' ";
	$result = $dbClassifiedObj->query($sql);
	$cat = mysql_fetch_assoc($result);
	$totalAmount = $cat["numberOfResults"] + 1 /* All */;
	$numberOfPages = ceil($totalAmount / MAX_ITEM_PER_PAGE);
	unset($dbClassifiedObj);

	$xml_output = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_result = "<feed>";

	//$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";

	$xml_result .= "\r\n<eDirectoryData amount=\"".$totalAmount."\" numberOfPages=\"".$numberOfPages."\" actualPage=\"".$page."\" object=\"Classified\" >";
	$xml_result .= "\r\n<ObjectData>";
	$xml_result .= "\r\n<entry>";
	$xml_result .= "<classifiedId>0</classifiedId>";
	$xml_result .= "<title><![CDATA[" . LANG_LABEL_VIEW_ALL . " - {$headerTitle}]]></title>";
	$xml_result .= "</entry>";

	if ($classifieds) {
		foreach ($classifieds as $classified) {
			$xml_result .=  "\r\n<entry>";
			//echo "<li><span rel=\"".EDIRECTORYM_HTTPHOST."/article/results.php?category_id=".$event["id"]."\">".$event["title".$langIndex]."</span></li>";
			$xml_result .= "<classifiedId>".$classified["id"]."</classifiedId>";
			$xml_result .= "<title><![CDATA[" . $classified["title"] . "]]></title>";
			$xml_result .=  "</entry>";
		}
	}

	$xml_result .= "\r\n</ObjectData>";
	$xml_result .= "\r\n</eDirectoryData>";
	//$xml_result .= "\r\n</feed>";

	header("Content-type: text/xml");
	print($xml_result);
?>