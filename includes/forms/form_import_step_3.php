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
	# * FILE: /includes/forms/form_import_step_3.php
	# ----------------------------------------------------------------------------------------------------

?>
    <?  // Account Search Javascript /////////////////////////////////////////////////////// ?>
    <script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/accountsearch.js"></script>
    
    <script type="text/javascript">
        function JS_ShowHideAccount() {
            if (document.getElementById('import_sameaccount').checked) document.getElementById('import_account_id').style.display = "";
            else document.getElementById('import_account_id').style.display = "none";
        }
        
        <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
            function JS_ShowHideAccountEvent() {
                if (document.getElementById('import_sameaccount_event').checked) document.getElementById('import_account_id_event').style.display = "";
                else document.getElementById('import_account_id_event').style.display = "none";
            }
        <? } ?>
    </script>

    <div class="import-holder">
   
    <? if ($error_sameaccount) { ?>
		<div id="warning" class="<?=$message_style?>"><?=$errorMsg?></div>
	<? } ?>
    
   

	<!-- LISTINGS -->
	<div id="importInfo_0" <?=$module == "event" ? "style=\"display:none\"" : ""?>>
		
		<table cellpadding="2" cellspacing="0" class="table-form table-form-settings table-form-margin">
            <tr>
                <td align="left" colspan="4"><div class="label-form"><?=system_showText(LANG_SITEMGR_STEP3_TIP1)?></div></td>
            </tr>
			<tr>
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_IMPORT_FROMEXPORT)?></div></td>
				<td colspan="3" align="left">
					<input type="checkbox" id="import_from_export_listing" name="import_from_export_listing" value="1" align="absmiddle" <?=$import_from_export_listing?> style="width: auto; border: 0;" class="inputCheck" />
				</td>
			</tr>
			<tr>
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_ALLASACTIVE)?></div></td>
				<td align="left">
					<input type="checkbox" id="import_enable_active_listing" name="import_enable_active_listing" value="1" align="absmiddle" <?=$import_enable_active_listing?> style="width: auto; border: 0;" class="inputCheck" />
				</td>
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_FEATURED_CATEGS)?></div></td>
				<td align="left">
					<input type="checkbox" id="import_featured_categs_listing" name="import_featured_categs_listing" value="1" align="absmiddle" <?=$import_featured_categs_listing?> style="width: auto; border: 0;" class="inputCheck" />
				</td>                
			</tr>
			<tr>
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_UPDATE)?></div></td>
				<td align="left">
					<input type="checkbox" id="import_update_items_listing" name="import_update_items_listing" value="1" align="absmiddle" <?=$import_update_items_listing?> style="width: auto; border: 0;" class="inputCheck" />
				</td>
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_FRIENDLYURL)?></div></td>
				<td align="left">
					<input type="checkbox" id="import_update_friendlyurl_listing" name="import_update_friendlyurl_listing" value="1" align="absmiddle" <?=$import_update_friendlyurl_listing?> style="width: auto; border: 0;" class="inputCheck" />
				</td>
			</tr>
			<tr>
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_SAMEACCOUNT)?></div></td>
				<td align="left"><input type="checkbox" id="import_sameaccount"  name="import_sameaccount_listing" value="1"  align="absmiddle" <?=$import_sameaccount_listing?> style="width: auto; border: 0;" class="inputCheck" onclick="JS_ShowHideAccount();"/></td>            
				<td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_DEFAULTLEVEL)?></div></td>
				<td colspan="3" align="left">
					<select name="import_defaultlevel_listing" style="width: 150px;">
					<?
					$levelObj = new ListingLevel();
					$levelvalues = $levelObj->getLevelValues();
					foreach ($levelvalues as $levelvalue) {
						if ($import_defaultlevel_listing == $levelvalue) {
							$selected = " selected=\"selected\"";
                        } else { 
                            $selected = "";
                        }
						echo "<option value=\"".$levelvalue."\" $selected>".$levelObj->showLevel($levelvalue)."</option> ";
					}
					?>
					</select>
				</td>
			</tr>
		</table>

		<div id="import_account_id" class="base-table-form-account" <?=($import_sameaccount_listing != "checked") ? "style=\"display:none;\"" : ""?>>
			<? // Account Search ////////////////////////////////////////////////////////////////// ?>
				<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
					<tr>
						<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_TOACCOUNT)?> <span><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_TOACCOUNT_SPAN)?></span></th>
					</tr>
				</table>
				<?
				$acct_search_table_title = system_showText(LANG_SITEMGR_ACCOUNTSEARCH_SELECT_DEFAULT);
				$acct_search_field_name = "account_id_listing";
				$acct_search_field_value = $account_id_listing;
				$acct_search_required_mark = false;
				$acct_search_form_width = "95%";
				$acct_search_cell_width = "";
				$return = system_generateAjaxAccountSearch($acct_search_table_title, $acct_search_field_name, $acct_search_field_value, $acct_search_required_mark, $acct_search_form_width, $acct_search_cell_width);
				echo $return;
				?>
			<? //////////////////////////////////////////////////////////////////////////////////// ?>
		</div>
	</div>

	<? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
		<!-- EVENTS -->
		<div id="importInfo_1" <?=$module == "event" ? "" : "style=\"display:none\""?>>

            <table cellpadding="2" cellspacing="0" class="table-form table-form-settings table-form-margin">
                <tr>
                    <td align="left" colspan="4"><div class="label-form"><?=system_showText(LANG_SITEMGR_STEP3_TIP1)?></div></td>
                </tr>
                <tr>
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_IMPORT_FROMEXPORT)?></div></td>
                    <td colspan="3" align="left">
                        <input type="checkbox" id="import_from_export_event" name="import_from_export_event" value="1" align="absmiddle" <?=$import_from_export_event?> style="width: auto; border: 0;" class="inputCheck" />
                    </td>
                </tr>
                <tr>
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_ALLASACTIVE)?></div></td>
                    <td align="left">
                        <input type="checkbox" id="import_enable_active_event" name="import_enable_active_event" value="1" align="absmiddle" <?=$import_enable_active_event?> style="width: auto; border: 0;" class="inputCheck" />
                    </td>
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_FEATURED_CATEGS)?></div></td>
                    <td align="left">
                        <input type="checkbox" id="import_featured_categs_event" name="import_featured_categs_event" value="1" align="absmiddle" <?=$import_featured_categs_event?> style="width: auto; border: 0;" class="inputCheck" />
                    </td>                
                </tr>
                <tr>
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_UPDATE)?></div></td>
                    <td align="left">
                        <input type="checkbox" id="import_update_items_event" name="import_update_items_event" value="1" align="absmiddle" <?=$import_update_items_event?> style="width: auto; border: 0;" class="inputCheck" />
                    </td>
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_FRIENDLYURL)?></div></td>
                    <td align="left">
                        <input type="checkbox" id="import_update_friendlyurl_event" name="import_update_friendlyurl_event" value="1" align="absmiddle" <?=$import_update_friendlyurl_event?> style="width: auto; border: 0;" class="inputCheck" />
                    </td>
                </tr>
                <tr>
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_SAMEACCOUNT)?></div></td>
                    <td align="left"><input type="checkbox" id="import_sameaccount_event"  name="import_sameaccount_event" value="1"  align="absmiddle" <?=$import_sameaccount_event?> style="width: auto; border: 0;" class="inputCheck" onclick="JS_ShowHideAccountEvent();"/></td>            
                    <td align="right"><div class="label-form"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_DEFAULTLEVEL)?></div></td>
                    <td colspan="3" align="left">
                        <select name="import_defaultlevel_event" style="width: 150px;">
                        <?
                        $levelObj = new EventLevel();
                        $levelvalues = $levelObj->getLevelValues();
                        foreach ($levelvalues as $levelvalue) {
                            if ($import_defaultlevel_event == $levelvalue) {
                                $selected = " selected=\"selected\"";
                            } else {
                                $selected = "";
                            }
                            echo "<option value=\"".$levelvalue."\" $selected>".$levelObj->showLevel($levelvalue)."</option> ";
                        }
                        ?>
                        </select>
                    </td>
                </tr>
            </table>

			<div id="import_account_id_event" class="base-table-form-account" <?=($import_sameaccount_event != "checked") ? "style=\"display:none;\"" : ""?>>
				<? // Account Search ////////////////////////////////////////////////////////////////// ?>
					<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
						<tr>
							<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_TOACCOUNT)?> <span><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_TOACCOUNT_SPAN)?></span></th>
						</tr>
					</table>
					<?
					$acct_search_table_title = system_showText(LANG_SITEMGR_ACCOUNTSEARCH_SELECT_DEFAULT);
					$acct_search_field_name = "account_id_event";
					$acct_search_field_value = $account_id_event;
					$acct_search_required_mark = false;
					$acct_search_form_width = "95%";
					$acct_search_cell_width = "";
					$return = system_generateAjaxAccountSearch($acct_search_table_title, $acct_search_field_name, $acct_search_field_value, $acct_search_required_mark, $acct_search_form_width, $acct_search_cell_width, 0, true);
					echo $return;
					?>
				<? //////////////////////////////////////////////////////////////////////////////////// ?>
			</div>
		</div>
	<? } ?>
    
    </div>

    <button type="button" name="submit_button" class="input-button-form left" value="Submit" onclick="JS_submit(<?=$step-1?>, false);"><?=system_showText(LANG_SITEMGR_BACK)?></button>
    <button type="submit" name="submit_button" class="input-button-form right" value="Submit"><?=system_showText(LANG_SITEMGR_IMPORTER)?></button>

    <br clear="all" />

    <div class="header-form">
        <?=system_showText(LANG_SITEMGR_STEP3_TIP2);?>
    </div>    

    <div class="wrapper import-box">

        <p><strong><?=system_showText(LANG_SITEMGR_IMPORT_FROMEXPORT)?></strong><?=system_showText(LANG_SITEMGR_IMPORT_FROMEXPORT_TIP)?></p>
        
        <p><strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_ALLASACTIVE)?></strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_ALLASACTIVE_TIP)?></p>
        
        <p><strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_UPDATE)?></strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_UPDATE_TIP)?></p>
        
        <p><strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_FRIENDLYURL)?></strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_FRIENDLYURL_TIP)?></p>
        
        <p><strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_SAMEACCOUNT)?></strong><?=system_showText(LANG_SITEMGR_SETTINGS_IMPORT_SAMEACCOUNT_TIP)?></p>
    </div>