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
	# * FILE: /includes/tables/table_content_submenu.php
	# ----------------------------------------------------------------------------------------------------

?>

<div class="submenu">
	<ul>
		<li id="privateMenu_home"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/index.php"><?=system_showText(LANG_SITEMGR_MENU_GENERAL)?></a></li>
		<li id="privateMenu_advertisement"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/advertisement.php"><?=system_showText(LANG_SITEMGR_MENU_ADVERTISEMENT)?></a></li>
		<li id="privateMenu_member"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/member.php"><?=string_ucwords(system_showText(LANG_SITEMGR_MEMBER))?></a></li>
		<li id="privateMenu_custom"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/client.php"><?=system_showText(LANG_SITEMGR_MENU_CUSTOM)?></a></li>
		<li id="privateMenu_listing"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/listing.php"><?=string_ucwords(system_showText(LANG_SITEMGR_LISTING))?></a></li>
		<? if (PROMOTION_FEATURE == "on" && CUSTOM_PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on") { ?>
            <li id="privateMenu_promotion"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/deal.php"><?=string_ucwords(system_showText(LANG_SITEMGR_PROMOTION));?></a></li>
        <? } ?>
        <? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
			<li id="privateMenu_event"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/event.php"><?=string_ucwords(system_showText(LANG_SITEMGR_EVENT))?></a></li>
		<? } ?>
		<? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
			<li id="privateMenu_classified"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/classified.php"><?=string_ucwords(system_showText(LANG_SITEMGR_CLASSIFIED))?></a></li>
		<? } ?>
		<? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
			<li id="privateMenu_article"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/article.php"><?=string_ucwords(system_showText(LANG_SITEMGR_ARTICLE))?></a></li>
		<? } ?>
        <? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
			<li id="privateMenu_blog"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/blog.php"><?=string_ucwords(system_showText(LANG_SITEMGR_BLOG))?></a></li>
		<? } ?>    
		<li id="privateMenu_editor"><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/htmleditor.php"><?=string_ucwords(system_showText(LANG_SITEMGR_SETTINGS_HTMLEDITOR))?></a></li>
		<li id="privateMenu_slider">
			<a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/content_slider.php">
				<?=string_ucwords(system_showText(LANG_SITEMGR_NAVBAR_SLIDER))?>
			</a>
		</li>
        <li id="privateMenu_navigation">
            <a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/navigation.php">
				<?=string_ucwords(system_showText(LANG_SITEMGR_SETTINGS_NAVIGATION))?>
			</a>
        </li>
	</ul>
</div>

<br clear="all" style="height:0; line-height:0">

<?
$openPMhome = string_strpos($_SERVER["PHP_SELF"], "index");
$openPMsiteheader = string_strpos($_SERVER["PHP_SELF"], "content_header");
$openPMsitefooter = string_strpos($_SERVER["PHP_SELF"], "content_footer");
$openPMnoimage = string_strpos($_SERVER["PHP_SELF"], "noimage");
$openPMicon = string_strpos($_SERVER["PHP_SELF"], "icon");
$openPMadvertisement = string_strpos($_SERVER["PHP_SELF"], "advertisement");
$openPMmember = string_strpos($_SERVER["PHP_SELF"], "member");
$openPMcustom = (string_strpos($_SERVER["PHP_SELF"], "client")||string_strpos($_SERVER["PHP_SELF"], "custom"));
$openPMlisting = string_strpos($_SERVER["PHP_SELF"], LISTING_FEATURE_FOLDER);
$openPMpromotion = string_strpos($_SERVER["PHP_SELF"], PROMOTION_FEATURE_FOLDER);
$openPMevent = string_strpos($_SERVER["PHP_SELF"], EVENT_FEATURE_FOLDER);
$openPMclassified = string_strpos($_SERVER["PHP_SELF"], CLASSIFIED_FEATURE_FOLDER);
$openPMarticle = string_strpos($_SERVER["PHP_SELF"], ARTICLE_FEATURE_FOLDER);
$openPMblog = string_strpos($_SERVER["PHP_SELF"], BLOG_FEATURE_FOLDER);
$openPMeditor = string_strpos($_SERVER["PHP_SELF"], "htmleditor");
$openPMslider = string_strpos($_SERVER["PHP_SELF"], "slider");
$openPMnavigation = string_strpos($_SERVER["PHP_SELF"], "navigation");
?>

<? if ($openPMhome || $openPMsiteheader || $openPMsitefooter || $openPMnoimage || $openPMicon) { ?> <script type="text/javascript"> addClass('home') </script><? } ?>
<? if ($openPMsiteheader) { ?> <script type="text/javascript"> addClass('siteheader') </script><? } ?>
<? if ($openPMsitefooter) { ?> <script type="text/javascript"> addClass('sitefooter') </script><? } ?>
<? if ($openPMnoimage) { ?> <script type="text/javascript"> addClass('noimage') </script><? } ?>
<? if ($openPMadvertisement) { ?> <script type="text/javascript"> addClass('advertisement') </script><? } ?>
<? if ($openPMmember) { ?> <script type="text/javascript"> addClass('member') </script><? } ?>
<? if ($openPMcustom) { ?> <script type="text/javascript"> addClass('custom') </script><? } ?>
<? if ($openPMlisting) { ?> <script type="text/javascript"> addClass('listing') </script><? } ?>
<? if ($openPMpromotion) { ?> <script type="text/javascript"> addClass('promotion') </script><? } ?>
<? if ($openPMevent) { ?> <script type="text/javascript"> addClass('event') </script><? } ?>
<? if ($openPMclassified) { ?> <script type="text/javascript"> addClass('classified') </script><? } ?>
<? if ($openPMarticle) { ?> <script type="text/javascript"> addClass('article') </script><? } ?>
<? if ($openPMblog) { ?> <script type="text/javascript"> addClass('blog') </script><? } ?>
<? if ($openPMeditor) { ?> <script type="text/javascript"> addClass('editor') </script><? } ?>
<? if ($openPMslider) { ?> <script type="text/javascript"> addClass('slider') </script><? } ?>
<? if ($openPMnavigation) { ?> <script type="text/javascript"> addClass('navigation') </script><? } ?>