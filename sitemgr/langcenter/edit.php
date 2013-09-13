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
	# * FILE: /sitemgr/lancenter/edit.php
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
	# VALIDATING FEATURES
	# ----------------------------------------------------------------------------------------------------
	if (MULTILANGUAGE_FEATURE != "on") { exit; }

    # ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
    $url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/langcenter/edit.php";
    extract($_GET);
    extract($_POST);
    $actionFrom = "editLang";

	# ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/language_center.php");
    
	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

	//increases frequently actions
	if (!isset($message)) system_setFreqActions("prefs_langcenter", "MULTILANGUAGE_FEATURE");

?>

    <script language="javascript" type="text/javascript">
        
        function JS_submit() {
            document.language.submit();
        }
        
        function downloadFile() {
            <? if (!DEMO_LIVE_MODE) { ?>
                $("#language_id").attr("value", $("#lang").val());
                $("#language_area").attr("value", $("#area").val());
                document.language_file.submit();
            <? } else { ?>
				livemodeMessage('<?=system_showText(LANG_SITEMGR_LANGUAGE_DEMO_MESSAGE);?>');
			<? } ?>
        }
        
        function validateValidateAndSave() {
            $.ajax({
                url: "<?=DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$fileTempValidateName?>"+"?"+encodeURI(Math.random()),
                error: function(xhr, statusText, errorThrown) {
                    errorStatus = xhr.status;
                    errorStatus = errorStatus.toString();
                },
                success: function(html) {
                    errorStatus = html;
                },
                complete: function() {
                    if (errorStatus.length > 1) {
                        fancy_alert("<?=system_showText(LANG_SITEMGR_EDITOR_PHPERROR)?>", 'errorMessage', false, 450, 100, false);
                        saveCleanFiles(1);
                    } else {
                        saveCleanFiles(0);
                    }
                }
            });
        }
        
        function saveCleanFiles(clean) {
            $.post(DEFAULT_URL + "/" + SITEMGR_ALIAS + "/langcenter/edit.php", {
                action: "ajax",
                clean: clean,
                file: '<?=($fileTempOriginal ? $fileTempOriginal : "0")?>',
                temp_file: '<?=($fileTempValidatePath ? $fileTempValidatePath : "0")?>',
                file_name: '<?=($file_name ? $file_name : "0")?>'
            }, function () {
                if (!clean) {
                    $("#successMsg").fadeIn(500);
                }
            });
        }
        
        <? if ($checkTempFile) { ?>
            $(document).ready(function() {
                validateValidateAndSave();
            });
        <? } ?>

    </script>

    <div id="main-right">

        <div id="top-content">
            <div id="header-content">
                <h1><?=system_showText(LANG_SITEMGR_LANGUAGES)?></h1>
            </div>
        </div>
        
        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                
                <? include(INCLUDES_DIR."/tables/table_lang_submenu.php"); ?>

                <form name="language" id="language" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
                    
                    <div class="lang-content">

                        <h3><?=system_showText(LANG_SITEMGR_LANGUAGE_DATA_OPTIONS);?></h3>

                        <div class="lang-content-box lang-data-options">
                            <p><?=system_showText(LANG_SITEMGR_LANGUAGE_DATA_OPTIONS_TIP);?></p>

                            <select id="lang" name="lang">
                                <? foreach ($allLanguages as $langInfo) { ?>
                                    <option <?=($lang == $langInfo["id"] ? "selected=\"seleted\"" : "")?> value="<?=$langInfo["id"]?>"><?=$langInfo["name"]?></option>
                                <? } ?>
                            </select>

                            <select id="area" name="area">
                                <option <?=($area == "sitemgr" ? "selected=\"seleted\"" : "")?> value="sitemgr"><?=system_showText(LANG_SITEMGR_LANGUAGE_AREA_SITEMGR);?></option>
                                <option <?=($area == "front" ? "selected=\"seleted\"" : "")?> value="front"><?=system_showText(LANG_SITEMGR_LANGUAGE_AREA_FRONT);?></option>
                            </select>                    
                        </div>

                        <div class="lang-content-box lang-data-options">
                            
                            <? if ($error) { ?>
                                <p class="errorMessage"><?=$error?></p>
                            <? } ?>
                                
                            <p class="successMessage" style="display:none;" id="successMsg"><?=$msg_lang[0]?></p>
                              
                            <p style="margin:0 0 20px;"><?=system_showText(LANG_SITEMGR_LANGUAGE_DOWNLOAD_TIP);?></p>
                            
                            <a class="down-file" href="javascript: void(0);" onclick="downloadFile();"><?=system_showText(LANG_SITEMGR_LANGUAGE_DOWNLOAD)?></a>  
                            <div class="fileEscondido-box">
                            
                                <input type="text" name="" id="edited-file" value="<?=system_showText(LANG_SITEMGR_LANGUAGE_UPLOADHERE);?>" readonly="readonly">                                
                                <span class="fileEscondido">
                                    <button type="button"><?=system_showText(LANG_SITEMGR_LANGUAGE_CHOOSEFILE)?></button>
                                    <input type="file" name="lang_edited_file" onchange="javascript: $('#edited-file').val($(this).val())" size="43" />
                                </span>
                            
                            </div>
                            
                        </div>

                        <? /*
                        <p class="lang-pagination">2723 Language Fields | Showing Page 1 of 42 
                            <span>Go to page: <select onchange="this.form.submit();" name="screen"><option selected="selected" value="1">1</option></select> <a title="next page" href="#" class="rightArrow">&nbsp;</a></span>
                        </p>

                        <h3>Language Editor</h3>
                        <select class="lang-editor" name="lang">
                            <option value="en_us">English</option>
                        </select>

                        <table class="lang-table-value" border="0" cellspacing="0" width="765">
                            <tr>
                                <th width="200">Field Name</th>
                                <th width="315">Default Value</th>
                                <th width="170">New Value</th>
                                <th width="80">Revert/Accept</th>
                            </tr>  
                            <tr class="tr-alt">
                                <td>LANG_YEAR</td>
                                <td>Year</td>
                                <td><input type="text" value="New Value..." name="" /></td>
                                <td class="lang-table-value-actions"><a href="#"><img width="15" height="16" border="0" title="" alt="" src="<?=DEFAULT_URL?>/sitemgr/images/design/icon-reload.png"></a> <a href="#"><img width="15" height="16" border="0" title="" alt="" src="<?=DEFAULT_URL?>/sitemgr/images/design/icon-checked.png"></a></td>
                            </tr>  
                            <tr>
                                <td>LANG_YEAR_PLURAL</td>
                                <td>Years</td>
                                <td><input type="text" value="New Value..." name="" /></td>
                                <td class="lang-table-value-actions"><a href="#"><img width="15" height="16" border="0" title="" alt="" src="<?=DEFAULT_URL?>/sitemgr/images/design/icon-reload.png"></a> <a href="#"><img width="15" height="16" border="0" title="" alt="" src="<?=DEFAULT_URL?>/sitemgr/images/design/icon-checked.png"></a></td>
                            </tr>   
                            <tr class="tr-alt">
                                <td>LANG_MONTH</td>
                                <td>Month</td>
                                <td><input type="text" value="New Value..." name="" /></td>
                                <td class="lang-table-value-actions"><a href="#"><img width="15" height="16" border="0" title="" alt="" src="<?=DEFAULT_URL?>/sitemgr/images/design/icon-reload.png"></a> <a href="#"><img width="15" height="16" border="0" title="" alt="" src="<?=DEFAULT_URL?>/sitemgr/images/design/icon-checked.png"></a></td>
                            </tr>                                                                           
                        </table> 

                        <p class="lang-pagination lang-pagination-bottom">2723 Language Fields | Showing Page 1 of 42 
                            <span>Go to page: <select onchange="this.form.submit();" name="screen"><option selected="selected" value="1">1</option></select> <a title="next page" href="#" class="rightArrow">&nbsp;</a></span>
                        </p>                
                        */?>
                        
                        <button class="input-button-form" type="button" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_LANGUAGE_DEMO_MESSAGE)."');" : "JS_submit();"?>"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>

                    </div>
                    
                </form>
                    
                <form name="language_file" id="language_file" action="language_file.php" method="get" target="_blank">
                    <input type="hidden" name="language_id" id="language_id" value="" />
                    <input type="hidden" name="language_area" id="language_area" value="" />
                    <input type="hidden" name="domain_id" value="<?=SELECTED_DOMAIN_ID?>" />
                </form>

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