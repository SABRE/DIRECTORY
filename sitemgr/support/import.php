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
	# * FILE: /sitemgr/suport/import.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# THIS PAGE IS ONLY USED BY THE SUPPORT TEAM TO SET THE CONTROL CRON TABLES WITH DFAULT VALUES
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
	# CODE
	# ----------------------------------------------------------------------------------------------------
	$success = 0;
	if ($_GET["cron"]){
        $event = false;
		if ($_GET["cron"] == "import"){
			if ($_GET["scheduled"] == "N"){
				$sql = "UPDATE Control_Import_Listing SET scheduled = 'Y' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["scheduled"] == "Y"){
				$sql = "UPDATE Control_Import_Listing SET scheduled = 'N' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
			if ($_GET["running"] == "N"){
				$sql = "UPDATE Control_Import_Listing SET running = 'Y' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["running"] == "Y"){
				$sql = "UPDATE Control_Import_Listing SET running = 'N' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
		} elseif ($_GET["cron"] == "import_event"){
            $event = true;
			if ($_GET["scheduled"] == "N"){
				$sql = "UPDATE Control_Import_Event SET scheduled = 'Y' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["scheduled"] == "Y"){
				$sql = "UPDATE Control_Import_Event SET scheduled = 'N' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
			if ($_GET["running"] == "N"){
				$sql = "UPDATE Control_Import_Event SET running = 'Y' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["running"] == "Y"){
				$sql = "UPDATE Control_Import_Event SET running = 'N' WHERE domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
		}

		if ($_GET["cron"] == "prepare"){
			if ($_GET["running"] == "N"){
				$sql = "UPDATE Control_Cron SET running = 'Y' WHERE type = 'prepare_import' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["running"] == "Y"){
				$sql = "UPDATE Control_Cron SET running = 'N' WHERE type = 'prepare_import' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
		} elseif ($_GET["cron"] == "prepare_event"){
            $event = true;
			if ($_GET["running"] == "N"){
				$sql = "UPDATE Control_Cron SET running = 'Y' WHERE type = 'prepare_import_events' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["running"] == "Y"){
				$sql = "UPDATE Control_Cron SET running = 'N' WHERE type = 'prepare_import_events' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
        }
		

		if ($_GET["cron"] == "rollback"){
			if ($_GET["running"] == "N"){
				$sql = "UPDATE Control_Cron SET running = 'Y' WHERE type = 'rollback_import' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["running"] == "Y"){
				$sql = "UPDATE Control_Cron SET running = 'N' WHERE type = 'rollback_import' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
		} elseif ($_GET["cron"] == "rollback_event"){
            $event = true;
			if ($_GET["running"] == "N"){
				$sql = "UPDATE Control_Cron SET running = 'Y' WHERE type = 'rollback_import_events' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			} else if ($_GET["running"] == "Y"){
				$sql = "UPDATE Control_Cron SET running = 'N' WHERE type = 'rollback_import_events' AND domain_id = ".SELECTED_DOMAIN_ID;
				$dbMain->query($sql);
			}
		}

		if (!$dbMain->mysql_error) {
            if ($event) {
                $successEvent = 1;
            } else {
                $success = 1;
            }
		} else {
			if ($event) {
                $successEvent = 2;
            } else {
                $success = 2;
            }
		}
	}

	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
    $import = new ImportLog();
    
	//Listing
    $sql = "SELECT scheduled, running, last_run_date, last_importlog FROM Control_Import_Listing WHERE domain_id = ".SELECTED_DOMAIN_ID;
	$row = mysql_fetch_assoc($dbMain->query($sql));

	$import_scheduled		= $row["scheduled"];
	$import_running			= $row["running"];
	$import_last_run_date	= $row["last_run_date"];
	$import_last_importlog	= $row["last_importlog"];

	$sql = "SELECT running, last_run_date FROM Control_Cron WHERE type = 'prepare_import' AND domain_id = ".SELECTED_DOMAIN_ID;
	$row = mysql_fetch_assoc($dbMain->query($sql));

	$prepareImport_running			= $row["running"];
	$prepareImport_last_run_date	= $row["last_run_date"];

	$sql = "SELECT running, last_run_date FROM Control_Cron WHERE type = 'rollback_import' AND domain_id = ".SELECTED_DOMAIN_ID;
	$row = mysql_fetch_assoc($dbMain->query($sql));

	$rollbackImport_running			= $row["running"];
	$rollbackImport_last_run_date	= $row["last_run_date"];
    
	$importsListing = $import->getImports("listing");
    
    //Event
    $sql = "SELECT scheduled, running, last_run_date, last_importlog FROM Control_Import_Event WHERE domain_id = ".SELECTED_DOMAIN_ID;
	$row = mysql_fetch_assoc($dbMain->query($sql));

	$import_scheduled_event         = $row["scheduled"];
	$import_running_event			= $row["running"];
	$import_last_run_date_event     = $row["last_run_date"];
	$import_last_importlog_event	= $row["last_importlog"];

	$sql = "SELECT running, last_run_date FROM Control_Cron WHERE type = 'prepare_import_events' AND domain_id = ".SELECTED_DOMAIN_ID;
	$row = mysql_fetch_assoc($dbMain->query($sql));

	$prepareImport_running_event		= $row["running"];
	$prepareImport_last_run_date_event	= $row["last_run_date"];

	$sql = "SELECT running, last_run_date FROM Control_Cron WHERE type = 'rollback_import_events' AND domain_id = ".SELECTED_DOMAIN_ID;
	$row = mysql_fetch_assoc($dbMain->query($sql));

	$rollbackImport_running_event		= $row["running"];
	$rollbackImport_last_run_date_event	= $row["last_run_date"];
    
    $importsEvent = $import->getImports("event");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <script type="text/javascript">
        function showTips() {
            $("#tip").css("display", "");
            $('html,body').animate({scrollTop: $("#tip").offset().top},'slow');
        }
    </script>

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
                
                <? if ($_GET["message"] == 1) { ?>
                    <p class="successMessage">ImportLog successfully updated!</p>
                <? } ?>

                <div class="tip-base">
                    <p style="text-align: justify;"><a href="javascript: void(0);" onclick="showTips();">Do you have questions about the import flags (status/action)? Click here.</a></p>
                </div>

                <br />

                <div id="header-export">Control Cron Tables Status - Listing</div>
                <? if ($success != 0) { ?>
                    <div id="logMessages">
                        <p class=<?=($success == 1? "successMessage" : "errorMessage")?>><?=($success == 1 ? "Cron setting successfully changed!" : "Error trying to change the cron setting, please try again.")?></p>
                    </div>
                <? } ?>

                <br class="clear" />

                <? include(INCLUDES_DIR."/forms/form_support_importlisting.php"); ?>

                <br class="clear" />

                <div id="header-export">Control Cron Tables Status - Event</div>
                <? if ($successEvent != 0) { ?>
                    <div id="logMessages">
                        <p class=<?=($successEvent == 1? "successMessage" : "errorMessage")?>><?=($successEvent == 1 ? "Cron setting successfully changed!" : "Error trying to change the cron setting, please try again.")?></p>
                    </div>
                <? } ?>

                <br class="clear" />

                <? include(INCLUDES_DIR."/forms/form_support_importevent.php"); ?>

                <div id="tip" class="tip-base" style="display: none">
                    <p><strong>Description of the field <i>Status</i>:</strong></p>
                    <p>&#8226; P (Pending): Import process waiting cron import.php or cron prepare_import.php, according to the column action.</p>
                    <p>&#8226; F (Finished): Import process finished and successfully done.</p>
                    <p>&#8226; C (Cancelled): Import process cancelled after a roll back.</p>
                    <p>&#8226; D (Deleted): Import log deleted by sitemgr. Imported items are not removed.</p>
                    <p>&#8226; W (Waiting): Intermediate status after sitemgr stop an import process.</p>
                    <p>&#8226; E (Error): Wrong CSV file or error in the sql lote file (check the column mysql_error in ImportLog table).</p>
                    <p>&#8226; R (Running): Cron import.php is running.</p>
                    <p>&#8226; S (Stopped): Import process stopped by cron import.php after sitemgr stopped it. This process can not be resumed. Sitemgr will be able only to roll back this import.</p>

                    <br />

                    <p><strong>Description of the field <i>Action</i>:</strong></p>
                    <p>&#8226; RI (Ready to Import): Import scheduled and the table ImportTemporary has already been populated. Cron import.php is ready to run.</p>
                    <p>&#8226; NC (Need to Convert): CSV file with more than 100 thousand lines. The file will be processed by the cron prepare_import.php.</p>
                    <p>&#8226; NA (Need to Approve): After prepare_import.php has finished the process and the option "Start import automatically" is not checked.</p>
                    <p>&#8226; D (Done): Import finished (successfully or not, according to the column status)</p>
                    <p>&#8226; C (Converting): Cron prepare_import.php is running.</p>
                    <p>&#8226; NR (Need to rollback): Waiting cron job (rollback_import.php) to roll back the import.</p>

                    <br />

                    <p><strong>Possible combinations between <i>Status</i> and <i>Action</i>:</strong></p>
                    <br />
                    <p>&#8226; P/RI (In Queue): <?=import_getLogTip("P", "RI");?></p>
                    <br />
                    <p>&#8226; P/NC (Waiting to prepare import): <?=import_getLogTip("P", "NC");?></p>
                    <br />
                    <p>&#8226; P/NA (Waiting approval): <?=import_getLogTip("P", "NA");?></p>
                    <br />
                    <p>&#8226; P/C (Converting .csv): <?=import_getLogTip("P", "C");?></p>
                    <br />
                    <p>&#8226; F/D (Finished): <?=import_getLogTip("F", "D");?></p>
                    <br />
                    <p>&#8226; F/NR (Need to Rollback): <?=import_getLogTip("F", "NR");?></p>
                    <br />
                    <p>&#8226; C/D (Cancelled): <?=import_getLogTip("C", "D");?></p>
                    <br />
                    <p>&#8226; D/RI (Deleted): <?=import_getLogTip("D", "RI");?></p>
                    <br />
                    <p>&#8226; D/NC (Deleted): <?=import_getLogTip("D", "NC");?></p>
                    <br />
                    <p>&#8226; D/NA (Deleted): <?=import_getLogTip("D", "NA");?></p>
                    <br />
                    <p>&#8226; D/D (Deleted): <?=import_getLogTip("D", "D");?></p>
                    <br />
                    <p>&#8226; W/RI (Waiting): <?=import_getLogTip("W", "RI");?></p>
                    <br />
                    <p>&#8226; E/D (Error): <?=import_getLogTip("E", "D");?></p>
                    <br />
                    <p>&#8226; R/RI (Running): <?=import_getLogTip("R", "RI");?></p>
                    <br />
                    <p>&#8226; S/D (Stopped): <?=import_getLogTip("S", "D");?></p>
                    <br />

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