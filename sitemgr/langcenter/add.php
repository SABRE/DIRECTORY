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
	# * FILE: /sitemgr/lancenter/add.php
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
    $url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/langcenter/add.php";
    extract($_GET);
    extract($_POST);
    $actionFrom = "addLang";

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
        
		function download_file() {
            
			<? if (!DEMO_LIVE_MODE) { ?>
				document.location = "<?=$url_redirect?>?download=1";
			<? } else { ?>
				livemodeMessage('<?=system_showText(LANG_SITEMGR_LANGUAGE_DEMO_MESSAGE);?>');
			<? } ?>
                
		}
        
        function validateValidateAndSave() {
            $.ajax({
                url: "<?=DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$fileTempValidateNameFront?>"+"?"+encodeURI(Math.random()),
                error: function(xhr, statusText, errorThrown) {
                    errorStatus = xhr.status;
                    errorStatus = errorStatus.toString();
                },
                success: function(html) {
                    errorStatus = html;
                },
                complete: function() {
                    if (errorStatus.length > 1) {
                        fancy_alert("<?=system_showText(LANG_SITEMGR_EDITOR_PHPERROR)?> (<?=system_showText(LANG_SITEMGR_LANGUAGE_AREA_FRONT)?>)", 'errorMessage', false, 450, 100, false);
                        saveCleanFiles(1);
                    } else {
                        
                        $.ajax({
                            url: "<?=DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$fileTempValidateNameSitemgr?>"+"?"+encodeURI(Math.random()),
                            error: function(xhr, statusText, errorThrown) {
                                errorStatus = xhr.status;
                                errorStatus = errorStatus.toString();
                            },
                            success: function(html) {
                                errorStatus = html;
                            },
                            complete: function() {
                                if (errorStatus.length > 1) {
                                    fancy_alert("<?=system_showText(LANG_SITEMGR_EDITOR_PHPERROR)?> (<?=system_showText(LANG_SITEMGR_LANGUAGE_AREA_SITEMGR)?>)", 'errorMessage', false, 450, 100, false);
                                    saveCleanFiles(1);
                                } else {
                                    saveCleanFiles(0);
                                }
                            }
                        });
                        
                    }
                }
            });
        }
        
        function saveCleanFiles(clean) {
            $.post(DEFAULT_URL + "/" + SITEMGR_ALIAS + "/langcenter/add.php", {
                action: "ajax",
                clean: clean,
                domain_id: '<?=(SELECTED_DOMAIN_ID)?>',
                lang_id: '<?=($language_abbr ? $language_abbr : "0")?>',
                lang_name: '<?=($language_name ? $language_name : "0")?>',
                flagPath: '<?=($flagPath ? $flagPath : "0")?>',
                fileFront: '<?=($fileTempOriginalFront ? $fileTempOriginalFront : "0")?>',
                temp_fileFront: '<?=($fileTempValidatePathFront ? $fileTempValidatePathFront : "0")?>',
                file_nameFront: '<?=($fileName ? $fileName : "0")?>',
                fileSitemgr: '<?=($fileTempOriginalSitemgr ? $fileTempOriginalSitemgr : "0")?>',
                temp_fileSitemgr: '<?=($fileTempValidatePathSitemgr ? $fileTempValidatePathSitemgr : "0")?>',
                file_nameSitemgr: '<?=($fileName_sitemgr ? $fileName_sitemgr : "0")?>'
            }, function () {
                if (!clean) {
                    $("#successMsg").fadeIn(500);
                    $("#language_name").attr("value", '<?=LANG_SITEMGR_LANGUAGE_NAMELANG?>');
                    $("#language_abbr").attr("value", '<?=LANG_SITEMGR_LANGUAGE_ABBRLANG?>');
                }
            });
        }
        
        $(document).ready(function() {
            
			//Language Name
			$('#table_addLang').find('#language_name').focus(function()
            {
                $(this).removeClass("blur").addClass("focus");
                if (this.value == '<?=LANG_SITEMGR_LANGUAGE_NAMELANG?>') {
                    this.value = '';
                }
                if(this.value != this.defaultValue) {
                    this.select();
                }
            });

            $('#table_addLang').find('#language_name').blur(function()
            {
                $(this).removeClass("focus").addClass("blur");
                if ($.trim(this.value) == '') {
                    this.value = '<?=LANG_SITEMGR_LANGUAGE_NAMELANG?>';
                }
            });

			//Language Abbreviation
			$('#table_addLang').find('#language_abbr').focus(function()
            {
                $(this).removeClass("blur").addClass("focus");
                if (this.value == '<?=LANG_SITEMGR_LANGUAGE_ABBRLANG?>') {
                    this.value = '';
                }
                if(this.value != this.defaultValue) {
                    this.select();
                }
            });

            $('#table_addLang').find('#language_abbr').blur(function()
            {
                $(this).removeClass("focus").addClass("blur");
                if ($.trim(this.value) == '') {
                    this.value = '<?=LANG_SITEMGR_LANGUAGE_ABBRLANG?>';
                }
            });
            
            <? if ($checkTempFile) { ?>
                validateValidateAndSave();
            <? } ?>
        });
	</script>

    <div id="main-right">

        <div id="top-content">
            <div id="header-content">
                <h1><?=system_showText(LANG_SITEMGR_LANGUAGES)?></h1>
            </div>
        </div>
        
        <form name="language" id="language" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
            
            <div id="content-content">
                
                <div class="default-margin">

                    <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                    <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>

                    <? include(INCLUDES_DIR."/tables/table_lang_submenu.php"); ?>

                    <div class="lang-content">
                        
                        <? if ($error) { ?>
                            <p class="errorMessage"><?=$error?></p>
                        <? } ?>
                            
                        <p class="successMessage" style="display:none;" id="successMsg"><?=$msg_lang[1]?></p> 

                        <h3><?=system_showText(LANG_SITEMGR_LANGUAGES_ADD)?></h3>
                        
                        <table id="table_addLang" class="lang-table-value lang-add-value" border="0" cellspacing="0" width="765">
                            <tr>
                                <th colspan="3"><?=system_showText(LANG_SITEMGR_LANGUAGE_ADDTIP)?></th>
                            </tr>
                            <tr class="tr-alt">
                                <td class="number">1</td>
                                <td class="desc"><?=system_showText(LANG_SITEMGR_LANGUAGE_ADD_1)?></td>
                                <td class="action"> <a href="javascript:void(0);" onclick="download_file();" class="down-file"><?=system_showText(LANG_SITEMGR_LANGUAGE_DOWNLOAD)?></a></td>
                            </tr>
                            <tr>
                                <td class="number">2</td>
                                <td class="desc"><?=system_showText(LANG_SITEMGR_LANGUAGE_ADD_2)?></td>
                                <td class="action"> <input type="text" id="language_name" name="language_name" maxlength="12" value="<?=($language_name ? $language_name :system_showText(LANG_SITEMGR_LANGUAGE_NAMELANG))?>"></td>
                            </tr>
                            <tr class="tr-alt">
                                <td class="number">3</td>
                                <td class="desc"><?=system_showText(LANG_SITEMGR_LANGUAGE_ADD_3)?> <img src="<?=DEFAULT_URL?>/images/icon_interrogation.gif" alt="?" title="<?=string_htmlentities(system_showText(LANG_SITEMGR_LANGUAGE_ADD_3_TIP))?>" border="0" /></td>
                                <td class="action"> <input type="text" id="language_abbr" name="language_abbr" maxlength="5" value="<?=($language_abbr ? $language_abbr : system_showText(LANG_SITEMGR_LANGUAGE_ABBRLANG))?>"></td>
                            </tr>
                            <tr>
                                <td class="number">4</td>
                                <td class="desc"><?=system_showText(LANG_SITEMGR_LANGUAGE_ADD_4)?></td>
                                <td class="action" colspan="2">
                                <div class="fileEscondido-box">
                                    <input type="text" name="" id="arquivo-img" value="<?=system_showText(LANG_SITEMGR_LANGUAGE_UPFLAG)?>" readonly="readonly">
                                    <span class="fileEscondido">
                                        <button type="button"><?=system_showText(LANG_SITEMGR_LANGUAGE_CHOOSEFILE)?></button>
                                        <input type="file" name="flag_image" size="43" onchange="javascript: $('#arquivo-img').val($(this).val())" />
                                    </span>
                                </div>    
                                </td>
                            </tr>
                            <tr class="tr-alt">
                                <td class="number">5</td>
                                <td class="desc"><?=system_showText(LANG_SITEMGR_LANGUAGE_ADD_5)?></td>
                                <td class="action action-section">
                                    <div class="fileEscondido-box">
                                    	<label><?=system_showText(LANG_SITEMGR_LANGUAGE_AREA_FRONT)?>:</label>
                                        <input type="text" name="" id="arquivo-front" value="<?=system_showText(LANG_SITEMGR_LANGUAGE_UPLOADHERE)?>" readonly="readonly">
                                        <span class="fileEscondido">
                                            <button type="button"><?=system_showText(LANG_SITEMGR_LANGUAGE_CHOOSEFILE)?></button>
                                            <input type="file" name="lang_edited_front" size="43" onchange="javascript: $('#arquivo-front').val($(this).val())" />
                                        </span>
                                    </div>
                                    <div class="fileEscondido-box">
                                    	<label><?=system_showText(LANG_SITEMGR_LANGUAGE_AREA_SITEMGR)?>:</label>
                                        <input type="text" name="" id="arquivo-sitemgr" value="<?=system_showText(LANG_SITEMGR_LANGUAGE_UPLOADHERE)?>" readonly="readonly">
                                        <span class="fileEscondido">
                                            <button type="button"><?=system_showText(LANG_SITEMGR_LANGUAGE_CHOOSEFILE)?></button>
                                            <input type="file" name="lang_edited_sitemgr" size="43" onchange="javascript: $('#arquivo-sitemgr').val($(this).val())" />
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <button class="input-button-form"type="button" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_LANGUAGE_DEMO_MESSAGE)."');" : "JS_submit();"?>">Submit</button>

                    </div>

                </div>
                
            </div>
        </form>

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