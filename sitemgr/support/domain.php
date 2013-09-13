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
	# * FILE: /sitemgr/support/domain.php
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
	# CODE
	# ----------------------------------------------------------------------------------------------------
    $dbMain = db_getDBObject(DEFAULT_DB, true);
    $sql = "SELECT * FROM Domain";
    $result = $dbMain->query($sql);
    
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
                <h1>Config Checker - Domains</h1>
            </div>
        </div>

        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
                
                <? include(INCLUDES_DIR."/tables/table_support_submenu.php"); ?>

                <table cellpadding="0" cellspacing="0" border="0" class="standard-table">
                    
                    <? while ($row = mysql_fetch_assoc($result)) { ?>
                    
                        <tr>
                            <th colspan="2" class="standard-tabletitle"><?=$row["name"]?></th>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <td><?=$row["id"]?></td>
                        </tr>
                        <tr>
                            <th>Database Host</th>
                            <td><?=$row["database_host"]?></td>
                        </tr>
                        <tr>
                            <th>Database Port</th>
                            <td><?=$row["database_port"]?></td>
                        </tr>
                        <tr>
                            <th>Database Username</th>
                            <td><?=$row["database_username"]?></td>
                        </tr>
                        <tr>
                            <th>Database Password</th>
                            <td><?=$row["database_password"]?></td>
                        </tr>
                        <tr>
                            <th>Database Name</th>
                            <td><?=$row["database_name"]?></td>
                        </tr>
                        <tr>
                            <th>URL</th>
                            <td><?=$row["url"]?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?=$row["status"]?></td>
                        </tr>
                        <tr>
                            <th>Activation Status</th>
                            <td><?=$row["activation_status"]?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?=$row["created"]?></td>
                        </tr>
                        <tr>
                            <th>Deleted Date</th>
                            <td><?=$row["deleted_date"]?></td>
                        </tr>
                        <tr>
                            <th>Article</th>
                            <td><?=$row["article_feature"]?></td>
                        </tr>
                        <tr>
                            <th>Banner</th>
                            <td><?=$row["banner_feature"]?></td>
                        </tr>
                        <tr>
                            <th>Classified</th>
                            <td><?=$row["classified_feature"]?></td>
                        </tr>
                        <tr>
                            <th>Event</th>
                            <td><?=$row["event_feature"]?></td>
                        </tr>
                        <tr>
                            <th>Subfolder</th>
                            <td><?=$row["subfolder"]?></td>
                        </tr>
                    
                    <? } ?>
                    
                </table>

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