<? 
        # ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../../conf/loadconfig.inc.php");

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
        //$manageOrder = system_getManageOrderBy($_POST["order_by"] ? $_POST["order_by"] : $_GET["order_by"], "Listing", LISTING_SCALABILITY_OPTIMIZATION, $fields);

	extract($_GET);
	extract($_POST);
        
	//increases frequently actions
	//if (!isset($message)) system_setFreqActions('listing_manage','listing');
	
	// Page Browsing /////////////////////////////////////////
	unset($pageObj);
        
        $fields = '*';
        
	$pageObj  = new pageBrowsing("category_state_friendlyurl", $screen, RESULTS_PER_PAGE, ($_GET["newest"] ? "id DESC" : $manageOrder), "friendly_url", $letter, false, $fields);

	$categoryStateUrlListing = $pageObj->retrievePage();
        
        $paging_url = DEFAULT_URL."/".SITEMGR_ALIAS."/categoryandstatefriendlyurl.php";

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
			<h1>Friendly URL (Category & state combination)<? if (DEMO_MODE) { ?> <span>(Optional Module)</span> <? } ?></h1>
		</div>
	</div>
        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

               <? include(INCLUDES_DIR."/tables/table_categoryandstatefriendlyurl_submenu.php"); ?>
                <? if ($categoryStateUrlListing) { ?>
                    <? include(INCLUDES_DIR."/tables/table_listing.php"); ?>
                <? } else {?>
                    <? include(INCLUDES_DIR."/tables/table_paging.php"); ?>
                    <p class="informationMessage">
                        <? echo "No friendly URL in the system with the combination of category and state.";?>
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