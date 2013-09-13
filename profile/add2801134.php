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
	# * FILE: /profile/add.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------

	include("../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# MAINTENANCE MODE
	# ----------------------------------------------------------------------------------------------------
	verify_maintenanceMode();

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSessionFront();

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	if (SOCIALNETWORK_FEATURE == "off") { exit; }

	if (sess_isAccountLogged()) {
		header("Location: ".SOCIALNETWORK_URL."/");
		exit;
	}

	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		$validate_account = validate_addAccount($_POST, $message_account);
		$validate_contact = validate_form("contact", $_POST, $message_contact);
		
		if ($message_account && $message_contact){
			$message_account .= "<br />";
		}

		if ($validate_account && $validate_contact) {
            $_POST['publish_contact'] = ($_POST['publish_contact']?'y':'n');
			$account = new Account($_POST);
			$account->Save();
			$contact = new Contact($_POST);
			$contact->setNumber("account_id", $account->getNumber("id"));
			$contact->Save();


			$profileObj = new Profile(sess_getAccountIdFromSession());
			$profileObj->setNumber("account_id", $account->getNumber("id"));
			if (!$profileObj->getString("nickname")) {
				$profileObj->setString("nickname", $_POST["first_name"]." ".$_POST["last_name"]);
			}
			$profileObj->Save();

			$accDomain = new Account_Domain($account->getNumber("id"), SELECTED_DOMAIN_ID);
			$accDomain->Save();
			$accDomain->saveOnDomain($account->getNumber("id"), $account, $contact, $profileObj);

			sess_registerAccountInSession($_POST["username"]);
			setcookie("username_members", $_POST['username'], time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");

			/*****************************************************
			*
			* E-mail notify
			*
			******************************************************/
			setting_get("sitemgr_email", $sitemgr_email);
			setting_get("sitemgr_account_email", $sitemgr_account_email);
			$sitemgr_account_emails = explode(",", $sitemgr_account_email);

			// sending e-mail to user //////////////////////////////////////////////////////////////////////////
			if ($emailNotificationObj = system_checkEmail(SYSTEM_NEW_PROFILE)) {
				$subject = $emailNotificationObj->getString("subject");
				$body = $emailNotificationObj->getString("body");
				$login_info = trim(system_showText(LANG_LABEL_USERNAME)).": ".$_POST["username"];
				$login_info .= ($emailNotificationObj->getString("content_type") == "text/html"? "<br />": "\n");
				$login_info .= trim(system_showText(LANG_LABEL_PASSWORD)).": ".$_POST["password"];
				$body = str_replace("ACCOUNT_LOGIN_INFORMATION",$login_info,$body);
				$body = system_replaceEmailVariables($body, $account->getNumber("id"), 'account');
				$subject = system_replaceEmailVariables($subject, $account->getNumber("id"), 'account');
				$body = html_entity_decode($body);
				$subject = html_entity_decode($subject);
				system_mail($contact->getString("email"), $subject, $body, EDIRECTORY_TITLE." <$sitemgr_email>", $emailNotificationObj->getString("content_type"), "", $emailNotificationObj->getString("bcc"), $error);
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////

			// site manager warning message /////////////////////////////////////
            $emailSubject = "[".EDIRECTORY_TITLE."] ".system_showText(LANG_NOTIFY_NEWACCOUNT);
            $sitemgr_msg = system_showText(LANG_LABEL_SITE_MANAGER).",<br /><br />
							".system_showText(LANG_NOTIFY_NEWACCOUNT_1)." ".EDIRECTORY_TITLE.".<br />
							".system_showText(LANG_NOTIFY_NEWACCOUNT_2)."<br /><br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_USERNAME).": </b>".$account->getString("username")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_FIRST_NAME).": </b>".$contact->getString("first_name")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_LAST_NAME).": </b>".$contact->getString("last_name")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_COMPANY).": </b>".$contact->getString("company")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_ADDRESS).": </b>".$contact->getString("address")." ".$contact->getString("address2")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_CITY).": </b>".$contact->getString("city")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_STATE).": </b>".$contact->getString("state")."<br />";
							$sitemgr_msg .= "<b>".string_ucwords(ZIPCODE_LABEL).": </b>".$contact->getString("zip")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_PHONE).": </b>".$contact->getString("phone")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_FAX).": </b>".$contact->getString("fax")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_LABEL_URL).": </b>".$contact->getString("url")."<br />";
							$sitemgr_msg .= "<b>".system_showText(LANG_IGREETERMS).": </b>".(($account->getString("agree_tou") ==1) ? system_showText(LANG_YES) : system_showText(LANG_NO))."<br />";
							$sitemgr_msg .="<br /><a href=\"".((SSL_ENABLED == "on" && FORCE_SITEMGR_SSL == "on") ? SECURE_URL : NON_SECURE_URL)."/".SITEMGR_ALIAS."/account/view.php?id=".$account->getNumber("id")."\" target=\"_blank\">".((SSL_ENABLED == "on" && FORCE_SITEMGR_SSL == "on") ? SECURE_URL : NON_SECURE_URL)."/".SITEMGR_ALIAS."/account/view.php?id=".$account->getNumber("id")."</a><br /><br />";
                            $sitemgr_msg .= EDIRECTORY_TITLE;
            
            system_notifySitemgr($sitemgr_account_emails, $emailSubject, $sitemgr_msg);

			header("Location: ".SOCIALNETWORK_URL."/");
			exit;

		} else {
			// removing slashes added if required
			$_POST = format_magicQuotes($_POST);
			$_GET  = format_magicQuotes($_GET);
			extract($_POST);
			extract($_GET);
		}

	}

	# ----------------------------------------------------------------------------------------------------
	# SITE CONTENT
	# ----------------------------------------------------------------------------------------------------
    $sitecontentSection = "Add Profile Page";
    $array_HeaderContent = front_getSiteContent($sitecontentSection);
    extract($array_HeaderContent);

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	$headertag_title = $headertagtitle;
	$headertag_description = $headertagdescription;
	$headertag_keywords = $headertagkeywords;
	$hide_search = true;
	include(system_getFrontendPath("header.php", "layout"));

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	require(EDIRECTORY_ROOT."/frontend/checkregbin.php");

	# ----------------------------------------------------------------------------------------------------
	# BODY
	# ----------------------------------------------------------------------------------------------------
	include(THEMEFILE_DIR."/".EDIR_THEME."/body/profile_add.php");
	
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(system_getFrontendPath("footer.php", "layout"));

?>