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
	# * FILE: /frontend/socialnetwork/dealscontent.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	if (!$_GET["id"]) {
		$id = sess_getAccountIdFromSession();
	} else {
		$id = $_GET["id"];
	}

	if ($id) {
		$dealObj = new Promotion();
		$dealsArr = $dealObj->getDealsFromUser($id);
	}

    if ($dealsArr) { ?>
        <h2 class="standardSubTitle"><?=system_showText(DEAL_RECENTDEALS)?></h2>

        <table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
            <tr>
                <th style="width: auto;"><?=system_showText(LANG_PROMOTION_FEATURE_NAME);?></th>
                <th style="width: 140px;"><?=system_showText(LANG_LABEL_DATE);?></th>
                <th style="width: 100px;"><?=system_showText(LANG_LABEL_STATUS);?></th>
                <? if ($id == sess_getAccountIdFromSession() || string_strpos($_SERVER["PHP_SELF"], MEMBERS_ALIAS)) { ?>
                    <th style="width: 8%;"><?=system_showText(LANG_LABEL_OPTIONS)?></th>
                <? } ?>
            </tr>
            <?  foreach ($dealsArr as $dealdone) {
                    $promotionObj = new Promotion($dealdone["promotion_id"]);
                    $promotionLink = $promotionObj->getString("friendly_url").".html";
            ?>
                <tr>
                    <td>
                        <a href="<?=PROMOTION_DEFAULT_URL?>/<?=$promotionLink?>" target="_blank"><?=$promotionObj->getString("name")?></a>
                    </td>
                    <td>
                        <?=format_date($dealdone["datetime"], DEFAULT_DATE_FORMAT)?> - <?=format_getTimeString($dealdone["datetime"]);?>
                    </td>
                    <td>
                        <?=$dealdone["used"] ? string_ucwords(system_showText(LANG_DEAL_CHECKOUT)) : string_ucwords(system_showText(LANG_DEAL_OPENED));?>
                    </td>
                    <? if ($id == sess_getAccountIdFromSession() || string_strpos($_SERVER["PHP_SELF"], MEMBERS_ALIAS)) { ?>
                        <td>
                            <? if ($dealdone["used"]) { ?>
                                <img src="<?=DEFAULT_URL?>/images/icon_print_off.gif" border="0" alt="<?=string_ucwords(system_showText(LANG_DEAL_CHECKOUT));?>" title="<?=string_ucwords(system_showText(LANG_DEAL_CHECKOUT));?>" />
                            <? } else { ?>
                                <a href="<?=DEFAULT_URL."/popup/popup.php?pop_type=deal_redeem&amp;reprint=true&amp;redeemit=true&amp;nofacebook=true&amp;id=".$promotionObj->getNumber("id");?>" class="iframe fancy_window_redeem">
                                    <img src="<?=DEFAULT_URL?>/images/icon_print.gif" border="0" alt="<?=system_showText(LANG_PROMOTION_PRINT);?>" title="<?=system_showText(LANG_PROMOTION_PRINT);?>" />
                                </a>
                            <? } ?>
                        </td>
                    <? } ?>
                </tr>
            <? } ?>
        </table>
    <? } else { ?>
        <p class="informationMessage"><?=system_showText(DEAL_DIDNTNOTFINISHED)?></p>
    <? } ?>