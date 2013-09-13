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

	//GET
	//user_name
	//promotion_id
	//profile  yes|no
	//twitter  yes|no
	//facebook yes|no

	$xml_output  = "{";


	//Setting session
	sess_registerAccountInSession($_POST["user_name"]);

	$profileObj = new Profile(sess_getAccountIdFromSession());

	$promotion = new Promotion((int)$_POST["promotion_id"]);
	$promotion->account_id = (int)$profileObj->account_id;
	$redeem = $promotion->alreadyRedeemed((int)$_POST["promotion_id"]);

	//result
	//0 => ok
	//1 => already redeem
	//2 => sold out

	if ($redeem) {
		$xml_output  .= "\"result\": 1,";
		$xml_output  .= "\"redeem_code\": \"".$redeem."\"\n";
		$xml_output  .= "}";

		echo $xml_output;
		return;
	}

	if (!$promotion->amount){
		$xml_output  .= "\"result\": 2";
		$xml_output  .= "}";
		echo $xml_output;
		return;
	}

	if ($_POST["facebook"] == "yes")
	{
		$redeem_code = $profileObj->deal_done('facebook',$_POST["promotion_id"], "facebook by mobile") ;
	}
	if ($_POST["twitter"] == "yes")
	{
		$redeem_code = $profileObj->deal_done('twitter' ,$_POST["promotion_id"], "twitter by mobile") ;
	}
	if ($_POST["profile"] == "yes")
	{
		$redeem_code = $profileObj->deal_done('profile', $_POST["promotion_id"], "profile by mobile") ;
	}

	$promotion->amount=(int)$promotion->amount-1;
    $promotion->Save();

	$xml_output  .= "\"result\": 0,";
	$xml_output  .= "\"redeem_code\": \"".$redeem_code."\"\n";
	$xml_output  .= "}";

	echo $xml_output;

?>