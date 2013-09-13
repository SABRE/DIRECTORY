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
	# * FILE: /includes/forms/form_classified_seocenter.php
	# ----------------------------------------------------------------------------------------------------

	if ($message) {
		echo "<p class='errorMessage'>";
			echo $message;
		echo "</p>";
	}

?>

<script type="text/javascript">

	$(document).ready(function(){
		
        var field_name = 'seo_summarydesc';
        var count_field_name = 'seo_summarydesc_remLen';

        var options = {
                    'maxCharacterSize': 250,
                    'originalStyle': 'originalTextareaInfo',
                    'warningStyle' : 'warningTextareaInfo',
                    'warningNumber': 40,
                    'displayFormat' : '<span><input readonly="readonly" type="text" id="'+count_field_name+'" name="'+count_field_name+'" size="3" maxlength="3" value="#left" class="textcounter" disabled="disabled" /> <?=system_showText(LANG_MSG_CHARS_LEFT)?> <?=system_showText(LANG_MSG_INCLUDING_SPACES_LINE_BREAKS)?></span>' 
            };
        $('#'+field_name).textareaCount(options);

	});

</script>

<table border="0" cellpadding="0" cellspacing="0" class="standard-table">
	<tr>
		<th class="standard-tabletitle"><?=system_showText(LANG_LABEL_SEO_TUNING)?> <?=system_showText(LANG_LABEL_TITLE)?></th>
	</tr>
	<tr>
		<td>
			<input type="text" name="seo_title" value="<?=$seo_title?>" maxlength="100" onblur="easyFriendlyUrl(this.value, 'friendly_url', '<?=FRIENDLYURL_VALIDCHARS?>', '<?=FRIENDLYURL_SEPARATOR?>');" />
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="standard-table" style="margin-bottom: 0;">
	<tr>
		<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_LABEL_FRIENDLY_URL)?></th>
	</tr>
	<tr>
		<td colspan="2" class="standard-tableContent">
			<?=system_showText(LANG_MSG_FRIENDLY_URL1)?><br /><br /><strong><?=system_showText(LANG_LABEL_FOR_EXAMPLE)?>:</strong><br /><br /><?=system_showText(LANG_MSG_FRIENDLY_URL2)?><br />"<?=CLASSIFIED_DEFAULT_URL?>/john-auto-repair.html"
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="standard-table">    
	<tr>
		<th><?=system_showText(LANG_LABEL_PAGE_NAME)?>:</th>
		<td>
			<input type="text" name="friendly_url" id="friendly_url" value="<?=$friendly_url?>" maxlength="150" onblur="easyFriendlyUrl(this.value, 'friendly_url', '<?=FRIENDLYURL_VALIDCHARS?>', '<?=FRIENDLYURL_SEPARATOR?>');" />
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
	
    <tr>
        <th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_SEO_DESCRIPTION)?>&nbsp;<span>(<?=system_showText(LANG_SEO_LINEBREAK)?>)</span></th>
    </tr>
	<tr>
		<td colspan="2">
			<textarea id="seo_summarydesc" name="seo_summarydesc" rows="5" cols="1"><?=$seo_summarydesc;?></textarea>
		</td>
	</tr>
	<tr>
		<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_SEO_KEYWORDS)?>&nbsp;<span>(<?=ucfirst(system_showText(LANG_SEO_COLON))?>)</span></th>
	</tr>
	<tr>
		<td colspan="2">
			<input type="text" name="seo_keywords" value="<?=$seo_keywords?>" />
		</td>
	</tr>
</table>