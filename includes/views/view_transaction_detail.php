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
	# * FILE: /includes/views/view_transaction_detail.php
	# ----------------------------------------------------------------------------------------------------

?>

<?
$log_id = $transaction["id"];
$dbMain = db_getDBObject(DEFAULT_DB, true);
$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
$sql = "SELECT level FROM Payment_Classified_Log WHERE payment_log_id = $log_id LIMIT 1";
$result = $dbObj->query($sql);
$log_level = mysql_fetch_assoc($result);

$str_time = "";

$startTimeStr = explode(":", $transaction["transaction_datetime"]);
$startTimeStr[0] = string_substr($startTimeStr[0],-2);
if (CLOCK_TYPE == '24') {
	$start_time_hour = $startTimeStr[0];
} elseif (CLOCK_TYPE == '12') {
	if ($startTimeStr[0] > "12") {
		$start_time_hour = $startTimeStr[0] - 12;
		$start_time_am_pm = "pm";
	} elseif ($startTimeStr[0] == "12") {
		$start_time_hour = 12;
		$start_time_am_pm = "pm";
	} elseif ($startTimeStr[0] == "00") {
		$start_time_hour = 12;
		$start_time_am_pm = "am";
	} else {
		$start_time_hour = $startTimeStr[0];
		$start_time_am_pm = "am";
	}
}
if ($start_time_hour < 10) $start_time_hour = "0".($start_time_hour+0);
$start_time_min = $startTimeStr[1];
$str_time .= $start_time_hour.":".$start_time_min." ".$start_time_am_pm;

$transac_date = explode(" ",$transaction["transaction_datetime"]);
?>

<br />
<? if ($transaction["system_type"] == "manual") {?>
	<p class="informationMessage"><?=system_showText(LANG_TRANSACTION_MANUAL);?></p>
<? } ?>

<h2 class="standardSubTitle"><?=system_showText(LANG_TRANSACTION_INFO)?></h2>

<ul class="general-item">
	<? if (string_strpos($url_base, "/".SITEMGR_ALIAS."")) { ?>
		<li>
			<strong><?=system_showText(LANG_LABEL_ACCOUNT)?>:</strong>
			<? if ($transaction["account_id"]) echo "<a href=\"".DEFAULT_URL."/".SITEMGR_ALIAS."/account/view.php?id=".$transaction["account_id"]."\" title = \"".$transaction["username"]."\">"; ?>
				<?=system_showTruncatedText(system_showAccountUserName($transaction["username"]), 50);?>
			<? if ($transaction["account_id"]) echo "</a>"; ?>
		</li>
	<? } ?>
    
	<li>
		<strong><?=system_showText(LANG_LABEL_PAYMENT_TYPE)?>:</strong>
		<?
		if (($transaction["system_type"] != "simplepay") && ($transaction["system_type"] != "paypal") && ($transaction["system_type"] != "manual") && ($transaction["system_type"] != "pagseguro")) {
			echo system_showText(LANG_CREDITCARD);
		} else {
			echo $transaction["system_type"];
		}
		if ($transaction["recurring"] == "y") {
			echo "&nbsp;<em>".system_showText(LANG_MSG_PRICES_AMOUNT_PER_INSTALLMENTS)."</em>";
		}
		?>
	</li>
	<li><strong><?=system_showText(LANG_LABEL_ID)?>:</strong> <?=$transaction["transaction_id"]?></li>
	<li><strong><?=system_showText(LANG_LABEL_STATUS)?>:</strong> <?=@constant(string_strtoupper(("LANG_LABEL_".$transaction["transaction_status"])))?></li>
	<li><strong><?=system_showText(LANG_LABEL_DATE)?>:</strong> <?=$transac_date[0]." - ".$str_time;?></li>
	<li><strong><?=system_showText(LANG_LABEL_IP)?>:</strong> <?=$transaction["ip"]?></li>
	<li><strong><?=system_showText(LANG_LABEL_SUBTOTAL)?>:</strong> <?=$transaction["transaction_subtotal"]?> (<?=$transaction["transaction_currency"]?>)</li>
	<li><strong><?=system_showText(LANG_LABEL_TAX)?>:</strong> <?=$transaction["transaction_tax"]?> (<?=$transaction["transaction_currency"]?>)</li>
	<li><strong><?=system_showText(LANG_LABEL_AMOUNT)?>:</strong> <?=$transaction["transaction_amount"]?> (<?=$transaction["transaction_currency"]?>)</li>
	<li><strong><?=system_showText(LANG_LABEL_NOTES)?>:</strong> <?=$transaction["notes"]?></li>
