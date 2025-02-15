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
	# * FILE: /sitemgr/eventcategs/featured.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");
	
	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (EVENT_FEATURE != "on") {
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
		exit;
	}
	
	if (FEATURED_CATEGORY == "on") {
		setting_get("featuredcategory", $featuredcategory);
		if ($featuredcategory == "on") {
			setting_get("event_featuredcategory", $event_featuredcategory);
			if (!$event_featuredcategory) {
				exit;
			}
		} else exit;
	} else {
		exit;
	}

    # ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	if (!permission_hasSMPermSection(SITEMGR_PERMISSION_EVENTS)){
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
		exit;
	}

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/eventcategs/featured.php";
    $url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
    $sitemgr = 1;
    $featuredEventCategory = 1;
	
	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

    extract($_GET);
    extract($_POST);

	if (!isset($pop_categories)) system_setFreqActions('event_featuredcateg','EVENT_FEATURE');

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['save'] == 1) {
		if ($featured_status == 'n' && count($featureds)) {
			$info_message = LANG_SITEMGR_FEATUREDCATEGORIES_INFO;
		}
		if ($non_feat_categories!="")
			system_changeFeaturedAtribute ("EventCategory", "$non_feat_categories", "n" );
		if ($feat_categories!="")
			system_changeFeaturedAtribute ("EventCategory", "$feat_categories", "y" );
        $message = ucfirst(LANG_SITEMGR_FEATUREDCATEGORIES_WERESUCCESSUPDATED);
    }

    # ----------------------------------------------------------------------------------------------------
	# PAGE BROWSING
	# ----------------------------------------------------------------------------------------------------
    $fields = "id, featured, enabled, `title`";
    $letterfield = "`title`";

	$pageObj   = new pageBrowsing("EventCategory", $screen, RESULTS_PER_PAGE, "title, id", $letterfield, $letter, "category_id = ".db_formatNumber($category_id), $fields);
    $categories = $pageObj->retrievePage("array");

    $paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/eventcategs/featured.php";

    // Letters Menu
    $letters = $pageObj->getString("letters");
    foreach ($letters as $each_letter) {
        if ($each_letter == "#") {
           $letters_menu .= "<a href=\"$paging_url?letter=no\" ".(($letter == "no") ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
        } else {
            $letters_menu .= "<a href=\"$paging_url?letter=".$each_letter."\" ".(($each_letter == $letter) ? "style=\"color:#EF413D\"" : "" ).">".string_strtoupper($each_letter)."</a>";
        }
    }

    # PAGES DROP DOWN ----------------------------------------------------------------------------------------------
    $pagesDropDown = $pageObj->getPagesDropDown($_GET, $paging_url, $screen, system_showText(LANG_SITEMGR_PAGING_GOTOPAGE)." ", "this.form.submit();");

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
		<h1><?=string_ucwords(system_showText(LANG_SITEMGR_EVENT_PLURAL))?> - <?=string_ucwords(system_showText(LANG_SITEMGR_FEATUREDCATEGORY_PLURAL))?> </h1>
	</div>
</div>
<div id="content-content">
	<div class="default-margin">

		<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
		<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
		<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
		<?if (CUSTOM_EVENT_FEATURE != "on"){ ?>
				<p class="informationMessage">
					<?=system_showText(LANG_SITEMGR_MODULE_UNAVAILABLE)?>
				</p>
			<? }else { ?>
		<? include(INCLUDES_DIR."/tables/table_category_submenu.php");?>
        <br />
        <? include(INCLUDES_DIR."/tables/table_paging.php"); ?>
		<? if (!$category_id) { ?>
			<div class="tip-base">
				<p style="text-align: justify;">
					<a href="<?=DEFAULT_URL;?>/<?=SITEMGR_ALIAS?>/faq/faq.php?keyword=<?=urlencode("featured");?>" target="_blank"><?=system_showText(LANG_SITEMGR_FEATUREDCATEGORIES_TIP)?></a>
				</p>
			</div>
		<? } ?>
		<?
		if ($info_message) {
			echo "<p class=\"informationMessage\">".$info_message."</p>";
		}
        if ($message) {
         echo "<p class=\"successMessage\">".$message."</p>";
        } 
        ?>
		<? include_once(EDIRECTORY_ROOT."/includes/forms/form_category_featured.php");?>
		<br />
		<? } ?>
	</div>
</div>
<div id="bottom-content">
	&nbsp;
</div>
</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>