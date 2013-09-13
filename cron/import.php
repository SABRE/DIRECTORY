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
	# * FILE: /cron/import.php
	# ----------------------------------------------------------------------------------------------------

	////////////////////////////////////////////////////////////////////////////////////////////////////
	define("IMPORT_DEBUG", "off");
	define("IMPORT_TIME_DEBUG", "off");
	define("IMPORT_TIME_EMAIL_DEBUG", "off");
	define("IMPORT_MAX_LISTINGS_PERTIME", 100000);
	define("IMPORT_MAX_SECONDS_PERTIME", 60*19);
	////////////////////////////////////////////////////////////////////////////////////////////////////

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
						Domain.`id`, 
                        Domain.`database_host`, 
                        Domain.`database_port`, 
                        Domain.`database_username`, 
                        Domain.`database_password`, 
                        Domain.`database_name`, 
                        Control_Import_Listing.`last_importlog`
					FROM `Domain` AS Domain
                        LEFT OUTER JOIN `Control_Import_Listing` AS Control_Import_Listing ON (Control_Import_Listing.`domain_id` = Domain.`id`)
                        LEFT OUTER JOIN `Control_Cron` AS Control_Cron ON (Control_Cron.`domain_id` = Domain.`id`)
					WHERE Control_Import_Listing.`scheduled` = 'Y' AND 
                          Control_Import_Listing.`running` = 'N' AND 
                          Control_Cron.`running` = 'N' AND 
                          Control_Cron.`type` = 'prepare_import' AND 
                          Domain.`status` = 'A'
					ORDER BY
						IF (Control_Import_Listing.`last_run_date` IS NULL, 0, 1),
						Control_Import_Listing.`last_run_date`,
						Domain.`id`
					LIMIT 1";
	$resDomain = mysql_query($sqlDomain, $link);

	$sqlRunning = "	SELECT `domain_id` FROM `Control_Import_Listing` WHERE `running` = 'Y' LIMIT 1";
	$resRunning = mysql_query($sqlRunning, $link);

	if (mysql_num_rows($resDomain) > 0 && mysql_num_rows($resRunning) == 0) {
		$rowDomain = mysql_fetch_assoc($resDomain);
		define("SELECTED_DOMAIN_ID", $rowDomain["id"]);

		$sqlUpdate = "UPDATE `Control_Import_Listing` SET `scheduled` = 'N', `running` = 'Y', `last_run_date` = NOW() WHERE `domain_id` = ".$rowDomain["id"];
		mysql_query($sqlUpdate, $link);
        $messageLog = "Starting cron";
        log_addCronRecord($link, "import", $messageLog, false, $cron_log_id);

		$last_import_log = $rowDomain["last_importlog"];

    ////////////////////////////////////////////////////////////////////////////////////////////////////
		$domainHost = $rowDomain["database_host"].($rowDomain["database_port"]? ":".$rowDomain["database_port"]: "");
		$domainUser = $rowDomain["database_username"];
		$domainPass = $rowDomain["database_password"];
		$domainDBName = $rowDomain["database_name"];

		$linkDomain = mysql_connect($domainHost, $domainUser, $domainPass, true);
		mysql_query("SET NAMES 'utf8'", $linkDomain);
		mysql_query('SET character_set_connection=utf8', $linkDomain);
		mysql_query('SET character_set_client=utf8', $linkDomain);
		mysql_query('SET character_set_results=utf8', $linkDomain);
		mysql_select_db($domainDBName);
    ////////////////////////////////////////////////////////////////////////////////////////////////////
	} else {
        $messageLog = "No import scheduled.";
        log_addCronRecord($link, "import", $messageLog, false, $cron_log_id);
        
        //Check if any import was left with the flag "W" (waiting to stop), for all domains.
        $sqlW = "SELECT
						Domain.`id`, 
                        Domain.`database_host`, 
                        Domain.`database_port`, 
                        Domain.`database_username`, 
                        Domain.`database_password`, 
                        Domain.`database_name`
					FROM `Domain` AS Domain
                        LEFT OUTER JOIN `Control_Import_Listing` AS Control_Import_Listing ON (Control_Import_Listing.`domain_id` = Domain.`id`)
					WHERE Control_Import_Listing.`scheduled` = 'N' AND 
                          Control_Import_Listing.`running` = 'N' AND 
                          Domain.`status` = 'A'
					ORDER BY
						IF (Control_Import_Listing.`last_run_date` IS NULL, 0, 1),
						Control_Import_Listing.`last_run_date`,
						Domain.`id`";
        $resW = mysql_query($sqlW, $link);
        if (mysql_num_rows($resW) > 0) {
            while ($rowW = mysql_fetch_assoc($resW)) {
                
                $domainHost = $rowW["database_host"].($rowW["database_port"]? ":".$rowW["database_port"]: "");
                $domainUser = $rowW["database_username"];
                $domainPass = $rowW["database_password"];
                $domainDBName = $rowW["database_name"];

                $linkDomain = mysql_connect($domainHost, $domainUser, $domainPass, true);
                mysql_query("SET NAMES 'utf8'", $linkDomain);
                mysql_query('SET character_set_connection=utf8', $linkDomain);
                mysql_query('SET character_set_client=utf8', $linkDomain);
                mysql_query('SET character_set_results=utf8', $linkDomain);
                mysql_select_db($domainDBName);
                
                $messageLog = "Reschedule import if need to fix \"stopped\" imports - LINE: ".__LINE__;
                log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
                
                $sqlLog = "SELECT id FROM ImportLog WHERE `status` = 'W' AND type = 'listing'";
                $resLog = mysql_query($sqlLog, $linkDomain);
                if (mysql_num_rows($resLog) > 0) {
                    $sqlUpdate = "UPDATE `Control_Import_Listing` SET `scheduled` = 'Y' WHERE domain_id = ".$rowW["id"];
                    mysql_query($sqlUpdate, $link);
                }
            }
        }
        
        $messageLog = "Cron finished";
        log_addCronRecord($link, "import", $messageLog, true, $cron_log_id, true);
		exit;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////

	$_inCron = false;
	include_once(EDIRECTORY_ROOT."/conf/loadconfig.inc.php");
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	define("IMPORTFOLDER", str_replace(EDIRECTORY_ROOT, "", IMPORT_FOLDER));
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$import_process_id = uniqid("import_");
	define("IMPORT_PROCESS_ID", string_strtolower($import_process_id));
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	setting_get("sitemgr_send_email",$sitemgr_send_email);
    setting_get("sitemgr_email", $sitemgr_email);
    setting_get("sitemgr_import_email", $sitemgr_import_email);
	setting_get("edir_default_language", $edir_default_language);
	setting_get("default_url", $default_url);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	if (!$edir_default_language) {
        $edir_default_language = 'en_us';
    }
	require_once(EDIRECTORY_ROOT.'/lang/'.$edir_default_language.'.php');
	require_once(EDIRECTORY_ROOT.'/lang/'.$edir_default_language.'_sitemgr.php');
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$import_stop = false;
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	function getmicrotime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	$time_start = getmicrotime();
	import_logDebug("++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
	import_logDebug("Start Date/Time: ".date("Y-m-d H:i:s"));
	////////////////////////////////////////////////////////////////////////////////////////////////////

    $messageLog = "Check if there is any data left on ImportTemporary from previous imports - LINE: ".__LINE__;
    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
	$no_data_importtemporarytable = false;
	$sql = "SELECT id FROM ImportTemporary WHERE inserted = 'n' AND import_log_id = ".$last_import_log;
	$result = mysql_query($sql, $linkDomain);
	if (!mysql_num_rows($result)) {
		$no_data_importtemporarytable = true;
		import_logDebug("No data in import temporary table - LINE: ".__LINE__);
        $messageLog = "No data in import temporary table - LINE: ".__LINE__;
        log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($no_data_importtemporarytable) {
		$sql = "SELECT id FROM ImportLog WHERE status = 'P' AND action = 'RI' AND type = 'listing' ORDER BY id LIMIT 1";
		$result = mysql_query($sql, $linkDomain);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			$importlog_id = $row["id"];
			$sql = "UPDATE ImportLog SET status = 'R' WHERE id = ".$importlog_id;
			mysql_query($sql, $linkDomain);
			import_logDebug("Changing status - running - LINE: ".__LINE__);
            $messageLog = "Changing status - running - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
		} else {
			$import_stop = true;
			import_logDebug("No pending process - LINE: ".__LINE__);
            $messageLog = "No pending process - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$time_total_location = 0;
	$time_total_category = 0;
	$time_total_account = 0;
	$time_total_listing = 0;
	$time_total_summary = 0;
	$time_total_type = 0;
	////////////////////////////////////////////////////////////////////////////////////////////////////
	if (!$import_stop) {

		$_locations = explode(",", EDIR_LOCATIONS);

        $messageLog = "Get import info from ImportLog - LINE: ".__LINE__;
        log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
		$sql = "SELECT id, phisicalname, update_itens, from_export, active_item, update_friendlyurl, featured_categs, default_level, same_account, account_id FROM ImportLog WHERE status = 'R' AND action = 'RI' AND type = 'listing' ORDER BY id LIMIT 1";
		$result = mysql_query($sql, $linkDomain);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			$importlog_id = $row["id"];
			$phisicalname = $row["phisicalname"];
            
            //Settings
			$update_listings = $row["update_itens"];
			$import_from_export = $row["from_export"];
			$import_sameaccount = $row["same_account"];
			$import_account_id = $row["account_id"];
			$import_enable_listing_active = $row["active_item"];
			$import_defaultlevel = $row["default_level"];
			$import_featured_categs = $row["featured_categs"];
			$import_update_friendlyurl = $row["update_friendlyurl"];
            
			$phisicalname = EDIRECTORY_ROOT.IMPORTFOLDER."/preview_".$phisicalname;
			$phisicalname = str_replace(".csv", ".php", $phisicalname);
			$aux_import_ids = "";
			$sql = "SELECT * FROM ImportTemporary WHERE import_log_id = '".$importlog_id."' AND inserted != 'y' ORDER BY id";

			$result = mysql_query($sql, $linkDomain);
			$current_listing = 0;
			$current_time = getmicrotime() - $time_start;
			$statusObj = new ItemStatus();
			$allStatus = $statusObj->getValueName();
			$defaultStatus = $statusObj->getDefaultStatus();
			$levelObj = new ListingLevel();

			####################################################################################################
			### IMPORT HISTORY
			####################################################################################################
			if ($no_data_importtemporarytable){
				if ($import_enable_listing_active == "y") {
					import_setHistory("LANG_SITEMGR_IMPORT_ALLIMPORTEDASACTIVE", $linkDomain, db_formatNumber($importlog_id));
				}

				if ($update_listings == "y"){
					import_setHistory("LANG_SITEMGR_IMPORT_ALLIMPORTEDUPDATED", $linkDomain, db_formatNumber($importlog_id));
				}

				if ($import_featured_categs == "y") {
					import_setHistory("LANG_SITEMGR_IMPORT_FEATURED_CATEGS", $linkDomain, db_formatNumber($importlog_id));
				}
                
                if ($import_update_friendlyurl == "y") {
					import_setHistory("LANG_SITEMGR_SETTINGS_IMPORT_FRIENDLYURL", $linkDomain, db_formatNumber($importlog_id));
				}
			}

			$message_SameAccount = false;

			if ($no_data_importtemporarytable){
				import_setHistory("LANG_SITEMGR_IMPORT_DEFAULTLEVELSETTO[".$levelObj->showLevel($import_defaultlevel)."].", $linkDomain, db_formatNumber($importlog_id));
			}
			####################################################################################################

			$sqlListing_Category = "INSERT INTO Listing_Category (listing_id, category_id, status, category_root_id, category_node_left, category_node_right) VALUES ";
			$auxT = 0;

            $messageLog = "Starting import - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
			while (($row = mysql_fetch_assoc($result)) && !$import_stop && ($current_listing < IMPORT_MAX_LISTINGS_PERTIME) && ($current_time < IMPORT_MAX_SECONDS_PERTIME)) {
				$needToUpdate = false;

				$sqlAux = "SELECT linesadded, totallines, status FROM ImportLog WHERE id = ".$importlog_id." AND status = 'R' AND action = 'RI' AND type = 'listing'";
				$resultAux = mysql_query($sqlAux, $linkDomain);
				$rowAux = mysql_fetch_assoc($resultAux);

				$lines_added = $rowAux["linesadded"];
				$totallines = $rowAux["totallines"];
				$status = $rowAux["status"];
                
                if ($row["error"] == "n") {

                    if ($update_listings == "y" && $row["listing_id"]){
                        $sqlUpdate = "SELECT id, status FROM Listing WHERE id = ".$row["listing_id"];

                        $resultUpdate = mysql_query($sqlUpdate, $linkDomain);
                        if(mysql_num_rows($resultUpdate) > 0){
                            $needToUpdate = true;
                            $rowUpdate = mysql_fetch_assoc($resultUpdate);
                            $old_status = $row["status"];
                        }
                    }

                    ####################################################################################################
                    ### LOCATIONS
                    ####################################################################################################
                    $time_start_aux = getmicrotime();

                    foreach ($_locations as $_location_level) {

                        system_retrieveLocationRelationship ($_locations, $_location_level, $_location_father_level, $_location_child_level);

                        ${"location_".$_location_level} = 0;

                        $flag_continue = false;
                        if (!$_location_father_level) {
                            $flag_continue = true;
                        } else {
                            if (${"location_".$_location_father_level} != 0) {
                                $flag_continue = true;
                            }
                        }

                        if ($flag_continue) {

                            //just do something if exists location name. If theres an abbreviation, but no name, do nothing
                            if ($row["listing_location".$_location_level]) {

                                $father_filter = "";
                                if ($_location_father_level) {
                                    $father_filter = "AND location_".$_location_father_level." = ".db_formatNumber(${"location_".$_location_father_level});
                                }

                                $result_location_num_rows = 0;
                                $result_abbreviation_num_rows = 0;
                                $sqlLocation = "SELECT id FROM Location_".$_location_level." WHERE name = ".db_formatString(trim($row["listing_location".$_location_level]), "", true)." ".$father_filter;
                                $result_location = mysql_query($sqlLocation, $link);
                                $result_location_num_rows = mysql_num_rows($result_location);

                                //If theres abbreviation, check if it already exists at database. If so, don't add the new location in case it was typed wrong
                                if ($row["listing_location".$_location_level."_abbreviation"]){
                                    $sqlLocation = "SELECT id FROM Location_".$_location_level." WHERE abbreviation = ".db_formatString($row["listing_location".$_location_level."_abbreviation"], "", true)." ".$father_filter;
                                    $result_location_abbreviation = mysql_query($sqlLocation, $link);
                                    $result_abbreviation_num_rows = mysql_num_rows($result_location_abbreviation);
                                }

                                if (($result_location_num_rows<=0) && ($result_abbreviation_num_rows<=0)) { //location not found, insert new one

                                    ${$_location_level."_friendly_url"} = preg_replace("/[^".FRIENDLYURL_VALIDCHARS."]/", FRIENDLYURL_SEPARATOR, $row["listing_location".$_location_level]);
                                    ${$_location_level."_friendly_url"} = string_strtolower(preg_replace("/[\\".FRIENDLYURL_SEPARATOR."]{2,}/", FRIENDLYURL_SEPARATOR, ${$_location_level."_friendly_url"}));

                                    unset($fathers_values);
                                    $fathers_values = false;
                                    $select_father_chain = "";
                                    foreach ($_locations as $each_location) {

                                        if ($each_location<$_location_level) {
                                            $fathers_values['levels'][] = "location_".$each_location;
                                            $fathers_values['ids'][]    = ${"location_".$each_location};
                                            $query_father_chain .= "location_".${"location_".$each_location}." = ".${"location_".$each_location}." AND ";
                                        }
                                    }

                                    $sqlLocation = "INSERT INTO Location_".$_location_level." (".($fathers_values?implode(',',$fathers_values['levels']).',':'')."`name`, `friendly_url`, `abbreviation`, `seo_description`, `seo_keywords`) VALUES (".($fathers_values?implode(',',$fathers_values['ids']).',':'')."".db_formatString(trim($row["listing_location".$_location_level]), "", true).", ".db_formatString(${$_location_level."_friendly_url"}).", ".db_formatString($row["listing_location".$_location_level."_abbreviation"], "", true).", '', '')";
                                    mysql_query($sqlLocation, $link);
                                    ${"location_".$_location_level} = mysql_insert_id($link);
                                    $sqlLocation = "SELECT id FROM Location_".$_location_level." WHERE id != ".${"location_".$_location_level}." AND ".($fathers_values?$select_father_chain:'')." friendly_url = ".db_formatString(${$_location_level."_friendly_url"})."";
                                    $result_location = mysql_query($sqlLocation, $link);
                                    if (mysql_num_rows($result_location) > 0) {
                                        ${$_location_level."_friendly_url"} .= FRIENDLYURL_SEPARATOR.${"location_".$_location_level};
                                        $sqlLocation = "UPDATE Location_".$_location_level." SET friendly_url = ".db_formatString(${$_location_level."_friendly_url"})." WHERE ".($fathers_values?$select_father_chain:'')." id = ".${"location_".$_location_level}."";
                                        mysql_query($sqlLocation, $link);
                                    }
                                //found location or location abbreviation
                                } else {
                                    if ($row["listing_location".$_location_level."_abbreviation"]){
                                        if ($result_abbreviation_num_rows > 0){
                                            $row_location_abbreviation = mysql_fetch_assoc($result_location_abbreviation);
                                            ${"location_".$_location_level} = $row_location_abbreviation["id"];
                                        }else{
                                            $row_location = mysql_fetch_assoc($result_location);
                                            ${"location_".$_location_level} = $row_location["id"];
                                            $sqlLocation = "UPDATE Location_".$_location_level." SET abbreviation = ".db_formatString($row["listing_location".$_location_level."_abbreviation"], "", true)." WHERE id = ".${"location_".$_location_level}."";
                                            $result_location = mysql_query($sqlLocation, $link);
                                        }
                                    } else {
                                        $row_location = mysql_fetch_assoc($result_location);
                                        ${"location_".$_location_level} = $row_location["id"];
                                    }
                                }
                            }
                        }
                    }
                    $time_end_aux = getmicrotime();
                    $time_aux = $time_end_aux - $time_start_aux;
                    $time_total_location = $time_total_location + $time_aux;
                    ####################################################################################################

                    ####################################################################################################
                    ### CATEGORY
                    ####################################################################################################
                    $time_start_aux = getmicrotime();

                    unset($categoryIDArray);
                    unset($current_listing_categories);
                    if ($row["listing_category_1"]) {
                        $current_listing_categories[] = $row["listing_category_1"];
                    }
                    if ($row["listing_category_2"]) {
                        $current_listing_categories[] = $row["listing_category_2"];
                    }
                    if ($row["listing_category_3"]) {
                        $current_listing_categories[] = $row["listing_category_3"];
                    }
                    if ($row["listing_category_4"]) {
                        $current_listing_categories[] = $row["listing_category_4"];
                    }
                    if ($row["listing_category_5"]) {
                        $current_listing_categories[] = $row["listing_category_5"];
                    }
                    $i = 0;
                    if ($current_listing_categories) {
                        foreach ($current_listing_categories as $current_listing_category) {
                            unset($current_category_tree);
                            $current_category_tree = explode("->", $current_listing_category);
                            while (count($current_category_tree) > CATEGORY_LEVEL_AMOUNT) {
                                array_pop($current_category_tree);
                            }
                            for ($count=0; $count<count($current_category_tree); $count++) {
                                $current_category_tree[$count] = trim($current_category_tree[$count]);
                            }
                            $j = 0;
                            $last_category_id = 0;
                            if ($current_category_tree) {
                                foreach ($current_category_tree as $current_category) {
                                    $sqlCategory = "SELECT id FROM ListingCategory WHERE category_id = ".db_formatNumber($last_category_id)." AND title = ".db_formatString($current_category, "", true)."";
                                    $resultCategory = mysql_query($sqlCategory, $linkDomain);
                                    if (mysql_num_rows($resultCategory) <= 0) {
                                        $category_friendly_url = preg_replace("/[^".FRIENDLYURL_VALIDCHARS."]/", FRIENDLYURL_SEPARATOR, $current_category);
                                        $category_friendly_url = string_strtolower(preg_replace("/[\\".FRIENDLYURL_SEPARATOR."]{2,}/", FRIENDLYURL_SEPARATOR, $category_friendly_url));

                                        unset($listingCategoryObj);
                                        $listingCategoryObj = new ListingCategory();
                                        $listingCategoryObj->setString("title",$current_category);
                                        $listingCategoryObj->SetNumber("category_id",$last_category_id);
                                        $listingCategoryObj->setString("page_title",$current_category);
                                        $listingCategoryObj->setString("friendly_url",$category_friendly_url);
                                        $listingCategoryObj->setString("featured",$import_featured_categs == "y" ? "y" : "n");
                                        $listingCategoryObj->setString("enabled", "y");

                                        $listingCategoryObj->Save(false);
                                        $current_category_id = $listingCategoryObj->getNumber("id");

                                        $sqlCategory = "SELECT id FROM ListingCategory WHERE id != ".$current_category_id." AND category_id = ".db_formatNumber($last_category_id)." AND friendly_url = ".db_formatString($category_friendly_url)."";
                                        $resultCategory = mysql_query($sqlCategory, $linkDomain);
                                        if (mysql_num_rows($resultCategory) > 0) {
                                            $category_friendly_url .= FRIENDLYURL_SEPARATOR.$current_category_id;
                                            $sqlCategory = "UPDATE ListingCategory SET friendly_url = ".db_formatString($category_friendly_url)." WHERE id = ".$current_category_id."";
                                            mysql_query($sqlCategory, $linkDomain);
                                        }
                                        $listingCategoryObj->updateFullFriendlyURL();
                                    } else {
                                        $rowCategory = mysql_fetch_assoc($resultCategory);
                                        $current_category_id = $rowCategory["id"];
                                    }
                                    $last_category_id = $current_category_id;
                                    $categoryIDArray[$i][$j] = $current_category_id;
                                    $j++;
                                }
                            }
                            $i++;
                        }
                    }
                    $time_end_aux = getmicrotime();
                    $time_aux = $time_end_aux - $time_start_aux;
                    $time_total_category = $time_total_category + $time_aux;
                    ####################################################################################################

                    ####################################################################################################
                    ### ACCOUNT
                    ####################################################################################################
                    $time_start_aux = getmicrotime();

                    $account_id = 0;
                    if ($import_sameaccount == "y") {
                        $accountObj = new Account($import_account_id);
                        $sqlSameAccount = "SELECT history FROM ImportLog WHERE id = ".db_formatNumber($importlog_id)." AND type = 'listing'";
                        $resultSameAccount = mysql_query($sqlSameAccount, $linkDomain);
                        $import_sameaccount_message = false;
                        if (mysql_num_rows($resultSameAccount) > 0) {
                            $rowSameAccount = mysql_fetch_assoc($resultSameAccount);
                            if (string_strpos($rowSameAccount["history"], "same account") === false) {
                                $import_sameaccount_message = true;
                            }
                        }
                        if ($accountObj->getNumber("id") > 0) {
                            $account_id = $accountObj->getNumber("id");
                            if ($import_sameaccount_message) {
                                if (!$message_SameAccount){
                                    $message_SameAccount = true;
                                    if ($no_data_importtemporarytable){
                                        import_setHistory("LANG_SITEMGR_IMPORT_WILLBEIMPORTEDTOSAMEACCOUNT[".$accountObj->getString("username")."].", $linkDomain, db_formatNumber($importlog_id));
                                    }
                                }
                                import_logDebug("All ".LISTING_FEATURE_NAME_PLURAL." will be imported to same account: ".$accountObj->getString("username")." - LINE: ".__LINE__);
                            }
                        } else {
                            if ($import_sameaccount_message) {
                                import_setHistory("LANG_SITEMGR_IMPORT_IMPORTTOSAMEACCOUNTINVALIDACCOUNTID", $linkDomain, db_formatNumber($importlog_id));
                                import_logDebug("Import to same account: invalid account id - LINE: ".__LINE__);
                            }
                        }
                    } else {
                        if ($row["account_username"]) {
                            $sqlAccount = "SELECT id FROM Account WHERE username = ".db_formatString($row["account_username"])."";
                            $resultAccount = mysql_query($sqlAccount, $link);
                            if (mysql_num_rows($resultAccount) <= 0) {
                                $sqlAccount = "INSERT INTO Account (updated, entered, agree_tou, lastlogin, username, password, importID, domain_importID, is_sponsor, has_profile) VALUES ('".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 1, 0, ".db_formatString($row["account_username"]).", ".db_formatString(((($import_from_export == "n") && (string_strtolower(PASSWORD_ENCRYPTION) == "on")) ? md5($row["account_password"]) : $row["account_password"])).", ".db_formatNumber($importlog_id).", ".db_formatNumber(SELECTED_DOMAIN_ID).", 'y', 'n')";
                                mysql_query($sqlAccount, $link);
                                $account_id = mysql_insert_id($link);
                                $sqlContact = "INSERT INTO Contact (account_id, updated, entered, first_name, last_name, company, address, address2, country, state, city, zip, phone, fax, email, url, importID) VALUES (".db_formatNumber($account_id).", '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', ".db_formatString($row["account_first_name"], "", true).", ".db_formatString($row["account_last_name"], "", true).", ".db_formatString($row["account_company"], "", true).", ".db_formatString($row["account_address"], "", true).", ".db_formatString($row["account_address2"], "", true).", ".db_formatString($row["account_country"], "", true).", ".db_formatString($row["account_state"], "", true).", ".db_formatString($row["account_city"], "", true).", ".db_formatString($row["account_zip"], "", true).", ".db_formatString($row["account_phone"], "", true).", ".db_formatString($row["account_fax"], "", true).", ".db_formatString($row["account_email"], "", true).", ".db_formatString($row["account_url"], "", true).", ".db_formatNumber($importlog_id).")";
                                mysql_query($sqlContact, $link);
                                $sqlProfile = "INSERT INTO Profile (account_id, nickname, friendly_url) VALUES (".db_formatNumber($account_id).", ".db_formatString($row["account_first_name"]." ".$row["account_last_name"], "", true).", '".md5($account_id)."')";
                                mysql_query($sqlProfile, $link);
                            } else {
                                $rowAccount = mysql_fetch_assoc($resultAccount);
                                $account_id = $rowAccount["id"];
                            }
                            $accDomain = new Account_Domain($account_id, SELECTED_DOMAIN_ID);
                            $accDomain->Save();
                            $accDomain->saveOnDomain($account_id);
                        }
                    }
                    $time_end_aux = getmicrotime();
                    $time_aux = $time_end_aux - $time_start_aux;
                    $time_total_account = $time_total_account + $time_aux;
                    ####################################################################################################

                    ####################################################################################################
                    ### LISTING TEMPLATE
                    ####################################################################################################
                    $time_start_aux = getmicrotime();

                    $listingtemplate_id = 0;
                    
                    if (USING_THEME_TEMPLATE && THEME_TEMPLATE_ID) {
                        $listingtemplate_id = THEME_TEMPLATE_ID;
                    } elseif ($row["listing_template"]) {
                        $sqlTemplate = "SELECT id FROM ListingTemplate WHERE title = ".db_formatString($row["listing_template"], "", true)." AND editable = 'y'";
                        $resultTemplate = mysql_query($sqlTemplate, $linkDomain);
                        if (mysql_num_rows($resultTemplate)) {
                            $rowTemplate = mysql_fetch_assoc($resultTemplate);
                            $listingtemplate_id = $rowTemplate["id"];
                        }
                    }

                    $time_end_aux = getmicrotime();
                    $time_aux = $time_end_aux - $time_start_aux;
                    $time_total_type = $time_total_type + $time_aux;
                    ####################################################################################################
                    ### LISTING
                    ####################################################################################################
                    $time_start_aux = getmicrotime();

                    $listing_id = 0;
                    if ($row["listing_title"]) {
                        if ($import_enable_listing_active == "y") {
                            $listing_status = trim(system_showText(LANG_LABEL_ACTIVE));
                        } else {
                            $listing_status = $row["listing_status"];
                        }
                        $validStatus = "";
                        foreach ($allStatus as $eachStatusValue=>$eachStatusName) {
                            if ($eachStatusName && $listing_status) {
                                if (string_strpos(string_strtoupper($eachStatusName), string_strtoupper($listing_status)) !== false) {
                                    $validStatus = $eachStatusValue;
                                }
                            }
                        }
                        if ($validStatus) {
                            $listing_status = $validStatus;
                        } else {
                            $listing_status = $defaultStatus;
                        }
                        $allLevels = $levelObj->getValueName();
                        $validLevel = "";
                        foreach ($allLevels as $eachLevelValue=>$eachLevelName) {
                            if ($row["listing_level"]) {
                                if (string_strpos(string_strtoupper($eachLevelName), string_strtoupper($row["listing_level"])) !== false) {
                                    $validLevel = $eachLevelValue;
                                }
                            }
                        }
                        if ($validLevel) {
                            $listing_level = $validLevel;
                        } else {
                            if ($import_defaultlevel) {
                                $listing_level = $import_defaultlevel;
                            } else {
                                $listing_level = $levelObj->getDefaultLevel();
                            }
                        }
                        $listing_friendly_url = preg_replace("/[^".FRIENDLYURL_VALIDCHARS."]/", FRIENDLYURL_SEPARATOR, $row["listing_title"]);
                        $listing_friendly_url = string_strtolower(preg_replace("/[\\".FRIENDLYURL_SEPARATOR."]{2,}/", FRIENDLYURL_SEPARATOR, $listing_friendly_url));
                        $listing_friendly_url = $listing_friendly_url.FRIENDLYURL_SEPARATOR.uniqid();

                        $locations_chain_field = "";
                        $locations_chain_value = "";
                        $locations_chain_update = "";
                        foreach ($_locations as $_location_level) {
                            $locations_chain_field .= "location_".$_location_level.", ";
                            $locations_chain_value .= db_formatNumber(${"location_".$_location_level}).", ";
                            $locations_chain_update .= "location_".$_location_level." = ".db_formatNumber(${"location_".$_location_level}).", ";
                        }

                        if ($needToUpdate){
                            $sqlListing = "UPDATE Listing SET ";
                            $sqlListing .= "account_id = ".db_formatNumber($account_id).", ";
                            $sqlListing .= $locations_chain_update;
                            $sqlListing .= "updated = '".date('Y-m-d H:i:s')."', ";
                            $sqlListing .= "renewal_date = '".import_formatDate($row["listing_renewal_date"])."', ";
                            $sqlListing .= "title = ".db_formatString($row["listing_title"], "", true).", ";
                            $sqlListing .= "seo_title = ".db_formatString($row["listing_title"], "", true).", ";
                            if ($import_update_friendlyurl == "y") {
                                $sqlListing .= "friendly_url = ".db_formatString($listing_friendly_url).", ";
                            }
                            $sqlListing .= "email = ".db_formatString($row["listing_email"], "", true).", ";
                            $sqlListing .= "url = ".db_formatString($row["listing_url"], "", true).", ";
                            $sqlListing .= "address = ".db_formatString($row["listing_address"], "", true).", ";
                            $sqlListing .= "address2 = ".db_formatString($row["listing_address2"], "", true).", ";
                            $sqlListing .= "zip_code = ".db_formatString($row["listing_zip"], "", true).", ";
                            $sqlListing .= "latitude = ".db_formatString($row["listing_latitude"], "", true).", ";
                            $sqlListing .= "longitude = ".db_formatString($row["listing_longitude"], "", true).", ";
                            $sqlListing .= "phone = ".db_formatString($row["listing_phone"], "", true).", ";
                            $sqlListing .= "fax = ".db_formatString($row["listing_fax"], "", true).", ";
                            $sqlListing .= "description = ".db_formatString($row["listing_description"], "", true).", ";
                            $sqlListing .= "seo_description = ".db_formatString($row["listing_description"], "", true).", ";
                            $sqlListing .= "long_description = ".db_formatString($row["listing_long_description"], "", true).", ";
                            $sqlListing .= "keywords = ".db_formatString($row["listing_keyword"], "", true).", ";
                            $sqlListing .= "seo_keywords = ".db_formatString(str_replace(" || ", ",", $row["listing_keyword"]), "", true).", ";
                            $sqlListing .= "status = ".db_formatString($listing_status).", ";
                            $sqlListing .= "level = ".db_formatString($listing_level).", ";
                            $sqlListing .= "importID = ".db_formatNumber($importlog_id).", ";
                            $sqlListing .= "listingtemplate_id = ".db_formatNumber($listingtemplate_id)." ";
                            $sqlListing .= "WHERE id = ".$row["listing_id"];
                        } else {
                            $sqlListing = "INSERT INTO Listing (account_id,
                                                                image_id,
                                                                thumb_id,
                                                                promotion_id,
                                                                ".$locations_chain_field."
                                                                updated,
                                                                entered,
                                                                renewal_date,
                                                                discount_id,
                                                                title,
                                                                seo_title,
                                                                friendly_url,
                                                                email,
                                                                url,
                                                                display_url,
                                                                address,
                                                                address2,
                                                                zip_code,
                                                                latitude,
                                                                longitude,
                                                                phone,
                                                                fax,
                                                                description,
                                                                seo_description,
                                                                long_description,
                                                                keywords,
                                                                seo_keywords,
                                                                attachment_file,
                                                                attachment_caption,
                                                                status,
                                                                level,
                                                                fulltextsearch_keyword,
                                                                fulltextsearch_where,
                                                                video_snippet,
                                                                importID,
                                                                hours_work,
                                                                locations,
                                                                listingtemplate_id,
                                                                custom_text0,
                                                                custom_text1,
                                                                custom_text2,
                                                                custom_text3,
                                                                custom_text4,
                                                                custom_text5,
                                                                custom_text6,
                                                                custom_text7,
                                                                custom_text8,
                                                                custom_text9,
                                                                custom_short_desc0,
                                                                custom_short_desc1,
                                                                custom_short_desc2,
                                                                custom_short_desc3,
                                                                custom_short_desc4,
                                                                custom_short_desc5,
                                                                custom_short_desc6,
                                                                custom_short_desc7,
                                                                custom_short_desc8,
                                                                custom_short_desc9,
                                                                custom_long_desc0,
                                                                custom_long_desc1,
                                                                custom_long_desc2,
                                                                custom_long_desc3,
                                                                custom_long_desc4,
                                                                custom_long_desc5,
                                                                custom_long_desc6,
                                                                custom_long_desc7,
                                                                custom_long_desc8,
                                                                custom_long_desc9,
                                                                custom_checkbox0,
                                                                custom_checkbox1,
                                                                custom_checkbox2,
                                                                custom_checkbox3,
                                                                custom_checkbox4,
                                                                custom_checkbox5,
                                                                custom_checkbox6,
                                                                custom_checkbox7,
                                                                custom_checkbox8,
                                                                custom_checkbox9,
                                                                custom_dropdown0,
                                                                custom_dropdown1,
                                                                custom_dropdown2,
                                                                custom_dropdown3,
                                                                custom_dropdown4,
                                                                custom_dropdown5,
                                                                custom_dropdown6,
                                                                custom_dropdown7,
                                                                custom_dropdown8,
                                                                custom_dropdown9)
                                                                VALUES
                                                                (".db_formatNumber($account_id).",
                                                                    0,
                                                                    0,
                                                                    0,
                                                                    ".$locations_chain_value."
                                                                    '".date('Y-m-d H:i:s')."',
                                                                    '".date('Y-m-d H:i:s')."',
                                                                    '".import_formatDate($row["listing_renewal_date"])."',
                                                                    '',
                                                                    ".db_formatString($row["listing_title"], "", true).",
                                                                    ".db_formatString($row["listing_title"], "", true).",
                                                                    ".db_formatString($listing_friendly_url).",
                                                                    ".db_formatString($row["listing_email"], "", true).",
                                                                    ".db_formatString($row["listing_url"], "", true).",
                                                                    '',
                                                                    ".db_formatString($row["listing_address"], "", true).",
                                                                    ".db_formatString($row["listing_address2"], "", true).",
                                                                    ".db_formatString($row["listing_zip"], "", true).",
                                                                    ".db_formatString($row["listing_latitude"], "", true).",
                                                                    ".db_formatString($row["listing_longitude"], "", true).",
                                                                    ".db_formatString($row["listing_phone"], "", true).",
                                                                    ".db_formatString($row["listing_fax"], "", true).",
                                                                    ".db_formatString($row["listing_description"], "", true).",
                                                                    ".db_formatString($row["listing_description"], "", true).",
                                                                    ".db_formatString($row["listing_long_description"], "", true).",
                                                                    ".db_formatString($row["listing_keyword"], "", true).",
                                                                    ".db_formatString(str_replace(" || ", ",", $row["listing_keyword"]), "", true).",
                                                                    '',
                                                                    '',
                                                                    ".db_formatString($listing_status).",
                                                                    ".db_formatString($listing_level).",
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    ".db_formatNumber($importlog_id).",
                                                                    '',
                                                                    '',
                                                                    ".db_formatNumber($listingtemplate_id).",
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '',
                                                                    '')";
                        }
                        mysql_query($sqlListing, $linkDomain);
                        $listing_id = mysql_insert_id($linkDomain);

                        if ($needToUpdate){
                            $listing_id = $row["listing_id"];
                        } else {
                            $listing_id = mysql_insert_id($linkDomain);
                        }

                        if (!$needToUpdate){
                            $sqlGallery = "
                                    INSERT INTO `Gallery`
                                        (`account_id`, `title`, `entered`, `updated`)
                                    VALUES
                                    (
                                        ".db_formatNumber($account_id).",
                                        ".db_formatString($row["listing_title"], "", true).",
                                        NOW(),
                                        NOW()
                                    )";
                            mysql_query($sqlGallery, $linkDomain);
                            $gallery_id = mysql_insert_id($linkDomain);

                            $sqlGalleryI = "
                                    INSERT INTO `Gallery_Item`
                                        (`item_type`, `item_id`, `gallery_id`)
                                    VALUES
                                    (
                                        'listing',
                                        ".db_formatNumber($listing_id).",
                                        ".db_formatString($gallery_id)."
                                    )";
                            mysql_query($sqlGalleryI, $linkDomain);
                        }
                        /*
                        * Increment the Dashboard Values
                        */
                        if (!$needToUpdate){
                            domain_updateDashboard("number_listings","inc");
                        }

                        if ($needToUpdate){
                            if ($old_status != "P" && $listing_status == "P"){
                                activity_newToApproved(SELECTED_DOMAIN_ID, $listing_id, "listing", db_formatString($row["listing_title"], "", true));
                            }

                        } else {
                            if ($listing_status == "P"){
                                activity_newToApproved(SELECTED_DOMAIN_ID, $listing_id, "listing", db_formatString($row["listing_title"], "", true));
                            }
                        }

                        if ($needToUpdate){
                            $sqlDeleteCat = "DELETE FROM Listing_Category WHERE listing_id = $listing_id";
                            mysql_query($sqlDeleteCat, $linkDomain);
                        }

                        if ($categoryIDArray){
                            $count_cat_aux = 1;
                            foreach ($categoryIDArray as $category_id_tree) {

                                unset($listing_categoryObj, $lCatObj);

                                $lCatObj = new ListingCategory(end($category_id_tree));

                                $sqlListing_Category .= "(".$listing_id.",".end($category_id_tree).",'".str_replace("'", "", $listing_status)."',".$lCatObj->getNumber("root_id").",".$lCatObj->getNumber("left").",".$lCatObj->getNumber("right")."),";
                            }
                        }

                        unset($fulltextsearch_keyword);
                        unset($fulltextsearch_where);
                        if ($row["listing_title"]) {
                            $fulltextsearch_keyword[] = $row["listing_title"];
                        }
                        if ($row["listing_keyword"]) {
                            $fulltextsearch_keyword[] = str_replace(" || ", " ", $row["listing_keyword"]);
                        }
                        if ($row["listing_address"]) {
                            $fulltextsearch_where[] = $row["listing_address"];
                        }
                        if ($row["listing_zip"]) {
                            $fulltextsearch_where[] = $row["listing_zip"];
                        }

                        foreach ($_locations as $_location_level) {
                            if ($row["listing_location".$_location_level]) {
                                $fulltextsearch_where[] = $row["listing_location".$_location_level];
                            }
                            if($row["listing_location".$_location_level."_abbreviation"]){
                                $fulltextsearch_where[] = $row["listing_location".$_location_level."_abbreviation"];
                            }
                        }

                        if ($row["listing_category_1"]) {
                            $fulltextsearch_keyword[] = str_replace("->", " ", $row["listing_category_1"]);
                        }
                        if ($row["listing_category_2"]) {
                            $fulltextsearch_keyword[] = str_replace("->", " ", $row["listing_category_2"]);
                        }
                        if ($row["listing_category_3"]) {
                            $fulltextsearch_keyword[] = str_replace("->", " ", $row["listing_category_3"]);
                        }
                        if ($row["listing_category_4"]) {
                            $fulltextsearch_keyword[] = str_replace("->", " ", $row["listing_category_4"]);
                        }
                        if ($row["listing_category_5"]) {
                            $fulltextsearch_keyword[] = str_replace("->", " ", $row["listing_category_5"]);
                        }
                        if ($row["listing_description"]) {
                            $fulltextsearch_keyword[] = string_substr($row["listing_description"], 0, 100);
                        }
                        if ($fulltextsearch_keyword) {
                            $fulltextsearch_keyword_sql = db_formatString(implode(" ", $fulltextsearch_keyword), "", true);
                            $sqlFullTextSearch = "UPDATE Listing SET fulltextsearch_keyword = ".$fulltextsearch_keyword_sql." WHERE id = ".db_formatNumber($listing_id)."";
                            mysql_query($sqlFullTextSearch, $linkDomain);
                        }
                        if ($fulltextsearch_where) {
                            $fulltextsearch_where_sql = db_formatString(implode(" ", $fulltextsearch_where), "", true);
                            $sqlFullTextSearch = "UPDATE Listing SET fulltextsearch_where = ".$fulltextsearch_where_sql." WHERE id = ".db_formatNumber($listing_id)."";
                            mysql_query($sqlFullTextSearch, $linkDomain);
                        }

                        if (ZIPCODE_PROXIMITY == "on") {
                            zipproximity_updateDB("Listing", $listing_id);
                        }
                        $time_end_aux = getmicrotime();
                        $time_aux = $time_end_aux - $time_start_aux;
                        $time_total_listing = $time_total_listing + $time_aux;

                        if ($needToUpdate){

                            //Location
                            $sqlListingSummary_Loc = "";
                            $locField = explode(",", $locations_chain_field);
                            $locValue = explode(",", $locations_chain_value);
                            for ($i = 0; $i < count($locField); $i++) {
                                unset($locObj);
                                unset($objAux);
                                $objAux = str_replace("_", "", $locField[$i]);
                                $objAux = trim($objAux);
                                $objAux = ucfirst($objAux);
                                if ($objAux) {
                                    $locObj = new $objAux($locValue[$i]);
                                    $sqlListingSummary_Loc .= trim($locField[$i])." = ".db_formatNumber($locValue[$i]).", ";
                                    $sqlListingSummary_Loc .= trim($locField[$i])."_title =".db_formatString($locObj->getString("name"), "", true).", ";
                                    $sqlListingSummary_Loc .= trim($locField[$i])."_abbreviation =".db_formatString($locObj->getString("abbreviation"), "", true).", ";
                                    $sqlListingSummary_Loc .= trim($locField[$i])."_friendly_url =".db_formatString($locObj->getString("friendly_url")).", ";
                                }
                            }

                            $sqlListingSummary = "UPDATE Listing_Summary SET ";
                            $sqlListingSummary .= $sqlListingSummary_Loc;
                            $sqlListingSummary .= "account_id = ".db_formatNumber($account_id).", ";
                            $sqlListingSummary .= "updated = '".date('Y-m-d H:i:s')."', ";
                            $sqlListingSummary .= "renewal_date = '".import_formatDate($row["listing_renewal_date"])."', ";
                            $sqlListingSummary .= "title = ".db_formatString($row["listing_title"], "", true).", ";
                            if ($import_update_friendlyurl == "y") {
                                $sqlListingSummary .= "friendly_url = ".db_formatString($listing_friendly_url).", ";
                            }
                            $sqlListingSummary .= "email = ".db_formatString($row["listing_email"], "", true).", ";
                            $sqlListingSummary .= "url = ".db_formatString($row["listing_url"], "", true).", ";
                            $sqlListingSummary .= "address = ".db_formatString($row["listing_address"], "", true).", ";
                            $sqlListingSummary .= "address2 = ".db_formatString($row["listing_address2"], "", true).", ";
                            $sqlListingSummary .= "zip_code = ".db_formatString($row["listing_zip"], "", true).", ";
                            $sqlListingSummary .= "latitude = ".db_formatString($row["listing_latitude"], "", true).", ";
                            $sqlListingSummary .= "longitude = ".db_formatString($row["listing_longitude"], "", true).", ";
                            $sqlListingSummary .= "phone = ".db_formatString($row["listing_phone"], "", true).", ";
                            $sqlListingSummary .= "fax = ".db_formatString($row["listing_fax"], "", true).", ";
                            $sqlListingSummary .= "description = ".db_formatString($row["listing_description"], "", true).", ";
                            if ($fulltextsearch_keyword) $sqlListingSummary .= "fulltextsearch_keyword = ".db_formatString(implode(" ", $fulltextsearch_keyword), "", true).", ";
                            if ($fulltextsearch_where)  $sqlListingSummary .= "fulltextsearch_where = ".db_formatString(implode(" ", $fulltextsearch_where), "", true).", ";
                            $sqlListingSummary .= "status = ".db_formatString($listing_status).", ";
                            $sqlListingSummary .= "level = ".db_formatString($listing_level).", ";
                            $sqlListingSummary .= "listingtemplate_id = ".db_formatNumber($listingtemplate_id).", ";
                            $sqlListingSummary .= "template_layout_id = ".db_formatNumber($rowTemplate["layout_id"]).", ";
                            $sqlListingSummary .= "template_cat_id = ".db_formatNumber($rowTemplate["cat_id"]).", ";
                            $sqlListingSummary .= "template_title = ".db_formatString($rowTemplate["title"], "", true).", ";
                            $sqlListingSummary .= "template_status = ".db_formatString($rowTemplate["status"]).", ";
                            $sqlListingSummary .= "template_price = ".db_formatString($rowTemplate["price"])." ";
                            $sqlListingSummary .= "WHERE id = ".db_formatNumber($row["listing_id"]);

                            mysql_query($sqlListingSummary, $linkDomain);

                        } else {
                            /*
                            * Populate table to search on Front
                            */
                            $time_start_aux = getmicrotime();

                            unset($listingSummaryObj);
                            $listingSummaryObj = new ListingSummary();

                            $listingSummaryObj->setNumber("id", $listing_id);
                            $listingSummaryObj->setNumber("account_id", $account_id);
                            //Location
                            $locField = explode(",", $locations_chain_field);
                            $locValue = explode(",", $locations_chain_value);
                            for ($i = 0; $i < count($locField); $i++) {
                                unset($locObj);
                                unset($objAux);
                                $objAux = str_replace("_", "", $locField[$i]);
                                $objAux = trim($objAux);
                                $objAux = ucfirst($objAux);
                                if ($objAux) {
                                    $locObj = new $objAux($locValue[$i]);
                                    $listingSummaryObj->setNumber(trim($locField[$i]), $locValue[$i]);
                                    $listingSummaryObj->setString(trim($locField[$i])."_title", $locObj->getString("name"));
                                    $listingSummaryObj->setString(trim($locField[$i])."_abbreviation", $locObj->getString("abbreviation"));
                                    $listingSummaryObj->setString(trim($locField[$i])."_friendly_url", $locObj->getString("friendly_url"));
                                }
                            }
                            $listingSummaryObj->setString("title", $row["listing_title"]);
                            $listingSummaryObj->setString("friendly_url", $listing_friendly_url);
                            $listingSummaryObj->setString("email", $row["listing_email"]);
                            $listingSummaryObj->setString("url", $row["listing_url"]);
                            $listingSummaryObj->setString("address", $row["listing_address"]);
                            $listingSummaryObj->setString("address2", $row["listing_address2"]);
                            $listingSummaryObj->setString("zip_code", $row["listing_zip"]);
                            $listingSummaryObj->setString("latitude", $row["listing_latitude"]);
                            $listingSummaryObj->setString("longitude", $row["listing_longitude"]);
                            $listingSummaryObj->setString("phone", $row["listing_phone"]);
                            $listingSummaryObj->setString("fax", $row["listing_fax"]);
                            $listingSummaryObj->setString("description", $row["listing_description"]);
                            if ($fulltextsearch_keyword) $listingSummaryObj->setString("fulltextsearch_keyword", implode(" ", $fulltextsearch_keyword));
                            if ($fulltextsearch_where) $listingSummaryObj->setString("fulltextsearch_where", implode(" ", $fulltextsearch_where));
                            $listingSummaryObj->setString("status", $listing_status);
                            $listingSummaryObj->setString("level", $listing_level);
                            $listingSummaryObj->setNumber("listingtemplate_id", $listingtemplate_id);
                            $listingSummaryObj->setNumber("template_layout_id", $rowTemplate["layout_id"]);
                            $listingSummaryObj->setNumber("template_cat_id", $rowTemplate["cat_id"]);
                            $listingSummaryObj->setString("template_title", $rowTemplate["title"]);
                            $listingSummaryObj->setString("template_status", $rowTemplate["status"]);
                            $listingSummaryObj->setString("template_price", $rowTemplate["price"]);
                            $listingSummaryObj->setString("claim_disabled","n");
                            $listingSummaryObj->setString("renewal_date",import_formatDate($row["listing_renewal_date"]));
                            $listingSummaryObj->Add();

                            $time_end_aux = getmicrotime();
                            $time_aux = $time_end_aux - $time_start_aux;
                            $time_total_summary = $time_total_summary + $time_aux;
                        }
                        if (ZIPCODE_PROXIMITY == "on") {
                            zipproximity_updateDB("Listing_Summary", $listing_id);
                        }

                    }

                    ####################################################################################################
                }
                
                if ($totallines > 0){
                    $sql = "UPDATE ImportLog SET ".($row["error"] == "n" ? "linesadded = ".((int)$lines_added+1).", " : "")."progress = '".(floor(($row["file_line_number"]-1)/($totallines)*100))."%' WHERE id = '".$importlog_id."' AND type = 'listing'";
                    $result_total_log = mysql_query($sql, $linkDomain);
                }

                $aTS = getmicrotime();

                $aux_import_ids .= ($row["id"].",");
                $current_listing++;
                $current_time = getmicrotime() - $time_start;
                $aTE = getmicrotime();
                $auxT += $aTE - $aTS;

                $sqlv = "SELECT id FROM ImportLog WHERE id = ".$importlog_id." AND status = 'W' AND type = 'listing'";
				if(mysql_num_rows(mysql_query($sqlv, $linkDomain)) > 0){
					$import_stop = true;
                    $messageLog = "Sitemgr stopped import. Flag \"W\" found and it will be change to \"S\" on the next loop. - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
				}
			}

            $messageLog = "Update inserted records - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
			$sql = "UPDATE ImportTemporary SET inserted = 'y' WHERE id in (".(substr($aux_import_ids,-1) == "," ? substr($aux_import_ids,0,-1) : $aux_import_ids).")";
			$result = mysql_query($sql, $linkDomain);

            $messageLog = "Save Listing_Category relationship - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
			$sqlListing_Category = substr($sqlListing_Category, 0, -1);
			mysql_query($sqlListing_Category, $linkDomain);
			
			$sql = "SELECT count(`id`) AS `total` FROM `Account` WHERE `importID` = ".$importlog_id." AND `domain_importID` = ".db_formatNumber(SELECTED_DOMAIN_ID);
			$result = mysql_query($sql, $link);
			$row = mysql_fetch_assoc($result);
			$accounts_added = $row['total'];

			$sql = "SELECT count(`id`) AS `total` FROM `Listing` WHERE `importID` = ".$importlog_id;
			$result = mysql_query($sql, $linkDomain);
			$row = mysql_fetch_assoc($result);
			$listings_added = $row['total'];
			
            $messageLog = "Update ImportLog - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
			$sql = "UPDATE `ImportLog` SET `itens_added` = '$listings_added', `accounts_added` = '$accounts_added' WHERE `id` = $importlog_id AND type = 'listing'";
			mysql_query($sql, $linkDomain);

            $messageLog = "Check if this import has finished - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
			$sql = "SELECT id FROM ImportTemporary WHERE import_log_id = '".$importlog_id."' AND inserted = 'n' ORDER BY id LIMIT 1";
			$result = mysql_query($sql, $linkDomain);
			if (mysql_num_rows($result) <= 0) {
				if ($status == "R") {
                    $sitemgr_emails = explode(",",$sitemgr_email);
					$sitemgr_import_emails = explode(",",$sitemgr_import_email);

					// site manager warning message ////////////////////////////////////////////////////////////////////
					$sitemgr_msg = "
						<html>
							<head>
								<style>
									.email_style_settings{
										font-size:12px;
										font-family:Verdana, Arial, Sans-Serif;
										color:#000;
									}
								</style>
							</head>
							<body>
								<div class=\"email_style_settings\">
									Site Manager,<br /><br />
									Your listing import was done.<br /><br />
									You can see details at:<br />";
									$sitemgr_msg .= "<br /><a href=\"http://".$default_url."/".SITEMGR_ALIAS."/import/importlog.php?log_id=".$importlog_id."\" target=\"_blank\">http://".$default_url."/".SITEMGR_ALIAS."/import/importlog.php?log_id=".$importlog_id."</a><br /><br />
								</div>
							</body>
						</html>";
                             
                    $messageLog = "Notify sitemgr - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
                    if ($sitemgr_send_email == "on") {
                        if ($sitemgr_emails[0]) {
                            foreach ($sitemgr_emails as $sitemgr_email) {
                                system_mail($sitemgr_email, "[".EDIRECTORY_TITLE."] Listing Import Notification", $sitemgr_msg, EDIRECTORY_TITLE." <".$sitemgr_email.">", "text/html", '', '', $error);
                            }
                        }
                    }

					if ($sitemgr_import_emails[0]) {
						foreach ($sitemgr_import_emails as $sitemgr_import_email) {
							system_mail($sitemgr_import_email, "[".EDIRECTORY_TITLE."] Listing Import Notification", $sitemgr_msg, EDIRECTORY_TITLE." <$sitemgr_import_email>", "text/html", '', '', $error);
						}
					}

                    $messageLog = "Import finished. Update flags on ImportLog. - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
					$sqlUpdate = "UPDATE ImportLog SET status = 'F', action = 'D', progress = '100%' WHERE id = ".$importlog_id." AND type = 'listing'";
					mysql_query($sqlUpdate, $linkDomain);
                    $messageLog = "Delete records from ImportTemporary - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
					$sqlDelete = "DELETE FROM ImportTemporary WHERE import_log_id = '".$importlog_id."'";
					mysql_query($sqlDelete, $linkDomain);
                    $messageLog = "Set last_importlog on Control_Import_Listing - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
					$sqlUpdate = "UPDATE `Control_Import_Listing` SET last_importlog = '$importlog_id' WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
					mysql_query($sqlUpdate, $link);

					import_logDebug("Changing status - finished - LINE: ".__LINE__);

                    $messageLog = "Leaving preview file  - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
					if ($phisicalname) {
						if (!unlink($phisicalname)) {
							$eDirMailerObj = new EDirMailer(EDIR_ADMIN_EMAIL, "[eDirectory Cron] - Import Process", "Error: file delete (".$phisicalname.").", $sitemgr_email);
							$eDirMailerObj->send();
                            $messageLog = "Error trying to delete preview file - LINE: ".__LINE__;
                            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
						}
						import_logDebug("Leaving preview file - LINE: ".__LINE__);
					}

				} else {
                    $messageLog = "Process not running - LINE: ".__LINE__;
                    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
					import_logDebug("Process not running - LINE: ".__LINE__);
				}

				$sqlLog = "SELECT COUNT(id) AS total FROM `ImportLog` WHERE `status` = 'P' AND type = 'listing'";
				$resLog = mysql_query($sqlLog, $linkDomain);
				$rowLog = mysql_fetch_assoc($resLog);
                $messageLog = "Check if there is any other import to schedule Control_Import_Listing again. - LINE: ".__LINE__;
                log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
				if ($rowLog["total"] > 0) {
					$sqlCron = "UPDATE `Control_Import_Listing` SET `scheduled` = 'Y', `running` = 'N' WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
				} else {
					$sqlCron = "UPDATE `Control_Import_Listing` SET `scheduled` = 'N', `running` = 'N' WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
				}
				mysql_query($sqlCron, $link);
			} else {
                $messageLog = "Import not finished, it will be processed again. - LINE: ".__LINE__;
                log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
				$needToContinue = true;
			}
		} else {
			$sqlLog = "SELECT COUNT(id) AS total FROM `ImportLog` WHERE `status` = 'P' AND type = 'listing'";
			$resLog = mysql_query($sqlLog, $linkDomain);
			$rowLog = mysql_fetch_assoc($resLog);
            $messageLog = "Reschedule import if needed - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
			if ($rowLog["total"] > 0) {
				$sqlCron = "UPDATE `Control_Import_Listing` SET `scheduled` = 'Y', `running` = 'N' WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
			} else {
				$sqlCron = "UPDATE `Control_Import_Listing` SET `scheduled` = 'N', `running` = 'N' WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
			}
			mysql_query($sqlCron, $link);

			$import_stop = true;
			import_logDebug("No running process - LINE: ".__LINE__);
            $messageLog = "No running process - LINE: ".__LINE__;
            log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
		}
	}

	/*
	* Count listing per category
	*/
	system_countActiveListingByCategory();
    $messageLog = "Upadate category counter - LINE: ".__LINE__;
    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	unset($sql);
	unset($result);
	unset($row);
	$sql = "SELECT id, progress FROM ImportLog WHERE status = 'W' AND type = 'listing' ORDER BY id LIMIT 1";
	$result = mysql_query($sql, $linkDomain);
	if (mysql_num_rows($result) > 0) {
        $messageLog = "Change status \"W\" to \"S\" - LINE: ".__LINE__;
        log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
		$row = mysql_fetch_assoc($result);
		$sql = "UPDATE ImportLog SET status = 'S' WHERE id = ".$row["id"];
		mysql_query($sql, $linkDomain);
		import_setHistory("LANG_SITEMGR_IMPORT_IMPORTINGPROCESSSTOPPED["." - ".$row["progress"]."].", $linkDomain, db_formatNumber($row["id"]));
		import_logDebug("Import process stopped - ".$row["progress"]." - LINE: ".__LINE__);
		$sql = "DELETE FROM ImportTemporary WHERE import_log_id = '".$row["id"]."'";
		mysql_query($sql, $linkDomain);
	}
	unset($row);
	unset($result);
	unset($sql);

	$sqlLog = "SELECT COUNT(id) AS total FROM `ImportLog` WHERE `status` = 'P' AND type = 'listing'";
	$resLog = mysql_query($sqlLog, $linkDomain);
	$rowLog = mysql_fetch_assoc($resLog);
    $messageLog = "Reschedule import if needed - LINE: ".__LINE__;
    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
	if ($rowLog["total"] > 0 || ($needToContinue && !$import_stop)) {
		$sqlCron = "UPDATE `Control_Import_Listing` SET `scheduled` = 'Y', `running` = 'N', `last_importlog` = $importlog_id WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
	} else {
		$sqlCron = "UPDATE `Control_Import_Listing` SET `scheduled` = 'N', `running` = 'N' WHERE `domain_id` = ".SELECTED_DOMAIN_ID;
	}
	mysql_query($sqlCron, $link);

	$sql = "SELECT id FROM ImportLog WHERE status = 'E' AND type = 'listing' ORDER BY id";
	$result = mysql_query($sql, $linkDomain);
	if (mysql_num_rows($result) > 0) {
        $messageLog = "Delete data from ImportTemporary for imports with erros - LINE: ".__LINE__;
        log_addCronRecord($link, "import", $messageLog, true, $cron_log_id);
		while ($row = mysql_fetch_array($result)){
			$sql = "DELETE FROM ImportTemporary WHERE import_log_id = '".$row["id"]."'";
			mysql_query($sql, $linkDomain);
		}
	}
	unset($importObj);
	unset($row);
	unset($result);
	unset($sql);
	////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$time_end = getmicrotime();
	import_logDebug("End Date/Time: ".date("Y-m-d H:i:s"));
	import_logDebug("++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
	$time = $time_end - $time_start;
	if (!$import_stop) {
		print "Import Process on Domain ".SELECTED_DOMAIN_ID." - ".date("Y-m-d H:i:s")." - ".string_ucwords(LISTING_FEATURE_NAME_PLURAL).": ".$current_listing." - ".round($time, 2)." seconds.\n";

		$str_debug = "AuxTime: ".round($auxT, 2)."\nLocation creation/update: ".round($time_total_location,2)."\nCategory creation: ".round($time_total_category,2)."\nAccount creation: ".round($time_total_account,2)."\nListing Type creation: ".round($time_total_type,2)."\nListing creation/update: ".round($time_total_listing,2)."\nListing_Summary creation/update: ".round($time_total_summary,2)."\n";
		if (IMPORT_TIME_DEBUG == "on"){
			print "++++++++++++++++++\n\n";
			print "Time spent (seconds) in each import operation:\n\n";
			print $str_debug;
		}

		if (IMPORT_TIME_EMAIL_DEBUG == "on"){
			import_logDebug("Import Speed Report\n\nDomain: $default_url\nImport id: $importlog_id\nTime spent (seconds) in each import operation:\n\n$str_debug", true);
		}

		if (!setting_set("last_datetime_import", date("Y-m-d H:i:s"))) {
			if (!setting_new("last_datetime_import", date("Y-m-d H:i:s"))) {
				print "last_datetime_import error - Domain - ".SELECTED_DOMAIN_ID." - ".date("Y-m-d H:i:s")."\n";
			}
		}
	}
    $messageLog = "Cron finished";
    log_addCronRecord($link, "import", $messageLog, true, $cron_log_id, true, round($time, 2));
	////////////////////////////////////////////////////////////////////////////////////////////////////
