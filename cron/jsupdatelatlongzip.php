<?

////////////////////////////////////////////////////////////////////////////////////////////////////
	ini_set("html_errors", FALSE);
	////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$path = "";
	$full_name = "";
	$file_name = "";
	$full_name = $_SERVER["SCRIPT_FILENAME"];
	if (strlen($full_name) > 0) {
		$osslash = ((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '\\' : '/');
		$file_pos = strpos($full_name, $osslash."cron".$osslash);
		if ($file_pos !== false) {
			$file_name = substr($full_name, $file_pos);
		}
		$path = substr($full_name, 0, (strlen($file_name)*(-1)));
	}
	if (strlen($path) == 0) $path = "..";
	define("EDIRECTORY_ROOT", $path);
	define("BIN_PATH", EDIRECTORY_ROOT."/bin");
	////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$_inCron = true;
	include_once(EDIRECTORY_ROOT."/conf/config.inc.php");
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function getmicrotime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	$time_start = getmicrotime();
	////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$host = _DIRECTORYDB_HOST;
	$db   = _DIRECTORYDB_NAME;
	$user = _DIRECTORYDB_USER;
	$pass = _DIRECTORYDB_PASS;
	////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$link = mysql_connect($host, $user, $pass);
	mysql_query("SET NAMES 'utf8'", $link);
	mysql_query('SET character_set_connection=utf8', $link);
	mysql_query('SET character_set_client=utf8', $link);
	mysql_query('SET character_set_results=utf8', $link);
	mysql_select_db($db);
	////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$sqlDomain = "	SELECT
						D.`id`, D.`database_host`, D.`database_port`, D.`database_username`, D.`database_password`, D.`database_name`, D.`url`
					FROM `Domain` AS D
					LEFT JOIN `Control_Cron` AS CC ON (CC.`domain_id` = D.`id`)
					WHERE CC.`type` = 'daily_maintenance'
					AND D.`status` = 'A'
					ORDER BY
						IF (CC.`last_run_date` IS NULL, 0, 1),
						CC.`last_run_date`,
						D.`id`
					LIMIT 1";
	$resDomain = mysql_query($sqlDomain, $link);
	if (mysql_num_rows($resDomain) > 0) {
		$rowDomain = mysql_fetch_assoc($resDomain);
		define("SELECTED_DOMAIN_ID", $rowDomain["id"]);

	////////////////////////////////////////////////////////////////////////////////////////////////////
		$domainHost = $rowDomain["database_host"].($rowDomain["database_port"]? ":".$rowDomain["database_port"]: "");
		$domainUser = $rowDomain["database_username"];
		$domainPass = $rowDomain["database_password"];
		$domainDBName = $rowDomain["database_name"];
		$domainURL = $rowDomain["url"];
		$linkDomain = mysql_connect($domainHost, $domainUser, $domainPass, true);
		mysql_query("SET NAMES 'utf8'", $linkDomain);
		mysql_query('SET character_set_connection=utf8', $linkDomain);
		mysql_query('SET character_set_client=utf8', $linkDomain);
		mysql_query('SET character_set_results=utf8', $linkDomain);
		mysql_select_db($domainDBName);
	////////////////////////////////////////////////////////////////////////////////////////////////////
	} else {
	
		exit;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$_inCron = false;
	include_once(EDIRECTORY_ROOT."/conf/loadconfig.inc.php");
	
	$useGoogle = true;
	
	if ($useGoogle)
	{       //echo "AA";
			function getGoogleCoordinates($address, &$long, &$lat)
			{
				$htmlAddress = urlencode($address);
		
				$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$htmlAddress.'&sensor=false');
				
				echo 'http://maps.google.com/maps/api/geocode/json?address='.$htmlAddress.'&sensor=false'; 
				echo "<br>\n";
				$output= json_decode($geocode);
				
				//exit();
				//echo "444";
				$lat = round($output->results[0]->geometry->location->lat, 6);
				$long = round($output->results[0]->geometry->location->lng, 6);
				//echo "555"; 
			}
			//echo "111";
			//$query3 = "SELECT id,zip_code FROM `Listing_Summary` WHERE zip_code <> '' AND latitude = '' AND longitude = '' ";
			//$query3 = "SELECT id,zip_code, location_5_title, location_4_title, location_3_title, location_2_title, location_1_title FROM `Listing_Summary` WHERE zip_code = ''"; //add lat and long
            //$query3 = "SELECT id,zip_code, address, address2, location_5_title, location_4_title, location_3_title, location_2_title, location_1_title FROM `Listing_Summary` WHERE latitude = '' AND longitude = ''";
			$query3 = "SELECT Listing_Summary.id,Listing_Summary.zip_code, Listing_Summary.address, Listing_Summary.address2, Listing_Summary.location_5_title, Listing_Summary.location_4_title, Listing_Summary.location_3_title, Listing_Summary.location_2_title, Listing_Summary.location_1_title FROM `Listing_Summary` INNER JOIN `Listing` on Listing_Summary.id = Listing.id WHERE Listing.maptuning_date = '0000-00-00 00:00:00' LIMIT 5";
            $res1 = mysql_query($query3,$linkDomain);
			$cntListing = 0;
			while ($val1 = mysql_fetch_assoc($res1))
			{
				//echo "222";
                $address = "";
                $lat = "";
                $long = "";                                                               
                if ($val1["address"]) $address .= $val1["address"].", ";
                if ($val1["address2"]) $address .= $val1["address2"].", ";
                if (empty($val1["address"]) && empty($val1["address"]) && !empty($val1["zip_code"])) $address .= $val1["zip_code"].", ";
				if ($val1["location_5_title"]) $address .= $val1["location_5_title"].", ";
				if ($val1["location_4_title"]) $address .= $val1["location_4_title"].", ";
				if ($val1["location_3_title"]) $address .= $val1["location_3_title"].", ";
				if ($val1["location_2_title"]) $address .= $val1["location_2_title"].", ";
				if ($val1["location_1_title"]) $address .= $val1["location_1_title"]." ";
				//echo "333";
				getGoogleCoordinates($address, $long, $lat);
				//echo "666";
				
		        //echo "777";
				echo $queryUplatlong = "UPDATE Listing SET latitude = '".$lat."', longitude = '".$long."', maptuning_date = NOW() WHERE id = '".$val1['id']."'";
				mysql_query($queryUplatlong, $linkDomain);
				
				echo $queryUplatlong2 = "UPDATE Listing_Summary SET latitude = '".$lat."', longitude = '".$long."' WHERE id = '".$val1['id']."'";
				mysql_query($queryUplatlong2, $linkDomain);
                //echo "888<br>";
                echo "<br>\n";
                
                echo date("Y-m-d H:i:s")."<br><br>\n\n";
                $cntListing++;
                if (($cntListing % 10) == 0) {
                    echo $cntListing."<br>\n";
                    sleep(2);
                }
			}
	}else{
	    echo "BB ";
		echo $query = "SELECT id, zip_code FROM Listing WHERE zip_code <> '' AND longitude = '' AND latitude = ''";
		$res = mysql_query($query, $linkDomain);
		//$val = mysql_fetch_assoc($res);
        echo "<br>";
		while ($val = mysql_fetch_assoc($res))
		{
			$newListing = new Listing($val["id"]);
			//echo $val["id"]."----".$newListing->getString("title"); exit();
			$newLong = "";
			$newLat = "";
			
			$query1 = "SELECT * FROM $db.ZipCode_Data WHERE ZipCode = '".$val["zip_code"]."'";
			$res1 = mysql_query($query1);
			if ($val1 = mysql_fetch_assoc($res1))
			{
				$newLong = $val1["Longitude"];
				$newLat = $val1["Latitude"];
				
				$newListing->setString("longitude", $newLong);
				$newListing->setString("latitude", $newLat);
				
				$newListing->Save();
			}
			
			unset($newListing);
		}
		
		
	}
	
    $time_end = getmicrotime();
    $time = $time_end - $time_start;
    print "Update latitude and longitude on Domain ".SELECTED_DOMAIN_ID." - ".date("Y-m-d H:i:s")." - ".round($time, 2)." seconds.\n";
?>