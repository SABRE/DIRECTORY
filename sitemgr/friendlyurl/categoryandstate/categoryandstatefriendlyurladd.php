<?
        # ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../../conf/loadconfig.inc.php");
	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	
	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/categoryandstatefriendlyurl.php";
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

	include(EDIRECTORY_ROOT."/includes/code/categoryandstatefriendlyurl.php");

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
            <h1 class="highlight">Add Friendly URL (Category & state combination)</h1>
        </div>
    </div>
    <div id="content-content">
        <div class="default-margin">
            <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
            <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
            <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
            <? include(INCLUDES_DIR."/tables/table_categoryandstatefriendlyurl_submenu.php"); ?>
            <div class="baseForm">
                <form name="categoryandstateurladd" id="categoryandstateurladd" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" >
                    <?=system_getFormInputSearchParams((($_POST)?($_POST):($_GET)));?>
                    <? include(INCLUDES_DIR."/forms/form_categoryandstatefriendlyurl.php"); ?>
                    <button type="submit" value="Submit" class="input-button-form"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>
                    <button type="button" name="cancel" value="Cancel" class="input-button-form" onclick="document.getElementById
('formcategoryandstateaddcancel').submit();"><?=system_showText(LANG_SITEMGR_CANCEL)?></button>
                </form>
                <form  id="formcategoryandstateaddcancel" action="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/friendlyurl/categoryandstate/categoryandstatefriendlyurl.php" method="post" style="margin: 0;">
                    <?=system_getFormInputSearchParams((($_POST)?($_POST):($_GET)));?>
                </form>
            </div>
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