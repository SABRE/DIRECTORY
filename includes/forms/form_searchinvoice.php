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
	# * FILE: /includes/forms/form_searchinvoice.php
	# ----------------------------------------------------------------------------------------------------

?>
<?  // Account Search Javascript /////////////////////////////////////////////////////// ?>

<script type="text/javascript" src="<?=DEFAULT_URL?>/scripts/accountsearch.js"></script>

<?  //////////////////////////////////////////////////////////////////////////////////// ?>

<? if ($message_searchinvoice) { ?>
	<div id="warning" class="errorMessage">
		<?=$message_searchinvoice?>
	</div>
<? } ?>

<?  // Account Search ////////////////////////////////////////////////////////////////// ?>
<? if (!$members) { 
	$acct_search_table_title = system_showText(LANG_SITEMGR_ACCOUNTSEARCH_SELECT_DEFAULT);
	$acct_search_field_name = "search_account_id";
	$acct_search_field_value = $search_account_id;
	$acct_search_required_mark = false;
	$acct_search_form_width = "95%";
	$acct_search_cell_width = "";
	$return = system_generateAjaxAccountSearch($acct_search_table_title, $acct_search_field_name, $acct_search_field_value, $acct_search_required_mark, $acct_search_form_width, $acct_search_cell_width);
	echo $return;
} ?>
<?  //////////////////////////////////////////////////////////////////////////////////// ?>

<table border="0" cellpadding="2" cellspacing="0" class="standard-table" style="margin-top: 0;">
	<tr>
		<th>
			<?=system_showText(LANG_SITEMGR_LABEL_INVOICEID)?>:
		</th>
		<td>
			<input type="text" name="search_id" value="<?=$search_id?>" />
		</td>
	</tr>
	<? if (!$members) { ?>
	<tr>
		<th>
			<?=system_showText(LANG_SITEMGR_STATUS)?>:
		</th>
		<td>
			<?=$statusDropDown?>
		</td>
	</tr>
	<? } ?>
	<? if (!$members) { ?>
	<tr>
		<th><?=system_showText(LANG_SITEMGR_LABEL_AMOUNTRANGE)?>: </th>
		<td>
		&nbsp;<?=system_showText(LANG_SITEMGR_LABEL_FROM)?>:&nbsp;
		<input type="text" name="search_amount_range1" value="<? if ($search_amount_range1) echo format_money($search_amount_range1)?>" style="width:80px;" maxlength="10" />
		&nbsp;<?=system_showText(LANG_SITEMGR_LABEL_TO2)?>:&nbsp;
		<input type="text" name="search_amount_range2" value="<? if ($search_amount_range2) echo format_money($search_amount_range2)?>" style="width:80px;" maxlength="10" />
		</td>
	</tr>
	
	<tr>
		<th><?=system_showText(LANG_SITEMGR_LABEL_PAYMENTDATERANGE)?>: </th>
		<td>
		&nbsp;<?=system_showText(LANG_SITEMGR_LABEL_FROM)?>:&nbsp;
		<input type="text" name="search_date_range1" id="search_date_range1" value="<?=$search_date_range1?>" style="width:80px;" maxlength="10" />
		&nbsp;<?=system_showText(LANG_SITEMGR_LABEL_TO2)?>:&nbsp;
		<input type="text" name="search_date_range2" id="search_date_range2" value="<?=$search_date_range2?>" style="width:80px;" maxlength="10" />
		&nbsp;(<?=format_printDateStandard()?>)
		</td>
	</tr>
	
	<tr>
		<th class="alignTop"><?=system_showText(LANG_SITEMGR_EXPIRATIONDATE)?>: </th>
		<td>
		<input type="text" name="search_expiration_date" id="search_expiration_date" value="<?=$search_expiration_date?>" style="width:80px;" maxlength="10" />
		&nbsp;
		<input type="radio" name="search_opt_expiration_date" value="1" class="inputCheck" <?php if (!isset($search_opt_expiration_date) || intval($search_opt_expiration_date) == 1) { echo "checked"; } ?> />
		<?=system_showText(LANG_SITEMGR_LABEL_EXPIRATION_OPT1)?>&nbsp; <?=system_showText(LANG_SITEMGR_LABEL_OR)?> &nbsp;
		<input type="radio" name="search_opt_expiration_date" value="2" class="inputCheck" <?php if (intval($search_opt_expiration_date) == 2) { echo "checked"; } ?> />
		<?=system_showText(LANG_SITEMGR_LABEL_EXPIRATION_OPT2)?>
		<span>(<?=format_printDateStandard()?>)</span>
		</td>
	</tr>
	<tr>
		<th><?=system_showText(LANG_SITEMGR_LABEL_DISCOUNTCODE)?>: </th>
		<td>
		<input type="text" name="search_discount_code" id="search_discount_code" value="<?=$search_discount_code?>" style="width:80px;" maxlength="10" />
		<?
		    $autocomplete_discount_code_url = DEFAULT_URL."/".SITEMGR_ALIAS."/autocomplete_discountcode.php";
		?>
		<script type="text/javascript">
		    <!--
		    $(document).ready(function() {
			    $('#search_discount_code').autocomplete(
			    '<?=$autocomplete_discount_code_url?>',
				    {
					    delay:10,
					    dataType: 'html',
					    minChars:<?=AUTOCOMPLETE_MINCHARS?>,
					    matchSubset:0,
					    selectFirst:0,
					    matchContains:1,
					    cacheLength:<?=AUTOCOMPLETE_MAXITENS?>,
					    autoFill:false,
					    maxItemsToShow:<?=AUTOCOMPLETE_MAXITENS?>,
					    max:<?=AUTOCOMPLETE_MAXITENS?>
				    }
			    );
		    });
		    -->
		</script>
		</td>
	</tr>
	
	<? } ?>
</table>
