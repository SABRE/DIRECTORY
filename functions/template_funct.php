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
	# * FILE: /functions/template_funct.php
	# ----------------------------------------------------------------------------------------------------

	function template_CreateDynamicField($fieldvalues, $themeTemplate = false, &$hideExtraFieldsTable) {
		$fieldType = preg_replace('/[0-9]/i', '', $fieldvalues["field"]);
        if ($themeTemplate || (string_strpos($fieldvalues["label"], "LANG_LABEL") !== false)){
            $fieldvalues["label"] = @constant($fieldvalues["label"]);
        }
		switch ($fieldType) {
			case "custom_text":
				$hideExtraFieldsTable = false; ?>
				<tr>
					<th class="wrap"><?=($fieldvalues["required"]=="y") ? "* " : ""?><?=$fieldvalues["label"]?>:</th>
					<td><input type="text" name="<?=$fieldvalues["field"]?>" value="<?=$fieldvalues["form_value"]?>" maxlength="250" /><?=($fieldvalues["instructions"]) ? "<span>".$fieldvalues["instructions"]."</span>" : ""?></td>
				</tr>
				<?
			break;
			case "custom_short_desc":
				$hideExtraFieldsTable = false; ?>
				<script language="javascript" type="text/javascript">
					
					$(document).ready(function(){
		
						var field_name = '<?=$fieldvalues["field"]?>';
						var count_field_name = '<?=$fieldvalues["field"]?>_remLen';

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
				<tr>
					<th class="wrap"><?=($fieldvalues["required"]=="y") ? "* " : ""?><?=$fieldvalues["label"]?>:</th>
					<td>
						<textarea id="<?=$fieldvalues["field"]?>" name="<?=$fieldvalues["field"]?>" rows="5" cols="1" ><?=$fieldvalues["form_value"]?></textarea>
					</td>
				</tr>
				<?
			break;
			case "custom_long_desc":
				$hideExtraFieldsTable = false; ?>
				<tr>
					<th class="wrap"><?=($fieldvalues["required"]=="y") ? "* " : ""?><?=$fieldvalues["label"]?>:</th>
					<td><textarea name="<?=$fieldvalues["field"]?>" rows="5"><?=$fieldvalues["form_value"]?></textarea><?=($fieldvalues["instructions"]) ? "<span>".$fieldvalues["instructions"]."</span>" : ""?></td>
				</tr>
				<?
			break;
			case "custom_checkbox":
				$hideExtraFieldsTable = false; ?>
				<tr>
					<th class="wrap"><input type="checkbox" name="<?=$fieldvalues["field"]?>" value="y" <?=($fieldvalues["form_value"] == "y") ? "checked" : ""?> class="inputCheck" /></th>
					<td><?=($fieldvalues["required"]=="y") ? "* " : ""?><?=$fieldvalues["label"]?><?=($fieldvalues["instructions"]) ? "<span>".$fieldvalues["instructions"]."</span>" : ""?></td>
				</tr>
				<?
			break;
			case "custom_dropdown":
				$hideExtraFieldsTable = false; ?>
				<tr>
					<th class="wrap"><?=($fieldvalues["required"]=="y") ? "* " : ""?><?=$fieldvalues["label"]?>:</th>
					<td>
						<select name="<?=$fieldvalues["field"]?>">
							<option value=""><?=$fieldvalues["label"];?></option>
							<?
							$auxfieldvalues = explode(",", $fieldvalues["fieldvalues"]);
							foreach ($auxfieldvalues as $fieldvalue) {
								?><option value="<?=$fieldvalue;?>" <? if ($fieldvalue == $fieldvalues["form_value"]) { echo "selected"; } ?>><?=$fieldvalue;?></option><?
							}
							?>
						</select>
						<?=($fieldvalues["instructions"]) ? "<span>".$fieldvalues["instructions"]."</span>" : "";?>
					</td>
				</tr>
				<?
			break;
		}
	}
?>