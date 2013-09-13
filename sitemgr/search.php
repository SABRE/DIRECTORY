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
	# * FILE: /sitemgr/index.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();

	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");
	?>

	<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
	<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
	<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

<script type="text/javascript">

	function pageResults(url, feature, message, direction) {

		url = "getMoreResults.php?"+url+"&searchFor="+feature+"&direction="+direction+"&screen="+$('#lm_'+feature).val();
		var xmlhttp;
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {
					xmlhttp = false;
				}
			}
		}
		if (xmlhttp) {
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 1) {
					document.getElementById('dinamic_'+feature).innerHTML="<div style=\"height:238px; padding-top:160px; font-family: trebuchet MS, arial; font-weight: bold; font-size: 14px; \"><img src=\"../images/img_loading.gif\" /><p style=\"text-align: center;\">"+message+"</p></div>";
				}
				if (xmlhttp.readyState == 4) {
					if (xmlhttp.status == 200) {
						document.getElementById('dinamic_'+feature).innerHTML=xmlhttp.responseText;
					}
				}
			}
			xmlhttp.open("GET", url, true);
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1")
			xmlhttp.send(null);
		}
	}

	function updateSMAccount(id, active, row_id){
		var url_ajax = "<?=DEFAULT_URL?>"+"/<?=SITEMGR_ALIAS?>/smaccount/updateStatus.php?id="+id+"&active="+active+"&row_id="+row_id;
		loadOnDIV(url_ajax,'tableSmaccount_rowId_'+row_id);
	}

</script>



