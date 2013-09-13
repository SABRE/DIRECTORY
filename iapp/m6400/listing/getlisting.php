<?php

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/configuration.inc.php");
	require_once '../classes/GeoLocation.php';
	
	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");	

	unset($items);
	$dbObj = db_getDBObject();
	$sql = "";

	$sql = "select * from Listing_Summary where id = ".$_GET["listingID"];

	$result = $dbObj->query($sql);
	if ($result) {
		$item_amount = mysql_num_rows($result);
		if ($item_amount > 0) {
			while ($listing = mysql_fetch_assoc($result)) {
				$items[] = $listing;
			}
		}
	}

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
			
			$address .= addslashes($item["zip_code"]);
			
			$xml_output  .= "<listingID>".$item["id"]."</listingID>";
			$xml_output  .= "<level>".$item["level"]."</level>";
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
			$xml_output  .= "<phone>".str_replace(' ', '',$item["phone"])."</phone>";
			$xml_output  .= "<email>".$item["email"]."</email>";
			$xml_output  .= "<url><![CDATA[".$item["url"]."]]></url>";
			$xml_output  .= "<mapTunning>".$item["maptuning"]."</mapTunning>";

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