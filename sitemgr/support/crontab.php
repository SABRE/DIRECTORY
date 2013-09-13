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
	# * FILE: /sitemgr/support/crontab.php
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
    
	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------   
    
    $crons_manager = array();
    $crons_manager[] = "rollback_import.php";
    $crons_manager[] = "rollback_import_events.php";
    $crons_manager[] = "export_listings.php";
    $crons_manager[] = "export_events.php";
    $crons_manager[] = "daily_maintenance.php";
    $crons_manager[] = "email_traffic.php";
    $crons_manager[] = "location_update.php";
    $crons_manager[] = "randomizer.php";
    $crons_manager[] = "renewal_reminder.php";
    $crons_manager[] = "report_rollup.php";
    $crons_manager[] = "sitemap.php";
    $crons_manager[] = "statisticreport.php";
    
    $cronTabText = "0,20,40 * * * * php -f ".EDIRECTORY_ROOT."/cron/email_traffic.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
0,20,40 * * * * php -f ".EDIRECTORY_ROOT."/cron/renewal_reminder.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
10,30,50 * * * * php -f ".EDIRECTORY_ROOT."/cron/randomizer.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
0 */3 * * * php -f ".EDIRECTORY_ROOT."/cron/daily_maintenance.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
5 0 * * * php -f ".EDIRECTORY_ROOT."/cron/report_rollup.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
5 0 * * * php -f ".EDIRECTORY_ROOT."/cron/statisticreport.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
0 20 * * * php -f ".EDIRECTORY_ROOT."/cron/sitemap.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/export_listings.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/export_events.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/location_update.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/import.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/import_events.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/10 * * * * php -f ".EDIRECTORY_ROOT."/cron/prepare_import.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/10 * * * * php -f ".EDIRECTORY_ROOT."/cron/prepare_import_events.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/10 * * * * php -f ".EDIRECTORY_ROOT."/cron/rollback_import.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/10 * * * * php -f ".EDIRECTORY_ROOT."/cron/rollback_import_events.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log";
    
    $cronTabText2 = "*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/cron_manager.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/import.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/5 * * * * php -f ".EDIRECTORY_ROOT."/cron/import_events.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/10 * * * * php -f ".EDIRECTORY_ROOT."/cron/prepare_import.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log
*/10 * * * * php -f ".EDIRECTORY_ROOT."/cron/prepare_import_events.php 1>&2>> ".EDIRECTORY_ROOT."/cron/cron.log";
    
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
                <h1>Config Checker - Crontab</h1>
            </div>
        </div>

        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
                
                <? include(INCLUDES_DIR."/tables/table_support_submenu.php"); ?>
                
                <? if ($errorMessage) { ?>
                    <p class="errorMessage"><?=$errorMessage?></p>
                <? } elseif ($_GET["message"] == "ok") { ?>
                    <p class="successMessage">Settings changed!</p>
                <? } ?>
                
                <p class="informationMessage">
                    Please, be aware that <i>cron_manager.php</i> runs others crons indicated below. If you schedule cron_manager, <strong>DO NOT SCHEDULE</strong> any other cron that cron_manager runs.<br />
                    If you need to schedule any cron separately and still keep cron_manager scheduled, go to the file cron/cron_manager.php and comment the line that corresponds to the cron you want to run separately.
                </p>
                
                <div class="crontab-content">
                    <h2>Crontab <strong>without cron manager</strong></h2>
                    <textarea name="text" id="textarea"><?=htmlspecialchars($cronTabText)?></textarea>
				</div>

                <div class="crontab-content">
                    <h2>Crontab <strong>with cron manager</strong></h2>
                    <textarea name="text" id="textarea"><?=htmlspecialchars($cronTabText2)?></textarea>
                </div>
                
                <div id="tip" class="tip-base">
                    <p>Cron Manager includes:</p>
                    <? foreach ($crons_manager as $cron) { ?>
                        <p>&#8226; <?=$cron?></p>
                    <? } ?>
                </div>

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