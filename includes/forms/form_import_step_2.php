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
	# * FILE: /includes/forms/form_import_step_2.php
	# ----------------------------------------------------------------------------------------------------
    
    setting_get("import_update_listings", $import_update);
    
    if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on"){
        setting_get("import_update_events", $import_update_event);
    }
    
    $message = "";
    if (function_exists("mb_detect_encoding") && function_exists("mb_convert_encoding")) {
        $message = system_showText(LANG_SITEMGR_MSG_IMPORT_CONVERT_UTF8);
    } else { 
        $message = system_showText(LANG_SITEMGR_MSG_IMPORT_CHECK_UTF8);
    }
    
    if ($import_update){
        $message = $message." ".system_showText(LANG_SITEMGR_MSG_IMPORT_UPDATE_ITENS);
    }
    if ($import_update_event){
        $message_event = $message." ".system_showText(LANG_SITEMGR_MSG_IMPORT_UPDATE_ITENS);
    }
?>
    <!-- LISTINGS -->
    
    <div class="import-holder">

    <div id="importInfo_0" <?=($module != "listing" ? "style=\"display: none;\"" : "")?>>
        <table class="standard-table import-tip import-table">
            <tr>
                <td class="standard-tablenote" style="text-align: justify;">
                    <a href="<?=DEFAULT_URL;?>/<?=SITEMGR_ALIAS?>/faq/faq.php?keyword=<?=urlencode("import");?>" target="_blank"><?=system_showText(LANG_SITEMGR_IMPORT_HOMETIP1_3)?></a><br />
                    <?=system_showText(LANG_SITEMGR_DOWNLOAD_TEMPLATE);?>
                </td>
            </tr>
            <tr>
                <td class="button-box">
                    <button type="button" class="input-button-form input-largebutton-form" onclick="JS_submit(<?=$step-1?>, true, 'listing');"><?=system_showText(LANG_SITEMGR_DOWNLOAD_TEMPLATE2);?></button>
                </td>
            </tr>
        </table>
    </div>
    
    <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
        <!-- EVENTS -->
        <div id="importInfo_1" <?=($module != "event" ? "style=\"display: none;\"" : "")?>>
            <table class="standard-table import-tip import-table">
                <tr>
                    <td class="standard-tablenote" style="text-align: justify;">
                        <a href="<?=DEFAULT_URL;?>/<?=SITEMGR_ALIAS?>/faq/faq.php?keyword=<?=urlencode("import");?>" target="_blank"><?=system_showText(LANG_SITEMGR_IMPORT_HOMETIP1_3)?></a><br />
                        <?=system_showText(LANG_SITEMGR_DOWNLOAD_TEMPLATE);?>
                    </td>
                </tr>
                <tr>
                    <td class="button-box">
                        <button type="button" class="input-button-form input-largebutton-form" onclick="JS_submit(<?=$step-1?>, true, 'event');"><?=system_showText(LANG_SITEMGR_DOWNLOAD_TEMPLATE2);?></button>
                    </td>
                </tr>
            </table>
        </div>
    <? } ?>
    
    </div>

    <button type="button" name="submit_button" class="input-button-form left" value="Submit" onclick="JS_submit(<?=$step-1?>, false);"><?=system_showText(LANG_SITEMGR_BACK)?></button>
    <button type="submit" name="submit_button" class="input-button-form right" value="Submit"><?=system_showText(LANG_SITEMGR_NEXT)?></button>
	
    <br clear="all" />

    <div class="header-form">
        <?=system_showText(LANG_SITEMGR_IMPORT_HOMETIP1_1);?>
    </div>    
    <div class="wrapper import-box">
        <p><?=system_showText(LANG_SITEMGR_IMPORT_HOMETEXT2)?></p>
        <p><?=system_showText(LANG_SITEMGR_IMPORT_HOMETIP1_4)?></p>
        <p><?=system_showText(LANG_SITEMGR_IMPORT_HOMETEXT4." (/ | * . ; _ :).")?></p>
        <p id="extraMessage_0" <?=($module != "listing" ? "style=\"display: none;\"" : "")?>><?=$message?></p>
        <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
        <p id="extraMessage_1" <?=($module != "event" ? "style=\"display: none;\"" : "")?>><?=$message_event?></p>
        <? } ?>
        <p><?=system_showText(LANG_SITEMGR_IMPORT_HOMETEXT1_2)?></p>
        <p><?=system_showText(LANG_SITEMGR_IMPORT_HOMETEXT1_3)?></p>
    </div>