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
	# * FILE: /includes/forms/form_navigation.php
	# ----------------------------------------------------------------------------------------------------

?>

    <input type="hidden" name="order_options" id="order_options" value="" />
    <input type="hidden" name="aux_count_li" id="aux_count_li" value="<?=count($arrayOptions)?>" />
    <input type='hidden' name='SaveByAjax' value='true' id='SaveByAjax' value=""/>
    
    <p style="padding-bottom: 10px;">
        <?=system_showText(LANG_SITEMGR_NAVIGATION_EXPLANATION)?>
    </p>
    
    <? if (THEME_HAS_FOOTER) { ?>
        <div class="select-section">
            <select name="navigation_area" id="navigation_area" onchange="ChangeArea(this.value)">
                <option value="header" <?=($navigation_area == "header" ? "selected" : "")?>><?=system_showText(LANG_SITEMGR_HEADER)?></option>
                <option value="footer" <?=($navigation_area == "footer" ? "selected" : "")?>><?=system_showText(LANG_SITEMGR_FOOTER)?></option>
            </select>
            <span><?=system_showText(LANG_SITEMGR_SECTION)?>:</span>
        </div>
    <? } else { ?>
        <input type="hidden" name="navigation_area" value="header" />
    <? } ?>

    <h3><?=system_showText(LANG_SITEMGR_NAVIGATION_EDIT)?></h3>
    
    <table class="standardTableNAVIGATION" border="0" cellpadding="0" cellspacing="0" rules="0">    
        <tr>
            <th class="sortable-lorder"><?=system_showText(LANG_SITEMGR_NAVIGATION_ORDER)?></th>
            <th class="sortable-ltext"><?=system_showText(LANG_SITEMGR_NAVIGATION_NAVIGATION_TEXT)?></th>
            <th class="sortable-llinks"><?=system_showText(LANG_SITEMGR_NAVIGATION_LINKS_TO)?></th>
            <th class="sortable-lcustom"><?=system_showText(LANG_SITEMGR_NAVIGATION_CUSTOM_LINK)?></th>
            <th class="sortable-lremove"><?=system_showText(LANG_SITEMGR_REMOVE)?></th>
        </tr>

        <tr>
            <td colspan="5">
                <ul id="sortable" class="sortable-desc">
                    <? for ($i = 0; $i < count($arrayOptions); $i++) { ?>
                        <li class="ui-state-default" id="<?=$i?>">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" rules="0">
                                <tr>
                                    <td class="sortable-lorder">&nbsp;
                                        
                                    </td>
                                    
                                    <td class="sortable-ltext">
                                        <input type="text" name="navigation_text_<?=$i?>" id="navigation_text_<?=$i?>" value="<?=$arrayOptions[$i]["label"]?>" />
                                    </td>
                                    
                                    <td class="sortable-llinks">
                                        <select name="dropdown_link_to_<?=$i?>" id="dropdown_link_to_<?=$i?>" onchange="enableCustomLink(<?=$i?>)">
                                            <? for($j = 0; $j < count($array_modules); $j++) {
                                                $selected = false;
                                                if (($array_modules[$j]["url"] == $arrayOptions[$i]["link"]) || ($array_modules[$j]["url"] == "custom" && $arrayOptions[$i]["custom"] == "y")) {
                                                    $selected = "selected = \"selected\"";
                                                }
                                            ?>
                                                <option value="<?=$array_modules[$j]["url"]?>" <?=($selected ? $selected : "")?>>
                                                    <?=string_ucwords($array_modules[$j]["name"])?>
                                                </option>
                                            <? } ?>
                                        </select>
                                    </td>
                                    
                                    <td class="sortable-lcustom">
                                        <input type="text" name="custom_link_<?=$i?>" id="custom_link_<?=$i?>" value="<?=($arrayOptions[$i]["custom"] == "y" ? $arrayOptions[$i]["link"] :"")?>" <?=($arrayOptions[$i]["custom"] == "n" ? "disabled=\"true\" style=\"background-color:#f0f0f0\"" : "")?> />
                                    </td>
                                    
                                    <td class="sortable-lremove" align="center">
                                        <a class="sortable-remove" href="javascript:void(0)" onclick="javascript:removeItem(<?=$i?>)">&nbsp;</a>
                                    </td>                                
                                </tr>
                            </table>
                        </li>    
                    <? } ?>
                </ul>
            </td>
        </tr>
        
        <tr>
            <td colspan="2">
                <a class="sortable-add" href="javascript:void(0)" onclick="javascript:CreateNewItem();"><?=system_showText(LANG_SITEMGR_NAVIGATION_ADDROW);?></a>
            </td>
            <td colspan="3" class="sortable-btn">
                <button type="button" name="submit_button" class="input-button-form" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "JS_submit();"?>"><?=system_showText(LANG_SITEMGR_SUBMIT);?></button>
            </td>
        </tr>

    </table>