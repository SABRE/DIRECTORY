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
	# * FILE: /includes/forms/form_content_editor.php
	# ----------------------------------------------------------------------------------------------------

?>
    <?=$editorSelect;?>
    <br clear="all" />

    <textarea name="text" id="textarea" style="width:765px; height:450px;"><?=htmlspecialchars($text)?></textarea>
    
    <textarea id="text_temp" name="text_temp" style="display:none"></textarea>

    <input type="hidden" name="file" value="<?=$file?>" />
    <input type="hidden" name="fileType" value="<?=$fileType?>" />

    <div class="left">
        <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmlsource.php?file=$file"?>" id="info_window" class="iframe fancy_window_htmleditor" style="display:none"></a>
        <p class="button">
            <button type="button" onclick="show_source();">
                <?=system_showText(LANG_SITEMGR_EDITOR_SHOWSOURCE)?>
            </button>
        </p>
        <p class="button">
            <button type="button" onclick="download_source();">
                <?=system_showText(LANG_SITEMGR_EDITOR_DOWNLOADSOURCE)?>
            </button>
        </p>

    </div>

    <div class="right">
        <p>
            <span><?=system_showText(LANG_SITEMGR_EDITOR_SHOWSOURCE_TXT1)?> <input type="checkbox" value="on" name="send_backup" <?=($send_backup == "on" ? "checked=\"checked\"" : "")?> class="inputradio" /></span>
            <input type="text" maxlength="255" value="<?=$sitemgr_email?>" name="email_backup" />
        </p>
        
        <p class="nav-action">

            <? if ($fileType == "header_footer" || ($fileType == "lang")) { ?>
        
                <button type="submit" name="htmleditorPreview" value="SubmitPreview" class="preview-link" >
                    <?=system_showText(LANG_SITEMGR_PREVIEW)?>
                </button>

                <span class="lang_or"><?=system_showText(LANG_OR);?></span>
            
            <? } ?>
    
            <span class="button go-action">
                <? if ($fileType == "lang") { ?>
                
                    <button type="button" name="htmleditor" id="button_submit" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "validateSubmit();"?>" class="button" >
                        <?=system_showText(LANG_SITEMGR_EDITOR_SUBMITCHANGES)?>
                    </button>
                
                <? } else { ?>
                
                    <button type="submit" name="htmleditor" value="Submit" class="button" >
                        <?=system_showText(LANG_SITEMGR_EDITOR_SUBMITCHANGES)?>
                    </button>
                
                <? } ?>
                
					
            </span>
            
            <span class="lang_or"><?=system_showText(LANG_OR);?></span>
            
            <a href="<?=DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php?file=".$file?>"><?=system_showText(LANG_SITEMGR_CANCEL)?></a>
        
        </p>

    </div>

    <div class="codereversion">

        <h3><?=system_showText(LANG_SITEMGR_EDITOR_CODE_REVERSION)?></h3>
        <p><?=system_showText(LANG_SITEMGR_EDITOR_CODE_REVERSION_TXT1)?></p>

        <p class="button alert-action">
            <button type="submit" name="revert" value="Submit" class="button">
                <?=system_showText(LANG_SITEMGR_EDITOR_REVERT)?>
            </button> 
        </p> 

    </div>