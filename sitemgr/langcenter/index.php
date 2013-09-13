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
	# * FILE: /sitemgr/lancenter/index.php
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
    $url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/langcenter/index.php";
    extract($_GET);
    extract($_POST);
    $actionFrom = "changeLang";

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
	if (!isset($message) && !isset($message_admin)) system_setFreqActions("prefs_langcenter", "MULTILANGUAGE_FEATURE");

?>

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
                
                <p><?=system_showText(LANG_SITEMGR_LANGUAGES_WELCOME);?></p>

                <div class="lang-content">

                    <h3><?=system_showText(LANG_SITEMGR_LANGUAGE_MAIN);?> - <span><?=system_showText(LANG_SITEMGR_LANGUAGE_MAIN_TIP);?></span></h3>
                    
                    <div class="lang-content-box">
                        
                        <? if (is_numeric($message) && isset($msg_lang[$message])) { ?>
                            <p class="successMessage"><?=$msg_lang[$message]?></p>
                        <? } ?>
                        
                        <? foreach ($allLanguages as $lang) {

                            if (file_exists($flagFolder."/".$lang["id"].".png")) {
                                $flagPath = DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/lang/flags/".$lang["id"].".png";
                            } else {
                                $flagPath = DEFAULT_URL."/".SITEMGR_ALIAS."/images/design/img-flag-".$lang["id"].".png";
                            }
                            if ($lang["lang_default"] == "y") {
                                $auxStyle = "style=\"cursor: default;\"";
                            } else {
                                $auxStyle = "";
                            }
                        ?>
                        
                            <div class="lang">                        
                                <a href="<?=($lang["lang_default"] == "y" ? "javascript: void(0);" : $url_redirect."?active&id=".$lang["id"])?>" <?=$auxStyle?>>
                                    <img width="63" height="43" border="0" alt="<?=$lang["name"]?>" src="<?=$flagPath?>">
                                    <span><?=$lang["name"]?></span>
                                </a>
                                <span <?=($lang["lang_default"] == "y" ? "class=\"checked\" style=\"cursor:default;\"" : "");?>>&nbsp;</span>
                            </div>
                        
                        <? } ?>

                    </div>

                </div>
                
                <div class="lang-content">

                    <h3><?=system_showText(LANG_SITEMGR_LANGUAGE_ADMIN);?> - <span><?=system_showText(LANG_SITEMGR_LANGUAGE_ADMIN_TIP);?></span></h3>
                    
                    <div class="lang-content-box">
                        
                        <? if (is_numeric($message_admin) && isset($msg_lang[$message_admin])) { ?>
                            <p class="successMessage"><?=$msg_lang[$message_admin]?></p>
                        <? } ?>
                        
                        <? foreach ($allLanguages as $lang) {

                            if (file_exists($flagFolder."/".$lang["id"].".png")) {
                                $flagPath = DEFAULT_URL."/custom/domain_".SELECTED_DOMAIN_ID."/lang/flags/".$lang["id"].".png";
                            } else {
                                $flagPath = DEFAULT_URL."/".SITEMGR_ALIAS."/images/design/img-flag-".$lang["id"].".png";
                            }
                            if ($sitemgr_language == $lang["id"]) {
                                $auxStyle = "style=\"cursor: default;\"";
                            } else {
                                $auxStyle = "";
                            }
                        ?>
                        
                            <div class="lang">                        
                                <a href="<?=($sitemgr_language == $lang["id"] ? "javascript: void(0);" : $url_redirect."?activeAdmin&id=".$lang["id"])?>" <?=$auxStyle?>>
                                    <img width="63" height="43" border="0" title="" alt="<?=$lang["name"]?>" src="<?=$flagPath?>">
                                    <span><?=$lang["name"]?></span>
                                </a>
                                <span <?=($sitemgr_language == $lang["id"] ? "class=\"checked\" style=\"cursor:default;\"" : "");?>>&nbsp;</span>
                            </div>
                        
                        <? } ?>

                    </div>

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