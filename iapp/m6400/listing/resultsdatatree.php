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
	$searchReturn = search_frontListingSearch($_GET, "mobile");
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


			$level = new ListingLevel();

			$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
			//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";

			$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";
			//$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\">\n";
			$xml_output  .= "<LocationInfo latitude=\"".$thisLatitude."\" longitude=\"".$thisLongitude."\">";
			$xml_output  .= "</LocationInfo>\n";
			$xml_output  .= "<ObjectData>\n";

			$listingLevel = new ListingLevel();

			while ($item = mysql_fetch_assoc($result)) {
				$xml_output  .= "<entry>";

				unset($dbReviewAmount);
				unset($sqlReviewAmount);
				unset($resultReviewAmount);
				$rateObj = new Review();
				$rate_avg = $rateObj->getRateAvgByItem("listing", $item["id"]);
				$rate_avg = ($rate_avg == "N/A") ? 0 : $rate_avg;
				unset($review_stars);


				$dbReviewAmount = db_getDBObject();
				$sqlReviewAmount = "SELECT count(0) as amount FROM Review WHERE item_type = 'listing' AND item_id = '".$item["id"]."' and approved=1 ";
				$resultReviewAmount = $dbReviewAmount->query($sqlReviewAmount);
				$reviews = mysql_fetch_assoc($resultReviewAmount);
				$reviewAmount = $reviews['amount'];

				unset($imagePath);
				unset($imageURL);
				unset($hasThumb);

				/*
				$objCat = new ListingCategory($item["id"]);
				$cats = $objCat->retrieveAllCategories();
				$categoryTitle = $cats[0]["title"];
				*/

				unset($listingObj);
				$listingObj = new Listing($item["id"]);
				$array = $listingObj->getCategories(false, false, $item["id"], true);
				//print_r($array);
				$categoryTitle = "<![CDATA[".$array[0]["title"]. "]]>";


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
				$xml_output  .= "<regionName><![CDATA[".($regionObj->getString("name"))."]]></regionName>";
				$xml_output  .= "<rawAddress><![CDATA[".($item["address"])."]]></rawAddress>";
				$xml_output  .= "<address><![CDATA[".($address)."]]></address>";
				$xml_output  .= "<rateAvg>".$rate_avg."</rateAvg>";
				$xml_output  .= "<reviewAmount>".$reviewAmount."</reviewAmount>";
				$xml_output  .= "<zipCode>".$item["zip_code"]."</zipCode>";
				$xml_output  .= "<listingTitle><![CDATA[".($item["title"])."]]></listingTitle>";
				$xml_output  .= "<description><![CDATA[".($item["description"])."]]></description>";
				$xml_output  .= "<phone><![CDATA[".str_replace(' ', '',$item["phone"])."]]></phone>";
				$xml_output  .= "<email>".$item["email"]."</email>";
				$xml_output  .= "<url><![CDATA[".$item["url"]."]]></url>";
				#$xml_output  .= "<mapTunning>".$item["maptuning"]."</mapTunning>";
				$xml_output  .= "<category>".$categoryTitle."</category>";
				if ($hasThumb) {
					$xml_output  .= "<imageURLString>".$imageURL."</imageURLString>";
				} else {

					if (file_exists(EDIRECTORY_ROOT.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT)) {
						$xml_output  .= "<imageURLString>".DEFAULT_URL.NOIMAGE_PATH."/".NOIMAGE_NAME.".".NOIMAGE_IMGEXT."</imageURLString>";
	   				} else {
						$xml_output  .= "<imageURLString>".DEFAULT_URL."/images/bg_noimage.gif</imageURLString>";
	   				}
			    }
//				$xml_output  .= "<imageURLString>". ($hasThumb ? $imageURL : IMAGE_URL."/../content_files/noimage.gif")."</imageURLString>";

				if ($item["promotion_id"]) {

					$dealObj = new Promotion($item["promotion_id"]);

					$current = time();
					$visibilityStart = mktime(0, $dealObj->visibility_start, 0, date("m")  , date("d"), date("Y"));
					$visibilityEnd = mktime(0, $dealObj->visibility_end, 0, date("m")  , date("d"), date("Y"));

					/*if (
						(
							($current > $visibilityStart && $current < $visibilityEnd)
							|| ($dealObj->visibility_start == 24 && $dealObj->visibility_end == 24)
						)
						&& $dealObj->amount > 0 && strtotime($dealObj->end_date." 23:59:59") > strtotime(date('Y-m-d H:i:s'))
					   ) {*/

					if (
						(
							($current > $visibilityStart && $current < $visibilityEnd)
							|| ($dealObj->visibility_start == 24 && $dealObj->visibility_end == 24)
						)
						&& strtotime($dealObj->end_date." 23:59:59") > strtotime(date('Y-m-d H:i:s'))
					   ) {

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
						//$xml_output  .= "<promotionEnd>".$dealObj->end_date."</promotionEnd>";

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
						$xml_output  .= "<promotionFriendlyURL>".DEFAULT_URL."/".PROMOTION_FEATURE_NAME."/".$dealObj->friendly_url.".html"."</promotionFriendlyURL>";


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


		} else {


			$xml_output  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";

			$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\" object=\"Listing\" >\n";
			//$xml_output  .= "<eDirectoryData amount=\"".$item_amount."\" numberOfPages=\"".$numberOfResultsPage."\" actualPage=\"".$page."\">\n";
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