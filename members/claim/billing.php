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
	# * FILE: /members/claim/billing.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_GET);
	extract($_POST);

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();
	$acctId = sess_getAccountIdFromSession();
	$url_redirect = "".DEFAULT_URL."/".MEMBERS_ALIAS."/claim";
	$url_base = "".DEFAULT_URL."/".MEMBERS_ALIAS."";
	$members = 1;

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (CLAIM_FEATURE != "on") { exit; }
	if (PAYMENT_FEATURE != "on") { exit; }
	if ((CREDITCARDPAYMENT_FEATURE != "on") && (INVOICEPAYMENT_FEATURE != "on") && (MANUALPAYMENT_FEATURE) != "on") { exit; }
	if (!$claimlistingid) {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/");
		exit;
	}
	$listingObject = new Listing($claimlistingid);
	if (!$listingObject->getNumber("id") || ($listingObject->getNumber("id") <= 0)) {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/");
		exit;
	}
	if ($listingObject->getNumber("account_id") != $acctId) {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/");
		exit;
	}

	$db = db_getDBObject(DEFAULT_DB, true);
	$dbObjClaim = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $db);
	$sqlClaim = "SELECT id FROM Claim WHERE account_id = '".$acctId."' AND listing_id = '".$claimlistingid."' AND status = 'progress' AND step = 'c' ORDER BY date_time DESC LIMIT 1";
	$resultClaim = $dbObjClaim->query($sqlClaim);
	if ($rowClaim = mysql_fetch_assoc($resultClaim)) $claimID = $rowClaim["id"];
	if (!$claimID) {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/");
		exit;
	}
	$claimObject = new Claim($claimID);
	if (!$claimObject->getNumber("id") || ($claimObject->getNumber("id") <= 0)) {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/");
		exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		$claimObject->setString("step", "d");
		$claimObject->save();

		if ($payment_method == "invoice") {
			header("Location: ".$url_redirect."/invoice.php?claimlistingid=".$claimlistingid);
		} else {
			header("Location: ".$url_redirect."/payment.php?payment_method=".$payment_method."&claimlistingid=".$claimlistingid);
		}
		exit;

	}

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	$listing_id[] = $listingObject->getNumber("id");
	$second_step = 1;
	$payment_method = "claim";

	setting_get("payment_tax_status", $payment_tax_status);
	setting_get("payment_tax_value", $payment_tax_value);
	customtext_get("payment_tax_label", $payment_tax_label);

	include(INCLUDES_DIR."/code/billing.php");
	if ($bill_info["listings"]) foreach ($bill_info["listings"] as $id => $info);

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/header.php");

?>

	<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
    <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
    <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
        
    <ul class="standardStep">
        <li class="standardStepAD"><?=system_showText(LANG_LABEL_EASY_AND_FAST);?> <span>3 <?=system_showText(LANG_LABEL_STEPS);?> &raquo;</span></li>
        <li><span>1</span>&nbsp;<?=system_showText(LANG_LABEL_ACCOUNT_SIGNUP);?></li>
        <li><span>2</span>&nbsp;<?=system_showText(LANG_LISTING_UPDATE);?></li>
        <li class="stepActived"><span>3</span>&nbsp;<?=system_showText(LANG_LABEL_CHECKOUT)?></li>
    </ul>

	<div class="content content-full">
		<h2><?=system_showText(LANG_MSG_CLAIM_THIS_LISTING)?></h2>

        <div>
            <?
            if (!$bill_info["listings"]){
                echo "<p class=\"informationMessage\">".system_showText(LANG_MSG_NO_ITEMS_REQUIRING_PAYMENT)."</p>";
            } else {
                ?>
    
                <form name="claimbilling" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">
    
                    <input type="hidden" name="claimlistingid" value="<?=$claimlistingid?>" />
    
                    <table class="standard-tableTOPBLUE">
                        <tr>
                            <th><?=system_showText(LANG_LISTING_FEATURE_NAME);?></th>
                            <th><?=system_showText(LANG_LABEL_LEVEL);?></th>
                            <th><?=system_showText(LANG_LABEL_EXTRA_CATEGORY);?></th>
                            <?
                            if (PAYMENT_FEATURE == "on") {
                                if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) {
                                    ?><th><?=system_showText(LANG_LABEL_DISCOUNT_CODE)?></th><?
                                }
                            }
                            ?>
                            <th><?=system_showText(LANG_LABEL_RENEWAL);?></th>
                            
                            <? if ($payment_tax_status == "on") { ?>
                                <th><?=system_showText(LANG_SUBTOTAL);?></th>
                            <? } ?>
    
                            <? if ($payment_tax_status == "on") { ?>
                                <th><?=$payment_tax_label."(".$payment_tax_value."%)";?></th>
                            <? } ?>
    
                            <th><?=system_showText(LANG_LABEL_TOTAL);?></th>
                        </tr>
                        <tr>
                            <td class="wrap">
                                <strong title="<?=$info["title"]?>">
                                    <?= system_showTruncatedText($info["title"], 60);?>
                                    <?=($info["listingtemplate"]?"<span class=\"itemNote\">(".$info["listingtemplate"].")</span>":"");?>
                                </strong>
                            </td>
                            <td><?=string_ucwords($info["level"]);?></td>
                            <td><?=$info["extra_category_amount"];?></td>
                            <?
                            if (PAYMENT_FEATURE == "on") {
                                if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) {
                                    ?><td><?=(($info["discount_id"]) ? ($info["discount_id"]) : (system_showText(LANG_NA)));?></td><?
                                }
                            }
                            ?>
                            <td><?=format_date($info["renewal_date"]);?></td>
                            
                            <? if ($payment_tax_status == "on") { ?>
                                <td><?=CURRENCY_SYMBOL." ".$bill_info["total_bill"];?></td>
                            <? } ?>
    
                            <? if ($payment_tax_status == "on") { ?>
                                <td><?=CURRENCY_SYMBOL." ".payment_calculateTax($bill_info["total_bill"], $payment_tax_value, true, false);?></td>
                            <? } ?>
    
                            <td>
                                <?
                                    if ($payment_tax_status == "on") echo CURRENCY_SYMBOL." ".payment_calculateTax($bill_info["total_bill"], $payment_tax_value, true);
                                    else echo CURRENCY_SYMBOL." ".$bill_info["total_bill"];
                                ?>
                            </td>
                        </tr>
                    </table>
    
                    <br />
    
                    <? if (PAYMENT_FEATURE == "on") { ?>
                        <? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
                            <div id="check_out_payment">
                                <? include(INCLUDES_DIR."/forms/form_paymentmethod.php"); ?>
                                <br />
                                <p class="standardButton claimButton">
                                    <button type="submit" name="submit" value="<?=system_showText(LANG_BUTTON_NEXT);?>"><?=system_showText(LANG_BUTTON_NEXT);?></button>
                                </p>
                            </div>
                        <? } ?>
                    <? } ?>
    
                </form>
    
                <?
            }
            ?>
    
        </div>
    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>