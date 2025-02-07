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
	# * FILE: /includes/forms/form_help.php
	# ----------------------------------------------------------------------------------------------------

?>

<? if ($message_help) { ?>
	<? if ($success) { ?>
		<p class="successMessage"><?=$message_help?></p>
	<? } else { ?>
		<p class="errorMessage"><?=$message_help?></p>
	<? } ?>
<? } ?>

<table border="0" cellpadding="2" cellspacing="0" class="standard-table">
	<tr>
		<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_LABEL_HELP_INFORMATION);?></th>
	</tr>
	<tr>
		<th><?=system_showText(LANG_LABEL_NAME)?>:</th>
		<td><input type="text" name="name" value="<?=$name?>" /></td>
	</tr>
	<tr>
		<th><?=system_showText(LANG_LABEL_EMAIL)?>:</th>
		<td><input type="text" name="email" value="<?=$email?>" /></td>
	</tr>
	<tr>
		<th><?=system_showText(LANG_LABEL_TEXT)?>:</th>
		<td><textarea name="text" value="<?=$text?>" rows="6"></textarea></td>
	</tr>
</table>
