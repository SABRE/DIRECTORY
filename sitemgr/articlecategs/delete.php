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
	# * FILE: /sitemgr/articlecategs/delete.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (ARTICLE_FEATURE != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	if (!permission_hasSMPermSection(SITEMGR_PERMISSION_ARTICLES)){
        header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
        exit;
	}

	$url_redirect = "".DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs";
	$url_base = "".DEFAULT_URL."/".SITEMGR_ALIAS."";
	$sitemgr = 1;

	extract($_GET);
	extract($_POST);

	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	if ($id) {
		$fields = "`id`, `title`";
		$category = db_getFromDB("articlecategory", "id", $id, 1, "", "object", SELECTED_DOMAIN_ID, false, $fields);

		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
		$sql = "SELECT COUNT(0) as row_amount FROM Article WHERE
		cat_1_id = '$id'
		OR cat_2_id = '$id'
		OR cat_3_id = '$id'
		OR cat_4_id = '$id'
		OR cat_5_id = '$id' ";

		$row = mysql_fetch_assoc($db->query($sql));
		$confirmDelItems = false;
		if ($row["row_amount"]>0) $confirmDelItems = true;
	} else {
        $message = 0;
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&category_id=".$category_id."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "")."");
		exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$category = new ArticleCategory($_POST['id']);
		$category->delete();
        $message = 1;
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/articlecategs/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&category_id=".$category_id."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "")."");
		exit;
	}

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
            <h1><?=system_showText(LANG_SITEMGR_CATEGORY_DELETECATEGORY)?></h1>
        </div>
    </div>
    <div id="content-content">
        <div class="default-margin">

            <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
            <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
            <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
            <?if (CUSTOM_ARTICLE_FEATURE != "on"){ ?>
                <p class="informationMessage">
                    <?=system_showText(LANG_SITEMGR_MODULE_UNAVAILABLE)?>
                </p>
            <? }else { ?>
                <div class="baseForm">

                <form name="article" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post">

                    <input type="hidden" name="id" value="<?=$id?>" />
                    <input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>" />

                    <div class="header-form"><?=system_showText(LANG_SITEMGR_CATEGORY_DELETECATEGORY)?> - <?=$category->getString("title")?></div>
                    <p class="informationMessage"><?=system_showText(LANG_SITEMGR_CATEGORY_DELETEQUESTION)?> <?=($confirmDelItems? "<br />".system_showText(LANG_SITEMGR_CATEGORY_HAS).$row["row_amount"].($row["row_amount"] > 1 ? system_showText(LANG_SITEMGR_CATEGORY_ITEM_ASSOCIATED_PLURAL) : system_showText(LANG_SITEMGR_CATEGORY_ITEM_ASSOCIATED_SING) )."<a href=\"$url_base/".ARTICLE_FEATURE_FOLDER."/search.php?acct_search_field_name=search_account_id&search_category_id=$id&search_submit=Search\">".($row["row_amount"] > 1 ? system_showText(LANG_SITEMGR_CATEGORY_CLICKTOSEE_PLURAL) : system_showText(LANG_SITEMGR_CATEGORY_CLICKTOSEE_SING))."</a>".system_showText(LANG_SITEMGR_CATEGORY_BEFOREDELETE) : "")?></p>
                    <?=system_getFormInputSearchParams((($_POST)?($_POST):($_GET)));?>
                    <input type="hidden" name="letter" value="<?=$letter?>" />
                    <input type="hidden" name="screen" value="<?=$screen?>" />
                    <button type="submit" class="input-button-form"><?=system_showText(LANG_SITEMGR_SUBMIT)?></button>

                </form>
                <form action="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/articlecategs/<?=(($search_page) ? "search.php" : "index.php");?>" method="get">

                    <input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>" />
                    <?=system_getFormInputSearchParams((($_POST)?($_POST):($_GET)));?>
                    <input type="hidden" name="letter" value="<?=$letter?>" />
                    <input type="hidden" name="screen" value="<?=$screen?>" />
                    <button type="submit" class="input-button-form"><?=system_showText(LANG_SITEMGR_CANCEL)?></button>

                </form>

                </div>
            <? } ?>
        </div>
    </div>
    <div id="bottom-content">&nbsp;</div>
</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>