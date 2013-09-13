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
	# * FILE: /includes/forms/form_support_importlisting.php
	# ----------------------------------------------------------------------------------------------------

?>
    <table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE" >
        <tr>
            <th width="130px" nowrap="nowrap">Cron</th>
            <th width="130px" nowrap="nowrap">Last Run Date</th>
            <th width="130px" nowrap="nowrap">Scheduled</th>
            <th width="130px" nowrap="nowrap">Running</th>
            <th width="130px" nowrap="nowrap">Last Import ID Done</th>
        </tr>
        <tr>
            <td>
                Import
            </td>
            <td>
                <?
                if ($import_last_run_date != "0000-00-00 00:00:00") {
                    echo format_date($import_last_run_date, DEFAULT_DATE_FORMAT, "datetime")." - ".format_getTimeString($import_last_run_date);
                } else {
                    echo "0000-00-00 00:00:00";
                }
                ?>
            </td>
            <td>
                <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/import.php?cron=import&scheduled=<?=$import_scheduled?>"><img src="<?=DEFAULT_URL?>/images/<?=$import_scheduled == 'Y' ? 'icon_check.gif' : 'icon_uncheck.gif'?>" border="0" alt="<?=($import_scheduled == 'Y' ? "Scheduled" : "Not Scheduled")?>" title="<?=($import_scheduled == 'Y' ? "Scheduled" : "Not Scheduled")?>" /></a>
            </td>
            <td>
                <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/import.php?cron=import&running=<?=$import_running?>"><img src="<?=DEFAULT_URL?>/images/<?=$import_running == 'Y' ? 'icon_check.gif' : 'icon_uncheck.gif'?>" border="0" alt="<?=($import_running == 'Y' ? "Running" : "Not Running")?>" title="<?=($import_running == 'Y' ? "Running" : "Not Running")?>" /></a>
            </td>
            <td>
                <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/import/importlog.php?log_id=<?=$import_last_importlog?>"><?=$import_last_importlog;?></a>
            </td>
        </tr>
        <tr>
            <td>
                Prepare Import
            </td>
            <td>
                <?
                if ($prepareImport_last_run_date != "0000-00-00 00:00:00") {
                    echo format_date($prepareImport_last_run_date, DEFAULT_DATE_FORMAT, "datetime")." - ".format_getTimeString($prepareImport_last_run_date);
                } else {
                    echo "0000-00-00 00:00:00";
                }
                ?>
            </td>
            <td>
                Automatic
            </td>
            <td>
                <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/import.php?cron=prepare&running=<?=$prepareImport_running?>"><img src="<?=DEFAULT_URL?>/images/<?=$prepareImport_running == 'Y' ? 'icon_check.gif' : 'icon_uncheck.gif'?>" border="0" alt="<?=($prepareImport_running == 'Y' ? "Running" : "Not Running")?>" title="<?=($prepareImport_running == 'Y' ? "Running" : "Not Running")?>" /></a>
            </td>
            <td>
                ---
            </td>
        </tr>
        <tr>
            <td>
                Roll Back Import
            </td>
            <td>
                <?
                if ($rollbackImport_last_run_date != "0000-00-00 00:00:00") {
                    echo format_date($rollbackImport_last_run_date, DEFAULT_DATE_FORMAT, "datetime")." - ".format_getTimeString($rollbackImport_last_run_date);
                } else {
                    echo "0000-00-00 00:00:00";
                }
                ?>
            </td>
            <td>
                Automatic
            </td>
            <td>
                <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/support/import.php?cron=rollback&running=<?=$rollbackImport_running?>"><img src="<?=DEFAULT_URL?>/images/<?=$rollbackImport_running == 'Y' ? 'icon_check.gif' : 'icon_uncheck.gif'?>" border="0" alt="<?=($rollbackImport_running == 'Y' ? "Running" : "Not Running")?>" title="<?=($rollbackImport_running == 'Y' ? "Running" : "Not Running")?>" /></a>
            </td>
            <td>
                ---
            </td>
        </tr>
    </table>

    <div id="header-export">Import Log - Listing</div>

    <? if (is_array($importsListing) && $importsListing[0]) { ?>
        <table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE" >
            <tr>
                <th width="15px" nowrap="nowrap">ID</th>
                <th width="130px" nowrap="nowrap">Date/Time</th>
                <th nowrap="nowrap">Filename</th>
                <th width="200px" nowrap="nowrap">Status</th>
                <th width="200px" nowrap="nowrap">Action</th>
                <th width="15px" nowrap="nowrap">&nbsp;</th>
            </tr>
            <? foreach ($importsListing as $import) {
                    include (INCLUDES_DIR."/tables/table_import_support.php");
                }
            ?>
        </table>
    <? } else { ?>
        <p class="informationMessage">No records found.</p>
    <? } ?>