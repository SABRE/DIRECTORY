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
	# * FILE: /twitter.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("./conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSessionFront();

	if (!$_SERVER["HTTP_REFERER"]) header("Location: ".DEFAULT_URL);

	# ----------------------------------------------------------------------------------------------------
	# TWITTER
	# ----------------------------------------------------------------------------------------------------
	$accObj = new Account(sess_getAccountIdFromSession());
	if ($accObj->getString("is_sponsor") == "y") {
		$urlRedirect = DEFAULT_URL."/".MEMBERS_ALIAS."/account/account.php";
	} else {
		$urlRedirect = DEFAULT_URL."/".SOCIALNETWORK_FEATURE_NAME."/edit.php";
	}

	if (isset($_GET['signoffTwitter'])){
		setcookie("oauth_token", '', time()-100);
		setcookie("oauth_token_secret", '', time()-100);
		unset($_COOKIE['oauth_token']);
		unset($_COOKIE['oauth_token_secret']);
		$profileObj = new Profile(sess_getAccountIdFromSession());
		$profileObj->setNumber('tw_post',0);
		$profileObj->setString("tw_oauth_token", "");
		$profileObj->setString("tw_oauth_token_secret", "");
		$profileObj->setString("tw_screen_name", "");
		$profileObj->setString("twitter_account", "");
		$profileObj->Save();
		$twpost_checked='';
		header('Location: '.$urlRedirect."?signofftwitter=success");
		exit;
	}

	// REFRESH PROFILE TWITTER TOKEN
	if (isset($_GET['oauth_token'])){
		setting_get("foreignaccount_twitter_apikey", $foreignaccount_twitter_apikey);
		setting_get("foreignaccount_twitter_apisecret", $foreignaccount_twitter_apisecret);

		$twitterObj = new EpiTwitter($foreignaccount_twitter_apikey, $foreignaccount_twitter_apisecret);
		$twitterObj->setToken($_GET['oauth_token']);

		$token = $twitterObj->getAccessToken();
		$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);

		$db = db_getDBObject(DEFAULT_DB, true);
		$sqlCheck = "SELECT COUNT(`account_id`) AS `total` FROM `Profile` WHERE `tw_screen_name` = ".db_formatString($token->screen_name);
		$resCheck = $db->Query($sqlCheck);
		$rowCheck = mysql_fetch_assoc($resCheck);

		if ($rowCheck["total"] == 0) {
			$profileObj = new Profile(sess_getAccountIdFromSession());
			$profileObj->setString("tw_oauth_token", $token->oauth_token);
			$profileObj->setString("tw_oauth_token_secret", $token->oauth_token_secret);
			$profileObj->setString("tw_screen_name",$token->screen_name);
			$profileObj->setString("twitter_account",$token->screen_name);
			$profileObj->setNumber('tw_post', 1);
			$profileObj->Save();

			header('Location: '.$urlRedirect."?twitter=success");
			exit;
		} else {
			header('Location: '.$urlRedirect."?twitter=alreadyused");
			exit;
		}

	}  else if(isset($_GET['denied']))   {
		// user denied access
		header('Location: '.$urlRedirect."?twitter=fail");
		exit;
	}
?>