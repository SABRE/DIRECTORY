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
	# * FILE: /sitemgr/blogcategs/disabled.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BLOG_FEATURE != "on") {
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
		exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	if (!permission_hasSMPermSection(SITEMGR_PERMISSION_BLOG)){
        header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
        exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);

	//increases frequently actions
	if (!isset($message)) system_setFreqActions('blogcateg_disabled', 'BLOG_FEATURE');

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
			<h1><?=string_ucwords(system_showText(LANG_SITEMGR_BLOG))?> - <?=system_showText(LANG_SITEMGR_DISABLED_CATEGORIES)?> </h1>
		</div>
	</div>

	<div id="content-content">

		<div class="default-margin">

			<? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
			<? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
			<? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>
			<? if (CUSTOM_BLOG_FEATURE != "on") { ?>
				<p class="informationMessage">
					<?=system_showText(LANG_SITEMGR_MODULE_UNAVAILABLE)?>
				</p>
			<? } else { ?>
			<? include(INCLUDES_DIR."/tables/table_category_submenu.php"); ?>
				<br />

				<script language="javascript" type="text/javascript" src="<?=DEFAULT_URL?>/scripts/categoriesdisabledtree.js"></script>
				<div id="blog_disabledCategoryTree">
					<table cellpadding="0" cellspacing="0" border="0" class="standard-table">
						<tr>
							<th colspan="2" class="standard-tabletitle">
								<?=system_showText(LANG_SITEMGR_DISABLED_CATEGORIES)?>
							</th>
						</tr>
						<input type="hidden" name="return_categories" value="" />
						<tr>
							<td colspan="2" class="treeView">
								<ul id="blog_categorytree_id_0" class="categoryTreeview">&nbsp;</ul>
							</td>
						</tr>
					</table>
				</div>
			<? } ?>
		</div>
	</div>

	<div id="bottom-content">&nbsp;</div>

</div>

<script type="text/javascript">	
    $(document).ready(function(){
        showDisabledCategories('blog_', 'BlogCategory','<?=DEFAULT_URL;?>','<?=SELECTED_DOMAIN_ID;?>');
    });
</script>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>