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
	$section = "article";
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_ARTICLEHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";
	$page = (isset($_GET["page"]) ? $_GET["page"] : 1);


	unset($catObj);
	$catObj = new ArticleCategory();
	unset($categories);
	if (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == "on") {
		$dbCatObj = db_getDBObJect();
		$sql = "SELECT * FROM ArticleCategory WHERE category_id = '0' ORDER BY title LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE."";
		$result = $dbCatObj->query($sql);
		$categories = false;
		while ($row = mysql_fetch_assoc($result)) $categories[] = $row;
		unset($dbCatObj);
	} else {
		$categories = $catObj->retrieveAllCategories(EDIR_LANGUAGE);
	}
	
	$dbCatObj = db_getDBObJect();
	$sql= "SELECT count(0) as numberOfResults FROM ArticleCategory WHERE category_id = '0'";
	$result = $dbCatObj->query($sql);
	$cat = mysql_fetch_assoc($result);
	$totalAmount = $cat["numberOfResults"] + 1 /* All */;
	$numberOfPages = ceil($totalAmount / MAX_ITEM_PER_PAGE);
	unset($dbCatObj);

	$xml_result = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_result = "<feed>";
	//$xml_result .= "\r\n<eDirectoryData amount=\"".$totalAmount."\" numberOfPages=\"".$numberOfPages."\" actualPage=\"".$page."\">";
	//$xml_result  .= "<eDirectoryData amount=\"_AMOUNT_\" numberOfPages=\"_NUMBER_OF_PAGES_\" actualPage=\"".$page."\" object=\"Article\" >\n";
	$xml_result  .= "<eDirectoryData amount=\"".$totalAmount."\" numberOfPages=\"".$numberOfPages."\" actualPage=\"".$page."\" object=\"Article\" >\n";


	$xml_result .= "\r\n<ObjectData>";
	$xml_result .=  "\r\n<entry>";
	$xml_result .= "<articleId>0</articleId>";
	$xml_result .= "<articleTitle><![CDATA[" . LANG_LABEL_VIEW_ALL . " - {$headerTitle}]]></articleTitle>";
	$xml_result .=  "</entry>";

	if ($categories) {
		foreach ($categories as $category) {
			$xml_result .=  "\r\n<entry>";
			$xml_result .= "<articleId>".$category["id"]."</articleId>";
			$xml_result .= "<articleTitle><![CDATA[" . $category["title"] . "]]></articleTitle>";
			$xml_result .=  "</entry>";
		}
	}

	$xml_result .= "\r\n</ObjectData>";
	$xml_result .= "\r\n</eDirectoryData>";
	//$xml_result .= "\r\n</feed>";

	header("Content-type: text/xml");
	print($xml_result);
?>