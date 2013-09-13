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

	//Gets the number of the page for generate the results
	$page = (isset($_GET["page"]) ? $_GET["page"] : 1);

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");


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

		$cityObj = new Location4($region_id);
		$stateObj = new Location3($cityObj->location_3);
		$countryObj = new Location1($stateObj->location_1);


		$searchLocation = $cityObj->name.", ".$stateObj->name." - ".$countryObj->name;

		$geo = new GeoLocation($searchLocation);
		$thisLatitude = $geo->latitude;
		$thisLongitude = $geo->longitude;
	}



	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	$section = "listing";
	header("Content-type: text/xml");

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	# $whereValidList = " ((maptuning IS NOT NULL AND maptuning != '') OR (latitude <> 0 AND longitude <> 0)) ";
	$whereValidList = " (latitude <> 0 AND longitude <> 0) ";

	if($thisLatitude == 0 && $thisLongitude == 0){

		unset($xml_output);
		$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";

		$xml_output  .= "<eDirectoryData amount=\"0\" numberOfPages=\"0\" actualPage=\"0\" object=\"Listing\" >\n";
		$xml_output  .= "<LocationInfo latitude=\"0\" longitude=\"0\">";
		$xml_output  .= "</LocationInfo>\n";
		$xml_output  .= "<ObjectData>\n";


		$xml_output  .= "</ObjectData>\n";
		$xml_output  .= "</eDirectoryData>\n";

		echo $xml_output;

		die();
	}

	if ($_GET["keyword"] == LANG_M_KEYWORD) {
		$keywordSQL = "";
	} else {
		$keywordSQL = $_GET["keyword"];
	}


	$latitudeSQL = $thisLatitude;
	$longitudeSQL = $thisLongitude;
	$distanceSQL = $_GET["edirNearbyDist"];

	// 2 km   = zoom 13
	// 2 mile = zoom 13
	// 4 km   = zomm 12
	// 4 mile = zomm 12
	// 6 km   = zoom 12
	// 6 mile = zoom 11
	$thisZoom = 12;
	if ((ZIPCODE_UNIT == "mile") && ($_GET["edirNearbyDist"] == 6)) $thisZoom = 11;
	elseif ($_GET["edirNearbyDist"] == 2) $thisZoom = 13;

	$constMile = 0.014473204925797298063067594227;
	$constKm   = 0.008993232600237922265686778139;
	if (ZIPCODE_UNIT == "mile") $constDist = $constMile;
	elseif (ZIPCODE_UNIT == "km") $constDist = $constKm;
	$HighLatitude = $latitudeSQL + $distanceSQL * $constDist;
	$LowLatitude = $latitudeSQL - $distanceSQL * $constDist;
	$HighLongitude = $longitudeSQL + $distanceSQL * $constDist;
	$LowLongitude = $longitudeSQL - $distanceSQL * $constDist;
	$whereZipCodeProximity = "";
	$whereZipCodeProximity .= "((";
	$whereZipCodeProximity .= "latitude <= ".$HighLatitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "latitude >= ".$LowLatitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "longitude <= ".$HighLongitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "longitude >= ".$LowLongitude;

	if ($_GET["edirnearby"] == "zipcode") {
		$whereZipCodeProximity .= " AND ";
		$whereZipCodeProximity .= "zip5 != '0'";
		$whereZipCodeProximity .= " AND ";
		$whereZipCodeProximity .= "zip5 != ''";
	}


	/* This part of the code splits the maptuning value using the comma character.
	   It expects that maptuning value has a following format:
	   		0.000000000, 0.000000000
	   		OR
	   		0.000000000,0.000000000

	 *
	$whereZipCodeProximity .= ") or (";
	$whereZipCodeProximity .= "trim(substring_index(maptuning, ',',1)) <= ".$HighLatitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "trim(substring_index(maptuning, ',',1)) >= ".$LowLatitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "trim(substring_index(maptuning, ',',-1)) <= ".$HighLongitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "trim(substring_index(maptuning, ',',-1)) >= ".$LowLongitude;
	/**/


	if ($_GET["edirnearby"] == "zipcode") {
		$whereZipCodeProximity .= " AND ";
		$whereZipCodeProximity .= "zip5 != '0'";
		$whereZipCodeProximity .= " AND ";
		$whereZipCodeProximity .= "zip5 != ''";
	}

	$whereZipCodeProximity .= "))";

	if (ZIPCODE_UNIT == "mile") {
		$order_by_zipcode_score = "SQRT(POW((69.1 * (".$latitudeSQL." - latitude)), 2) + POW((53.0 * (".$longitudeSQL." - longitude)), 2)) AS zipcode_score";
	} elseif (ZIPCODE_UNIT == "km") {
		$order_by_zipcode_score = "SQRT(POW((69.1 * (".$latitudeSQL." - latitude)), 2) + POW((53.0 * (".$longitudeSQL." - longitude)), 2)) * 1.609344 AS zipcode_score";
	}

	unset($items);
	$dbObj = db_getDBObject();
	$sql = "";
	$sqlWhereKeyword = "";
	$order_by_keyword_score = "";

	if ($keywordSQL) {
		$search_for_keyword_fields[] = "fulltextsearch_keyword";
		$sqlWhereKeyword = search_getSQLFullTextSearch($keywordSQL, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, "anyword", $na);
		$message_searchresults = LANG_M_SEARCHRESULTSKEYWORD." <strong>".$_GET["keyword"]."</strong>";
	}

	if (($_GET["edirnearby"] != "zipcode") && ($country_id && $state_id && $region_id)) {

		$sql_location[] = "Listing_Summary.location_1 = ".$country_id."";
		$sql_location[] = "Listing_Summary.location_3 = ".$state_id."";
		$sql_location[] = "Listing_Summary.location_4 = ".$region_id."";

		$sqlWhereLocation = "(".implode(" AND ", $sql_location).")";
	}


	$sql .= " SELECT * ";
	if ($order_by_keyword_score) $sql .= " , ".$order_by_keyword_score." ";
	$sql .= " , ".$order_by_zipcode_score." ";
	$sql .= " FROM Listing_Summary WHERE status = 'A' ";
	$sql .= " AND ".$whereValidList;
	if ($sqlWhereKeyword) $sql .= " AND ".$sqlWhereKeyword." ";
	//if ($sqlWhereLocation) $sql .= " AND ".$sqlWhereLocation." ";
	$sql .= " AND ".$whereZipCodeProximity." ";
	$sql .= " ORDER BY level ";
	if ($order_by_keyword_score) $sql .= " , keyword_score DESC ";
	$sql .= " , zipcode_score LIMIT ".(($page-1)*MAX_ITEM_PER_PAGE).",".MAX_ITEM_PER_PAGE."";


	// echo $sql;
	// die();

	$result = $dbObj->query($sql);
	if ($result) {
		$item_amount = mysql_num_rows($result);
		if ($item_amount > 0) {
			while ($listing = mysql_fetch_assoc($result)) {
				$items[] = $listing;
			}
		}
	}


	unset($sql);
	$sql .= " SELECT count(0) as row_amount ";
	$sql .= " FROM Listing_Summary WHERE status = 'A' ";
	$sql .= " AND ".$whereValidList;
	if ($sqlWhereKeyword) $sql .= " AND ".$sqlWhereKeyword." ";
	if ($sqlWhereLocation) $sql .= " AND ".$sqlWhereLocation." ";
	$sql .= " AND ".$whereZipCodeProximity." ";


	$resultFoundRows = $dbObj->query($sql);
	$foundRows = mysql_fetch_assoc($resultFoundRows);
	$item_total_amount = $foundRows["row_amount"];

	$numberOfResultsPage = ceil($item_total_amount/MAX_ITEM_PER_PAGE);


	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
    //$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";

	$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";
	$xml_output  .= "<LocationInfo latitude=\"".$thisLatitude."\" longitude=\"".$thisLongitude."\">";
	$xml_output  .= "</LocationInfo>\n";
	$xml_output  .= "<ObjectData>\n";

	$listingLevel = new ListingLevel();
	$review = new Review();

	if ($items) {
		$aux = 0;
		foreach ($items as $item) {

			$xml_output  .= "<entry>";

			$aux++;

			unset($dbReviewAmount);
			unset($sqlReviewAmount);
			unset($resultReviewAmount);
			unset($reviewAmount);
			$rateObj = new Review();
			$rate_avg = $rateObj->getRateAvgByItem("listing", $item["id"]);
			$rate_avg = ($rate_avg == "N/A") ? 0 : $rate_avg;
			unset($review_stars);


			$dbReviewAmount = db_getDBObject();
			$sqlReviewAmount = "SELECT count(0) as amount FROM Review WHERE item_type = 'listing' AND item_id = '".$item["id"]."' and approved=1 ";
			$resultReviewAmount = $dbReviewAmount->query($sqlReviewAmount);
			$reviews = mysql_fetch_assoc($resultReviewAmount);
			$reviewAmount = $reviews['amount'];
			//$reviewAmount = (strlen($reviewAmount)<1) ? 0 : $reviewAmount;

			unset($listingObj);

			$listingObj = new Listing($item["id"]);
			$array = $listingObj->getCategories();


			$categoryTitle = "<![CDATA[".$array[0]["title"]. "]]>";


			unset($dbReviewAmount);
			unset($sqlReviewAmount);
			unset($resultReviewAmount);

			unset($imagePath);
			unset($imageURL);
			unset($hasThumb);

			$hasThumb = false;

			$imageObj = new Image($item["thumb_id"]);

			if ($imageObj->imageExists()) {

				$imageURL = strtolower(IMAGE_URL . "/".$imageObj->prefix."photo_" . $imageObj->id . "." . $imageObj->type);
				$hasThumb = true;

			}

			$regionObj = new Location4($item["location_4"]);

			$address = "";
			if ($item["address"]) {
				$address .= addslashes($item["address"].", ");
			}


			if ($regionObj->getString("name")) {
				$address .= addslashes($regionObj->getString("name").", ");
			}

			$otherRegionObj = new Location3($item["location_3"]);
			if ($otherRegionObj->getString("name")) {
				$address .= addslashes($otherRegionObj->getString("name"));
			}

			$xml_output  .= "<listingID>".$item["id"]."</listingID>";
			$old_level = 80 - $item["level"];
			$xml_output  .= "<level>".$old_level."</level>";
			$xml_output  .= "<hasDetail>".$listingLevel->getDetail($item["level"])."</hasDetail>";
			$xml_output  .= "<latitude>".$item["latitude"]."</latitude>";
			$xml_output  .= "<longitude>".$item["longitude"]."</longitude>";
			$xml_output  .= "<regionID>".$item["location_4"]."</regionID>";
			$xml_output  .= "<regionName><![CDATA[".$regionObj->name."]]></regionName>";
			$xml_output  .= "<rawAddress><![CDATA[".($item["address"])."]]></rawAddress>";
			$xml_output  .= "<address><![CDATA[".($address)."]]></address>";
			$xml_output  .= "<rateAvg>".$rate_avg."</rateAvg>";
			$xml_output  .= "<reviewAmount>".$reviewAmount."</reviewAmount>";
			$xml_output  .= "<zipCode>".$item["zip_code"]."</zipCode>";
			$xml_output  .= "<listingTitle><![CDATA[".$item["title"]."]]></listingTitle>";
			$xml_output  .= "<description><![CDATA[".$item["description"]."]]></description>";
			$xml_output  .= "<phone><![CDATA[".str_replace(' ', '',$item["phone"])."]]></phone>";
			$xml_output  .= "<email>".$item["email"]."</email>";
			$xml_output  .= "<url><![CDATA[".$item["url"]."]]></url>";
			#$xml_output  .= "<mapTunning>".$item["maptuning"]."</mapTunning>";

			// Promo Image
			if ($hasThumb) {
				$xml_output  .= "<imageURLString>".$imageURL."</imageURLString>";
			} else {

				if (file_exists(EDIRECTORY_ROOT.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT)) {
					$xml_output  .= "<imageURLString>".DEFAULT_URL.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT."</imageURLString>";
   				} else {
					$xml_output  .= "<imageURLString>".DEFAULT_URL."/images/bg_noimage.gif</imageURLString>";
   				}
		   	}
			//$xml_output  .= "<imageURLString>". ($hasThumb ? $imageURL : IMAGE_URL."/../content_files/noimage.gif")."</imageURLString>";

			$xml_output  .= "<category>".$categoryTitle."</category>";

			if ($item["promotion_id"]) {

				$dealObj = new Promotion($item["promotion_id"]);

				$current = time();
				$visibilityStart = mktime(0, $dealObj->visibility_start, 0, date("m")  , date("d"), date("Y"));
				$visibilityEnd = mktime(0, $dealObj->visibility_end, 0, date("m")  , date("d"), date("Y"));

				if (($current > $visibilityStart && $current < $visibilityEnd) || ($dealObj->visibility_start == 24 && $dealObj->visibility_end == 24)) {

					$xml_output  .= "<promotionID>".$item["promotion_id"]."</promotionID>";

					//$xml_output  .= "<promotionName>".$dealObj->name."</promotionName>";
					$xml_output  .= "<promotionName><![CDATA[".$dealObj->name."]]></promotionName>";
					$xml_output  .= "<promotionRealValue>".$dealObj->realvalue."</promotionRealValue>";
					$xml_output  .= "<promotionDealValue>".$dealObj->dealvalue."</promotionDealValue>";
					$xml_output  .= "<promotionAmount>".$dealObj->amount."</promotionAmount>";
					$xml_output  .= "<promotionDescription><![CDATA[".$dealObj->{'description'}."]]></promotionDescription>";
					$xml_output  .= "<promotionConditions><![CDATA[".$dealObj->{'conditions'}."]]></promotionConditions>";
					$xml_output  .= "<promotionVisibilityStart>".$dealObj->visibility_start."</promotionVisibilityStart>";
					$xml_output  .= "<promotionVisibilityEnd>".$dealObj->visibility_end."</promotionVisibilityEnd>";
					$xml_output  .= "<promotionStart>".$dealObj->start_date."</promotionStart>";

					$xml_output  .= "<promotionEnd>".$dealObj->end_date." 23:59:59"."</promotionEnd>";

					/*
					if ($dealObj->visibility_end == 24)
					{
						$xml_output  .= "<promotionEnd>".$dealObj->end_date." 23:59:59"."</promotionEnd>";

					} else {
						$xml_output  .= "<promotionEnd>".$dealObj->end_date." ".m2h($dealObj->visibility_end)."</promotionEnd>";
					}
					*/

					//$xml_output  .= "<promotionFriendlyURL>".$dealObj->friendly_url."</promotionFriendlyURL>";
					$xml_output  .= "<promotionFriendlyURL>".DEFAULT_URL."/".PROMOTION_FEATURE_FOLDER."/".$dealObj->friendly_url.".html"."</promotionFriendlyURL>";

					$promotionDeals=$dealObj->getDealInfo();
					$xml_output  .= "<promotionDeals>".$promotionDeals['sold']."</promotionDeals>";

					if ($dealObj->account_id != 0) {
						$contactObj = new Contact($dealObj->account_id);
						$xml_output  .= "<promotionOwnerEmail>".$contactObj->email."</promotionOwnerEmail>";
					} else
						$xml_output  .= "<promotionOwnerEmail></promotionOwnerEmail>";
				} else $xml_output  .= "<promotionID>0</promotionID>";
			} else $xml_output  .= "<promotionID>0</promotionID>";

			$xml_output  .= "</entry>\n";



		}
	}
	$xml_output  .= "</ObjectData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";

	echo $xml_output;
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