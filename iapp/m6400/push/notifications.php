<?php 

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/configuration.inc.php");
	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
	# ----------------------------------------------------------------------------------------------------
	# QUERY STRING
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORYM_DOCUMENTROOT."/query_string.php");

	$latitudeSQL = $_GET["latitude"];
	$longitudeSQL = $_GET["longitude"];
//	$thisLatitude = 46.800059;
//	$thisLongitude = -119.091797;

	$distanceSQL = $_GET["distance"];
	
	
	$whereValidList = " ((l.maptuning IS NOT NULL AND l.maptuning != '') OR (l.latitude <> 0 AND l.longitude <> 0)) ";	

	$constMile = 0.014473204925797298063067594227;
	$constKm   = 0.008993232600237922265686778139;
	if (ZIPCODE_UNIT == "mile") $constDist = $constMile;
	elseif (ZIPCODE_UNIT == "km") $constDist = $constKm;
	$HighLatitude = $latitudeSQL + ($distanceSQL * $constDist);
	$LowLatitude = $latitudeSQL - ($distanceSQL * $constDist);
	$HighLongitude = $longitudeSQL + ($distanceSQL * $constDist);
	$LowLongitude = $longitudeSQL - ($distanceSQL * $constDist);
	

	$whereZipCodeProximity = "trim(substring_index(gps_value, ',',1)) <= ".$HighLatitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "trim(substring_index(gps_value, ',',1)) >= ".$LowLatitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "trim(substring_index(gps_value, ',',-1)) <= ".$HighLongitude;
	$whereZipCodeProximity .= " AND ";
	$whereZipCodeProximity .= "trim(substring_index(gps_value, ',',-1)) >= ".$LowLongitude;

	$latitudeIfQuery  = "if(maptuning is not null and maptuning != '',trim(substring_index(maptuning,',',1)) ,trim(substring_index(CONCAT(latitude,',',longitude),',',1)))";
	$longitudeIfQuery = "if(maptuning is not null and maptuning != '',trim(substring_index(maptuning,',',-1)) ,trim(substring_index(CONCAT(latitude,',',longitude),',',-1)))";


	if (ZIPCODE_UNIT == "mile") {
		$order_by_zipcode_score = "SQRT(POW((69.1 * (".$latitudeSQL." - ".$latitudeIfQuery.")), 2) + POW((53.0 * (".$longitudeSQL." - ".$longitudeIfQuery.")), 2)) AS zipcode_score";
	} elseif (ZIPCODE_UNIT == "km") {
		$order_by_zipcode_score = "SQRT(POW((69.1 * (".$latitudeSQL." - ".$latitudeIfQuery.")), 2) + POW((53.0 * (".$longitudeSQL." - ".$longitudeIfQuery.")), 2)) * 1.609344 AS zipcode_score";
	}

	unset($items);
	$dbObj = db_getDBObject();
	$sql = "";
	$sqlWhereKeyword = "";
	$order_by_keyword_score = "";



	//$sql .= " SELECT l.*, p.description ";
	$sql .= " SELECT l.* ";
    $sql .= " , if(l.maptuning is not null and l.maptuning != '',l.maptuning, CONCAT(l.latitude,',',l.longitude)) AS gps_value";
	$sql .= " , ".$order_by_zipcode_score." ";	 
	$sql .= " FROM Listing_Summary l inner join Promotion p on l.promotion_id = p.id ";
	$sql .= " WHERE l.status = 'A' ";
	$sql .= " AND l.promotion_id > 0 AND l.promotion_id is not null ";
	$sql .= " AND ".$whereValidList;		
//	$sql .= " AND ".$whereZipCodeProximity." ";
	$sql .= " AND amount > 0 ";
	
	$date = date('Y-m-d');

	
	$sql .= " AND p.start_date <= '".$date."'" ;
	$sql .= " AND p.end_date >= '".$date."'";
	$sql .= " having ".$whereZipCodeProximity." ";
	$sql .= " order by zipcode_score ";

	$sql .= " LIMIT 0,1 ";




//	echo $sql;
//	die();


	$result = $dbObj->query($sql);
	if ($result) {
		$item_amount = mysql_num_rows($result);
		if ($item_amount > 0) {
			while ($listing = mysql_fetch_assoc($result)) {
				$items[] = $listing;
			}
		} else {
        	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
            //$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";
        	$xml_output  .= "<eDirectoryData amount=\"0\" numberOfPages=\"1\" actualPage=\"1\" object=\"Notification\" >\n";
        	$xml_output  .= "<ObjectData>\n";
        	$xml_output  .= "</ObjectData></eDirectoryData>";
        	echo $xml_output;
        	die();
    	}
	} 
	
	unset($listingID);
	unset($dealID);
	unset($dealName);
	
	if ($items) { 
		$aux = 0;
		foreach ($items as $item) {
			$listingID = $item["id"];
			
//			echo $item["id"];
//			dir();
			
			$dealID = $item["promotion_id"];
			$dealObj = new Promotion($item["promotion_id"]);
			$dealName = $dealObj->name;
		}
	}
	///////////////////////////////////////////
    if ($listingID) {
       	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
        //$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";
    	
    	$xml_output  .= "<eDirectoryData amount=\"1\" numberOfPages=\"1\" actualPage=\"1\" object=\"Notification\" >\n";
    	$xml_output  .= "<ObjectData>\n";
    	$xml_output  .= "<entry>";
    	$xml_output  .= "<listingID>".$listingID."</listingID>";
    	$xml_output  .= "<dealName><![CDATA[".$dealName."]]></dealName>";
        $xml_output  .= "</entry>";
    	$xml_output  .= "</ObjectData></eDirectoryData>";
    }
    echo $xml_output;


//======================================================================//
//=                         PUSH NOTIFICATION                          =//
//======================================================================//
	
//	$deviceToken = "d83f1d8a f9164858 f8b14ba7 ac83cc01 843b3cd7 20f4cf14 4ab5bdf4 3c6f8973";//$_GET["token"];
//	$deviceToken = $_GET["deviceToken"];
	
//	$payload['aps'] = array('alert' => 'This is the alert text', 'badge' => 0, 'sound' => 'default');
//	$payload['aps'] = array('alert' => array('loc-key' => 'NearDealMessage', 'action-loc-key' => 'ViewDeal'), 'badge' => 0, 'sound' => 'default');
//	$payload['aps'] = array('alert' => array('loc-key' => 'NearDealMessage', 'loc-args' => $dealName,'action-loc-key' => 'ViewDeal'), 'badge' => 0, 'sound' => 'default');
//	$payload['aps'] = array('alert' => array('loc-key' => 'NearDealMessage', 'loc-args' => array($dealName),'action-loc-key' => 'ViewDeal'), 'badge' => 0, 'sound' => 'default');
	
//	echo $listingID;
//	die();
	
/*	$payload['server'] = array('listingId' => $listingID);
	$payload = json_encode($payload);	
	
	$apnsHost = 'gateway.sandbox.push.apple.com';
	$apnsPort = 2195;
	$apnsCert = 'apns-dev.pem';

	$streamContext = stream_context_create();
	stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
	$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
    if($error) {
        echo 'error: '.$errorString;
        return;
    }
	$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
	fwrite($apns, $apnsMessage);
	fclose($apns);*/
?>
