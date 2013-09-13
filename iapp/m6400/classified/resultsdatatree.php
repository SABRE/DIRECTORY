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
	$section = "classified";
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_CLASSIFIEDRESULTS;
	$languageButton = false;
	$homeButton = false;
	$searchButton = true;
	$searchButtonLink = EDIRECTORYM_HTTPHOST."/".$section."/main.php";

	$search_lock = false;
	if (CLASSIFIED_SCALABILITY_OPTIMIZATION == "on") {
		if (!$_GET["keyword"] && !$_GET["category_id"]) {
			$_GET["id"] = 0;
			$search_lock = true;
		}
	}

	$page = (isset($_GET["page"]) ? $_GET["page"] : 1);

	$dbObj = db_getDBObject();

	unset($searchReturn);
	$searchReturn = search_frontClassifiedSearch($_GET, "mobile");
	$sql = "SELECT SQL_CALC_FOUND_ROWS ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." ".(($searchReturn["where_clause"])?("WHERE ".$searchReturn["where_clause"]):(""))." ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE."";
	$result = $dbObj->query($sql);

	$xml_result  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_result  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";	
	
	$xml_result  .= "<eDirectoryData amount=\"_AMOUNT_\" numberOfPages=\"_NUMBER_OF_PAGES_\" actualPage=\"".$page."\" object=\"Classified\" >\n";
	$xml_result  .= "<ObjectData>\n";
	
	if ($result) {
		
		$sqlFoundRows = "SELECT FOUND_ROWS()";
		$resultFoundRows = $dbObj->query($sqlFoundRows);
		$foundRows = mysql_fetch_row($resultFoundRows);
		$item_total_amount = $foundRows[0];

		$item_amount = mysql_num_rows($result);
		
		if ($item_amount > 0) {

			$level = new ClassifiedLevel();

			$review = new Review();
			
			while ($classified = mysql_fetch_assoc($result)) {
				

				
				$hasThumb = false;
				$imageObj = new Image($classified["thumb_id"]);
				if ($imageObj->imageExists()) {
					$imageURL = strtolower(IMAGE_URL . "/".$imageObj->prefix."photo_" . $imageObj->id . "." . $imageObj->type);
					$hasThumb = true;
				}

				
				$xml_result .= "<entry>";
				$xml_result .= "<classifiedId>".$classified["id"]."</classifiedId>";
				$xml_result .= "<title><![CDATA[".$classified["title"]."]]></title>";				
				$xml_result .= "<address><![CDATA[".$classified["address"]."]]></address>";
				
				$xml_result .= "<zipCode><![CDATA[".$classified["zip_code"]."]]></zipCode>";
				$xml_result .= "<site><![CDATA[".$classified["url"]."]]></site>";
				$xml_result .= "<email><![CDATA[".$classified["email"]."]]></email>";
				$xml_result .= "<phone><![CDATA[".$classified["phone"]."]]></phone>";
				
				$old_level = 60 - $classified["level"];
				$xml_result .= "<level>".$old_level."</level>";
				$xml_result .= "<contact><![CDATA[".$classified["contact_name"]."]]></contact>";


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

				$xml_result .= "<summary><![CDATA[".nl2br($classified["summarydesc"])."]]></summary>";
				$xml_result .= "<description><![CDATA[".nl2br(($classified["detaildesc"]))."]]></description>";
				
				$xml_result .= "<price>".$classified["classified_price"]."</price>";
				
				$xml_result .= "<latitude>".$classified["latitude"]."</latitude>";
				$xml_result .= "<longitude>".$classified["longitude"]."</longitude>";
				
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

