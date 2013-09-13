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
	# * FILE: /includes/forms/form_promotion_conditions.php
	# ----------------------------------------------------------------------------------------------------

customtext_get("promotion_default_conditions", $promotion_default_conditions);

if ($promotion_force_redeem_by_facebook)
    $promotion_force_redeem_by_facebook_checked = " checked='checked' ";
?>

<? if ($message_promotion_conditions) { ?>
	<div id="warning" class="<?=$message_style?>">
		<?=$message_promotion_conditions?>
	</div>
<? } ?>

<div class="header-form">
	<?=system_showText(LANG_SITEMGR_OPTIONS)?>
</div>

<? if (FACEBOOK_APP_ENABLED == "on"){ ?>
<table cellpadding="2" cellspacing="0" border="0" class="standard-table">
	<tr class="tr-form">
		<th>
			<input type="checkbox" id="promotion_force_redeem_by_facebook" name="promotion_force_redeem_by_facebook" <?=$promotion_force_redeem_by_facebook_checked?> value="1" />
		</th>
		<td><?=system_showText(LANG_SITEMGR_REDEEMALSO_WOUT_FB)?></td>
	</tr>
</table>
<? } ?>

<table cellpadding="2" cellspacing="0" border="0" class="standard-table">
    <tr class="tr-form">
        <th colspan="2" class="standard-tabletitle">
            <?=string_ucwords(system_showText(LANG_SITEMGR_SETTINGS_PROMOTION_DEFAULTCONDITIONTEXT))?>
        </th>
    </tr>

    <tr>
        <th><?=system_showText(LANG_SITEMGR_SETTINGS_PROMOTION_CONDITIONS)?></th>
        <td>
            <textarea id="promotion_default_conditions" name="promotion_default_conditions" rows="10" cols="1" class="input-textarea-form-listing"><?=$promotion_default_conditions?></textarea>
        </td>
    </tr>

</table>

<script type="text/javascript">

	$(document).ready(function(){
		
        var field_name = 'promotion_default_conditions';
        var count_field_name = 'promotion_default_conditions_remLen';

        var options = {
                    'maxCharacterSize': 1000,
                    'originalStyle': 'originalTextareaInfo',
                    'warningStyle' : 'warningTextareaInfo',
                    'warningNumber': 40,
                    'displayFormat' : '<span><input readonly="readonly" type="text" id="'+count_field_name+'" name="'+count_field_name+'" size="3" maxlength="3" value="#left" class="textcounter" disabled="disabled" /> <?=system_showText(LANG_MSG_CHARS_LEFT)?> <?=system_showText(LANG_MSG_INCLUDING_SPACES_LINE_BREAKS)?></span>' 
            };
        $('#'+field_name).textareaCount(options);
		
	});

</script>