</ul>

<? if ($transaction_listing_log) {
	?>

	<h2 class="standardSubTitle"><?=system_showText(LANG_LISTING_FEATURE_NAME_PLURAL);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_TITLE);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_EXTRA_CATEGORY);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_LEVEL);?></th>
			<? if (PAYMENT_FEATURE == "on") { ?>
				<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
					<th style="width:120px;"><?=system_showText(LANG_LABEL_DISCOUNT_CODE)?></th>
				<? } ?>
			<? } ?>
			<th style="width:70px;"><?=system_showText(LANG_LABEL_RENEWAL);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_listing_log as $each_listing) { ?>
			<tr>
				<td>
					<?
					$transactionListingObj = new Listing($each_listing["listing_id"]);
					if ($transactionListingObj->getNumber("id") > 0) {
						?><a href="<?=$url_base?>/<?=LISTING_FEATURE_FOLDER;?>/view.php?id=<?=$each_listing["listing_id"]?>" class="link-table" title="<?=$each_listing["listing_title"];?>"><?=system_showTruncatedText($each_listing["listing_title"], 50);?></a><?
					} else {
						?><?=system_showTruncatedText($each_listing["listing_title"], 50);?><?
					}
					?>
					<?=($each_listing["listingtemplate"]?"<span class=\"itemNote\">(".$each_listing["listingtemplate"].")</span>":"");?>
				</td>
				<td style="text-align:center"><?=$each_listing["extra_categories"]?></td>
				<td><?=string_ucwords($each_listing["level_label"]);?></td>
				<? if (PAYMENT_FEATURE == "on") { ?>
					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<td style="text-align:center"><?=$each_listing["discount_id"]?></td>
					<? } ?>
				<? } ?>
				<td style="text-align:center"><?=($each_listing["renewal_date"] == "0000-00-00") ? system_showText(LANG_NA) : format_date($each_listing["renewal_date"],DEFAULT_DATE_FORMAT,"date")?></td>
				<td style="text-align:center">
					<?=$each_listing["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

<? if ($transaction_event_log) { ?>

	<h2 class="standardSubTitle"><?=system_showText(LANG_EVENT_FEATURE_NAME_PLURAL);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_TITLE);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_LEVEL);?></th>
			<? if (PAYMENT_FEATURE == "on") { ?>
				<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
					<th style="width:120px;"><?=system_showText(LANG_LABEL_DISCOUNT_CODE)?></th>
				<? } ?>
			<? } ?>
			<th style="width:70px;"><?=system_showText(LANG_LABEL_RENEWAL);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_event_log as $each_event) { ?>
			<tr>
				<td>
					<?
					$transactionEventObj = new Event($each_event["event_id"]);
					if ($transactionEventObj->getNumber("id") > 0) {
						?><a href="<?=$url_base?>/<?=EVENT_FEATURE_FOLDER;?>/view.php?id=<?=$each_event["event_id"]?>" class="link-table" title="<?=$each_event["event_title"];?>"><?=system_showTruncatedText($each_event["event_title"], 50);?></a><?
					} else {
						?><?=system_showTruncatedText($each_event["event_title"], 50);?><?
					}
					?>
				</td>
				<td><?=string_ucwords($each_event["level_label"]);?></td>
				<? if (PAYMENT_FEATURE == "on") { ?>
					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<td style="text-align:center"><?=$each_event["discount_id"]?></td>
					<? } ?>
				<? } ?>
				<td style="text-align:center"><?=($each_event["renewal_date"] == "0000-00-00") ? system_showText(LANG_NA) : format_date($each_event["renewal_date"],DEFAULT_DATE_FORMAT,"date")?></td>
				<td style="text-align:center">
					<?=$each_event["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

<? if ($transaction_banner_log) { ?>

	<h2 class="standardSubTitle"><?=system_showText(LANG_BANNER_FEATURE_NAME_PLURAL);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_CAPTION)?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_IMPRESSIONS)?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_LEVEL);?></th>
			<? if (PAYMENT_FEATURE == "on") { ?>
				<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
					<th style="width:120px;"><?=system_showText(LANG_LABEL_DISCOUNT_CODE)?></th>
				<? } ?>
			<? } ?>
			<th style="width:70px;"><?=system_showText(LANG_LABEL_RENEWAL);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_banner_log as $each_banner) {?>
			<tr>
				<td>
					<?
					$transactionBannerObj = new Banner($each_banner["banner_id"]);
					if ($transactionBannerObj->getNumber("id") > 0) {
						?><a href="<?=$url_base?>/<?=BANNER_FEATURE_FOLDER;?>/view.php?id=<?=$each_banner["banner_id"]?>" class="link-table" title="<?=$each_banner["banner_caption"]?>"><?=system_showTruncatedText($each_banner["banner_caption"], 50);?></a><?
					} else {
						?><?=system_showTruncatedText($each_banner["banner_caption"], 50);?><?
					}
					?>
				</td>
				<td style="text-align:center"><?=(($each_banner["impressions"]) ? $each_banner["impressions"] : system_showText(LANG_LABEL_UNLIMITED))?></td>
				<td><?=string_ucwords($each_banner["level_label"]);?></td>
				<? if (PAYMENT_FEATURE == "on") { ?>
					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<td style="text-align:center"><?=$each_banner["discount_id"]?></td>
					<? } ?>
				<? } ?>
				<td style="text-align:center"><?=($each_banner["renewal_date"] == "0000-00-00") ? (($each_banner["impressions"]) ? (system_showText(LANG_LABEL_UNLIMITED)) : (system_showText(LANG_NA))) : format_date($each_banner["renewal_date"],DEFAULT_DATE_FORMAT,"date")?></td>
				<td style="text-align:center">
					<?=$each_banner["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

<? if ($transaction_classified_log) { ?>

	<h2 class="standardSubTitle"><?=system_showText(LANG_CLASSIFIED_FEATURE_NAME_PLURAL);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_TITLE);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_LEVEL);?></th>
			<? if (PAYMENT_FEATURE == "on") { ?>
				<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
					<th style="width:120px;"><?=system_showText(LANG_LABEL_DISCOUNT_CODE)?></th>
				<? } ?>
			<? } ?>
			<th style="width:70px;"><?=system_showText(LANG_LABEL_RENEWAL);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_classified_log as $each_classified) { ?>
			<tr>
				<td>
					<?
					$transactionClassifiedObj = new Classified($each_classified["classified_id"]);
					if ($transactionClassifiedObj->getNumber("id") > 0) {
						?><a href="<?=$url_base?>/<?=CLASSIFIED_FEATURE_FOLDER;?>/view.php?id=<?=$each_classified["classified_id"]?>" class="link-table" title="<?=$each_classified["classified_title"]?>"><?=system_showTruncatedText($each_classified["classified_title"], 50);?></a><?
					} else {
						?><?=system_showTruncatedText($each_classified["classified_title"], 50);?><?
					}
					?>
				</td>
				<td><?=string_ucwords($each_classified["level_label"]);?></td>
				<? if (PAYMENT_FEATURE == "on") { ?>
					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<td style="text-align:center"><?=$each_classified["discount_id"]?></td>
					<? } ?>
				<? } ?>
				<td style="text-align:center"><?=($each_classified["renewal_date"] == "0000-00-00") ? system_showText(LANG_NA) : format_date($each_classified["renewal_date"],DEFAULT_DATE_FORMAT,"date")?></td>
				<td style="text-align:center">
					<?=$each_classified["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

<? if ($transaction_article_log) { ?>

	<h2 class="standardSubTitle"><?=system_showText(LANG_ARTICLE_FEATURE_NAME_PLURAL);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_TITLE);?></th>
			<? if (PAYMENT_FEATURE == "on") { ?>
				<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
					<th style="width:120px;"><?=system_showText(LANG_LABEL_DISCOUNT_CODE)?></th>
				<? } ?>
			<? } ?>
			<th style="width:70px;"><?=system_showText(LANG_LABEL_RENEWAL);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_article_log as $each_article) { ?>
			<tr>
				<td>
					<?
					$transactionArticleObj = new Article($each_article["article_id"]);
					if ($transactionArticleObj->getNumber("id") > 0) {
						?><a href="<?=$url_base?>/<?=ARTICLE_FEATURE_FOLDER;?>/view.php?id=<?=$each_article["article_id"]?>" class="link-table" title="<?=$each_article["article_title"]?>"><?=system_showTruncatedText($each_article["article_title"], 50);?></a><?
					} else {
						?><?=system_showTruncatedText($each_article["article_title"], 50);?><?
					}
					?>
				</td>
				<? if (PAYMENT_FEATURE == "on") { ?>
					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<td style="text-align:center"><?=$each_article["discount_id"]?></td>
					<? } ?>
				<? } ?>
				<td style="text-align:center"><?=($each_article["renewal_date"] == "0000-00-00") ? system_showText(LANG_NA) : format_date($each_article["renewal_date"],DEFAULT_DATE_FORMAT,"date")?></td>
				<td style="text-align:center">
					<?=$each_article["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

<? if ($transaction_custominvoice_log) { ?>
	<h2 class="standardSubTitle"><?=system_showText(LANG_CUSTOM_INVOICES);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_TITLE);?></th>
			<th style="width:120px;"><?=system_showText(LANG_LABEL_ITEMS);?></th>
			<th style="width:70px;"><?=system_showText(LANG_LABEL_DATE);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_custominvoice_log as $each_custominvoice) { ?>
			<tr>
				<td>
					<?
					$transactionCustomInvoiceObj = new CustomInvoice($each_custominvoice["custom_invoice_id"]);					
					
					if ($transactionCustomInvoiceObj->getNumber("id") > 0) {
						if (string_strpos($url_base, "/".SITEMGR_ALIAS."") !== false) {
							?><a href="<?=$url_base?>/custominvoices/view.php?id=<?=$each_custominvoice["custom_invoice_id"]?>" class="link-table" title="<?=$each_custominvoice["title"]?>"><?=system_showTruncatedText($each_custominvoice["title"], 50);?></a><?
						} else {
							?><?=system_showTruncatedText($each_custominvoice["title"], 50);?><?
						}
					} else {
						?><?=$each_custominvoice["title"]?><?
					}
					?>
				</td>
				<?
				if (string_strpos($url_base, "/".SITEMGR_ALIAS."") !== false) {
					$popup_url = DEFAULT_URL."/".SITEMGR_ALIAS."/custominvoices/view_items.php?";
				} else {
					$popup_url = DEFAULT_URL."/popup/popup.php?pop_type=custominvoice_items&";
				}
				?>
				<td><a href="<?=$popup_url?>id=<?=$each_custominvoice["custom_invoice_id"];?>&items=<?=urlencode($each_custominvoice["items"])?>&items_price=<?=urlencode($each_custominvoice["items_price"])?>&view=payment_log" class="link-table iframe fancy_window_custom" style="text-decoration: underline;"><?=system_showText(LANG_VIEWITEMS)?></a></td>
				<td><?=format_date($each_custominvoice["date"])?></td>
				<td style="text-align:center; width: 100px;">
					<?=$each_custominvoice["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

<? if ($transaction_package_log) { ?>
	<h2 class="standardSubTitle"><?=system_showText(LANG_PACKAGE_SING);?></h2>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">
		<tr>
			<th><?=system_showText(LANG_LABEL_TITLE);?></th>
			<th style="width:120px;"><?=system_showText(LANG_LABEL_ITEMS);?></th>
			<th style="width:100px;"><?=system_showText(LANG_LABEL_ITEMPRICE);?></th>
		</tr>
		<? foreach ($transaction_package_log as $each_package) { ?>
			<tr>
				<td>
					<?
					$transactionPackageObj = new Package($each_package["package_id"]);

					if ($transactionPackageObj->getNumber("id") > 0) {
						if (string_strpos($url_base, "/".SITEMGR_ALIAS."") !== false) {
							?><a href="<?=$url_base?>/package/view.php?id=<?=$each_package["package_id"]?>" class="link-table" title="<?=$each_package["package_title"]?>"><?=system_showTruncatedText($each_package["package_title"], 50);?></a><?
						} else {
							?><?=system_showTruncatedText($each_package["package_title"], 50);?><?
						}
					} else {
						?><?=$each_package["package_title"]?><?
					}
					?>
				</td>
				<?
				if (string_strpos($url_base, "/".SITEMGR_ALIAS."") !== false) {
					$popup_url = DEFAULT_URL."/".SITEMGR_ALIAS."/package/view_items.php?";
				} else {
                    $popup_url = DEFAULT_URL."/popup/popup.php?pop_type=package_items&";
				}
				?>
				<td><a href="<?=$popup_url?>id=<?=$each_package["package_id"];?>&items=<?=urlencode($each_package["items"])?>&items_price=<?=urlencode($each_package["items_price"])?>&view=payment_log" class="link-table iframe fancy_window_custom" style="text-decoration: underline;"><?=system_showText(LANG_VIEWITEMS)?></a></td>
				<td style="text-align:center; width: 100px;">
					<?=$each_package["amount"]." (".$transaction["transaction_currency"].")";?>
				</td>
			</tr>
		<? } ?>
	</table>
<? } ?>

