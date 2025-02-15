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
	# * FILE: /sitemgr/listing/index.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
        if (!permission_hasSMPermSection(SITEMGR_PERMISSION_SEOCENTER)) {
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
		exit;
	}
	
        $url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/categoryandstatefriendlyurl.php";
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));
        $manageOrder = system_getManageOrderBy($_POST["order_by"] ? $_POST["order_by"] : $_GET["order_by"], "Listing", LISTING_SCALABILITY_OPTIMIZATION, $fields);

	extract($_GET);
	extract($_POST);
        
	//increases frequently actions
	//if (!isset($message)) system_setFreqActions('listing_manage','listing');
	
	// Page Browsing /////////////////////////////////////////
	unset($pageObj);

	$pageObj  = new pageBrowsing("Categoryandstatefriendlyurl", $screen, RESULTS_PER_PAGE, ($_GET["newest"] ? "id DESC" : $manageOrder), "title", $letter, false, $fields);

	$listings = $pageObj->retrievePage();
	
	$paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/".LISTING_FEATURE_FOLDER."/index.php";

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
	# --------------------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------

	include(INCLUDES_DIR."/code/bulkupdate.php");

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
			<h1><?=string_ucwords(system_showText(LANG_SITEMGR_LISTING_PLURAL))?></h1>
		</div>
	</div>
    <div id="content-content">
        <div class="default-margin">

            <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
            <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
            <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

            <? //include(INCLUDES_DIR."/tables/table_listing_submenu.php"); ?>
            <? if ($listings) { ?>
                <? include(INCLUDES_DIR."/tables/table_listing.php"); ?>
            <? } else { ?>
                <? include(INCLUDES_DIR."/tables/table_paging.php"); ?>
                <p class="informationMessage">
                    <?=system_showText(LANG_SITEMGR_LISTING_NORECORDS)?>
                </p>
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