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
	# * FILE: /members/help.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();
	$acctId = sess_getAccountIdFromSession();
	$url_base = DEFAULT_URL."/".MEMBERS_ALIAS."";

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	extract($_GET);
	extract($_POST);

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	$contactObj = new Contact($acctId);
	$name       = $contactObj->getString("first_name")." ".$contactObj->getString("last_name");
	$email      = $contactObj->getString("email");

	if ($_POST) {

		extract($_POST);
		$validate_help = validate_form("help", $_POST, $message_help);

		if ($validate_help) {

			$text = $_POST["text"];
			$text = str_replace("\r\n", "\n", $text);
			$text = str_replace("\n", "\r\n", $text);
			$msg = "
				<html>
					<head>
						<style>
							.email_style_settings {
								font-size: 11px;
								font-family: Verdana, Arial, Sans-Serif;
								color: #000;
							}
						</style>
					</head>
					<body>
						<div class=\"email_style_settings\">From: ".$name."<br /><br />"."E-mail: ".$email."<br/ ><br />Message: <br /><br />".nl2br($text)."</div>
					</body>
				</html>";

			// sending e-mail to site manager
			setting_get("sitemgr_email", $sitemgr_email);
			$sitemgr_emails = explode(",", $sitemgr_email);
			setting_get("sitemgr_support_email", $sitemgr_support_email);
			$sitemgr_support_emails = explode(",", $sitemgr_support_email);
			$errors = array();
			if ($sitemgr_support_emails[0]) {
				foreach ($sitemgr_support_emails as $sitemgr_support_email) {
					system_mail($sitemgr_support_email, "[".EDIRECTORY_TITLE."] Help Request", stripslashes($msg), "$name <$email>", "text/html", '', '', $error, '', '', $email);
					if ($error) $errors[] = system_showText(LANG_CONTACTMSGFAILED).($error ? '<br />'.$error : '');
				}
			} elseif ($sitemgr_emails[0]) {
				foreach ($sitemgr_emails as $sitemgr_email) {
					system_mail($sitemgr_email, "[".EDIRECTORY_TITLE."] Help Request", stripslashes($msg), "$name <$email>", "text/html", '', '', $error, '', '', $email);
					if ($error) $errors[] = system_showText(LANG_CONTACTMSGFAILED).($error ? '<br />'.$error : '');
				}
			}
			
			if (!count($errors)) {
				$success = true;
				$message_help = system_showText(LANG_CONTACTMSGSUCCESS);
			} else {
				$success = false;
				$message_help = implode("<br />", $errors);
			}

		}

		// removing slashes added if required
		$_POST = format_magicQuotes($_POST);
		$_GET  = format_magicQuotes($_GET);
		extract($_POST);
		extract($_GET);

	}

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <div class="content">

        <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
        <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
        <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

        <h2><?=system_showText(LANG_LABEL_HELP)?></h2>

        <?
                $contentObj = new Content();
                $content = $contentObj->retrieveContentByType("Sponsor Help");
                if ($content) {
                    echo "<blockquote>";
                        echo "<div class=\"dynamicContent\">".$content."</div>";
                    echo "</blockquote>";
                }
        ?>

        <form name="help" method="post" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>">

            <? include(INCLUDES_DIR."/forms/form_help.php"); ?>

            <div class="baseButtons">
                <p class="standardButton">
                    <button type="submit"><?=system_showText(LANG_BUTTON_SEND)?></button>
                </p>
            </div>

        </form>

        <?
        $contentObj = new Content();
        $content = $contentObj->retrieveContentByType("Sponsor Help Bottom");
        if ($content) {
            echo "<blockquote>";
                echo "<div class=\"dynamicContent\">".$content."</div>";
            echo "</blockquote>";
        }
        ?>

    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>
