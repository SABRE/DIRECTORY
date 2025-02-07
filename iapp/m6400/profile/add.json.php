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
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../conf/configuration.inc.php");



	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	
	function escapeJsonString($value) { 
		$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
		$result = str_replace($escapers, $replacements, $value); 
		return $result;
	};
		
	header("Content-type: application/json"); 
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_LISTINGHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";
	
    $_POST['agree_tou'] = 1;
    //$_POST['retype_password'] = $_POST['password'];

	$validate_account = validate_addAccount($_POST, $message_account);
	//$validate_contact = validate_form("contact", $_POST, $message_contact);
	if ($validate_account) {
	    //$_POST['publish_contact'] = ($_POST['publish_contact']?'y':'n');
	    
	    $_POST['publish_contact'] = 'y';
		$account = new Account($_POST);
	
		
		$account->Save();
		$contact = new Contact($_POST);
		$contact->setNumber("account_id", $account->getNumber("id"));
		$contact->Save();
	
	
		$profileObj = new Profile($_POST);
		$profileObj->setNumber("account_id", $account->getNumber("id"));
		$profileObj->setString("nickname", $contact->first_name . ' ' . $contact->last_name);
		$profileObj->Save();

		$accDomain = new Account_Domain($account->getNumber("id"), SELECTED_DOMAIN_ID);
		$accDomain->Save();
		$accDomain->saveOnDomain($account->getNumber("id"), $account, $contact, $profileObj);

		sess_registerAccountInSession($_POST["username"]);
		//setcookie("username", $_POST['username'], time()+60*60*24*30, "".EDIRECTORY_FOLDER."/members");
	
		/*****************************************************
		*
		* E-mail notify
		*
		******************************************************/
		setting_get("sitemgr_send_email",$sitemgr_send_email);
		setting_get("sitemgr_email",$sitemgr_email);
		$sitemgr_emails = split(",",$sitemgr_email);
		setting_get("sitemgr_account_email",$sitemgr_account_email);
		$sitemgr_account_emails = split(",",$sitemgr_account_email);
	
		// sending e-mail to user //////////////////////////////////////////////////////////////////////////
		$error = false;
		$body = system_showText(LANG_DEAR)." ".$contact->getString("first_name")." ".$contact->getString("last_name").",\n".system_showText(LANG_MSG_THANK_YOU_FOR_SIGNING_UP)." ".EDIRECTORY_TITLE." (".DEFAULT_URL.").\n".system_showText(LANG_MSG_LOGIN_TO_MANAGE_YOUR_ACCOUNT)."\n\n".system_showText(LANG_LABEL_USERNAME).": ".$_POST["username"]."\n".system_showText(LANG_LABEL_PASSWORD).": ".$_POST["password"]."\n\n".system_showText(LANG_MSG_YOU_CAN_SEE).":\n".system_showText(LANG_MSG_YOUR_ACCOUNT_IN)." ".DEFAULT_URL."/members/account/account.php?id=".$account->getNumber("id")."\n";
		system_mail($contact->getString("email"), "[".EDIRECTORY_TITLE."] ".system_showText(LANG_LABEL_SIGNUP_NOTIFICATION), $body, EDIRECTORY_TITLE." <$sitemgr_email>", 'text/plain', '', '', $error);
		////////////////////////////////////////////////////////////////////////////////////////////////////
	
		// site manager warning message /////////////////////////////////////
		$sitemgr_msg = "
			<html>
				<head>
					<style>
						.email_style_settings{
							font-size:12px;
							font-family:Verdana, Arial, Sans-Serif;
							color:#000;
						}
					</style>
				</head>
				<body>
					<div class=\"email_style_settings\">
						Site Manager,<br /><br />
						A new account was created in ".EDIRECTORY_TITLE.".<br />
						Please review the account information below:<br /><br />";
						$sitemgr_msg .= "<b>Username: </b>".$account->getString("username")."<br />";
						$sitemgr_msg .= "<b>First Name: </b>".$contact->getString("first_name")."<br />";
						$sitemgr_msg .= "<b>Last Name: </b>".$contact->getString("last_name")."<br />";
						$sitemgr_msg .= "<b>Company: </b>".$contact->getString("company")."<br />";
						$sitemgr_msg .= "<b>Address: </b>".$contact->getString("address")." ".$contact->getString("address2")."<br />";
						$sitemgr_msg .= "<b>City: </b>".$contact->getString("city")."<br />";
						$sitemgr_msg .= "<b>State: </b>".$contact->getString("state")."<br />";
						$sitemgr_msg .= "<b>".ucwords(ZIPCODE_LABEL).": </b>".$contact->getString("zip")."<br />";
						$sitemgr_msg .= "<b>Phone: </b>".$contact->getString("phone")."<br />";
						$sitemgr_msg .= "<b>Fax: </b>".$contact->getString("fax")."<br />";
						$sitemgr_msg .= "<b>Email: </b>".$contact->getString("email")."<br />";
						$sitemgr_msg .= "<b>URL: </b>".$contact->getString("url")."<br />";
						$sitemgr_msg .= "<b>I agree with the terms of use: </b>".(($account->getString("agree_tou") ==1) ? "Yes" : "No")."<br />";
						$sitemgr_msg .="<br /><a href=\"".DEFAULT_URL."/sitemgr/account/view.php?id=".$account->getNumber("id")."\" target=\"_blank\">".DEFAULT_URL."/sitemgr/account/view.php?id=".$account->getNumber("id")."</a><br /><br />
					</div>
				</body>
			</html>";
	
		if ($sitemgr_send_email == "on") {
			if ($sitemgr_emails[0]) {
				foreach ($sitemgr_emails as $sitemgr_email) {
					system_mail($sitemgr_email, "[".EDIRECTORY_TITLE."] Account Notification", $sitemgr_msg, EDIRECTORY_TITLE." <$sitemgr_email>", "text/html", '', '', $error);
				}
			}
		}
		if ($sitemgr_account_emails[0]) {
			foreach ($sitemgr_account_emails as $sitemgr_account_email) {
				system_mail($sitemgr_account_email, "[".EDIRECTORY_TITLE."] Account Notification", $sitemgr_msg, EDIRECTORY_TITLE." <$sitemgr_account_email>", "text/html", '', '', $error);
			}
		}
	} 
	
	else 
	
	{
		
	}
	
	$message_account = str_replace("&#149;&nbsp;", "", $message_account);
	$message_account = str_replace("<br />", "\n", $message_account);
	
	unset($xml_output);
	$xml_output  = "{\n";	
	
	if (!$validate_account)
	{
		$xml_output .= '"validate": "false",';
		$xml_output  .= '"message": "'.escapeJsonString($message_account)."\"";
		
		$xml_output  .= "}";

		echo $xml_output; 
		
		return;

	}
	else
	{
		$xml_output .= '"validate": "true",';
	}
	
	
	
	
	if (sess_authenticateAccount($_POST["username"], $_POST["password"], $authmessage))
	{
		$xml_output  .= '"authenticateAccount": "true",';
		$Account = db_getFromDB("account", "username", db_formatString($_POST["username"]));
		
		//$Profile = new Profile($Account->id);

		$Contact = new Contact($Account->id);

		$xml_output  .= '"id":"'.escapeJsonString($Account->id)."\",\n";
		$xml_output  .= '"username":"'.escapeJsonString($Account->username)."\",\n";
		$xml_output  .= '"name":"'.escapeJsonString($Contact->first_name)." ".$Contact->last_name."\",\n";
		$xml_output  .= '"first_name":"'.escapeJsonString($Contact->first_name)."\",\n";
		$xml_output  .= '"last_name":"'.escapeJsonString($Contact->last_name)."\",\n";
		$xml_output  .= '"email":"'.escapeJsonString($Contact->email)."\",\n";
		$xml_output  .= '"location":"'.escapeJsonString($Contact->city.", ".$Contact->state)."\",\n";
		$xml_output  .= '"ip":"'.escapeJsonString($_SERVER["REMOTE_ADDR"])."\"\n";
	}	
	else
	{
		$xml_output  .= '"authenticateAccount": "false",';
		$xml_output  .= '"authmessage": "'.escapeJsonString($authmessage)."\"\n";
	}		
	
	
	$xml_output  .= "}";

	

	echo $xml_output; 
	
?>