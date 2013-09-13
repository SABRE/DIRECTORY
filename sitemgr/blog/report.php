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
	# * FILE: /sitemgr/blog/report.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BLOG_FEATURE != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();
	permission_hasSMPerm();

	$url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/".BLOG_FEATURE_FOLDER;
	$url_base = DEFAULT_URL."/".SITEMGR_ALIAS;
	$sitemgr = 1;

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_POST);
	extract($_GET);

	$url_search_params = system_getURLSearchParams((($_POST)?($_POST):($_GET)));

    # ----------------------------------------------------------------------------------------------------
    # OBJECTS
    # ----------------------------------------------------------------------------------------------------
	if ($id) {
        $post = new Post($id);
	} else {
        header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/index.php");
        exit;
	}

    $status = new ItemStatus();
    $statusName = $status->getStatus($post->getString('status'));

    # ----------------------------------------------------------------------------------------------------
    # REPORT DATA
    # ----------------------------------------------------------------------------------------------------
    $reports = retrievePostReport($id);

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
            <h1 class="highlight" title="<?=$post->getString("title");?>"><?=system_showText(LANG_BLOG)?> <?=system_showText(LANG_SITEMGR_REPORT_TRAFFICREPORT)?> - <?=$post->getString('title', true, 35); ?></h1>
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

            <? include(INCLUDES_DIR."/tables/table_blog_submenu.php"); ?>

            <? if ($reports) { ?>
                <? include(INCLUDES_DIR."/tables/table_blog_reports.php"); ?>
            <? } else { ?>
                <p class="informationMessage">
                    <?=system_showText(LANG_SITEMGR_BLOG_NOREPORT)?>
                </p>
            <? }
            }?>
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