<div id="main-right">

	<?
	$_GET = format_magicQuotes($_GET);
	extract($_GET);
	$_POST = format_magicQuotes($_POST);
	extract($_POST);

	if(isset($keywords))
		if ($keywords == string_ucwords(system_showText(LANG_SITEMGR_LABEL_KEYWORDS)))
			$keywords = "";


	$search_limit = 10;
	$limit_multiplier=1;

	$url = 'keywords='.$keywords.'&search_limit='.$search_limit;

	$no_regs = true;

	$features_array = array();

	if (permission_hasSMPermSection(SITEMGR_PERMISSION_LISTINGS))
		array_push($features_array, 'listing');
	if (BANNER_FEATURE == "on" && CUSTOM_BANNER_FEATURE == "on")
		if (permission_hasSMPermSection(SITEMGR_PERMISSION_BANNERS))
			array_push($features_array, 'banner');
	if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on")
		if (permission_hasSMPermSection(SITEMGR_PERMISSION_EVENTS))
			array_push($features_array, 'event');
	if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on")
		if (permission_hasSMPermSection(SITEMGR_PERMISSION_CLASSIFIEDS))
			array_push($features_array, 'classified');
	if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on")
		if (permission_hasSMPermSection(SITEMGR_PERMISSION_ARTICLES))
			array_push($features_array, 'article');
	if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on")
		if (permission_hasSMPermSection(SITEMGR_PERMISSION_LISTINGS))
			array_push($features_array, 'promotion');
	if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on")
		if (permission_hasSMPermSection(SITEMGR_PERMISSION_BLOG))
			 array_push($features_array, 'blog');
    if (permission_hasSMPermSection(SITEMGR_PERMISSION_ACCOUNTS)) {
		array_push($features_array, 'account');
		array_push($features_array, 'smaccount');
	}

	if (permission_hasSMPermSection(SITEMGR_PERMISSION_PAYMENT))
		if (PAYMENT_FEATURE == "on")
			if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on") || (MANUALPAYMENT_FEATURE == "on")) {
				if ((MANUALPAYMENT_FEATURE == "on") || (CREDITCARDPAYMENT_FEATURE == "on"))
					array_push($features_array, 'transaction');
				if (INVOICEPAYMENT_FEATURE == "on")
					array_push($features_array, 'invoice');
				if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on"))
					if (CUSTOM_INVOICE_FEATURE == "on")
						array_push($features_array, 'custominvoice');

			}


	foreach ($features_array as $feature) {

		if (($searchFor==$feature)||($searchFor=="All")) {

			unset ($search_for_keyword_fields);
			unset ($sql_where);
			unset ($where);

			if ($feature=='listing') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) {
					$keywords = str_replace("\\", "", $keywords);
					$search_for_keyword_fields[] = "Listing_Summary.fulltextsearch_keyword";
					$sql_where[] = search_getSQLFullTextSearch($keywords, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
				}

				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".LISTING_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Listing_Summary", 1, $search_limit, "level DESC, title", "title", "", $where);
					$listings = $pageObj->retrievePage("object");
				}

				$feature_plural = "listings";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_LISTING_PLURAL));

			}


			if ($feature=='banner') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------
				if ($keywords) { $sql_where[] = " caption LIKE ".db_formatString('%'.$keywords.'%')." "; }
				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------
                $fields = "id, type, status, account_id, expiration_setting, renewal_date, impressions,  `caption`";
                $letterField = "`caption`";

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Banner",  1, $search_limit, "type, caption", $letterField, $letter, $where, $fields);
					$banners = $pageObj->retrievePage("array");
				}
				$feature_plural = "banners";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_BANNER_PLURAL));
			}

			if ($feature=='event') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) {
					$keywords = str_replace("\\", "", $keywords);
					$search_for_keyword_fields[] = "Event.fulltextsearch_keyword";
					$sql_where[] = search_getSQLFullTextSearch($keywords, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
				}

				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".EVENT_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Event", 1, $search_limit, "level DESC, title", "title", $letter, $where);
					$events = $pageObj->retrievePage("object");
				}
				$feature_plural = "events";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_EVENT_PLURAL));

			}

			if ($feature=='classified') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) {
					$keywords = str_replace("\\", "", $keywords);
					$search_for_keyword_fields[] = "Classified.fulltextsearch_keyword";
					$sql_where[] = search_getSQLFullTextSearch($keywords, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
				}

				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".CLASSIFIED_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Classified", 1, $search_limit, "level DESC, title", "title", $letter, $where);
					$classifieds = $pageObj->retrievePage("object");
				}
				$feature_plural = "classifieds";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_CLASSIFIED_PLURAL));
			}

			if ($feature=='article') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) {
					$keywords = str_replace("\\", "", $keywords);
					$search_for_keyword_fields[] = "Article.fulltextsearch_keyword";
					$sql_where[] = search_getSQLFullTextSearch($keywords, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
				}

				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".ARTICLE_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Article", 1, $search_limit, "level DESC, title", "title", "", $where);
					$articles = $pageObj->retrievePage("object");
				}
				$feature_plural = "articles";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_ARTICLE_PLURAL));


			}

			if ($feature=='promotion') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) {
					$keywords = str_replace("\\", "", $keywords);
					$search_for_keyword_fields_promotion[] = "Promotion.fulltextsearch_keyword";
					$sql_where[] = "( (".search_getSQLFullTextSearch($keywords, $search_for_keyword_fields_promotion, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2").") )";
				}
				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/".PROMOTION_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Promotion", $screen, 10, "name", "name", $letter, $where, "Promotion.*", "Promotion");
					$promotions = $pageObj->retrievePage();
				}

				$feature_plural = "promotions";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_PROMOTION_PLURAL));

			}

            if ($feature=='blog') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) {
				    $keywords = str_replace("\\", "", $keywords);
                    $search_for_keyword_fields[] = "Post.fulltextsearch_keyword";
                    $sql_where[] = search_getSQLFullTextSearch($keywords, $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
                }
                if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER;

				if ($searchFor) {
					$pageObj = new pageBrowsing("Post", $screen, 10, "title DESC", "title", $letter, $where);
                    $posts = $pageObj->retrievePage("object");
				}

				$feature_plural = "posts";
				$feature_label  = system_showText(string_ucwords(LANG_SITEMGR_POST_BLOG_PLURAL)). " (".system_showText(string_ucwords(LANG_SITEMGR_BLOG)).")";


			}

			if ($feature=='account') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) $sql_where[] = " username like ".db_formatString('%'.$keywords.'%')." ";
				if ($sql_where)       $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/account";

				if ($searchFor) {
					$pageObj  = new pageBrowsing("Account", 1, $search_limit, "lastlogin DESC, username", "username", $letter, $where,  "*", false, false, true);
					$accounts = $pageObj->retrievePage();
				}

				$feature_plural = "accounts";
				$feature_label  = (SOCIALNETWORK_FEATURE == "on" ? system_showText(LANG_SITEMGR_LABEL_SPONSOR) : system_showText(LANG_SITEMGR_NAVBAR_ACCOUNTS));
			}

			if ($feature=='smaccount') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) $sql_where[] = " username like ".db_formatString('%'.$keywords.'%')." ";
				if ($sql_where)       $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/account";
				if ($searchFor) {
					$pageObj  = new pageBrowsing("SMAccount", 1, $search_limit, "username", "username", $letter, $where,  "*", false, false, true);
					$smaccounts = $pageObj->retrievePage();
				}

				$feature_plural = "smaccounts";
				$feature_label  = system_showText(LANG_SITEMGR_NAVBAR_SITEMGRACCOUNTS);

			}

			if ($feature=='transaction') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords)           $sql_where[] = " transaction_id = ".db_formatString($keywords)." ";
				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";


				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/transactions";

				if ($searchFor) {
					$pageObj  = new pageBrowsing("Payment_Log", 1, $search_limit, "transaction_datetime DESC", "", "", $where);
					$transactions = $pageObj->retrievePage("array");
				}

				$feature_plural = "transactions";
				$feature_label  = system_showText(LANG_SITEMGR_TRANSACTIONS);

			}

			if ($feature=='invoice') {

				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords)                       $sql_where[] = " id = ".db_formatString($keywords)." ";
				if ($search_status)                   $sql_where[] = " status != N ";

				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/invoices";

				if ($searchFor) {
					$pageObj  = new pageBrowsing("Invoice", 1, $search_limit, "date DESC", "", "",$where);
					$invoices = $pageObj->retrievePage("array");
				}

				$feature_plural = "invoices";
				$feature_label  = string_ucwords(system_showText(LANG_SITEMGR_INVOICE_PLURAL));

			}

			if ($feature=='custominvoice') {
				# ----------------------------------------------------------------------------------------------------
				# FORMS DEFINES
				# ----------------------------------------------------------------------------------------------------

				if ($keywords) $sql_where[] = " title LIKE '%".addslashes($keywords)."%' ";

				if ($sql_where) $where .= " ".implode(" AND ", $sql_where)." ";

				# ----------------------------------------------------------------------------------------------------
				# PAGE BROWSING
				# ----------------------------------------------------------------------------------------------------

				$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/custominvoices";

				if ($searchFor) {
					$pageObj  = new pageBrowsing("CustomInvoice", 1, $search_limit, "date DESC", "", "", $where);
					$custominvoices = $pageObj->retrievePage("object");
				}

				$feature_plural = "custominvoices";
				$feature_label  = string_ucwords(system_showText(LANG_SITEMGR_CUSTOMINVOICE_PLURAL));
			}

			$total_records = $pageObj->getString("record_amount");

			if ($total_records) {

				if ($no_regs) $no_regs = false;

				# SEE MORE BUTTON  ----------------------------------------------------------------------------------------------
				$seeMoreButton = $pageObj->getPagesButtons($_GET, $feature, $limit_multiplier, $search_limit, $total_records, "this.form.submit();");

				?>
				<div id="top-content">
					<div id="header-content">
						<h1><?=string_ucwords(system_showText(LANG_SITEMGR_MENU_SEARCH))?> <?=$feature_label?></h1>
					</div>
				</div>
				<div id="content-content">
					<div class="default-margin">
						<? if ($searchFor) { ?>
                        
							<div class="header-form">
								<?=string_ucwords(system_showText(LANG_SITEMGR_RESULTS))?>
							</div>
                        
							<table  border="0" cellpadding="0" cellspacing="0" align="center" class="pagingContent">
								<tr><td><?=(intval($total_records) != 1 ? system_showText(LANG_PAGING_FOUND_PLURAL) : system_showText(LANG_PAGING_FOUND))?> <b><?=$total_records?></b> <?=(($total_records!=1)?(system_showText(LANG_PAGING_RECORD_PLURAL)):(system_showText(LANG_PAGING_RECORD)))?></td></tr>
							</table>

							<div id="dinamic_<?=$feature?>"> <?
								if (${$feature_plural}) { ?>
									<? $legend=false; ?>
									<?=$seeMoreButton?>
									<? if ($total_records>=10) { ?>
										<table border="0" cellpadding="0" cellspacing="0" align="center" class="pagingContent">
											<tr><td><?=system_showText(LANG_PAGING_SHOWINGPAGE)?> <strong><?=$pageObj->getString("screen")?></strong> <?=system_showText(LANG_PAGING_PAGEOF)?> <strong><?=$pageObj->getString("pages")?></strong> <?=(intval($pageObj->getString("record_amount")) <= 1 ? system_showText(LANG_PAGING_PAGEOF) : system_showText(LANG_PAGING_PAGE_PLURAL))?></td></tr>
										</table>
									<? } ?>
									<? include(INCLUDES_DIR."/tables/table_".$feature.".php"); ?>
									<input id="lm_<?=string_strtolower($feature)?>" name="limit_multiplier_<?=string_strtolower($searchFor)?>" type="hidden" value="<?=$limit_multiplier?>" />
									<?
								} ?>
							</div>
                        
						<? } ?>
					</div>
				</div>


				<? // ******* LEGENDS ******* // ?>
				<? if ($feature=='listing') {
					$level = new ListingLevel(true);
					$levelvalues = $level->getLevelValues();
					?>
					<ul class="standard-iconDESCRIPTION">
						<?
						foreach ($levelvalues as $levelvalue) {
							if ($level->getActive($levelvalue) == 'y') {
								?><li style="background: url(<?=DEFAULT_URL?>/images/img_<?=$levelvalue?>.gif) no-repeat 0 50%; padding-left: 35px;"><?=$level->showLevel($levelvalue)?></li><?
							}
						}
						?>
					</ul>
					<ul class="standard-iconDESCRIPTION">
						<li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
						<li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
						<? if ( PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" ) { ?>
							<li class="promotion-icon"><?=system_showText(LANG_PROMOTION_FEATURE_NAME);?></li>
						<? } ?>
						<li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
						<li class="map-icon"><?=system_showText(LANG_LABEL_MAP_TUNING);?></li>
						<li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
						<li class="rating-icon"><?=system_showText(LANG_REVIEW);?></li>
					</ul>
                    <ul class="standard-iconDESCRIPTION">
                        <? if (TWILIO_APP_ENABLED == "on" && TWILIO_APP_ENABLED_CALL == "on") { ?>
                            <li class="call-icon"><?=system_showText(LANG_LABEL_CLICKTOCALL);?></li>
                        <? } ?>
                        <? if ($commenting_fb == "on") { ?>
                            <li class="facebook-icon"><?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS);?></li>
                        <? } ?>
                        <? if (BACKLINK_FEATURE == "on"){ ?> 
                            <li class="backlink-icon"><?=system_showText(LANG_LABEL_BACKLINK);?></li>
                        <? } ?>
                        <? if (string_strpos($url_base, "/".SITEMGR_ALIAS."")) { ?>
                            <? if ( PAYMENTSYSTEM_FEATURE == "on" ) { ?>
                                <li class="unpaid-icon"><?=system_showText(LANG_LABEL_UNPAID);?></li>
                                <li class="unpaid-icon-off"><?=system_showText(LANG_LABEL_PAID);?></li>
                                <li class="transaction-icon"><?=system_showText(LANG_LABEL_TRANSACTION);?></li>
                            <? } ?>
                            <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
                        <? } ?>
                    </ul>
					<?
				} ?>

				<? if ($feature=='banner') { ?>
					<ul class="standard-iconDESCRIPTION">
						<li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
						<li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
						<li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
						<? if (string_strpos($url_redirect, "/".SITEMGR_ALIAS."") && PAYMENTSYSTEM_FEATURE == "on") { ?>
							<li class="unpaid-icon"><?=system_showText(LANG_LABEL_UNPAID);?></li>
							<li class="unpaid-icon-off"><?=system_showText(LANG_LABEL_PAID);?></li>
							<li class="transaction-icon"><?=system_showText(LANG_LABEL_TRANSACTION);?></li>
						<? } ?>
						<li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
					</ul>
				<? } ?>

				<? if ($feature=='event') { ?>
					<?
					$level = new EventLevel(true);
					$levelvalues = $level->getLevelValues();
					?>
					<ul class="standard-iconDESCRIPTION">
                        <?
                        foreach ($levelvalues as $levelvalue) {
                            if ($level->getActive($levelvalue) == 'y') {
                                ?><li style="background: url(<?=DEFAULT_URL?>/images/img_even_<?=$levelvalue?>.gif) no-repeat 0 50%; padding-left: 35px;"><?=$level->showLevel($levelvalue)?></li><?
                            }
                        }
                        ?>
                    </ul>

                    <ul class="standard-iconDESCRIPTION">
                        <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW)?></li>
                        <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT)?></li>
                        <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS)?></li>
                        <li class="map-icon"><?=system_showText(LANG_LABEL_MAP_TUNING)?></li>
                        <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
                        <? if (string_strpos($url_base, "/".SITEMGR_ALIAS."") && PAYMENTSYSTEM_FEATURE == "on") { ?>
                            <li class="unpaid-icon"><?=system_showText(LANG_LABEL_UNPAID)?></li>
                            <li class="unpaid-icon-off"><?=system_showText(LANG_LABEL_PAID);?></li>
                            <li class="transaction-icon"><?=system_showText(LANG_LABEL_TRANSACTION)?></li>
                        <? } ?>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE)?></li>
                    </ul>

				<? } ?>

				<? if ($feature=='classified') { ?>
					<?
					$level = new ClassifiedLevel(true);
					$levelvalues = $level->getLevelValues();
					?>
					<ul class="standard-iconDESCRIPTION">
						<?
						foreach ($levelvalues as $levelvalue) {
							if ($level->getActive($levelvalue) == 'y') {
								?><li style="background: url(<?=DEFAULT_URL?>/images/img_class_<?=$levelvalue?>.gif) no-repeat 0 50%; padding:5px 0 5px 35px;"><?=$level->showLevel($levelvalue)?></li><?
							}
						}
						?>
					</ul>
					<ul class="standard-iconDESCRIPTION">
                        <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
                        <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
                        <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
                        <li class="map-icon"><?=system_showText(LANG_LABEL_MAP_TUNING);?></li>
                        <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
                        <? if (string_strpos($url_base, "/".SITEMGR_ALIAS."") && PAYMENTSYSTEM_FEATURE == "on") { ?>
                            <li class="unpaid-icon"><?=system_showText(LANG_LABEL_UNPAID);?></li>
                            <li class="unpaid-icon-off"><?=system_showText(LANG_LABEL_PAID);?></li>
                            <li class="transaction-icon"><?=system_showText(LANG_LABEL_TRANSACTION);?></li>
                        <? } ?>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE)?></li>
                    </ul>
				<? } ?>

				<? if ($feature=='article') { ?>
					<ul class="standard-iconDESCRIPTION">
                        <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
                        <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
                        <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
                        <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
                        <? if (($review_enabled == "on" && $commenting_edir) || string_strpos($url_base, "/".SITEMGR_ALIAS."")) { ?>
                            <li class="rating-icon"><?=system_showText(LANG_REVIEW);?></li>
                        <? } ?>
                    </ul>

                    <ul class="standard-iconDESCRIPTION">
                        <? if (string_strpos($url_base, "/".SITEMGR_ALIAS."") && PAYMENTSYSTEM_FEATURE == "on") { ?>
                            <li class="unpaid-icon"><?=system_showText(LANG_LABEL_UNPAID);?></li>
                            <li class="unpaid-icon-off"><?=system_showText(LANG_LABEL_PAID);?></li>
                            <li class="transaction-icon"><?=system_showText(LANG_LABEL_TRANSACTION);?></li>
                        <? } ?>
                        <? if ($commenting_fb == "on") { ?>
                            <li class="facebook-icon"><?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS);?></li>
                        <? } ?>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
                    </ul>
				<? } ?>

				<? if ($feature=='promotion') { ?>
					<ul class="standard-iconDESCRIPTION">
                        <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
                        <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
                        <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
                        <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
                        <? 
                        if (($review_enabled == "on" && $commenting_edir) || string_strpos($url_base, "/".SITEMGR_ALIAS."")) { 
                            ?>
                            <li class="rating-icon"><?=system_showText(LANG_REVIEW);?></li>
                            <? 
                        } 
                        if ($commenting_fb == "on") { 
                            ?>
                            <li class="facebook-icon"><?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS);?></li>
                            <? 
                        } 
                        ?>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
                    </ul>
				<? } ?>


                <?  if ($feature=='blog') {  ?>
                    <ul class="standard-iconDESCRIPTION">
                        <li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
                        <? if (!$wp_enabled){ ?>
                        <li class="edit-icon"><?=system_showText(LANG_LABEL_EDIT);?></li>
                        <? } ?>
                        <li class="traffic-icon"><?=system_showText(LANG_TRAFFIC_REPORTS);?></li>
                        <li class="seo-icon"><?=system_showText(LANG_LABEL_SEO_TUNING);?></li>
                        <? if ($commenting_fb == "on") { ?>
                        <li class="facebook-icon"><?=system_showText(LANG_LABEL_FACEBOOK_COMMENTS);?></li>
                        <? } ?>
                        <li class="delete-icon"><?=system_showText(LANG_LABEL_DELETE);?></li>
                    </ul>
                <? } ?>

				<? if ($feature=='account') { ?>
					<table class="table-subtitle-table">
						<tr class="tr-subtitle-table">
							<td class="td-subtitle-table">
								<img src="<?=DEFAULT_URL?>/images/bt_view.gif" alt="view" title="view" border="0" />
							</td>
							<td class="td-subtitle-table">
								<font class="font-subtitle-table">
									<?=system_showText(LANG_SITEMGR_VIEW)?>
								</font>
							</td>
							<td class="td-subtitle-table">
								<img src="<?=DEFAULT_URL?>/images/bt_edit.gif" alt="edit" title="edit" border="0" />
							</td>
							<td class="td-subtitle-table">
								<font class="font-subtitle-table">
									<?=system_showText(LANG_SITEMGR_EDIT)?>
								</font>
							</td>
							<td class="td-subtitle-table">
								<img src="<?=DEFAULT_URL?>/images/bt_delete.gif" alt="delete" title="delete" border="0" />
							</td>
							<td class="td-subtitle-table">
								<font class="font-subtitle-table">
									<?=system_showText(LANG_SITEMGR_DELETE)?>
								</font>
							</td>
						</tr>
					</table>
				<? } ?>

				<? if ($feature=='smaccount') { ?>
					<table class="table-subtitle-table" cellspacing="5">
                        <tr class="tr-subtitle-table">
                            <td class="td-subtitle-table">
                            <img src="<?=DEFAULT_URL?>/images/icon_check.gif" alt="<?=(system_showText(LANG_SITEMGR_ACTIVATED))?>" title="<?=(system_showText(LANG_SITEMGR_ACTIVATED))?>" border="0" />
                        </td>
                        <td class="td-subtitle-table">
                            <font class="font-subtitle-table">
                                <?=string_ucwords(system_showText(LANG_SITEMGR_ACTIVATED))?>
                            </font>
                        </td>

                        <td class="td-subtitle-table">
                            <img src="<?=DEFAULT_URL?>/images/icon_uncheck.gif" alt="<?=(system_showText(LANG_SITEMGR_DEACTIVATED))?>" title="<?=(system_showText(LANG_SITEMGR_DEACTIVATED))?>" border="0" />
                        </td>
                        <td class="td-subtitle-table">
                            <font class="font-subtitle-table">
                                <?=string_ucwords(system_showText(LANG_SITEMGR_DEACTIVATED))?>
                            </font>
                        </td>
                        <td class="td-subtitle-table">
                            <img src="<?=DEFAULT_URL?>/images/bt_view.gif" alt="<?=system_showText(LANG_SITEMGR_VIEW)?>" title="<?=system_showText(LANG_SITEMGR_VIEW)?>" border="0" />
                        </td>
                        <td class="td-subtitle-table">
                            <font class="font-subtitle-table">
                                <?=system_showText(LANG_SITEMGR_VIEW)?>
                            </font>
                        </td>
                        <td class="td-subtitle-table">
                            <img src="<?=DEFAULT_URL?>/images/bt_edit.gif" alt="<?=system_showText(LANG_SITEMGR_EDIT)?>" title="<?=system_showText(LANG_SITEMGR_EDIT)?>" border="0" />
                        </td>
                        <td class="td-subtitle-table">
                            <font class="font-subtitle-table">
                                <?=system_showText(LANG_SITEMGR_EDIT)?>
                            </font>
                        </td>
                        <td class="td-subtitle-table">
                            <img src="<?=DEFAULT_URL?>/images/bt_delete.gif" alt="<?=system_showText(LANG_SITEMGR_DELETE)?>" title="<?=system_showText(LANG_SITEMGR_DELETE)?>" border="0" />
                        </td>
                        <td class="td-subtitle-table">
                            <font class="font-subtitle-table">
                                <?=system_showText(LANG_SITEMGR_DELETE)?>
                            </font>
                        </td>
                    </tr>
                </table>

				<? } ?>

				<? if ($feature=='transaction') { ?>
					<ul class="standard-iconDESCRIPTION">
						<li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
					</ul>
				<? } ?>

				<? if ($feature=='invoice') { ?>
					<ul class="standard-iconDESCRIPTION">
						<li class="view-icon"><?=system_showText(LANG_LABEL_VIEW);?></li>
					</ul>
				<? } ?>

				<? if ($feature=='custominvoice') { ?>
					<ul class="standard-iconDESCRIPTION">
						<li class="view-icon"><?=system_showText(LANG_SITEMGR_VIEW)?></li>
						<li class="edit-icon"><?=system_showText(LANG_SITEMGR_EDIT)?></li>
						<li class="transaction-icon"><?=system_showText(LANG_SITEMGR_PAYMENTRECEIVED)?></li>
						<li class="send-icon"><?=system_showText(LANG_SITEMGR_SEND)?></li>
					</ul>
				<? } ?>

				<div id="bottom-content">&nbsp;</div> <?
			}
		}
	}

	if ($no_regs) { ?>

	<div id="top-content">
		<div id="header-content">
			<?if ($searchFor=="All") unset($feature_label);?>

			<h1><?=string_ucwords(system_showText(LANG_SITEMGR_MENU_SEARCH))?> <?=$feature_label?></h1>
		</div>
	</div>

	<div class="header-form">

		<? if ($keywords) { ?>
			<?=string_ucwords(system_showText(LANG_SITEMGR_RESULTS_KEYWORD))?><?=ucfirst($keywords)?>
		<? } else {?>
			<?=string_ucwords(system_showText(LANG_SITEMGR_RESULTS))?>
		<?}?>
	</div>

	<p class="errorMessage">
		<? if ($searchFor=="All") $feature_label= system_showText(LANG_SITEMGR_EDIRECTORY)?>
		<?=system_showText(LANG_MSG_NO_RESULTS_FOUND)?>


	</p> <? } ?>

</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>