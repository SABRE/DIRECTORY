#!/usr/bin/php -q
<?

	date_default_timezone_set('America/Los_Angeles');

	////////////////////////////////////////////////////////////////////////////////////////////////////
	ini_set("html_errors", FALSE);
	error_reporting(-1);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$path = "";
	$full_name = "";
	$file_name = "";
	$full_name = __FILE__; //$_SERVER["SCRIPT_FILENAME"];
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
    	include_once(EDIRECTORY_ROOT."/functions/log_funct.php");


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
	
	
	$r = mysql_query("SELECT l3 . * , l1.`name` AS  `country`  
FROM Location_3 l3, Location_1 l1 
WHERE l1.id = l3.location_1 
AND l3.latitude =0 
AND l3.longitude =0 
ORDER BY l3.id ASC 
LIMIT 100");
	
	$t = 0;
	while ($row = mysql_fetch_assoc($r))
	{
		echo 'updating '.$row['id'].'='.$row['name'].','.$row['country']." ... ";
		
		$address = urlencode($row['name'].','.$row['country']);
		$d = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false");
		$arr = json_decode($d,true);
		if (!$arr) die('error');
		
		if ($arr && $arr['results'])
		{
			$lat = $arr['results'][0]['geometry']['location']['lat'];
			$lng = $arr['results'][0]['geometry']['location']['lng'];	
		
			if ($lat && $lng)
			{
				$sql = "UPDATE Location_3 SET latitude='$lat',longitude='$lng' WHERE id=".$row['id'];
				mysql_query($sql);
				echo ' done';
			}
			else
			{
				echo 'error';
			}
		}
		$t++;
		echo "\n";
	}
	
	if ($t == 0) echo 'nothing to update'."\n";
	mysql_close($link);