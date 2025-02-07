#!/usr/bin/php -q
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
	# * FILE: /cron/cron_manager.php
	# ----------------------------------------------------------------------------------------------------
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

	/**
	 * Files to Cron manager
	 */
	$files[] = "daily_maintenance.php";
	$files[] = "email_traffic.php";
	$files[] = "location_update.php";
	$files[] = "randomizer.php";
	$files[] = "renewal_reminder.php";
	$files[] = "report_rollup.php";
	$files[] = "sitemap.php";
	$files[] = "statisticreport.php";
	$files[] = "export_listings.php";
	$files[] = "export_events.php";
	$files[] = "rollback_import.php";
	$files[] = "rollback_import_events.php";
	$files[] = "update_location3_coordinates.php";
	$files[] = "update_location4_coordinates.php";

	
	/*
	 * Save information about cron running
	 */
	$host = _DIRECTORYDB_HOST;
	$db   = _DIRECTORYDB_NAME;
	$user = _DIRECTORYDB_USER;
	$pass = _DIRECTORYDB_PASS;
	
	$link = mysql_connect($host, $user, $pass);
	mysql_query("SET NAMES 'utf8'", $link);
	mysql_query('SET character_set_connection=utf8', $link);
	mysql_query('SET character_set_client=utf8', $link);
	mysql_query('SET character_set_results=utf8', $link);
	mysql_select_db($db);
	
	$sql = "SELECT value FROM Setting WHERE name = 'running_cron_manager'";
	$result	= mysql_query($sql, $link);
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_assoc($result);
        
        if ($row["value"] == "n") {
            
            $sql_update = "UPDATE Setting SET value = 'y' WHERE name = 'running_cron_manager'";
            mysql_query($sql_update, $link);

            for ($i=0;$i<count($files);$i++) {
                if (is_file(EDIRECTORY_ROOT."/cron/".$files[$i])) {
                    system("php -f ".EDIRECTORY_ROOT."/cron/".$files[$i]);
                }
            }

            $sql_update = "UPDATE Setting SET value = 'n' WHERE name = 'running_cron_manager'";
            mysql_query($sql_update, $link);
        }
		
	} else {
        $sql = "INSERT INTO Setting (name, value) VALUES ('running_cron_manager', 'n');";
        $result	= mysql_query($sql, $link);
    }
	////////////////////////////////////////////////////////////////////////////////////////////////////
	$time_end = getmicrotime();
	$time = $time_end - $time_start;
	print "Cron Manager - ".date("Y-m-d H:i:s")." - ".round($time, 2)." seconds.\n";
?>