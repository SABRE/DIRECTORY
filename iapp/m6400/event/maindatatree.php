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
	$headerTitle = LANG_M_EVENTHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";
	$page = (isset($_GET["page"]) ? $_GET["page"] : 1);


	unset($catObj);
	$catObj = new EventCategory();
	unset($events);
	if (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
		$dbEvtObj = db_getDBObJect();
		$sql = "SELECT * FROM EventCategory WHERE category_id = '0' ORDER BY title LIMIT 20";
		$result = $dbEvtObj->query($sql);
		$events = false;
		while ($row = mysql_fetch_assoc($result)) $events[] = $row;
		unset($dbEvtObj);
	} else {
		$events = $catObj->retrieveAllCategories(EDIR_LANGUAGE);
	}
	
	$dbEvtObj = db_getDBObJect();
	$sql= "SELECT count(0) as numberOfResults FROM EventCategory WHERE category_id = '0'";
	$result = $dbEvtObj->query($sql);
	$cat = mysql_fetch_assoc($result);
	$totalAmount = $cat["numberOfResults"] + 1 /* All */;
	$numberOfPages = ceil($totalAmount / MAX_ITEM_PER_PAGE);
	unset($dbEvtObj);

	$xml_result = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_result = "<feed>";
	//$xml_result .= "\r\n<eDirectoryData amount=\"".$totalAmount."\" numberOfPages=\"".$numberOfPages."\" actualPage=\"".$page."\">";
	$xml_result .= "\r\n<eDirectoryData amount=\"".$totalAmount."\" numberOfPages=\"".$numberOfPages."\" actualPage=\"".$page."\" object=\"Event\" >";

	$xml_result .= "\r\n<ObjectData>";
	$xml_result .= "\r\n<entry>";
	$xml_result .= "<eventId>0</eventId>";
	$xml_result .= "<title><![CDATA[" . LANG_LABEL_VIEW_ALL . " - {$headerTitle}]]></title>";
	$xml_result .= "</entry>";

	if ($events) {
		foreach ($events as $event) {
			$xml_result .=  "\r\n<entry>";
			//echo "<li><span rel=\"".EDIRECTORYM_HTTPHOST."/article/results.php?category_id=".$event["id"]."\">".$event["title".$langIndex]."</span></li>";
			$xml_result .= "<eventId>".$event["id"]."</eventId>";
			$xml_result .= "<title><![CDATA[" . $event["title"] . "]]></title>";
			$xml_result .=  "</entry>";
		}
	}

	$xml_result .= "\r\n</ObjectData>";
	$xml_result .= "\r\n</eDirectoryData>";
	//$xml_result .= "\r\n</feed>";

	header("Content-type: text/xml");
	print($xml_result);
?>