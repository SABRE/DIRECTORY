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
	require_once '../classes/GeoLocation.php';

	# ----------------------------------------------------------------------------------------------------
	# DEFINE
	# ----------------------------------------------------------------------------------------------------
	define(MAX_ITEM_PER_PAGE, 20);
	define(MAX_DESC_LEN, 50);

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
	$section = "listing";


	if ($_GET["edirnearby"] == "zipcode") {
		if (zipproximity_getZip5($_GET["zipcode"], $zip5)) {
			if (zipproximity_validateZip5($zip5)) {
				if (zipproximity_getZip5Fields($zip5, $latitude, $longitude)) {
					$thisLatitude = $latitude;
					$thisLongitude = $longitude;

				}
			}
		}
	} else if ($_GET["edirnearby"] == "nearme") {

		$thisLatitude = $_GET["latitude"];
		$thisLongitude = $_GET["longitude"];

	}

	/*
	# ----------------------------------------------------------------------------------------------------
	# Solving the basis point of searches from a city using Google Maps Webservice
	# ----------------------------------------------------------------------------------------------------
	*/
	if($thisLatitude == 0 && $thisLongitude == 0){

		$cityObj = new Location4($pregion_id);
		$stateObj = new Location3($cityObj->location_3);
		$countryObj = new Location1($stateObj->location_1);


		$searchLocation = $cityObj->name.", ".$stateObj->name." - ".$countryObj->name;

		$geo = new GeoLocation($searchLocation);
		$thisLatitude = $geo->latitude;
		$thisLongitude = $geo->longitude;
	}



	$search_lock = false;
	if (LISTING_SCALABILITY_OPTIMIZATION == "on") {
		if (!$_GET["keyword"] && !$_GET["category_id"]) {
			$_GET["id"] = 0;
			$search_lock = true;
		}
	}

	if (!$page) $page = 1;

	$dbObj = db_getDBObject();

	unset($searchReturn);
	//$searchReturn = search_frontListingSearch($_GET, "mobile");
    $searchReturn=search_frontPromotionSearch($_GET,"mobile");

	$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." ".(($searchReturn["where_clause"])?("WHERE ".$searchReturn["where_clause"]):(""))." ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ".(($searchReturn["order_by"])?("ORDER BY ".$searchReturn["order_by"]):(""))." LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE."";

	$result = $dbObj->query($sql);

    if ($result) {

		if ($message_searchresults) echo $message_searchresults;

		$sqlFoundRows = "SELECT count(0) as countRows from ".$searchReturn["from_tables"].(($searchReturn["where_clause"])?(" WHERE ".$searchReturn["where_clause"]):(""));
		$resultFoundRows = $dbObj->query($sqlFoundRows);
		$foundRows = mysql_fetch_row($resultFoundRows);
		$item_total_amount = $foundRows[0];

		$numberOfResultsPage = ceil($item_total_amount/MAX_ITEM_PER_PAGE);

		$item_amount = mysql_num_rows($result);

		if ($item_amount > 0) {
			$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
			//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";
			$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";
			$xml_output  .= "<LocationInfo latitude=\"".$thisLatitude."\" longitude=\"".$thisLongitude."\">";
			$xml_output  .= "</LocationInfo>\n";
			$xml_output  .= "<ObjectData>\n";

			while ($item = mysql_fetch_assoc($result)) {
				$xml_output  .= "<entry>";

				unset($imagePath);
				unset($imageURL);
				unset($hasThumb);
				unset($dealObj);
                
                $dealObj = new Promotion($item["id"]);
                $listingObj = new Listing($dealObj->getListingID());

                // IMAGE
                $hasThumb = false;
				$imageObj = new Image($dealObj->getNumber('thumb_id'));
				if ($imageObj->imageExists()) {
					$imageURL = strtolower(IMAGE_URL . "/".$imageObj->prefix."photo_" . $imageObj->id . "." . $imageObj->type);
					$hasThumb = true;
				}

				$regionObj = new Location4($listingObj->location_4);
				$address = "";
				if ($listingObj->address) {
					$address .= addslashes($listingObj->address.", ");
				}
				if ($regionObj->getString("name")) {
					$address .= addslashes($regionObj->getString("name").", ");
				}
				$address .= addslashes($listingObj->zip_code);

				$xml_output  .= "<dealID>".$item["id"]."</dealID>";
				$xml_output  .= "<latitude>".$listingObj->latitude."</latitude>";
				$xml_output  .= "<longitude>".$listingObj->longitude."</longitude>";
				$xml_output  .= "<regionID>".$listingObj->location_4."</regionID>";
				$xml_output  .= "<regionName><![CDATA[".($regionObj->getString("name"))."]]></regionName>";
				$xml_output  .= "<rawAddress><![CDATA[".($listingObj->address)."]]></rawAddress>";
				$xml_output  .= "<address><![CDATA[".($address)."]]></address>";
				$xml_output  .= "<zipCode>".$listingObj->zip_code."</zipCode>";
				$xml_output  .= "<dealTitle><![CDATA[".($item["name"])."]]></dealTitle>";
				$xml_output  .= "<description><![CDATA[".($item["description"])."]]></description>";
				$xml_output  .= "<mapTunning>".$listingObj->maptuning."</mapTunning>";

				if ($hasThumb) {
					$xml_output  .= "<imageURLString>".$imageURL."</imageURLString>";
				} else {
					if (file_exists(EDIRECTORY_ROOT.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT)) {
						$xml_output  .= "<imageURLString>".DEFAULT_URL.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT."</imageURLString>";
	   				} else {
						$xml_output  .= "<imageURLString>".DEFAULT_URL."/images/bg_noimage.gif</imageURLString>";
	   				}
			    }

                $current = time();
                $visibilityStart = mktime(0, $dealObj->visibility_start, 0, date("m")  , date("d"), date("Y"));
                $visibilityEnd = mktime(0, $dealObj->visibility_end, 0, date("m")  , date("d"), date("Y"));
                //if (  ( ($current > $visibilityStart && $current < $visibilityEnd)  || ($dealObj->visibility_start == 24 && $dealObj->visibility_end == 24)  )  && strtotime($dealObj->end_date." 23:59:59") > strtotime(date('Y-m-d H:i:s'))  ) {
                $xml_output  .= "<promotionRealValue>".$dealObj->realvalue."</promotionRealValue>";
                $xml_output  .= "<promotionDealValue>".$dealObj->dealvalue."</promotionDealValue>";
                $xml_output  .= "<promotionAmount>".$dealObj->amount."</promotionAmount>";
                $xml_output  .= "<promotionConditions><![CDATA[".$dealObj->conditions."]]></promotionConditions>";
                $xml_output  .= "<promotionVisibilityStart>".$dealObj->visibility_start."</promotionVisibilityStart>";
                $xml_output  .= "<promotionVisibilityEnd>".$dealObj->visibility_end."</promotionVisibilityEnd>";
                $xml_output  .= "<promotionStart>".$dealObj->start_date."</promotionStart>";
                $xml_output  .= "<promotionEnd>".$dealObj->end_date." 23:59:59"."</promotionEnd>";
                $promotionDeals=$dealObj->getDealInfo();
                $xml_output  .= "<promotionDeals>".$promotionDeals['sold']."</promotionDeals>";
                //}
            $xml_output  .= "</entry>\n";
			}
		} else {
			$xml_output  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";
			$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";
			$xml_output  .= "<LocationInfo latitude=\"".$thisLatitude."\" longitude=\"".$thisLongitude."\">";
			$xml_output  .= "</LocationInfo>\n";
			$xml_output  .= "<ObjectData>\n";
		}
	}


	$xml_output  .= "</ObjectData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";


	header("Content-type: text/xml");
	print($xml_output);

?>
<?
function m2h($mins) {
            if ($mins < 0) {
                $min = Abs($mins);
            } else {
                $min = $mins;
            }
            $H = Floor($min / 60);
            $M = ($min - ($H * 60)) / 100;
            $hours = $H +  $M;
            if ($mins < 0) {
                $hours = $hours * (-1);
            }
            $expl = explode(".", $hours);
            $H = $expl[0];
            if (empty($expl[1])) {
                $expl[1] = 00;
            }
            $M = $expl[1];
            if (strlen($M) < 2) {
                $M = $M . 0;
            }
            $hours = $H . ":" . $M;
            return $hours;
    }
?>