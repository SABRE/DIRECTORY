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
	# * FILE: /controller/listing/claim.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
    $_GET["claim"] = $aux_array_url[$searchPos_3];
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
    include(EDIR_CONTROLER_FOLDER."/".LISTING_FEATURE_FOLDER."/rewrite.php");
    extract($_GET);
    # ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (CLAIM_FEATURE != "on") { 
        exit; 
    }
	if (!$claimlistingid) {
		header("Location: ".LISTING_DEFAULT_URL."/");
		exit;
	}
	$listingObject = new Listing($claimlistingid);
	if (!$listingObject->getNumber("id") || ($listingObject->getNumber("id") <= 0)) {
		header("Location: ".LISTING_DEFAULT_URL."/");
		exit;
	}
	if ($listingObject->getNumber("account_id")) {
		header("Location: ".LISTING_DEFAULT_URL."/");
		exit;
	}
	if ($listingObject->getString("claim_disable") != "n") {
		header("Location: ".LISTING_DEFAULT_URL."/");
		exit;
	}
	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if (($_SERVER['REQUEST_METHOD'] == "POST")) {
		
		$validate_account = validate_addAccount($_POST, $message_account);
		$validate_contact = validate_form("contact", $_POST, $message_contact);

		if ($validate_account && $validate_contact) {

			$account = new Account($_POST);
			$account->save();

			if ($_POST["claim"]) {
				$account->changeMemberStatus(true);
			}

			$contact = new Contact($_POST);
			$contact->setNumber("account_id", $account->getNumber("id"));
			$contact->save();

			$profileObj = new Profile(sess_getAccountIdFromSession());
			$profileObj->setNumber("account_id", $account->getNumber("id"));
			if (!$profileObj->getString("nickname")) {
				$profileObj->setString("nickname", $_POST["first_name"]." ".$_POST["last_name"]);
			}
			$profileObj->Save();
			$accDomain = new Account_Domain($account->getNumber("id"), SELECTED_DOMAIN_ID);
			$accDomain->Save();
			$accDomain->saveOnDomain($account->getNumber("id"), $account, $contact, $profileObj);

			/**************************************************************************************************/
			/*                                                                                                */
			/* E-mail notify                                                                                  */
			/*                                                                                                */
			/**************************************************************************************************/
			setting_get("sitemgr_send_email",$sitemgr_send_email);
			setting_get("sitemgr_email",$sitemgr_email);
			$sitemgr_emails = explode(",",$sitemgr_email);
			if ($sitemgr_emails[0]) $sitemgr_email = $sitemgr_emails[0];
			setting_get("sitemgr_account_email",$sitemgr_account_email);
			$sitemgr_account_emails = explode(",",$sitemgr_account_email);

			// sending e-mail to user //////////////////////////////////////////////////////////////////////////
			if ($emailNotificationObj = system_checkEmail(SYSTEM_CLAIM_SIGNUP)) {
				$subject = $emailNotificationObj->getString("subject");
				$body = $emailNotificationObj->getString("body");
				$body = str_replace("ACCOUNT_NAME",$contact->getString("first_name").' '.$contact->getString("last_name"),$body);
				$login_info = trim(system_showText(LANG_LABEL_USERNAME)).": ".$_POST["username"];
				$login_info .= ($emailNotificationObj->getString("content_type") == "text/html"? "<br />": "\n");
				$login_info .= trim(system_showText(LANG_LABEL_PASSWORD)).": ".$_POST["password"];
				$body = str_replace("ACCOUNT_LOGIN_INFORMATION",$login_info,$body);
				$body = system_replaceEmailVariables($body, $listingObject->getNumber('id'), 'listing');
				$subject = system_replaceEmailVariables($subject, $listingObject->getNumber('id'), 'listing');
				$body = html_entity_decode($body);
				$subject = html_entity_decode($subject);
				$error = false;
				system_mail($contact->getString("email"), $subject, $body, EDIRECTORY_TITLE." <$sitemgr_email>", $emailNotificationObj->getString("content_type"), "", $emailNotificationObj->getString("bcc"), $error);
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////

			sess_registerAccountInSession($account->getString("username"));
			setcookie("username_members", $account->getString("username"), time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
            
			header("Location: ".DEFAULT_URL."/".MEMBERS_ALIAS."/claim/getlisting.php?claimlistingid=".$claimlistingid);
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
    # HEADER
    # ----------------------------------------------------------------------------------------------------
    $headertag_title = (($listingObject->getString("seo_title"))?($listingObject->getString("seo_title")):($listingObject->getString("title")))." - ".system_showText(LANG_LISTING_CLAIMTHIS);
    $headertag_description = (($listingObject->getString("seo_description"))?($listingObject->getString("seo_description")):($listingObject->getString("description")));
    $headertag_keywords = (($listingObject->getString("seo_keywords"))?($listingObject->getString("seo_keywords")):(str_replace(" || ", ", ", $listingObject->getString("keywords"))));

    unset($loginTypes, $openIDEnabled, $facebookEnabled, $googleEnabled, $cUserEnabled);

    setting_get("foreignaccount_openid", $foreignaccount_openid);
    if ($foreignaccount_openid == "on") {
        $openIDEnabled = true;
    }

    setting_get("foreignaccount_google", $foreignaccount_google);
    if ($foreignaccount_google == "on") {
        $googleEnabled = true;
    }

    if (FACEBOOK_APP_ENABLED == "on") {
        $facebookEnabled = true;
    }

    if (sess_isAccountLogged() && SOCIALNETWORK_FEATURE == "on") {
        $cUserEnabled = true;
    }	

    $loginTypes	.= "formNewUser,";
    $loginTypes	.= "formDirectoryUser,";
    if ($openIDEnabled) {
        $loginTypes	.= "formOpenIDUser,";
    }
    if ($googleEnabled) {
        $loginTypes	.= "formGoogleUser,";
    }
    if ($facebookEnabled) {
        $loginTypes	.= "formFacebookUser,";
    }
    if ($cUserEnabled) {
        $loginTypes	.= "formCurrentUser,";
    }
    $loginTypes	= string_substr($loginTypes, 0, -1);

?>