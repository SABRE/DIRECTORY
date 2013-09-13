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
	# * FILE: /sitemgr/sitemap.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

<div id="main-right">
	<div id="top-content">
		<div id="header-content">
			<h1><?=system_showText(LANG_SITEMGR_LABEL_SITEMAP);?></h1>
		</div>
	</div>

	<div id="content-content">
    <ul class="sitemapList">

		<li class="standardSubTitle">
			<a class="sitemapSection" href="<?=DEFAULT_URL?>/"><?=system_showText(LANG_SITEMGR_VIEW_SITE);?></a>
		</li>

		<li class="standardSubTitle">
			<a class="sitemapSection" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/manageaccount.php"><?=system_showText(LANG_SITEMGR_MENU_MYACCOUNT);?></a>
		</li>

		<li class="standardSubTitle">
			<a class="sitemapSection" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/logout.php"><?=system_showText(LANG_SITEMGR_MENU_LOGOUT)?></a>
		</li>

		<li class="standardSubTitle">
			<a class="sitemapSection" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>"><?=system_showText(LANG_SITEMGR_DASHBOARD);?></a>
		</li>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_ACCOUNTS)) { ?>
			<li class="standardSubTitle">
				<div class="sitemapSection">
					<?=system_showText(string_ucwords(LANG_SITEMGR_ACCOUNT_PLURAL));?>
				</div>
				<p class="subt"><?=(SOCIALNETWORK_FEATURE == "on" ? string_ucwords(system_showText(LANG_SITEMGR_NAVBAR_MEMBERACCOUNTS)) : string_ucwords(system_showText(LANG_SITEMGR_SPONSORACCOUNTS)))?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/account.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/account/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
				</ul>
				<p class="subt"><?=system_showText(LANG_SITEMGR_NAVBAR_SITEMGRACCOUNTS);?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/smaccount/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/smaccount/smaccount.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/smaccount/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/manageaccount.php"><?=system_showText(LANG_SITEMGR_MENU_MYACCOUNT);?></a></li>
				</ul>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_DOMAIN)) { ?>
		<li class="standardSubTitle">
			<div class="sitemapSection">
				<?=system_showText(string_ucwords(LANG_SITEMGR_NAVBAR_DOMAIN_PLURAL));?>
			</div>
			<ul>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/domain/index.php"><?=system_showText(string_ucwords(LANG_SITEMGR_MANAGE));?> </a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/domain/domain.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
			</ul>
		</li>
		<? } ?>

		<li class="standardSubTitle">
			<div class="sitemapSection">
				<?=system_showText(LANG_SITEMGR_SUPPORT);?>
			</div>

			<ul>
				<li><a href="http://support.edirectory.com/" target="_blank"><?=system_showText(LANG_SITEMGR_EDIRECTORYMANUAL)?></a></li>
				<li><a class="iframe fancy_window_feedback" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/feedback.php"><?=system_showText(LANG_SITEMGR_FEEDBACK)?></a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/faq/faq.php?keyword=" target="_blank"><?=system_showText(LANG_SITEMGR_MENU_FAQ)?></a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/sitemap.php"><?=system_showText(LANG_SITEMGR_LABEL_SITEMAP)?></a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/about.php" class="iframe fancy_window_about"><?=system_showText(LANG_SITEMGR_MENU_ABOUT)?></a></li>
			</ul>
		</li>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_LISTINGS)) { ?>
			<li class="standardSubTitle">
				<div class="sitemapSection">
					<?=system_showText(LANG_SITEMGR_NAVBAR_LISTING);?>
				</div>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=LISTING_FEATURE_FOLDER;?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=LISTING_FEATURE_FOLDER;?>/listinglevel.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=LISTING_FEATURE_FOLDER;?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/listingcategs/index.php"><?=system_showText(string_ucwords(LANG_SITEMGR_CATEGORIES));?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/review/index.php?item_type=listing"><?=system_showText(string_ucwords(LANG_SITEMGR_REVIEWS));?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/claim"><?=system_showText(string_ucwords(LANG_SITEMGR_CLAIMED));?> </a></li>
                    <? if (LISTINGTEMPLATE_FEATURE == "on" && !USING_THEME_TEMPLATE) { ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/listingtemplate/index.php"><?=system_showText(LANG_SITEMGR_MENU_TEMPLATES);?> </a></li>
                    <? } ?>
				</ul>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_BANNERS)) { ?>
			<? if (BANNER_FEATURE == "on") { ?>
				<li class="standardSubTitle">
					<div class="sitemapSection">
						<?=system_showText(string_ucwords(LANG_SITEMGR_BANNER_PLURAL));?>
					</div>
					<ul>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/add.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
					</ul>
				</li>
			<? } ?>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_EVENTS)) { ?>
			<? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
				<li class="standardSubTitle">
					<div class="sitemapSection">
						<?=system_showText(string_ucwords(LANG_SITEMGR_EVENT_PLURAL));?>
					</div>
					<ul>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=EVENT_FEATURE_FOLDER;?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=EVENT_FEATURE_FOLDER;?>/eventlevel.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=EVENT_FEATURE_FOLDER;?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/eventcategs/index.php"><?=system_showText(string_ucwords(LANG_SITEMGR_CATEGORIES));?> </a></li>
					</ul>
				</li>
			<? } ?>
		<? } ?>


		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_CLASSIFIEDS)) { ?>
			<? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
				<li class="standardSubTitle">
					<div class="sitemapSection">
						<?=system_showText(string_ucwords(LANG_SITEMGR_CLASSIFIED_PLURAL));?>
					</div>
					<ul>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=CLASSIFIED_FEATURE_FOLDER;?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=CLASSIFIED_FEATURE_FOLDER;?>/classifiedlevel.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=CLASSIFIED_FEATURE_FOLDER;?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/classifiedcategs/index.php"><?=system_showText(string_ucwords(LANG_SITEMGR_CATEGORIES));?> </a></li>
					</ul>
				</li>
			<? } ?>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_ARTICLES)) { ?>
			<? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
				<li class="standardSubTitle">
					<div class="sitemapSection">
						<?=system_showText(string_ucwords(LANG_SITEMGR_ARTICLE_PLURAL));?>
					</div>
					<ul>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=ARTICLE_FEATURE_FOLDER;?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=ARTICLE_FEATURE_FOLDER;?>/article.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=ARTICLE_FEATURE_FOLDER;?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/articlecategs/index.php"><?=system_showText(string_ucwords(LANG_SITEMGR_CATEGORIES));?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/review/index.php?item_type=article"><?=system_showText(string_ucwords(LANG_SITEMGR_REVIEWS));?> </a></li>
					</ul>
				</li>
			<? } ?>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_LISTINGS)) {
			if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on") { ?>
				<li class="standardSubTitle">
					<div class="sitemapSection">
						<?=system_showText(LANG_SITEMGR_NAVBAR_PROMOTION);?>
					</div>
					<ul>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=PROMOTION_FEATURE_FOLDER?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=PROMOTION_FEATURE_FOLDER?>/deal.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=PROMOTION_FEATURE_FOLDER?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/review/index.php?item_type=promotion"><?=system_showText(string_ucwords(LANG_SITEMGR_REVIEWS));?> </a></li>
					</ul>
				</li>
			<? } ?>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_BLOG)) {
			if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
					<li class="standardSubTitle">
						<div class="sitemapSection">
							<?=system_showText(LANG_MENU_BLOG);?>
						</div>
						<ul>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BLOG_FEATURE_FOLDER;?>/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BLOG_FEATURE_FOLDER;?>/blog.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BLOG_FEATURE_FOLDER;?>/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/blogcategs/index.php"><?=system_showText(string_ucwords(LANG_SITEMGR_CATEGORIES));?> </a></li>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BLOG_FEATURE_FOLDER;?>/comments/index.php"><?=system_showText(string_ucwords(LANG_BLOG_COMMENTS));?> </a></li>
						</ul>
					</li>
				<? } ?>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_SITECONTENT)) { ?>
			<li class="standardSubTitle">
				<div class="sitemapSection">
					<?=system_showText(LANG_SITEMGR_MENU_SITECONTENT);?>
				</div>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/"><?=system_showText(LANG_SITEMGR_GENERAL);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/content_header.php"><?=system_showText(LANG_SITEMGR_HEADER);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/content_footer.php"><?=system_showText(LANG_SITEMGR_FOOTER);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/content_noimage.php"><?=system_showText(LANG_SITEMGR_CONTENT_DEFAULTIMAGE);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/content_icon.php"><?=system_showText(LANG_SITEMGR_CONTENT_ICON);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/advertisement.php"><?=system_showText(LANG_SITEMGR_ADVERTISEMENT);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/member.php"><?=system_showText(LANG_SITEMGR_MEMBER);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/client.php"><?=system_showText(LANG_SITEMGR_MENU_CUSTOM);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/listing.php"><?=system_showText(LANG_SITEMGR_LISTING_SING);?> </a></li>
					<? if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on") { ?>
                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/deal.php"><?=string_ucwords(system_showText(LANG_SITEMGR_PROMOTION_SING));?></a></li>
                    <? } ?>
                    <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/event.php"><?=system_showText(string_ucwords(LANG_SITEMGR_EVENT));?> </a></li>
					<? } ?>
					<? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/classified.php"><?=system_showText(LANG_SITEMGR_CLASSIFIED_SING);?> </a></li>
					<? } ?>
					<? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/article.php"><?=system_showText(string_ucwords(LANG_SITEMGR_ARTICLE));?> </a></li>
					<? } ?>
                    <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/blog.php"><?=system_showText(string_ucwords(LANG_SITEMGR_BLOG));?> </a></li>
					<? } ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/htmleditor.php"><?=system_showText(LANG_SITEMGR_SETTINGS_HTMLEDITOR);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/content_slider.php"><?=system_showText(LANG_SITEMGR_NAVBAR_SLIDER);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/navigation.php"><?=system_showText(LANG_SITEMGR_SETTINGS_NAVIGATION);?> </a></li>
				</ul>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_SEOCENTER)) { ?>
			<li class="standardSubTitle">
				<a class="sitemapSection" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/seocenter.php"><?=system_showText(LANG_SITEMGR_SEOCENTER);?> </a>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_PAYMENT)) { ?>
			<? if (PAYMENT_FEATURE == "on") { ?>
				<li class="standardSubTitle">

					<div class="sitemapSection">
						<?=system_showText(LANG_SITEMGR_REVENUECENTER);?>
					</div>

					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on") || (MANUALPAYMENT_FEATURE == "on")) { ?>
						<? if ((MANUALPAYMENT_FEATURE == "on") || (CREDITCARDPAYMENT_FEATURE == "on")) { ?>
							<p class="subt"><?=system_showText(LANG_SITEMGR_TRANSACTION);?></p>
							<ul>
								<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/transactions"><?=system_showText(LANG_SITEMGR_HISTORY);?> </a></li>
								<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/transactions/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
								<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_IMPORTEXPORT)) { ?>
									<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/export/payment.php?type=online"><?=system_showText(LANG_SITEMGR_MENU_EXPORTPAYMENTRECORDS);?> </a></li>
								<? } ?>
							</ul>
						<? } ?>

						<p class="subt"><?=system_showText(LANG_SITEMGR_INVOICE);?> </p>
						<ul>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/invoices"><?=system_showText(LANG_SITEMGR_HISTORY);?> </a></li>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/invoices/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
							<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_IMPORTEXPORT)) { ?>
								<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/export/payment.php?type=invoice"><?=system_showText(LANG_SITEMGR_MENU_EXPORTPAYMENTRECORDS);?> </a></li>
							<? } ?>
						</ul>
					<? } ?>

					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<? if (CUSTOM_INVOICE_FEATURE == "on") { ?>
							<p class="subt"><?=system_showText(LANG_SITEMGR_CUSTOMINVOICE);?> </p>
							<ul>
								<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/custominvoices/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
								<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/custominvoices/custominvoice.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
								<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/custominvoices/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?> </a></li>
							</ul>
						<? } ?>
					<? } ?>

					<? if ((CREDITCARDPAYMENT_FEATURE == "on") || (INVOICEPAYMENT_FEATURE == "on")) { ?>
						<p class="subt"><?=system_showText(LANG_SITEMGR_PROMOTIONALCODE);?> </p>
						<ul>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/discountcode/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?> </a></li>
							<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/discountcode/discountcode.php"><?=system_showText(LANG_SITEMGR_ADD);?> </a></li>
						</ul>
					<? } ?>
				</li>
			<? } ?>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_REPORTS)) { ?>
			<li class="standardSubTitle">
				<div class="sitemapSection">
					<?=system_showText(LANG_SITEMGR_NAVBAR_REPORTS);?>
				</div>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/reports/systemreport.php"><?=system_showText(LANG_SITEMGR_NAVBAR_SYSTEMREPORT);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/reports/statisticreport.php"><?=system_showText(LANG_SITEMGR_NAVBAR_STATISTICREPORT)?></a></li>
				</ul>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_SETTINGS)) { ?>
			<li class="standardSubTitle">
				<div class="sitemapSection">
					<?=system_showText(LANG_SITEMGR_MENU_SETTINGS);?>
				</div>

				<p class="subt"><?=system_showText(LANG_SITEMGR_GENERAL);?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/theme.php"><?=system_showText(LANG_SITEMGR_MENU_THEMES);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/foreignaccount.php"><?=system_showText(LANG_SITEMGR_MENU_LOGINOPTIONS);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/faq.php"><?=system_showText(LANG_SITEMGR_FREQUENTLYASKEDQUESTIONS);?> </a></li>


					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/robotsfilter.php"><?=system_showText(LANG_SITEMGR_SETTINGS_ROBOTS);?> </a></li>
					<? if (MAINTENANCE_FEATURE == "on") { ?>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/maintenance.php"><?=system_showText(LANG_SITEMGR_SETTING_MAINTENANCE);?> </a></li>
					<? } ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/twittersettings.php"><?=system_showText(LANG_SITEMGR_TWITTER);?> </a></li>
					<? if (FEATURED_CATEGORY == "on") { ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/featuredcategory.php"><?=string_ucwords(system_showText(LANG_SITEMGR_FEATUREDCATEGORY_PLURAL));?></a></li>
					<? } ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/approvalrequirement.php"><?=string_ucwords(system_showText(LANG_SITEMGR_SETTINGS_APPROVAL));?></a></li>
					<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_LOCATIONS)) { ?>
						<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/location.php"><?=system_showText(LANG_SITEMGR_SEOCENTER_LABEL_LOCATIONS);?> </a></li>
					<? } ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/visitorprofile.php"><?=string_ucwords(system_showText(LANG_SITEMGR_SOCIALNETWORK));?></a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/comments.php"><?=string_ucwords(system_showText(LANG_SITEMGR_COMMENTING_OPTIONS));?></a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/twilio.php"><?=string_ucwords(system_showText(LANG_SITEMGR_TWILIO));?></a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/api.php"><?=system_showText(LANG_SITEMGR_API);?></a></li>
				</ul>

				<p class="subt"><?=system_showText(LANG_SITEMGR_LABEL_EMAIL);?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/email.php"><?=system_showText(LANG_SITEMGR_SYSTEMEMAIL);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/emailnotifications/"><?=system_showText(LANG_SITEMGR_MENU_EMAILNOTIF);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/emailconfig.php"><?=system_showText(LANG_SITEMGR_SETTINGS_EMAILCONF_EMAILSENDINGCONFIGURATION);?> </a></li>			
				</ul>

				<p class="subt"><?=system_showText(LANG_SITEMGR_NAVBAR_MODULES);?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/editorchoice.php"><?=system_showText(LANG_SITEMGR_SETTINGS_EDITORCHOICE_DESIGNATIONS);?></a></li>
					<? if(ABLE_RENAME_LEVEL == "on") { ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/levels.php"><?=system_showText(LANG_SITEMGR_SETTINGS_LEVELS_MENULABEL);?></a></li>
					<? }
					if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on"){ ?>
                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/deal.php"><?=string_ucwords(system_showText(LANG_SITEMGR_PROMOTION));?></a></li>
                    <? } ?>
					<? if (CLAIM_FEATURE == "on") { ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/claim.php"><?=string_ucwords(system_showText(LANG_SITEMGR_CLAIM_CLAIMS))?></a></li>
                    <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/modules.php"><?=string_ucwords(system_showText(LANG_SITEMGR_SETTINGS_MANAGE_MODULES))?></a></li>
				<? } ?>
				</ul>

				<p class="subt"><?=system_showText(LANG_SITEMGR_NAVBAR_GOOGLESETTINGS);?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/googleprefs/googlemaps.php"><?=system_showText(LANG_SITEMGR_GOOGLEMAPS);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/googleprefs/googleads.php"><?=system_showText(LANG_SITEMGR_GOOGLEADS);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/googleprefs/googleanalytics.php"><?=system_showText(LANG_SITEMGR_GOOGLEANALYTICS);?> </a></li>
				</ul>

				<p class="subt"><?=system_showText(LANG_SITEMGR_PAYMENTSETTINGS);?> </p>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/pricing.php"><?=system_showText(LANG_SITEMGR_SETTINGS_PRICING);?> </a></li>
				<? if (PAYMENTSYSTEM_FEATURE == "on") { ?>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/paymentgateway.php"><?=system_showText(LANG_SITEMGR_SETTINGS_PAYMENT_PAYMENTGATEWAY);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/invoice.php"><?=system_showText(LANG_SITEMGR_INVOICE_INVOICEINFORMATION);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/prefs/tax.php"><?=system_showText(LANG_SITEMGR_SETTINGS_TAX);?></a></li>
				<? } ?>
				</ul>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_LOCATIONS)) {
				$_locations = explode(",", EDIR_LOCATIONS);
				$firsLevel = $_locations[0]; ?>
				<li class="standardSubTitle">
					<a class="sitemapSection" href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/locations/location_<?=$firsLevel?>/index.php"><?=string_ucwords(system_showText(LANG_SITEMGR_NAVBAR_LOCATIONS));?></a>
				</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_IMPORTEXPORT)) { ?>
			<li class="standardSubTitle">
				<div class="sitemapSection">
					<?=system_showText(string_ucwords(LANG_SITEMGR_NAVBAR_DATA_MANAGEMENT));?>
				</div>
				<ul>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/import/"><?=system_showText(string_ucwords(LANG_SITEMGR_IMPORT));?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/import/importlog.php"><?=system_showText(LANG_SITEMGR_IMPORT_IMPORTLOG);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/import/settings.php"><?=system_showText(LANG_SITEMGR_DEFAULTSETTINGS);?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/export/"><?=ucfirst(system_showText(LANG_SITEMGR_EXPORT));?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/export/download.php"><?=ucfirst(system_showText(LANG_SITEMGR_EXPORT_DOWNLOAD));?> </a></li>
					<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/export/payment.php"><?=system_showText(LANG_SITEMGR_MENU_EXPORTPAYMENTRECORDS);?> </a></li>
				</ul>
			</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_LANGUAGECENTER)) { ?>
				<li class="standardSubTitle">
                    <div class="sitemapSection">
                        <?=system_showText(string_ucwords(LANG_SITEMGR_NAVBAR_LANGUAGECENTER));?>
                    </div>
                    <ul>
                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/index.php"><?=system_showText(LANG_SITEMGR_LANGUAGE);?></a></li>
                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/edit.php"><?=system_showText(LANG_SITEMGR_LANGUAGES_EDIT);?></a></li>
                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/add.php"><?=system_showText(LANG_SITEMGR_LANGUAGES_ADD);?></a></li>
                        <li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/langcenter/flags.php"><?=system_showText(LANG_SITEMGR_LANGUAGES_CHANGE);?></a></li>
                    </ul>
				</li>
		<? } ?>

		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_PACKAGES)) { ?>
		<li class="standardSubTitle">
			<div class="sitemapSection">
				<?=system_showText(string_ucwords(LANG_SITEMGR_PACKAGE_PLURAL));?>
			</div>
			<ul>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/package/index.php"><?=system_showText(LANG_SITEMGR_MANAGE);?></a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/package/package.php"><?=system_showText(LANG_SITEMGR_ADD);?></a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/package/search.php"><?=system_showText(LANG_SITEMGR_SEARCH);?></a></li>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/package/reports.php"><?=system_showText(LANG_SITEMGR_NAVBAR_REPORTS);?></a></li>
			</ul>
		</li>
		<? } ?>
		<? if (permission_hasSMPermSection(SITEMGR_PERMISSION_PLUGINS)) { ?>
		<li class="standardSubTitle">
			<div class="sitemapSection">
				<?=system_showText(string_ucwords(LANG_SITEMGR_PLUGINS));?>
			</div>
			<ul>
				<? if (SUGARCRM_FEATURE == "on") { ?>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/plugins/index.php?type=0"><?=system_showText(LANG_SITEMGR_NAVBAR_SUGARCRM);?></a></li>
				<? } ?>
				<li><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/plugins/index.php?type=1"><?=system_showText(LANG_SITEMGR_NAVBAR_WORDPRESS);?></a></li>
			</ul>
		</li>
		<? } ?>

	</ul>
	</div>
</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>