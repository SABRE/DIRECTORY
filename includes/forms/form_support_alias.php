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
	# * FILE: /includes/forms/form_support_alias.php
	# ----------------------------------------------------------------------------------------------------

?>   

<p class="informationMessage">* <?=system_showText(LANG_LABEL_REQUIRED_FIELD)?></p>

<table border="0" cellpadding="0" cellspacing="0" class="standard-table">
    
	<tr>
		<th colspan="2" class="standard-tabletitle">Modules Alias</th>
	</tr>
    <? foreach ($aliasModules as $module) { ?>
	<tr>
		<th>* <?=$module["label"]?>:</th>
		<td>
			<input type="text" name="<?=strtolower($module["name"])?>" value="<?=$module["value"]?>" maxlength="100" />
			<span><?=$module["tip"]?></span>
		</td>
	</tr>
    <? } ?>
    
    <tr>
		<th colspan="2" class="standard-tabletitle">URL Divisors</th>
	</tr>
    <? foreach ($aliasDivisors as $divisor) { ?>
	<tr>
		<th>* <?=$divisor["label"]?>:</th>
		<td>
			<input type="text" name="<?=strtolower($divisor["name"])?>" value="<?=$divisor["value"]?>" maxlength="100" />
			<span><?=$divisor["tip"]?></span>
		</td>
	</tr>
    <? } ?>
    
    <tr>
		<th colspan="2" class="standard-tabletitle">Extra Pages</th>
	</tr>
    <? foreach ($aliasPages as $page) { ?>
	<tr>
		<th>* <?=$page["label"]?>:</th>
		<td>
			<input type="text" name="<?=strtolower($page["name"])?>" value="<?=$page["value"]?>" maxlength="100" />
			<span><?=$page["tip"]?></span>
		</td>
	</tr>
    <? } ?>
    
    <tr>
        <th>&nbsp;</th>
        <td class="alg-r">
            <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;" align="center">
                <tr>
                    <td>
                        <button type="submit" class="input-button-form" >Submit</button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
</table>