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
	# * FILE: /includes/forms/form_billing_authorize.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# INCLUDE
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/conf/payment_authorize.inc.php");

	if (AUTHORIZEPAYMENT_FEATURE == "on") {

		if (!AUTHORIZE_LOGIN || !AUTHORIZE_TXNKEY) {
			echo "<p class=\"errorMessage\">".system_showText(LANG_AUTHORIZE_NO_AVAILABLE)." <a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/help.php\" class=\"billing-contact\">".system_showText(LANG_LABEL_ADMINISTRATOR)."</a>.</p>";
		} elseif ((AUTHORIZERECURRING_FEATURE == "on") && (!AUTHORIZE_RECURRINGLENGTH || !AUTHORIZE_RECURRINGUNIT)) {
			echo "<p class=\"errorMessage\">".system_showText(LANG_AUTHORIZE_NO_AVAILABLE)." <a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/help.php\" class=\"billing-contact\">".system_showText(LANG_LABEL_ADMINISTRATOR)."</a>.</p>";
		} else {

			$block_bannerbyimpression = false;
			$block_custominvoice = false;

			if ($bill_info["listings"]) foreach ($bill_info["listings"] as $id => $info) {
				$listing_ids[] = $id;
				$listing_amounts[] = $info["total_fee"];
			}

			if ($bill_info["events"]) foreach ($bill_info["events"] as $id => $info) {
				$event_ids[] = $id;
				$event_amounts[] = $info["total_fee"];
			}

			if ($bill_info["banners"]) foreach ($bill_info["banners"] as $id => $info) {
				if ($info["expiration_setting"] == BANNER_EXPIRATION_IMPRESSION) {
					$block_bannerbyimpression = true;
				}
				$banner_ids[] = $id;
				$banner_amounts[] = $info["total_fee"];
			}

			if ($bill_info["classifieds"]) foreach ($bill_info["classifieds"] as $id => $info) {
				$classified_ids[] = $id;
				$classified_amounts[] = $info["total_fee"];
			}

			if ($bill_info["articles"]) foreach ($bill_info["articles"] as $id => $info) {
				$article_ids[] = $id;
				$article_amounts[] = $info["total_fee"];
			}

			if ($bill_info["custominvoices"]) foreach($bill_info["custominvoices"] as $id => $info) {
				$block_custominvoice = true;
				$custominvoice_ids[] = $id;
				$custominvoice_amounts[] = $info["amount"];
			}

			$stoppayment = false;

			if ((AUTHORIZERECURRING_FEATURE == "on") && (($block_bannerbyimpression) || ($block_custominvoice))) {
				echo "<p class=\"errorMessage\">";
					if (($block_bannerbyimpression) && ($block_custominvoice)) echo system_showText(LANG_MSG_BANNER_CUSTOM_INVOICE_PAID_ONCE);
					elseif ($block_bannerbyimpression) echo system_showText(LANG_MSG_BANNER_PAID_ONCE);
					elseif ($block_custominvoice) echo system_showText(LANG_MSG_CUSTOM_INVOICE_PAID_ONCE);
					echo "&nbsp;".system_showText(LANG_MSG_PLEASE_DO_NOT_USE_RECURRING_PAYMENT_SYSTEM);
					echo "<br /><a href=\"".DEFAULT_URL."/".MEMBERS_ALIAS."/billing/\">".system_showText(LANG_MSG_TRY_AGAIN)."</a>";
				echo "</p>";
				$stoppayment = true;
			}

			if (!$stoppayment) {

				$contactObj = new Contact(sess_getAccountIdFromSession());
				$amount = str_replace(",", ".", $bill_info["total_bill"]);
				if ($listing_ids) $listing_ids = implode("::",$listing_ids);
				if ($listing_amounts) $listing_amounts = implode("::",$listing_amounts);
				if ($event_ids) $event_ids = implode("::",$event_ids);
				if ($event_amounts) $event_amounts = implode("::",$event_amounts);
				if ($banner_ids) $banner_ids = implode("::",$banner_ids);
				if ($banner_amounts) $banner_amounts = implode("::",$banner_amounts);
				if ($classified_ids) $classified_ids = implode("::",$classified_ids);
				if ($classified_amounts) $classified_amounts = implode("::",$classified_amounts);
				if ($article_ids) $article_ids = implode("::",$article_ids);
				if ($article_amounts) $article_amounts = implode("::",$article_amounts);
				if ($custominvoice_ids) $custominvoice_ids = implode("::",$custominvoice_ids);
				if ($custominvoice_amounts) $custominvoice_amounts = implode("::",$custominvoice_amounts);
				$authorize_account_id = sess_getAccountIdFromSession();
				$authorize_x_first_name = $contactObj->getString("first_name");
				$authorize_x_last_name = $contactObj->getString("last_name");
				$authorize_x_company = $contactObj->getString("company");
				$authorize_x_address = $contactObj->getString("address");
				$authorize_x_city = $contactObj->getString("city");
				$authorize_x_state = $contactObj->getString("state");
				$authorize_x_zip = $contactObj->getString("zip");
				$authorize_x_country = $contactObj->getString("country");
				$authorize_x_phone = $contactObj->getString("phone");
				$authorize_x_email = $contactObj->getString("email");

				?>

				<script language="javascript" type="text/javascript">
					<!--
					function submitOrder() {
						document.getElementById("authorizebutton").disabled = true;
						document.authorizeform.submit();
					}
					//-->
				</script>

				<form name="authorizeform" target="_self" action="<?=DEFAULT_URL?>/<?=MEMBERS_ALIAS?>/<?=$payment_process?>/processpayment.php?payment_method=<?=$payment_method?>" method="post">

					<div style="display: none;">
						<?
							setting_get("payment_tax_status", $payment_tax_status);
							setting_get("payment_tax_value", $payment_tax_value);

							$subtotal_amount = $amount;
							if ($payment_tax_status == "on") {
								$tax_amount = payment_calculateTax($subtotal_amount, $payment_tax_value, true, false);
								$amount = payment_calculateTax($subtotal_amount, $payment_tax_value);
							} else {
								$tax_amount = 0;
								$payment_tax_value = 0;
							}
						?>

						<input type="hidden" name="pay" value="1" />
						<input type="hidden" name="x_tax_amount" value="<?=$payment_tax_value;?>" />
						<input type="hidden" name="x_subtotal_amount" value="<?=$subtotal_amount;?>" />
						<input type="hidden" name="x_amount" value="<?=$amount?>" />
						<input type="hidden" name="x_invoice_num" value="<?=uniqid(0);?>" />
						<input type="hidden" name="x_cust_id" value="<?=$authorize_account_id?>" />
						<input type="hidden" name="x_listing_ids" value="<?=$listing_ids?>" />
						<input type="hidden" name="x_listing_amounts" value="<?=$listing_amounts?>" />
						<input type="hidden" name="x_event_ids" value="<?=$event_ids?>" />
						<input type="hidden" name="x_event_amounts" value="<?=$event_amounts?>" />
						<input type="hidden" name="x_banner_ids" value="<?=$banner_ids?>" />
						<input type="hidden" name="x_banner_amounts" value="<?=$banner_amounts?>" />
						<input type="hidden" name="x_classified_ids" value="<?=$classified_ids?>" />
						<input type="hidden" name="x_classified_amounts" value="<?=$classified_amounts?>" />
						<input type="hidden" name="x_article_ids" value="<?=$article_ids?>" />
						<input type="hidden" name="x_article_amounts" value="<?=$article_amounts?>" />
						<input type="hidden" name="x_custominvoice_ids" value="<?=$custominvoice_ids?>" />
						<input type="hidden" name="x_custominvoice_amounts" value="<?=$custominvoice_amounts?>" />
						<input type="hidden" name="x_domain_id" value="<?=SELECTED_DOMAIN_ID?>" />
						<input type="hidden" name="x_package_id" value="<?=$package_id?>" />

					</div>

					<table align="center" width="95%" cellpadding="2" cellspacing="2" class="standard-table">
						<tr>
							<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_LABEL_BILLING_INFO);?></th>
						</tr>
						<tr>
							<th>* <?=system_showText(LANG_LABEL_CARD_NUMBER);?>:</th>
							<td><input type="text" name="x_card_num" value="" /></td>
						</tr>
						<tr>
							<th>* <?=system_showText(LANG_LABEL_CARD_EXPIRE_DATE);?>:</th>
							<td><input type="text" name="x_exp_date" value="" /><span><?=system_showText(LANG_LETTER_MONTH).system_showText(LANG_LETTER_MONTH)."/".system_showText(LANG_LETTER_YEAR).system_showText(LANG_LETTER_YEAR);?></span></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_CARD_CODE);?>:</th>
							<td><input type="text" name="x_card_code" value="" /></td>
						</tr>
						<tr>
							<th colspan="2" class="standard-tabletitle"><?=system_showText(LANG_LABEL_CUSTOMER_INFO);?></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_FIRST_NAME);?>:</th>
							<td><input type="text" name="x_first_name" value="<?=$authorize_x_first_name?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_LAST_NAME);?>:</th>
							<td><input type="text" name="x_last_name" value="<?=$authorize_x_last_name?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_COMPANY);?>:</th>
							<td><input type="text" name="x_company" value="<?=$authorize_x_company?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_ADDRESS);?>:</th>
							<td><input type="text" name="x_address" value="<?=$authorize_x_address?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_CITY)?>:</th>
							<td><input  type="text" name="x_city" value="<?=$authorize_x_city?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_STATE)?>:</th>
							<td><input type="text" name="x_state" value="<?=$authorize_x_state?>" /></td>
						</tr>
						<tr>
							<th><?= string_ucwords(system_showText(LANG_LABEL_ZIP))?>:</th>
							<td><input type="text" name="x_zip" value="<?=$authorize_x_zip?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_COUNTRY)?>:</th>
							<td><input type="text" name="x_country" value="<?=$authorize_x_country?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_PHONE)?>:</th>
							<td><input type="text" name="x_phone" value="<?=$authorize_x_phone?>" /></td>
						</tr>
						<tr>
							<th><?=system_showText(LANG_LABEL_EMAIL);?>:</th>
							<td><input type="text" name="x_email" value="<?=$authorize_x_email?>" /></td>
						</tr>
					</table>

					<?
					if (AUTHORIZERECURRING_FEATURE == "on") {
						echo "<p class=\"informationMessage\">";
						echo system_showText(LANG_MSG_RECURRINGUNTILCARDEXPIRATION)." (".system_showText(LANG_MSG_RECURRINGUNTILCARDEXPIRATIONMAXOF).").";
						echo "</p>";
					}
					?>

					<? if ($payment_process == "signup") { ?>
						<table width="100%" border="0" cellpadding="2" cellspacing="2">
							<tr>
								<td><p class="standardButton paymentButton"><a href="javascript:void(0);" id="authorizebutton" onclick="submitOrder();"><?=system_highlightWords(system_showText(LANG_LABEL_PLACE_ORDER_CONTINUE))?></a></p></td>
							</tr>
						</table>
					<? } else { ?>
						<p class="standardButton paymentButton">
							<button type="button" id="authorizebutton" onclick="submitOrder();"><?=system_showText(LANG_BUTTON_PAY_BY_CREDIT_CARD);?></button>
						</p>
					<? } ?>

				</form>

				<?

			}

		}

	}

?>
