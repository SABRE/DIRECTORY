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
	define('MAX_DESC_LEN', 100);

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# QUERY STRING
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORYM_DOCUMENTROOT."/query_string.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	$section = "event";
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_EVENTRESULTS;
	$languageButton = false;
	$homeButton = false;
	$searchButton = true;
	$searchButtonLink = EDIRECTORYM_HTTPHOST."/".$section."/main.php";

	$search_lock = false;
	if (EVENT_SCALABILITY_OPTIMIZATION == "on") {
		if (!$_GET["keyword"] && !$_GET["category_id"]) {
			$_GET["id"] = 0;
			$search_lock = true;
		}
	}

	$page = (isset($_GET["page"]) ? $_GET["page"] : 1);

	
	$dbObj = db_getDBObject();

	unset($searchReturn);
	$searchReturn = search_frontEventSearch($_GET, "mobile");
	$sql = "SELECT SQL_CALC_FOUND_ROWS ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." ".(($searchReturn["where_clause"])?("WHERE ".$searchReturn["where_clause"]):(""))." ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE."";
	$result = $dbObj->query($sql);

	$xml_result  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_result  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";	
	$xml_result  .= "<eDirectoryData amount=\"_AMOUNT_\" numberOfPages=\"_NUMBER_OF_PAGES_\" actualPage=\"".$page."\" object=\"Event\" >\n";
	//$xml_result  .= "<eDirectoryData amount=\"_AMOUNT_\" numberOfPages=\"_NUMBER_OF_PAGES_\" actualPage=\"".$page."\">\n";
	$xml_result  .= "<ObjectData>\n";
	
	if ($result) {
		
		$sqlFoundRows = "SELECT FOUND_ROWS()";
		$resultFoundRows = $dbObj->query($sqlFoundRows);
		$foundRows = mysql_fetch_row($resultFoundRows);
		$item_total_amount = $foundRows[0];

		$item_amount = mysql_num_rows($result);
		
		if ($item_amount > 0) {

			$level = new EventLevel();

			$review = new Review();
			
			while ($event = mysql_fetch_assoc($result)) {
				
				$hasThumb = false;
				
				$imageObj = new Image($event["thumb_id"]);
				
				if ($imageObj->imageExists()) {
					
					$imageURL = strtolower(IMAGE_URL . "/".$imageObj->prefix."photo_" . $imageObj->id . "." . $imageObj->type);
					$hasThumb = true;
					
				}
				
				
			if ($event["start_date"] == $event["end_date"]) $str_date = format_date($event["start_date"], DEFAULT_DATE_FORMAT, "date");
			else $str_date = format_date($event["start_date"], DEFAULT_DATE_FORMAT, "date")." - ".format_date($event["end_date"], DEFAULT_DATE_FORMAT, "date");
		
			$str_time = "";
			if ($event["has_start_time"] == "y") {
				$startTimeStr = explode(":", $event["start_time"]);
				if (CLOCK_TYPE == '24') {
					$start_time_hour = $startTimeStr[0];
				} elseif (CLOCK_TYPE == '12') {
					if ($startTimeStr[0] > "12") {
						$start_time_hour = $startTimeStr[0] - 12;
						$start_time_am_pm = "pm";
					} elseif ($startTimeStr[0] == "12") {
						$start_time_hour = 12;
						$start_time_am_pm = "pm";
					} elseif ($startTimeStr[0] == "00") {
						$start_time_hour = 12;
						$start_time_am_pm = "am";
					} else {
						$start_time_hour = $startTimeStr[0];
						$start_time_am_pm = "am";
					}
				}
				if ($start_time_hour < 10) $start_time_hour = "0".($start_time_hour+0);
				$start_time_min = $startTimeStr[1];
				$str_time .= $start_time_hour.":".$start_time_min." ".$start_time_am_pm;
			} else {
				$str_time .= "No Info";
			}
			$str_time .= " - ";
			if ($event["has_end_time"] == "y") {
				$endTimeStr = explode(":", $event["end_time"]);
				if (CLOCK_TYPE == '24') {
					$end_time_hour = $endTimeStr[0];
				} elseif (CLOCK_TYPE == '12') {
					if ($endTimeStr[0] > "12") {
						$end_time_hour = $endTimeStr[0] - 12;
						$end_time_am_pm = "pm";
					} elseif ($endTimeStr[0] == "12") {
						$end_time_hour = 12;
						$end_time_am_pm = "pm";
					} elseif ($endTimeStr[0] == "00") {
						$end_time_hour = 12;
						$end_time_am_pm = "am";
					} else {
						$end_time_hour = $endTimeStr[0];
						$end_time_am_pm = "am";
					}
				}
				if ($end_time_hour < 10) $end_time_hour = "0".($end_time_hour+0);
				$end_time_min = $endTimeStr[1];
				$str_time .= $end_time_hour.":".$end_time_min." ".$end_time_am_pm;
			} else {
				$str_time .= "No Info";
			}
			if (($event["has_start_time"] == "n") && ($event["has_end_time"] == "n")) {
				$str_time = "";
			}
				
				
				$xml_result .= "<entry>";
				$xml_result .= "<eventId>".$event["id"]."</eventId>";
				$xml_result .= "<title><![CDATA[".$event["title"]."]]></title>";				
				$xml_result .= "<startDate>".$event["start_date"]."</startDate>";
				$xml_result .= "<endDate>".$event["end_date"]."</endDate>";
				$xml_result .= "<startTime>".$event["start_time"]."</startTime>";
				$xml_result .= "<endTime>".$event["end_time"]."</endTime>";
				$xml_result .= "<location><![CDATA[".$event["location"]."]]></location>";
				$xml_result .= "<address><![CDATA[".$event["address"]."]]></address>";
				
				
				if ($event["state_id"]) {
					if (!$stateArray[$event["state_id"]]) {
						$sqlState = "SELECT name FROM Location_State WHERE id = ".$event["state_id"]."";
						$resultState = $dbObj->query($sqlState);
						if ($resultState) {
							if ($state = mysql_fetch_assoc($resultState)) {
								$xml_result .= "<state><![CDATA[".$state["name"]."]]></state>";
							}
						}
					}
				}
				
				if ($event["region_id"]) {
					if (!$regionArray[$event["region_id"]]) {
						$sqlRegion = "SELECT name FROM Location_Region WHERE id = ".$event["region_id"]."";
						$resultRegion = $dbObj->query($sqlRegion);
						if ($resultRegion) {
							if ($region = mysql_fetch_assoc($resultRegion)) {
								$xml_result .= "<city><![CDATA[".$region["name"]."]]></city>";
							}
						}
					}
				}
				
				
				$xml_result .= "<zipCode><![CDATA[".$event["zip_code"]."]]></zipCode>";
				$xml_result .= "<site><![CDATA[".$event["url"]."]]></site>";
				$xml_result .= "<email><![CDATA[".$event["email"]."]]></email>";
				$xml_result .= "<phone><![CDATA[".$event["phone"]."]]></phone>";
				
				$old_level = 60 - $event["level"];
				$xml_result .= "<level>".$old_level."</level>";
				$xml_result .= "<contact><![CDATA[".$event["contact_name"]."]]></contact>";
				if ($hasThumb) {
					$xml_result  .= "<imageUrl>".$imageURL."</imageUrl>";	
				} else {		
								
					if (file_exists(EDIRECTORY_ROOT.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT)) {
						$xml_result  .= "<imageUrl>".DEFAULT_URL.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT."</imageUrl>";			
	   				} else {
						$xml_result  .= "<imageUrl>".DEFAULT_URL."/images/bg_noimage.gif</imageUrl>";	   					
	   				}	
			    }					
//				$xml_result  .= "<imageUrl>". ($hasThumb ? $imageURL : IMAGE_URL."/../content_files/noimage.gif")."</imageUrl>";	

				$xml_result .= "<summary><![CDATA[".nl2br($event["description"])."]]></summary>";
				$xml_result .= "<description><![CDATA[".nl2br(($event["long_description"]))."]]></description>";
				
				$xml_result .= "<latitude>".$event["latitude"]."</latitude>";
				$xml_result .= "<longitude>".$event["longitude"]."</longitude>";
				
				$xml_result .= "</entry>\n";
			}
			
		}
	}

	$xml_result  .= "</ObjectData>\n";
	$xml_result  .= "</eDirectoryData>\n";
	//$xml_result  .="</feed>";
	
	$xml_result = str_replace("_AMOUNT_", $item_amount, $xml_result);
	$xml_result = str_replace("_NUMBER_OF_PAGES_", ceil($item_total_amount/MAX_ITEM_PER_PAGE), $xml_result);
	print($xml_result);

	?>

