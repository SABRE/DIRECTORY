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
	# * FILE: /members/resetpassword.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSession();

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		$accountObj = new Account(sess_getAccountIdFromSession());
		$member_username = $accountObj->getString("username");

		if ($_POST["password"]) {
			if (validate_MEMBERS_account($_POST, $message, sess_getAccountIdFromSession())) {
				$accountObj->setString("password", $_POST["password"]);
				$accountObj->updatePassword();
				$success_message = system_showText(LANG_MSG_PASSWORD_SUCCESSFULLY_UPDATED);

				if ($accountObj->getString("is_sponsor") == "y") {
					$urlRedirect = DEFAULT_URL."/".MEMBERS_ALIAS."/account/account.php";
				} else {
					$urlRedirect = SOCIALNETWORK_URL."/edit.php";
				}
			}
		} else {
			$message = system_showText(LANG_MSG_PASSWORD_IS_REQUIRED);
		}

	}

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	if ($_GET["key"]) {

		$forgotPasswordObj = new forgotPassword($_GET["key"]);

		if ($forgotPasswordObj->getString("unique_key") && ($forgotPasswordObj->getString("section") == "members")) {

			$accountObj = new Account($forgotPasswordObj->getString("account_id"));
			$member_username = $accountObj->getString("username");

			$forgotPasswordObj->Delete();

			if (!$member_username) {
				$error_message = system_showText(LANG_MSG_WRONG_ACCOUNT);
			}

		} else {
			$error_message = system_showText(LANG_MSG_WRONG_KEY);
		}

	} else {
		$error_message = system_showText(LANG_MSG_WRONG_KEY);
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

		<h2><?=system_showText(LANG_LABEL_RESET_PASSWORD);?></h2>

		<? if ($success_message) { ?>
			<p class="successMessage">
			<?=$success_message;?>
			<br />
			<a href="<?=$urlRedirect;?>"><?=system_showText(LANG_BUTTON_MANAGE_ACCOUNT)?></a>
			</p>
		<? } elseif ($error_message && !$message) { ?>
			<p class="errorMessage"><?=$error_message;?>
            <br /><br />
            <a href="<?=DEFAULT_URL?>/<?=MEMBERS_ALIAS?>/forgot.php"><?=system_showText(LANG_LABEL_FORGOTPASSWORD);?></a>
            </p> 
		<? } else { ?>

			<? if ($message) { ?>
				<p class="errorMessage"><?=$message;?></p>
			<? } ?>

			<form name="formResetPassword" method="post" action="<?=system_getFormAction($_SERVER["PHP_SELF"])?>">

				<script language="javascript" type="text/javascript" src="<?=DEFAULT_URL?>/scripts/checkpasswordstrength.js"></script>

				<table border="0" cellpadding="2" cellspacing="0" class="standard-table">
					<tr>
						<th><?=system_showText(LANG_LABEL_USERNAME)?>:</th>
						<td><?=$member_username;?></td>
					</tr>
					<tr>
						<th><?=system_showText(LANG_LABEL_PASSWORD)?>:</th>
						<td>
							<input type="password" name="password" maxlength="<?=PASSWORD_MAX_LEN?>" class="input-form-account" <?=(PASSWORD_STRENGTH == "on" ? "onkeyup=\"checkPasswordStrength(this.value, '".EDIRECTORY_FOLDER."')\"" : "")?> />
							<? if (PASSWORD_STRENGTH == "on") { ?>
							<div class="checkPasswordStrength">
								<span><?=system_showText(LANG_LABEL_PASSWORDSTRENGTH);?>:</span>
								<div id="checkPasswordStrength" class="strengthNoPassword">&nbsp;</div>
							</div>
							<? } ?>
							<span><?=system_showText(LANG_MSG_PASSWORD_MUST_BE_BETWEEN)?> <?=PASSWORD_MIN_LEN?> <?=system_showText(LANG_AND)?> <?=PASSWORD_MAX_LEN?> <?=system_showText(LANG_MSG_CHARACTERS_WITH_NO_SPACES)?></span>
						</td>
					</tr>
					<tr>
						<th><?=system_showText(LANG_LABEL_RETYPE_PASSWORD)?>:</th>
						<td><input type="password" name="retype_password" class="input-form-account" /></td>
					</tr>
				</table>

				<table border="0" align="center" cellpadding="5" cellspacing="5" style="margin: 0 auto 0 auto;">
					<tr>
						<td>
							<p class="standardButton">
								<button type="submit" value="<?=system_showText(LANG_BUTTON_SUBMIT);?>"><?=system_showText(LANG_BUTTON_SUBMIT);?></button>
							</p>
						</td>
					</tr>
				</table>

			</form>

		<? } ?>

	</div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(MEMBERS_EDIRECTORY_ROOT."/layout/footer.php");
?>
