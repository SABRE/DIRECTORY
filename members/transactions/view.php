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
	# * FILE: /members/transactions/view.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (PAYMENT_FEATURE != "on") { exit; }
	if ((CREDITCARDPAYMENT_FEATURE != "on") && (MANUALPAYMENT_FEATURE != "on")) { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();
	$acctId = sess_getAccountIdFromSession();

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	if ($cart_id = $_GET['id']) {

		$url_redirect = "".DEFAULT_URL."/".MEMBERS_ALIAS."/transactions";
		$url_base = "".DEFAULT_URL."/".MEMBERS_ALIAS."";

		include(INCLUDES_DIR."/code/transaction.php");

	} else {
		header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/transactions/index.php");
		exit;
	}

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <div class="content">

        <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
        <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
        <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

        <h2><?=system_showText(LANG_TRANSACTION_DETAIL);?></h2>

        <? if (is_array($transaction) && (is_array($transaction_listing_log) || is_array($transaction_event_log) || is_array($transaction_banner_log) || is_array($transaction_classified_log) || is_array($transaction_article_log) || is_array($transaction_custominvoice_log))) { ?>

            <? include_once(EDIRECTORY_ROOT."/includes/views/view_transaction_detail.php"); ?>

        <? } else { ?>

            <p class="informationMessage"><?=system_showText(LANG_TRANSACTION_NOT_FOUND_FOR_ACCOUNT)?><p>

        <? } ?>

    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>
