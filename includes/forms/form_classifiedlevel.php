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
	# * FILE: /includes/forms/form_classifiedlevel.php
	# ----------------------------------------------------------------------------------------------------

	setting_get("payment_tax_status", $payment_tax_status);
	setting_get("payment_tax_value", $payment_tax_value);
	customtext_get("payment_tax_label", $payment_tax_label);

	$levelObj = new ClassifiedLevel();
	$levelValue = $levelObj->getValues();
	unset($strArray);
	foreach ($levelValue as $value) {
		$strAux = "<tr><th>".$levelObj->showLevel($value).":</th><td style=\"white-space: nowrap;\"><strong>";
		if ($levelObj->getPrice($value) > 0) $strAux .= CURRENCY_SYMBOL.$levelObj->getPrice($value);
		else $strAux .= CURRENCY_SYMBOL.system_showText(LANG_LABEL_FREE);
		$strAux .= "</strong>";
		$strAux .= " ".system_showText(LANG_PER)." ";
		if (payment_getRenewalCycle("classified") > 1) {
			$strAux .= payment_getRenewalCycle("classified")." ";
			$strAux .= payment_getRenewalUnitName("classified")."s";
		}else {
			$strAux .= payment_getRenewalUnitName("classified");
		}
		$strAux .= "</td></tr>";
		$strArray[] = $strAux;
	}

?>

<table border="0" cellpadding="0" cellspacing="0" class="levelTable">
	<tr>
		<th class="levelTitle"><?=system_showText(LANG_MENU_SELECT_CLASSIFIED_LEVEL)?></th>
		<td class="levelTopdetail">
			<?=system_showText(LANG_LABEL_PRICE_PLURAL);?>
			<?
				if ($payment_tax_status == "on") {
					echo " (+".$payment_tax_value."% ".$payment_tax_label.")";
				}
			?>
		</td>
	</tr>
	<?if (count($levelValue) > 1){?>
    <tr>
		<th class="tableOption" colspan="2"><a href="<?=NON_SECURE_URL?>/<?=ALIAS_ADVERTISE_URL_DIVISOR?>.php?classified" target="_blank"><?=system_showText(LANG_CLASSIFIED_OPTIONS);?></a></th>
	</tr>

	<tr>
	<?}?>

	<? if ((!$classified) || (($classified) && ($classified->needToCheckOut())) || (string_strpos($url_base, "/".SITEMGR_ALIAS."")) || (($classified) && ($classified->getPrice() <= 0))) { ?>

		<td>
			<table border="0" cellpadding="2" cellspacing="2" class="standardChooseLevel">

                <?
                    $levelvalues = $levelObj->getLevelValues();
                    foreach ($levelvalues as $levelvalue) {
                    ?>
                    <tr>
                        <th><?=$levelObj->showLevel($levelvalue)?></th>
									<td><input class="standard-table-putradio" type="radio" name="level" value="<?=$levelvalue?>" <? if ($levelArray[$levelObj->getLevel($levelvalue)]) echo "checked"; ?> /></td>
				<? } ?>

			</table>
		</td>

	<? } else { ?>

		<td>
			<table border="0" cellpadding="0" cellspacing="0" class="standardChooseLevel">
                     <tr>
					<th><?=string_ucwords($levelObj->getLevel($level));?></th>
					<td><input type="hidden" name="level" value="<?=$level?>" /></td>
					</tr>
			</table>
		</td>

	<? } ?>

		<td class="levelPrice">

			<table border="0" cellpadding="2" cellspacing="2" class="standard-tableSAMPLE">
				<tr>
					<? echo implode("", $strArray); ?>
				</tr>
			</table>

		</td>

	</tr>
</table>
