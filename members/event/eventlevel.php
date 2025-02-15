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
	# * FILE: /members/event/eventlevel.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();

	extract($_GET);
	extract($_POST);

	$url_redirect = "".DEFAULT_URL."/".MEMBERS_ALIAS."/".EVENT_FEATURE_FOLDER;
	$url_base = "".DEFAULT_URL."/".MEMBERS_ALIAS."";
	$members = 1;

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	if ($id) {
		$event = new Event($id);
		if (sess_getAccountIdFromSession() != $event->getNumber("account_id")) {
			header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".EVENT_FEATURE_FOLDER."/index.php?screen=$screen&letter=$letter");
			exit;
		}
		$event->extract();
	}

	$levelObj = new EventLevel();
	if ($level) {
		$levelArray[$levelObj->getLevel($level)] = $level;
	} else {
		$levelArray[$levelObj->getLevel($levelObj->getDefaultLevel())] = $levelObj->getDefaultLevel();
	}

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (($id) && ($event)) {
			if ($_POST["level"] && ($_POST["level"] != $event->getNumber("level"))) {
				$status = new ItemStatus();
				$event->setString("status", $status->getDefaultStatus());
				$event->setDate("renewal_date", "00/00/0000");
			}
			$event->setString("level", $_POST['level']);
			$event->Save();
			header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".EVENT_FEATURE_FOLDER."/index.php?screen=$screen&letter=$letter");
			exit;
		} else {
			/*
			 * Check if exists package
			 */
			$packageObj = new Package();
			$array_package_offers = $packageObj->getPackagesByDomainID(SELECTED_DOMAIN_ID, "event", $_POST["level"]);
			if ((is_array($array_package_offers)) and (count($array_package_offers)>0) and $array_package_offers[0]) {
				header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".EVENT_FEATURE_FOLDER."/order_package.php?level=".$_POST["level"]);
			}else{
				header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/".EVENT_FEATURE_FOLDER."/event.php?level=".$_POST["level"]);
			}
			exit;
		}
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

        <h2><?=system_showText(LANG_EVENT_LEVEL)?> <? if (($event) && ($event->getString("title"))) echo "- ".$event->getString("title"); ?></h2>

        <form name="eventlevel" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$id?>" />
            <input type="hidden" name="screen" value="<?=$screen?>" />
            <input type="hidden" name="letter" value="<?=$letter?>" />
            <? include(INCLUDES_DIR."/forms/form_eventlevel.php"); ?>
        </form>

        <form action="<?=DEFAULT_URL?>/<?=MEMBERS_ALIAS?>/<?=EVENT_FEATURE_FOLDER;?>/index.php" method="post">

            <input type="hidden" name="screen" value="<?=$screen?>" />
            <input type="hidden" name="letter" value="<?=$letter?>" />

            <div class="baseButtons">

                <p class="standardButton">
                    <button type="button" onclick="document.eventlevel.submit();"><?=system_showText(LANG_BUTTON_SUBMIT)?></button>
                <p class="standardButton">
                    <button type="submit"><?=system_showText(LANG_BUTTON_CANCEL)?></button>
                </p>

            </div>

        </form>
    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>