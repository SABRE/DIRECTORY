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
	# * FILE: /sitemgr/export/download.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

	# ----------------------------------------------------------------------------------------------------
	# INCREASES FREQUENTLY ACTIONS
	# ----------------------------------------------------------------------------------------------------
	system_setFreqActions('export_downloadfiles', 'export');

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    $url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/export/download.php";
    
    extract($_GET);
    extract($_POST);
    
    if ($action == "downFile" && $file && $displayName) {
        $filePath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/import_files/".$file;
        if (file_exists($filePath)) {
            system_downloadFile($filePath, $displayName, "csv");
        } else {
            $messageStyle = "errorMessage";
            $message = system_showText(LANG_SITEMGR_EXPORT_DOWNLOAD_ERROR);
        }
    } elseif ($action == "deleteFile" && $file) {
        $filePath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/import_files/".$file;
        if (@unlink($filePath)) {
            header("Location: ".$url_redirect."?message=1");
            exit;
        } else {
            $messageStyle = "errorMessage";
            $message = system_showText(LANG_SITEMGR_EXPORT_DELETE_ERROR);
        }
    }
    
    //Success Message
    if ($message == 1) {
        $messageStyle = "successMessage";
        $message = system_showText(LANG_SITEMGR_EXPORT_DELETED);
    }
    
	$exportFiles = export_getFileList();
    
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
        
        function deleteFile(file) {
            document.getElementById("deleteFile").value = file;
            dialogBox('confirm','<?=system_showText(LANG_SITEMGR_EXPORT_DELETEQUESTION);?>','Submit','export_delete','180','<?=system_showText(LANG_SITEMGR_OK);?>','<?=system_showText(LANG_SITEMGR_CANCEL);?>');
        }
        
    </script>
    
    
    <div id="main-right">
        
        <div id="top-content">
            <div id="header-content">
                <h1><?=string_ucwords(LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT);?></h1>
            </div>
        </div>
        
        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
                
                <? include (INCLUDES_DIR."/tables/table_data_submenu.php"); ?>
                
                <div id="header-export">
                    <?=system_showText(LANG_SITEMGR_EXPORT_DOWNLOAD)?>
                </div>
                
                <? if ($message) { ?>
                    <p class="<?=$messageStyle;?>"><?=$message;?></p>
                <? } ?>

                <? if ($exportFiles) { ?>
                    
                    <ul class="standard-iconDESCRIPTION">
                        <li class="download-icon"><?=system_showText(LANG_LABEL_DOWNLOAD);?></li>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE)?></li>
                    </ul>
                
                    <table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
                        
                        <tr>
                            <th style="width:auto;">
                                <?=system_showText(LANG_SITEMGR_LABEL_FILENAME);?>
                            </th>
                            
                            <th style="width:auto;">
                                <?=system_showText(LANG_SITEMGR_IMPORT_FILESIZE);?>
                            </th>
                            
                            <th style="width:auto;">
                                <?=system_showText(LANG_SITEMGR_DATECREATED);?>
                            </th>
                            
                            <th style="width: 40px;">
                                <?=system_showText(LANG_LABEL_OPTIONS)?>
                            </th>
                            
                        </tr>
                            
                        <? foreach ($exportFiles as $k => $fInfo) { ?>
                            <? if ($fInfo["file_name"] && $fInfo["file_size"] && $fInfo["file_time"]) { ?>

                            <tr>
                                
                                <td>
                                    <?=$fInfo["file_display_name"];?>
                                </td>
                                
                                <td>
                                    <?=$fInfo["file_size"];?>
                                </td>
                                
                                <td>
                                    <?=$fInfo["file_time"];?>
                                </td>
                                
                                <td>
                                    <a href="<?=$url_redirect?>?action=downFile&file=<?=$fInfo["file_name"]?>&displayName=<?=$fInfo["file_display_name"];?>" class="link-table">
                                        <img src="<?=DEFAULT_URL?>/images/bt_download.gif" alt="<?=system_showText(LANG_MSG_CLICK_TO_DOWNLOAD_THIS_FILE)?>" border="0" title="<?=system_showText(LANG_MSG_CLICK_TO_DOWNLOAD_THIS_FILE)?>" />
                                    </a>
                                    
                                    <a href="javascript:void(0);" onclick="deleteFile('<?=$fInfo["file_name"]?>');" class="link-table">
                                        <img src="<?=DEFAULT_URL?>/images/bt_delete.gif" alt="<?=system_showText(LANG_MSG_CLICK_TO_DELETE_THIS_FILE)?>" border="0" title="<?=system_showText(LANG_MSG_CLICK_TO_DELETE_THIS_FILE)?>" />
                                    </a>
                                </td>
                                
                            </tr>
                            <? } ?>
                        <? } ?>
                    </table>
                    
                    <form name="export_delete" id="export_delete" action="<?=$url_redirect?>" method="get">
                        <input type="hidden" name="action" value="deleteFile" />
                        <input type="hidden" id="deleteFile" name="file" value="" />
                    </form>
                    
                    <ul class="standard-iconDESCRIPTION">
                        <li class="download-icon"><?=system_showText(LANG_LABEL_DOWNLOAD);?></li>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE)?></li>
                    </ul>

                <? } else { ?>
                    <p class="informationMessage">
                        <?=system_showText(LANG_SITEMGR_EXPORT_NORECORDS)?>
                    </p>
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