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
	# * FILE: /sitemgr/lancenter/flags.php
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
    $url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/langcenter/flags.php";
    extract($_GET);
    extract($_POST);
    $actionFrom = "editName";

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
        
        function changeComboLang (value) {
            if (value) {
                window.location.href = "<?=$url_redirect?>?clang="+value;
            }
            return true;
        }
        
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
                    
                    <input type="hidden" name="clang" value="<?=$clang?>" />
                    
                    <div class="lang-content">

                        <h3><?=system_showText(LANG_SITEMGR_LANGUAGES_CHANGE);?></h3>

                        <div class="lang-content-box lang-data-options">
                            
                            <? if ($error) { ?>
                                <p class="errorMessage"><?=$error?></p>
                            <? } ?>
                                
                            <? if (is_numeric($message) && $msg_lang[$message]) { ?>
                                <p class="successMessage" ><?=$msg_lang[$message]?></p>
                            <? } ?>
                            
                            <p><?=system_showText(LANG_SITEMGR_LANGUAGE_CHANGETIP);?></p>

                            <select name="lang" onchange="changeComboLang(this.value);">
                                <? foreach ($allLanguages as $langInfo) { ?>
                                    <option <?=($clang == $langInfo["id"] ? "selected=\"seleted\"" : "")?> value="<?=$langInfo["id"]?>"><?=$langInfo["name"]?></option>
                                <? } ?>
                            </select>                  
                        </div>

                        <div class="lang-content-box lang-data-options">
                                                       
                            <div class="left">
                            	<label><?=system_showText(LANG_SITEMGR_LANGUAGE_RENAME);?></label>                            
                            	<input type="text" name="language_name" maxlength="12" value="<?=$language_name?>" />
                                <span class="divisor">&nbsp;</span>
                            </div>
                                                                               
                            <div class="right">
                                <label><?=system_showText(LANG_SITEMGR_LANGUAGE_NEWFLAG);?></label>
                                <div class="fileEscondido-box">
                                    <input type="text" name="" id="edited-file" value="<?=system_showText(LANG_SITEMGR_LANGUAGE_UPFLAG);?>" readonly="readonly">                                
                                    <span class="fileEscondido">
                                        <button type="button"><?=system_showText(LANG_SITEMGR_LANGUAGE_CHOOSEFILE)?></button>
                                        <input type="file" name="flag_image" onchange="javascript: $('#edited-file').val($(this).val())" size="43" />
                                    </span>                            
                                </div>
                            </div>
                            
                        </div>

                        <button class="input-button-form" type="button" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_LANGUAGE_DEMO_MESSAGE)."');" : "JS_submit();"?>"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>

                    </div>
                    
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