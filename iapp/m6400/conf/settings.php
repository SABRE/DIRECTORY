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
	# DEFINE
	# ----------------------------------------------------------------------------------------------------
	define(MAX_ITEM_PER_PAGE, 20);

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	header("Content-type: text/xml");
	$backButton = false;
	$mapresultsButton = false;
	$listresultsButton = false;
	$backButtonLink = "";
	$headerTitle = LANG_M_LISTINGHOME;
	$languageButton = false;
	$homeButton = true;
	$searchButton = false;
	$searchButtonLink = "";



	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	$xml_output  .= "<eDirectoryData>\n";
	$xml_output  .= "<ListingData>\n";

	$xml_output  .= "<entry>";

	//Social Network enabled?

	if (SOCIALNETWORK_FEATURE == "off") {
		$xml_output .= "<socialnetwork_enabled>false</socialnetwork_enabled>";
	} else {
		$xml_output .= "<socialnetwork_enabled>true</socialnetwork_enabled>";
	}

	//Review enabled?
	setting_get("review_listing_enabled", $value);
	$value = ($value == "on") ? "true" : "false";
	$xml_output .= "<review_listing_enabled>".$value."</review_listing_enabled>";

	//Need login to review?
	$setting = new SettingSocialNetwork("listing_rate");
	$value = ($setting->value == "yes") ? "true" : "false";
	$xml_output .= "<need_login_to_listing_review>".$value."</need_login_to_listing_review>";

	//OpenID enabled?
	setting_get("foreignaccount_openid", $value);
	$value = ($value == "on") ? "true" : "false";
	$xml_output .= "<openid_enabled>".$value."</openid_enabled>";

	//Facebook enabled?
	setting_get("foreignaccount_facebook", $enabledValue);

	//Facebook api ID
	setting_get("foreignaccount_facebook_apiid", $apiIDValue);

	//Facebook api key
	setting_get("foreignaccount_facebook_apiid", $apiKeyValue);
	
	if (($apiKeyValue == "") || ($apiKeyValue === false)) {
	 	setting_get("foreignaccount_facebook_apikey", $apiKeyValue);
	}

	/* harcoded key for demodirectory.com */
	if (DEMO_LIVE_MODE) {
		if (string_strpos($_SERVER["HTTP_HOST"], "demodirectory.com") !== false) {
			$apiKeyValue = FACEBOOK_API_KEY;
		}
	}

	//Facebook enabled?
	$enabledValue = ($enabledValue == "on" && ($apiKeyValue)) ? "true" : "false";
	$xml_output .= "<facebook_enabled>".$enabledValue."</facebook_enabled>";

	//Facebook api key
	$xml_output .= "<facebook_apikey>".$apiKeyValue."</facebook_apikey>";

	//Facebook api ID
	$xml_output .= "<facebook_appid>".$apiIDValue."</facebook_appid>";

	//Twitter api key
	setting_get("foreignaccount_twitter_mobile_apikey", $value);
	$xml_output .= "<twitter_apikey>".$value."</twitter_apikey>";

	//Twitter api secret
	setting_get("foreignaccount_twitter_mobile_apisecret", $value);
	$xml_output .= "<twitter_apisecret>".$value."</twitter_apisecret>";

	//eDir Twitter api key
	setting_get("foreignaccount_twitter_apikey", $value);
	$xml_output .= "<twitter_edir_apikey>".$value."</twitter_edir_apikey>";

	//eDir Twitter api secret
	setting_get("foreignaccount_twitter_apisecret", $value);
	$xml_output .= "<twitter_edir_apisecret>".$value."</twitter_edir_apisecret>";
	
	//Promotion Force Redeem Facebook
	setting_get("promotion_force_redeem_by_facebook", $value);
	//$value = ($value =="1" ? "true" : "false");
	$value = ($value =="1" ? "false" : "true");
	$xml_output .= "<promotion_force_redeem_by_facebook>".$value."</promotion_force_redeem_by_facebook>";


	$xml_output  .= "</entry>\n";

	$xml_output  .= "</ListingData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .= "</feed>";


	echo $xml_output;


?>