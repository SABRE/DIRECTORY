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
	# * FILE: /includes/forms/form_import_step_4.php
	# ----------------------------------------------------------------------------------------------------
    
    if ($module == "event"){
        setting_get("import_update_events", $import_update);
    } else {
        setting_get("import_update_listings", $import_update);
    }

    $message = "";

    if (function_exists("mb_detect_encoding") && function_exists("mb_convert_encoding")) {
        $message = system_showText(LANG_SITEMGR_MSG_IMPORT_CONVERT_UTF8_2);
    } else {
        $message = system_showText(LANG_SITEMGR_MSG_IMPORT_CHECK_UTF8);
    }

    if ($import_update) {
        $message = $message." ".system_showText(LANG_SITEMGR_MSG_IMPORT_UPDATE_ITENS2);
    }

?>



    <div class="wrapper import-wrapper">

        <? if ($messageErrorUpload) { ?>
            <p class="errorMessage"><?=$messageErrorUpload;?></p>
        <? } ?>
        <p id="separator_message_id" class="errorMessage" style="display: none"><?=LANG_SITEMGR_IMPORT_INVALID_SEPARATOR?></p>
        <p id="max_mb_message" class="errorMessage" style="display: none"><?=system_showText(LANG_SITEMGR_MSGERROR_MAXFILESIZEALLOWEDIS)." ".MAX_MB_FILE_SIZE_ALLOWED_FTP."MB.";?></p>
        
        <div id="tools">
            <form id="importInfo" name="importInfo" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">

                <input type="hidden" id="step" name="step" value="<?=$step?>" />
                <input type="hidden" id="prev_step" name="prev_step" value="4" />
                <input type="hidden" id="module" name="module" value="<?=$module?>" />

                <input type="hidden" name="import_from_export_<?=$module?>" value="<?=${"import_from_export_".$module}?>" />
                <input type="hidden" name="import_enable_active_<?=$module?>" value="<?=${"import_enable_active_".$module}?>" />
                <input type="hidden" name="import_update_items_<?=$module?>" value="<?=${"import_update_items_".$module}?>" />
                <input type="hidden" name="import_update_friendlyurl_<?=$module?>" value="<?=${"import_update_friendlyurl_".$module}?>" />
                <input type="hidden" name="import_featured_categs_<?=$module?>" value="<?=${"import_featured_categs_".$module}?>" />
                <input type="hidden" name="import_defaultlevel_<?=$module?>" value="<?=${"import_defaultlevel_".$module}?>" />
                <input type="hidden" name="import_sameaccount_<?=$module?>" value="<?=${"import_sameaccount_".$module}?>" />
                <input type="hidden" name="account_id_<?=$module?>" value="<?=${"account_id_".$module}?>" />

                <input type="hidden" id="type" name="type" value="<?=$type? $type: "upload"?>"/>
                <input type="hidden" id="ftp_type" name="ftp_type" value="correct"/>
                
                <table cellpadding="0" cellspacing="0" border="0" class="standard-table import-holder-step4">
                    <tr>
                        <th>
                            <ul class="tabs">
                                <? if ($step == 4 || ($step == 5 && $type == "upload")) { ?>
                                    <li id="tab_upload" <?=($type == "upload" || !$type? "class=\"tabActived\"": "");?>>
                                        <a href="javascript:void(0);" <?=($step == 4 ? "onclick=\"changeFileForm('upload', false, false);\"" : "style=\"cursor: default;\"")?>><?=system_showText(LANG_SITEMGR_UPLOAD_FILE);?></a>
                                    </li>
                                <? } ?>
                                
                                <? if ($step == 4 || ($step == 5 && $type == "select")) { ?>
                                <li id="tab_select" <?=($type == "select"? "class=\"tabActived\"": "");?>>
                                    <a href="javascript:void(0);" <?=($step == 4 ? "onclick=\"changeFileForm('select', '$file_name', false);\"" : "style=\"cursor: default;\"")?>><?=system_showText(LANG_SITEMGR_SELECT_FILE);?></a>
                                </li>
                                <? } ?>
                            </ul>
                        </th>
                    </tr>
                    
                    <? if ($step == 4) { ?>
                    <tr id="uploadFile">
                        <td class="tools-table">
                            <p><?=$message;?></>
                            <p><b><?=system_showText(LANG_SITEMGR_FILES_CSV);?></b></p>
                            <p class="import-allow"><?=system_showText(LANG_SITEMGR_IMPORT_MAXFILESIZE_ALLOWED)?> <?=MAX_MB_FILE_SIZE_ALLOWED;?> MB.</p>
                            <input type="file" class="importFile" name="importFile" onchange="uploadFile('upload');" size="64" />
                        </td>
                    </tr>
                    <? } ?>
                    
                    <tr id="selectFile" style="display: none;">
                        <td class="tools-table">
                            <? if ($step == 4) { ?>
                                
                                <p id="msgFilePath" style="<?=$file_name ? "display: none;": "";?>"><?=system_showText(LANG_SITEMGR_IMPORT_YOUCANUPLOADAT)?> /custom/domain_<?=SELECTED_DOMAIN_ID?>/import_files</p>
                            
                                <p><?=$message;?></p>
                                
                                <p><b><?=system_showText(LANG_SITEMGR_FILES_CSV);?></b> <?=system_showText(LANG_SITEMGR_IMPORT_MAXFILESIZE_ALLOWED)?> <?=MAX_MB_FILE_SIZE_ALLOWED_FTP?>mb</p>
                            
                                <br clear="all" /> 
                            
                                    <p id="msgFileList" style="<?=$file_name && !$messageErrorUpload? "display: none;": "";?>"><strong><?=system_showText(LANG_SITEMGR_IMPORT_USEFORMTHEFORMBELOW);?></strong></p>
                                <? } else { ?>
                                    <p><?=system_showText(LANG_SITEMGR_FILES_PREVIEW);?></p>
                                <? } ?>
                                
                                <div class="load-ftp-table-box" style="<?=$file_name? "display: none;": "";?>">
                                    
                                    <table id="tblHeader" cellpadding="0" cellspacing="0" border="0" class="standard-table load-ftp-table" style="<?=$file_name? "display: none;": "";?>">
                                        <tr>
                                            <th class="table-select-file">&nbsp;</th>
                                            <th class="table-file-name"><?=system_showText(LANG_SITEMGR_IMPORT_FILENAME);?></th>
                                            <th class="table-file-size"><?=system_showText(LANG_SITEMGR_IMPORT_FILESIZE);?></th>
                                            <th class="table-file-date"><?=system_showText(LANG_SITEMGR_IMPORT_UPDATEDDATE);?></th>
                                        </tr>
                                    </table>

                                    <div id="fileList" class="fileList" style="<?=$file_name && !$messageErrorUpload? "display: none;": "";?>">
                                        <?=import_renderFileList($fileInfo);?>
                                    </div>

                                    <span class="clear"></span>

                                    <div id="dvButtons" class="import-button" style="<?=$file_name && !$messageErrorUpload? "display: none;": "";?>">
                                        <input type="button" name="Reload" value="<?=system_showText(LANG_SITEMGR_IMPORT_FILELIST_RELOAD);?>" class="input-button-form" onclick="reloadFileList(false);" style="width:113px;"/>
                                    </div>

                                </div>
                        </td>
                    </tr>
                   
                    <tr id="selectFile2" style="display: none;">
                        <td class="tools-table">
                            <input type="text" id="file_name" name="file_name" class="inputButtonFile" value="<?=!$messageErrorUpload? $file_name: "";?>" style="width: 333px;" readonly="readyonly"/>
                        </td>
                    </tr>
                     
                </table>
            </form>
            
        </div>

        <div id="pageLoad" style="display: none;">
        	<div>
                <img src="<?=DEFAULT_URL;?>/images/img_loading.gif" alt="<?=system_showText(LANG_SITEMGR_WAITLOADING);?>" title="<?=system_showText(LANG_SITEMGR_WAITLOADING);?>"/>
                <p class="import-loading"><?=system_showText(LANG_SITEMGR_WAITLOADING);?></p>
            </div>
        </div>

        <div id="wait_loading_file" style="display: none" align="center">
            <div>
                <img src="<?=DEFAULT_URL;?>/images/img_loading.gif" alt="<?=system_showText(LANG_SITEMGR_WAITLOADING);?>" title="<?=system_showText(LANG_SITEMGR_WAITLOADING);?>"/>
                <p class="import-loading"><?=system_showText(LANG_SITEMGR_IMPORT_PROCESSING);?></p>
            </div>
        </div>

        <form id="importOptions" name="importOptions" target="_parent" action="<?=DEFAULT_URL."/".SITEMGR_ALIAS;?>/import/importlog.php?import_type=<?=$module?>" method="post">
            <input type="hidden" name="type" value="options"/>
            <input type="hidden" name="upload_name" value="<?=$upload_name;?>"/>
            <input type="hidden" name="file_name" value="<?=$file_name;?>"/>
            <input type="hidden" name="ftp_type" value="<?=$ftp_type;?>"/>
            
            <input type="hidden" id="module" name="module" value="<?=$module?>" />
            <input type="hidden" name="import_from_export_<?=$module?>" value="<?=${"import_from_export_".$module}?>" />
            <input type="hidden" name="import_enable_active_<?=$module?>" value="<?=${"import_enable_active_".$module}?>" />
            <input type="hidden" name="import_update_items_<?=$module?>" value="<?=${"import_update_items_".$module}?>" />
            <input type="hidden" name="import_update_friendlyurl_<?=$module?>" value="<?=${"import_update_friendlyurl_".$module}?>" />
            <input type="hidden" name="import_featured_categs_<?=$module?>" value="<?=${"import_featured_categs_".$module}?>" />
            <input type="hidden" name="import_defaultlevel_<?=$module?>" value="<?=${"import_defaultlevel_".$module}?>" />
            <input type="hidden" name="import_sameaccount_<?=$module?>" value="<?=${"import_sameaccount_".$module}?>" />
            <input type="hidden" name="account_id_<?=$module?>" value="<?=${"account_id_".$module}?>" />
            
            <table id="tableCSV"  class="standard-table standard-import-table import-table-border" style="<?=$urlFileName? "" : "display: none;"; ?>">
                <tr>
                    <td class="import-table-title">
                        <?=system_showText(constant("LANG_SITEMGR_PREVIEW_".string_strtoupper($module)))." ".system_showText(LANG_SITEMGR_IMPORT_SHOWING_PREVIEWLINES);?>
                        <span><?=system_showText(LANG_SITEMGR_IMPORT_PREVIEW_MESSAGE);?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <div id="csvPreview">
                                    	<div>
                                            <img src="<?=DEFAULT_URL;?>/images/img_loading.gif" alt="<?=system_showText(LANG_SITEMGR_WAITLOADING);?>" title="<?=system_showText(LANG_SITEMGR_WAITLOADING);?>"/>
                                            <p class="import-loading"><?=system_showText(LANG_SITEMGR_WAITLOADING);?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
            <div class="import-table-divisor">&nbsp;</div>
            
            <input type="hidden" name="csvOption" value="<?=$csvDelimiter?>" />
            
            <button type="button" name="submit_button" class="input-button-form left" value="Submit" onclick="JS_submit(<?=$step-1?>, false);"><?=system_showText(LANG_SITEMGR_BACK)?></button>
           
            <? if ($step == 4) { ?>
                <button type="button" name="submit_button" id="btnPreview" class="input-button-form input-button-form-disabled right" disabled="disabled" value="Submit" onclick="JS_submitPreview();"><?=system_showText(LANG_SITEMGR_PREVIEW)?></button>
            <? } else { ?>
                <button type="submit" id="btnISubmit" value="Submit" onclick="changeDisplayForm();" class="input-button-form right <?=$urlFileName || $ftp_type == "schedule_cron"? "" : "input-button-form-disabled";?>" <?=$urlFileName || $ftp_type == "schedule_cron"? "" : "disabled=\"disabled\" style=\"display:none;\"";?>><?=$ftp_type == "schedule_cron"? system_showText(LANG_SITEMGR_SUBMIT): system_showText(LANG_SITEMGR_IMPORT_SUBMIT);?></button>
            <? } ?>
                
        </form>
        
        <div id="tableDivisor" class="import-table-divisor" style="display:none">&nbsp;</div>
                    
    </div>

    <script type="text/javascript">
        var fileName = '<?=$urlFileName;?>';
        var errorMessage = '<?=$messageErrorUpload;?>';
        var colSeparator = '<?=($csvDelimiter ? $csvDelimiter : ",")?>';
        var fileForm = '<?=$type;?>';
        var ftpType = '<?=$ftp_type == "schedule_cron"? $ftp_type: "";?>';
        
        function JS_submitPreview () {
            var importType = $("#type").val();
            $("#pageLoad").css("display", "");
            if (importType == "select") {
                uploadFile("select");
            } else {
                $("#importInfo").submit();
            }
        }

        function changeFileForm (type, file_name, ftpType) {
            $("#type").attr("value", type);
            if (type == "upload") {
                $("#tab_select").removeClass("tabActived");
                $("#tab_upload").addClass("tabActived");
                $('#uploadFile').show();
                $('#selectFile').hide();
                $('#selectFile2').hide();
            } else {
                $("#tab_upload").removeClass("tabActived");
                $("#tab_select").addClass("tabActived");
                $('#uploadFile').hide();
                $('#selectFile').show();
                if ((file_name || ftpType) && !$("#btnISubmit").attr("disabled")) {
                    $('#selectFile2').show();
                }
            }
        }

        function uploadFile (type) {
            $("#cron_message").css("display", "none");
            $("#tableCSV").css("display", "none");
            if (type == "select") {

                $("#btnPreview").addClass("input-button-form-disabled");
                $("#btnPreview").attr("disabled", "disabled");

                var row_file = $("#rowFile").val();
                if (row_file != "") {
                    $("#file_name").val(row_file);
                }
                var file_name = $("#file_name").val();
                $.post(DEFAULT_URL + "/includes/code/<?=$includeFile?>", {
                    type: "ajax",
                    option: "verify_lines",
                    domain_id: "<?=SELECTED_DOMAIN_ID;?>",
                    file_name: file_name
                }, function (res) {
                    /*
                        * 1 - ftp_type = correct;
                        * 2 - ftp_type = schedule_cron;
                        * 3 - ftp_type = file bigger than 100mb;
                        */
                    if (res == 1) {
                        $("#ftp_type").attr("value", "correct");
                    } else if (res == 2) {
                        $("#ftp_type").attr("value", "schedule_cron");
                    } else if (res == 3) {
                        $("#max_mb_message").show("fast");
                        $("#pageLoad").css("display", "none");
                    }
                    if (res < 3) {
                        $("#importInfo").submit();
                    }
                });
            } else {
                $("#ftp_type").attr("value", "correct");
                $("#btnPreview").removeClass("input-button-form-disabled");
                $("#btnPreview").attr("disabled", "");
            }
        }

        function selectRow(radioId) {
            $("#btnPreview").removeClass("input-button-form-disabled");
            $("#btnPreview").attr("disabled", "");
            
            $("#" + radioId).attr("checked", "checked");
            $("#rowFile").val($("#" + radioId).val());
        }

        function changeDisplayForm() {
            $('#importOptions').hide();
            $('#tableDivisor').show();
            $('#wait_loading_file').show();
            $('#toScroll').hide();
        }

        function reloadFileList(autoLoad) {
            if (autoLoad == false) {
                var loading = "<div class=\"import-loading-box\"><img src=\"<?=DEFAULT_URL;?>/images/img_loading.gif\" alt=\"<?=system_showText(LANG_SITEMGR_WAITLOADING);?>\" title=\"<?=system_showText(LANG_SITEMGR_WAITLOADING);?>\"/>";
                loading += "<p class=\"import-loading\"><?=system_showText(LANG_SITEMGR_WAITLOADING);?></p></div>";

                $("#fileList").html(loading);
            }

            $.post(DEFAULT_URL + "/includes/code/<?=$includeFile?>", {
                type: "ajax",
                option: "reload_fileList",
                domain_id: "<?=SELECTED_DOMAIN_ID?>"
            }, function (res) {
                var arRes = res.split("[||]");
                if (arRes[1] == "EMPTY") {
                    $("#fileList").html(arRes[0]);
                    setTimeout("reloadFileList(true)", 5000);
                } else {
                    $("#fileList").html(arRes[0]);
                }
            });
        }

        function preview () {
            if (colSeparator == "tab" || colSeparator == '\t') {
                $(function(){
                    $('#csvPreview').csv2table(
                    fileName , {
                        limit: [0, 5],
                        nowloadingMsg: LANG_JS_LOADING,
                        col_sep: '\t',
                        sortable: false,
                        className_table: "standard-tableTOPBLUE"
                    });
                });
            } else {
                $(function(){
                    $('#csvPreview').csv2table(
                    fileName , {
                        limit: [0, 10],
                        nowloadingMsg: LANG_JS_LOADING,
                        col_sep: colSeparator,
                        sortable: false,
                        className_table: "standard-tableTOPBLUE"
                    });
                });
            }
        }

        if (fileForm) {
            changeFileForm(fileForm, fileName, ftpType);
        }

        $(document).ready(function () {
            if (fileName && !errorMessage) {
                preview();
            }
        })
    </script>