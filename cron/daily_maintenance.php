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
	# * FILE: /cron/daily_maintenance.php
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
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$sqlDomain = "	SELECT
						D.`id`, D.`database_host`, D.`database_port`, D.`database_username`, D.`database_password`, D.`database_name`, D.`url`
					FROM `Domain` AS D
					LEFT JOIN `Control_Cron` AS CC ON (CC.`domain_id` = D.`id`)
					WHERE CC.`running` = 'N'
					AND CC.`type` = 'daily_maintenance'
					AND D.`status` = 'A'
					AND (ADDDATE(CC.`last_run_date`, INTERVAL 1 DAY) <= NOW() OR CC.`last_run_date` = '0000-00-00 00:00:00')
					ORDER BY
						IF (CC.`last_run_date` IS NULL, 0, 1),
						CC.`last_run_date`,
						D.`id`
					LIMIT 1";

	$resDomain = mysql_query($sqlDomain, $link);

	if (mysql_num_rows($resDomain) > 0) {
		$rowDomain = mysql_fetch_assoc($resDomain);
		define("SELECTED_DOMAIN_ID", $rowDomain["id"]);

		$sqlUpdate = "UPDATE `Control_Cron` SET `running` = 'Y', `last_run_date` = NOW() WHERE `domain_id` = ".SELECTED_DOMAIN_ID." AND `type` = 'daily_maintenance'";
		mysql_query($sqlUpdate, $link);
        $messageLog = "Starting cron";
        log_addCronRecord($link, "daily_maintenance", $messageLog, false, $cron_log_id);

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
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Delete old reviews - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "DELETE FROM Review WHERE (added <= DATE_SUB(NOW(), INTERVAL '2' YEAR))";
	$result = mysql_query($sql, $linkDomain);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update expired listings - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT id FROM Listing WHERE renewal_date < NOW() AND renewal_date != '0000-00-00' AND status != 'E'";
	$result = mysql_query($sql, $linkDomain);
	while ($row = mysql_fetch_assoc($result)) {
		$listingObj = new Listing($row["id"]);
		$listingObj->setString("status", "E");
		$listingObj->Save();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update expired events - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT id FROM Event WHERE renewal_date < NOW() AND renewal_date != '0000-00-00' AND status != 'E'";
	$result = mysql_query($sql, $linkDomain);
	while ($row = mysql_fetch_assoc($result)) {
		$eventObj = new Event($row["id"]);
		$eventObj->setString("status", "E");
		$eventObj->Save();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////////
	$messageLog = "Update expired banners - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
    $sql = "SELECT id FROM Banner WHERE renewal_date < NOW() AND renewal_date != '0000-00-00' AND expiration_setting = ".BANNER_EXPIRATION_RENEWAL_DATE." AND status != 'E'";
	$result = mysql_query($sql, $linkDomain);
	while ($row = mysql_fetch_assoc($result)) {
		$bannerObj = new Banner($row["id"]);
		$bannerObj->setString("status", "E");
		$bannerObj->Save();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$messageLog = "Update expired classifieds - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
    $sql = "SELECT id FROM Classified WHERE renewal_date < NOW() AND renewal_date != '0000-00-00' AND status != 'E'";
	$result = mysql_query($sql, $linkDomain);
	while ($row = mysql_fetch_assoc($result)) {
		$classifiedObj = new Classified($row["id"]);
		$classifiedObj->setString("status", "E");
		$classifiedObj->Save();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update expired articles - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT id FROM Article WHERE renewal_date < NOW() AND renewal_date != '0000-00-00' AND status != 'E'";
	$result = mysql_query($sql, $linkDomain);
	while ($row = mysql_fetch_assoc($result)) {
		$articleObj = new Article($row["id"]);
		$articleObj->setString("status", "E");
		$articleObj->Save();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update expired Promotional Codes - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "UPDATE Discount_Code SET status = 'E' WHERE expire_date < NOW() AND expire_date != '0000-00-00' AND status != 'E'";
	$result = mysql_query($sql, $linkDomain);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update expired invoices - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "UPDATE Invoice SET status = 'E' WHERE expire_date < NOW() AND expire_date != '0000-00-00' AND status != 'E' AND status != 'R'";
	$result = mysql_query($sql, $linkDomain);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Delete old invoices - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT id FROM Invoice WHERE status = 'N'";
	$result = mysql_query($sql, $linkDomain);
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$invoice_ids[] = $row["id"];
		}
	}
	if ($invoice_ids) {
		$invoice_ids = implode(",",$invoice_ids);
		$sql = "DELETE FROM Invoice WHERE id IN ($invoice_ids)";
		$result = mysql_query($sql, $linkDomain);
		$sql = "DELETE FROM Invoice_Listing WHERE invoice_id IN ($invoice_ids)";
		$result = mysql_query($sql, $linkDomain);
		$sql = "DELETE FROM Invoice_Event WHERE invoice_id IN ($invoice_ids)";
		$result = mysql_query($sql, $linkDomain);
		$sql = "DELETE FROM Invoice_Banner WHERE invoice_id IN ($invoice_ids)";
		$result = mysql_query($sql, $linkDomain);
		$sql = "DELETE FROM Invoice_Classified WHERE invoice_id IN ($invoice_ids)";
		$result = mysql_query($sql, $linkDomain);
		$sql = "DELETE FROM Invoice_Article WHERE invoice_id IN ($invoice_ids)";
		$result = mysql_query($sql, $linkDomain);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update listing statistic - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT COUNT(id) AS total FROM Listing WHERE status = 'P'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'l_pending'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'l_pending')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Listing WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_LISTING_DAYS_TO_EXPIRE." DAY)";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'l_expiring'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'l_expiring')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Listing WHERE status = 'E'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'l_expired'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'l_expired')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Listing WHERE status = 'A'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'l_active'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'l_active')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Listing WHERE status = 'S'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'l_suspended'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'l_suspended')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Listing WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'l_added30'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'l_added30')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update event statistic - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = 'P'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'e_pending'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'e_pending')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Event WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_EVENT_DAYS_TO_EXPIRE." DAY)";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'e_expiring'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'e_expiring')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = 'E'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'e_expired'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'e_expired')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = 'A'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'e_active'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'e_active')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Event WHERE status = 'S'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'e_suspended'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'e_suspended')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total from Event WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'e_added30'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'e_added30')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update banner statistic - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = 'P'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'b_pending'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'b_pending')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = 'E'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'b_expired'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'b_expired')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = 'A'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'b_active'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'b_active')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Banner WHERE status = 'S'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'b_suspended'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'b_suspended')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total from Banner WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'b_added30'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'b_added30')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update classified statistic - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = 'P'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'c_pending'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'c_pending')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Classified WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_CLASSIFIED_DAYS_TO_EXPIRE." DAY)";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'c_expiring'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'c_expiring')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = 'E'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'c_expired'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'c_expired')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = 'A'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'c_active'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'c_active')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Classified WHERE status = 'S'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'c_suspended'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'c_suspended')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total from Classified WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'c_added30'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'c_added30')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
    $messageLog = "Update article statistic - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = 'P'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'a_pending'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'a_pending')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Article WHERE renewal_date > NOW() AND renewal_date <= DATE_ADD(NOW(), INTERVAL ".DEFAULT_ARTICLE_DAYS_TO_EXPIRE." DAY)";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'a_expiring'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'a_expiring')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = 'E'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'a_expired'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'a_expired')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = 'A'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'a_active'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'a_active')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total FROM Article WHERE status = 'S'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'a_suspended'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'a_suspended')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	$sql = "SELECT COUNT(id) AS total from Article WHERE entered >= '".date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")))."'";
	$result = mysql_query($sql, $linkDomain);
	if ($result) {
		if ($row = mysql_fetch_assoc($result)) {
			$this_value = ((int)$row["total"]) ? (((int)($row["total"]/10)+1)*10) : ("0");
			$sql = "UPDATE ItemStatistic SET value = '".$this_value."' WHERE name = 'a_added30'";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_affected_rows($linkDomain) <= 0) {
				$sql = "INSERT INTO ItemStatistic (value, name) VALUES ('".$this_value."', 'a_added30')";
				$result = mysql_query($sql, $linkDomain);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////  Delete Unused Image Files  ////////////////////////////////////////
    $messageLog = "Delete unused image files - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sqlDomain = "SELECT id FROM Domain";
	$resultDomain = mysql_query($sqlDomain, $link);
	if (mysql_num_rows($resultDomain) > 0) {
		while ($rowDomain = mysql_fetch_assoc($resultDomain)) {
			$dir = EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]."/image_files";
			$imageFiles = glob("$dir/_*.*");
			foreach ($imageFiles as $file) unlink($file);

			$dir = EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]."/image_files";
			$imageFiles = glob("$dir/resize_*.*");
			foreach ($imageFiles as $file) unlink($file);
		}
	}

	$dir = EDIRECTORY_ROOT."/custom/profile";
	$profileFiles = glob("$dir/_*.*");
	foreach ($profileFiles as $file) unlink($file);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	//////  Delete old entries from Recent Activity Table (leave only the 20 newest entries)  //////////
    $messageLog = "Delete old entries from Recent Activity Table - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sql = "SELECT id FROM Recent_Activity ORDER BY id DESC LIMIT 20";
	$result = mysql_query($sql, $link);
	$str_ids = "";
	while ($row = mysql_fetch_assoc($result)){
		$str_ids .= $row["id"].",";
	}
	$str_ids = substr($str_ids, 0, -1);

	$sql = "DELETE FROM Recent_Activity WHERE id NOT IN ($str_ids)";
	$result = mysql_query($sql, $link);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	///////////////////// Delete Pending and Deleted Domain Information ////////////////////////////////
    $messageLog = "Delete pending and deleted domains - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$sqlDomain = "SELECT * FROM Domain WHERE (`status` = 'P') OR (`status` = 'D' AND ADDDATE(`deleted_date`, 7) <= CURDATE())";
	$resultDomain = mysql_query($sqlDomain, $link);
	if (mysql_num_rows($resultDomain) > 0) {
		while ($rowDomain = mysql_fetch_assoc($resultDomain)) {
			if ((int)system_checkPerm(EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]) >= (int)PERMISSION_CUSTOM_FOLDER) {
				unset($domainObj);
				$domainObj = new Domain($rowDomain);
				$domainObj->Delete();
			} else {
				print("\nPermission denied in folder \"".EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]."/\" Domain can not be deleted!\n");
                $messageLog = "Permission denied in folder \"".EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]."/\" Domain ".$rowDomain["id"]."/ can not be deleted!";
                log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	///////////////////// Turn on the scalability if necessary ////////////////////////////////
	$messageLog = "Turn on scalability if necessary - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
	$listing_scalability = LISTING_SCALABILITY_OPTIMIZATION;
	$promotion_scalability = PROMOTION_SCALABILITY_OPTIMIZATION;
	$promotion_scalability_autocomplete = PROMOTION_SCALABILITY_USE_AUTOCOMPLETE;
	$event_scalability = EVENT_SCALABILITY_OPTIMIZATION;
	$banner_scalability = BANNER_SCALABILITY_OPTIMIZATION;
	$classified_scalability = CLASSIFIED_SCALABILITY_OPTIMIZATION;
	$article_scalability = ARTICLE_SCALABILITY_OPTIMIZATION;
	$blog_scalability = BLOG_SCALABILITY_OPTIMIZATION;
	
	$listing_categ_scalability = LISTINGCATEGORY_SCALABILITY_OPTIMIZATION;
	$event_categ_scalability = EVENTCATEGORY_SCALABILITY_OPTIMIZATION;
	$classified_categ_scalability = CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION;
	$article_categ_scalability = ARTICLECATEGORY_SCALABILITY_OPTIMIZATION;
	$blog_categ_scalability = BLOGCATEGORY_SCALABILITY_OPTIMIZATION;
	
	$updateScalabilityFile = false;
	
	if (LISTING_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Listing_Summary";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= LISTING_SCALABILITY_NUMBER){
					$listing_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (PROMOTION_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Promotion";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= PROMOTION_SCALABILITY_NUMBER){
					$promotion_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
    
    if (PROMOTION_SCALABILITY_USE_AUTOCOMPLETE == "on"){
		$sql = "SELECT COUNT(id) AS total FROM Promotion";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= PROMOTION_SCALABILITY_NUMBER){
					$promotion_scalability_autocomplete = "off";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (EVENT_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Event";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= EVENT_SCALABILITY_NUMBER){
					$event_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (BANNER_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Banner";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= BANNER_SCALABILITY_NUMBER){
					$banner_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (CLASSIFIED_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Classified";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= CLASSIFIED_SCALABILITY_NUMBER){
					$classified_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (ARTICLE_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Article";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= ARTICLE_SCALABILITY_NUMBER){
					$article_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
    
    if (BLOG_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM Post";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= BLOG_SCALABILITY_NUMBER){
					$blog_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM ListingCategory WHERE category_id = 0 ";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= LISTINGCATEGORY_SCALABILITY_NUMBER){
					$listing_categ_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM EventCategory WHERE category_id = 0 ";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= EVENTCATEGORY_SCALABILITY_NUMBER){
					$event_categ_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM ClassifiedCategory WHERE category_id = 0 ";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= CLASSIFIEDCATEGORY_SCALABILITY_NUMBER){
					$classified_categ_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM ArticleCategory WHERE category_id = 0 ";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= ARTICLECATEGORY_SCALABILITY_NUMBER){
					$article_categ_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
    
    if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == "off"){
		$sql = "SELECT COUNT(id) AS total FROM BlogCategory WHERE category_id = 0 ";
		$result = mysql_query($sql, $linkDomain);
		if ($result) {
			if ($row = mysql_fetch_assoc($result)) {
				$this_value = $row["total"];
				
				if ($this_value >= BLOGCATEGORY_SCALABILITY_NUMBER){
					$blog_categ_scalability = "on";
					$updateScalabilityFile = true;
				}
			}
		}
	}
	
	if ($updateScalabilityFile){
		$fileScalPath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/conf/scalability.inc.php";
        
        $scalValues = array();
        $scalValues["listing_scalability"] = $listing_scalability;
        $scalValues["promotion_scalability"] = $promotion_scalability;
        $scalValues["promotion_auto_complete"] = $promotion_scalability_autocomplete;
        $scalValues["event_scalability"] = $event_scalability;
        $scalValues["banner_scalability"] = $banner_scalability;
        $scalValues["classified_scalability"] = $classified_scalability;
        $scalValues["article_scalability"] = $article_scalability;
        $scalValues["blog_scalability"] = $blog_scalability;
        $scalValues["listingcateg_scalability"] = $listing_categ_scalability;
        $scalValues["eventcateg_scalability"] = $event_categ_scalability;
        $scalValues["classifiedcateg_scalability"] = $classified_categ_scalability;
        $scalValues["articlecateg_scalability"] = $article_categ_scalability;
        $scalValues["blogcateg_scalability"] = $blog_categ_scalability;
        
        if (!system_writeScalabilityFile($fileScalPath, SELECTED_DOMAIN_ID, $scalValues)) {
                
            print("\nPermission denied in folder \"".EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]."/\" Can not rewrite scalability file!\n");
            $messageLog = "Permission denied in folder \"".EDIRECTORY_ROOT."/custom/domain_".$rowDomain["id"]."/\" Can not rewrite scalability file!";
            log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);

        }
        
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////  Delete old entries from Cron_Log Table  //////////
    $messageLog = "Delete old entries from Cron_Log Table - LINE: ".__LINE__;
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);

	$sql = "DELETE FROM Cron_Log WHERE (date <= DATE_SUB(NOW(), INTERVAL '".CRON_LOG_CLEAR_INTERVAL."' DAY))";
	$result = mysql_query($sql, $link);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	$sqlUpdate = "UPDATE `Control_Cron` SET `running` = 'N' WHERE `domain_id` = ".SELECTED_DOMAIN_ID." AND `type` = 'daily_maintenance'";
	mysql_query($sqlUpdate, $link);

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$time_end = getmicrotime();
	$time = $time_end - $time_start;
	print "Daily maintenance on Domain ".SELECTED_DOMAIN_ID." - ".date("Y-m-d H:i:s")." - ".round($time, 2)." seconds.\n";
	if (!setting_set("last_datetime_dailymaintenance", date("Y-m-d H:i:s"))) {
		if (!setting_new("last_datetime_dailymaintenance", date("Y-m-d H:i:s"))) {
			print "last_datetime_dailymaintenance error - Domain - ".SELECTED_DOMAIN_ID." - ".date("Y-m-d H:i:s")."\n";
            $messageLog = "Database error - LINE: ".__LINE__;
            log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id);
		}
	}
    $messageLog = "Cron finished";
    log_addCronRecord($link, "daily_maintenance", $messageLog, true, $cron_log_id, true, round($time, 2));
	////////////////////////////////////////////////////////////////////////////////////////////////////