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
	# * FILE: /members/facebookauth.php
	# ----------------------------------------------------------------------------------------------------



	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	//include("../conf/loadconfig.inc.php");
	include("../conf/configuration.inc.php");



	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------

	setting_get("foreignaccount_facebook", $foreignaccount_facebook);
	setting_get("foreignaccount_facebook_apikey", $foreignaccount_facebook_apikey);
	setting_get("foreignaccount_facebook_apisecret", $foreignaccount_facebook_apisecret);

	/* harcoded key for demodirectory.com */
	if (DEMO_LIVE_MODE) {
		if (string_strpos($_SERVER["HTTP_HOST"], "demodirectory.com") !== false) {
			$foreignaccount_facebook_apikey = FACEBOOK_API_KEY;
			$foreignaccount_facebook_apisecret = FACEBOOK_API_SECRET;
		}
	}



	/*
	$destiny = $_GET["destiny"];
	setcookie("userform", "facebook", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/members");
	if (!$destiny){
		if ((string_strpos($_SERVER["QUERY_STRING"], "destiny=") !== false) && (string_strpos($_SERVER["QUERY_STRING"], "query=") !== false)) {
			$destiny = string_substr($_SERVER["QUERY_STRING"], string_strpos($_SERVER["QUERY_STRING"], "destiny=")+8, (string_strpos($_SERVER["QUERY_STRING"], "query=")-string_strpos($_SERVER["QUERY_STRING"], "destiny=")-9));
			$query = string_substr($_SERVER["QUERY_STRING"], string_strpos($_SERVER["QUERY_STRING"], "query=")+6);
		} elseif (string_strpos($_SERVER["QUERY_STRING"], "claimlistingid=") !== false) {
			$destiny = EDIRECTORY_FOLDER."/members/claim/getlisting.php";
			$query = string_substr($_SERVER["QUERY_STRING"], string_strpos($_SERVER["QUERY_STRING"], "claimlistingid="));
		}
	}

	if ($destiny) {
		$url = $destiny;
		if ($query) $url .= "?".$query;
	} else {
		$url = ((SSL_ENABLED == "on" && FORCE_MEMBERS_SSL == "on") ? SECURE_URL : DEFAULT_URL)."/members/";
	}
	*/


	unset($user_details);
	$user_details["uid"] = $_GET["uid"];
	$user_details["first_name"] = $_GET["first_name"];
	$user_details["last_name"] = $_GET["last_name"];
	$user_details["email"] = $_GET["email"];

	/*
	$facebook = new Facebook(array('appId'  => $foreignaccount_facebook_apikey,	'secret' => $foreignaccount_facebook_apisecret,	'cookie' => true));

	$session = $facebook->getSession();


	if ($session) {
		try {
			$main_info = $facebook->api("/me");

			$music_info = $facebook->api("/me/music");
			$movie_info = $facebook->api("/me/movies");
			$book_info = $facebook->api("/me/books");

			$user_details["birthday_date"] = $main_info["birthday"];
			$user_details["home_town"] = $main_info["hometown"]["name"];
			$user_details["location"] = $main_info["location"]["name"];
			$user_details["picture"] = "https://graph.facebook.com/".$user_details["uid"]."/picture?type=large";

			foreach ($music_info["data"] as $muInfo) {
				$user_details["music"] .= $muInfo["name"].", ";
			}
			$user_details["music"] = string_substr($user_details["music"], 0, -2);

			foreach ($movie_info["data"] as $moInfo) {
				$user_details["movies"] .= $moInfo["name"].", ";
			}
			$user_details["movies"] = string_substr($user_details["movies"], 0, -2);

			foreach ($book_info["data"] as $bInfo) {
				$user_details["books"] .= $bInfo["name"].", ";
			}
			$user_details["books"] = string_substr($user_details["books"], 0, -2);

		} catch (FacebookApiException $e) {
			error_log($e);
		}

	} else {
		exit;
	}
	*/

	if(!$user_details){
		exit;
	}

	if (system_registerForeignAccount($user_details, "facebook")) {


		if ($_GET["advertise"] || $_GET["claim"]) {
			$accObj = new Account(sess_getAccountIdFromSession());
			$accObj->changeMemberStatus(true);

			$host = string_strtoupper(str_replace("www.", "", $_SERVER["HTTP_HOST"]));

			setcookie($host."_DOMAIN_ID_MEMBERS", "", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/members");
			setcookie($host."_DOMAIN_ID", "", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
			unset($_SESSION[$host."_DOMAIN_ID_MEMBERS"], $_SESSION[$host."_DOMAIN_ID"]);
		}

		$contact = new Contact(sess_getAccountIdFromSession());
		$profile = new Profile(sess_getAccountIdFromSession());
		if (!$profile->getString("nickname")) {
			$profile->setString("nickname", $contact->getString("first_name")." ".$contact->getString("last_name"));
		}
		$profile->setString("facebook_uid", $_GET['uid']);
		$profile->setString('location', $user_details["location"]);
		$profile->Save();

		if (!$_GET["advertise"] && !$_GET["claim"]) {
			if (!isset($accObj)) {
				$accObj = new Account(sess_getAccountIdFromSession());
			}
               if (!$_GET['redeemit']){
                   if ($accObj->getString("is_sponsor") == "y" || SOCIALNETWORK_FEATURE == "off") {
                       $url = DEFAULT_URL."/members";
                   } else {
                       $url = SOCIALNETWORK_URL;
                   }
               }
		}

		$accDomain = new Account_Domain($accObj->getNumber("id"), SELECTED_DOMAIN_ID);
		$accDomain->Save();
		$accDomain->saveOnDomain($accObj->getNumber("id"), $accObj, $contact, $profile);

		if ($_GET['redeemit']){
			$url .= "&redeemit=true";
               $url=str_replace('fb_xd_fragment', '', $url);
		}

		if ($_SESSION["REQUEST_URI"] && $_SESSION["ITEM_ACTION"] == "rate" && $_SESSION["ITEM_TYPE"] && is_numeric($_SESSION["ITEM_ID"]) && sess_isAccountLogged()) {
			$url = DEFAULT_URL.$_SESSION["REQUEST_URI"];

			if (MODREWRITE_FEATURE == "on") {
				if (string_strpos($url, "/detail.php")) {
					$iFriendlyUrl = string_substr($url, string_strpos($url, "=") + 1, string_strlen($url));
					$nUrl = string_substr($url, 0, string_strpos($url, "/detail.php"));
					$url = $nUrl."/".$iFriendlyUrl.".html";
				}
			}

			unset($_SESSION["REQUEST_URI"]);
		}


		$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
		//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";
		$xml_output  .= "<eDirectoryData>\n";
		$xml_output  .= "<ListingData>\n";
		$xml_output  .= "<entry>";
		$xml_output  .= "<member_id>".$accObj->getNumber("id")."</member_id>";
		$xml_output  .= "</entry>\n";
		$xml_output  .= "</ListingData>\n";
		$xml_output  .= "</eDirectoryData>\n";
		//$xml_output  .="</feed>";

		echo $xml_output;



	} else {

		$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
		//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";
		$xml_output  .= "<eDirectoryData>\n";
		$xml_output  .= "<ListingData>\n";
		$xml_output  .= "<entry>";
		$xml_output  .= "<member_id>0</member_id>";
		$xml_output  .= "</entry>\n";
		$xml_output  .= "</ListingData>\n";
		$xml_output  .= "</eDirectoryData>\n";
		//$xml_output  .="</feed>";

		echo $xml_output;

	}


?>