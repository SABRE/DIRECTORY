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
	# * FILE: /includes/forms/form_importsettings.php
	# ----------------------------------------------------------------------------------------------------

?>

<div class="header-form">Edit ImportLog Status - <?=$importObj->getString("filename")?></div>

<? if ($message_importsettings) { ?>
	<div id="warning" class="errorMessage"><?=$message_importsettings?></div>
<? } ?>

<table cellpadding="0" cellspacing="0" class="table-form table-form-settings table-form-margin">

	<tr class="tr-form">
		<td align="right" class="td-title-form">
			<div class="label-form">
				Status:
			</div>
		</td>
		<td align="left" class="td-form">
			<?=$statusDropDownStatus?>
		</td>
	</tr>
    <tr class="tr-form">
		<td align="right" class="td-title-form">
			<div class="label-form">
				Action:
			</div>
		</td>
        <td align="left" class="td-form">
			<?=$statusDropDownAction?>
		</td>
	</tr>

</table>
<br clear="all" />