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
	# * FILE: /sitemgr/support/cronlog.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# THIS PAGE IS ONLY USED BY THE SUPPORT
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();

	if (!sess_getSMIdFromSession()){
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
        exit;
	} else {
		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$sql = "SELECT username FROM SMAccount WHERE id = ".sess_getSMIdFromSession();
		$row = mysql_fetch_assoc($dbMain->query($sql));
		if ($row["username"] != "arcalogin"){
			header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
            exit;
		} 
	}
    
    $url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/support/cronlog.php";
    extract($_GET);
    extract($_POST);
    
    if ($_GET["orderby"] == "domain") {
        $orderByClause = "domain_id";
    } elseif ($_GET["orderby"] == "cron"){
        $orderByClause = "cron";
    } elseif ($_GET["orderby"] == "date"){
        $orderByClause = "date DESC";
    } elseif ($_GET["orderby"] == "finished"){
        $orderByClause = "finished";
    } elseif ($_GET["orderby"] == "time"){
        $orderByClause = "time";
    } else {
        $orderByClause = "date";
    }

	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
    $crons = array();
    $crons[] = "prepare_import";
    $crons[] = "prepare_import_events";
    $crons[] = "import";
    $crons[] = "import_events";
    $crons[] = "rollback_import";
    $crons[] = "rollback_import_events";
    $crons[] = "export_listings";
    $crons[] = "export_events";
    $crons[] = "daily_maintenance";
    $crons[] = "email_traffic";
    $crons[] = "location_update";
    $crons[] = "randomizer";
    $crons[] = "renewal_reminder";
    $crons[] = "report_rollup";
    $crons[] = "sitemap";
    $crons[] = "statisticreport";
    
    foreach($crons as $cron) {
        ${"cronLogs_".$cron} = db_getFromDBBySQL("cron_log", "SELECT id, domain_id, cron, date, history, finished, CAST(time AS DECIMAL(10,2)) AS time FROM Cron_Log WHERE cron = '$cron' ORDER BY $orderByClause", "array");
    }
    
	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <div id="main-right">
        <div id="top-content">
            <div id="header-content">
                <h1>Config Checker - Cron Log</h1>
            </div>
        </div>

        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
                
                <? include(INCLUDES_DIR."/tables/table_support_submenu.php"); ?>

                <? foreach($crons as $cron) { ?>

                    <br class="clear" />
                    
                    <? if (!ENABLE_CRON_LOG) { ?>
                        <p class="informationMessage"><strong>Cron Log is disabled!</strong></p>
                    <? } ?>

                    <div id="header-form">
                        <h1><?=$cron?></h1>
                    </div>

                    <br class="clear" />

                    <? if (is_array(${"cronLogs_".$cron}) && ${"cronLogs_".$cron}[0]) { ?>
                        <table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">

                            <tr>
                                <th style="width: 10%;"><a href="<?=$url_redirect."?orderby=domain"?>">Domain ID</a></th>
                                <th style="width: auto;"><a href="<?=$url_redirect."?orderby=date"?>">Date</a></th>
                                <th style="width: auto;"><a href="<?=$url_redirect."?orderby=finished"?>">Finished</a></th>
                                <th style="width: auto;"><a href="<?=$url_redirect."?orderby=time"?>">Time</a></th>
                                <th style="width: 12%;">History</th>
                            </tr>

                            <? foreach (${"cronLogs_".$cron} as $log) { ?>

                            <tr>
                                <td><?=$log["domain_id"]?></td>
                                <td><?=$log["date"]?></td>
                                <td><?=$log["finished"]?></td>
                                <td><?=$log["time"]?> s</td>
                                <td><a id="cronlog" class="iframe fancy_window_cronlog" href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/support/cronlog_history.php?id=".$log["id"]?>">View History</a></td>
                            </tr>

                            <? } ?>

                        </table>
                    <? } else { ?>
                        <p class="informationMessage">No logs found.</p>
                    <? } ?>
                <? } ?>

            </div>
        </div>

        <div id="bottom-content">
            &nbsp;
        </div>
    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>