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

	//GET
	//user_name
	//promotion_id
	//profile  yes|no
	//twitter  yes|no
	//facebook yes|no

	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	$xml_output  .= "<eDirectoryData>\n";
	$xml_output  .= "<ObjectData>\n";

	$xml_output  .= "<entry>";

	//Setting session
	sess_registerAccountInSession($_POST["user_name"]);

	$profileObj = new Profile(sess_getAccountIdFromSession());

	$promotion = new Promotion((int)$_POST["promotion_id"]);
	//$promotion->account_id = (int)$profileObj->account_id;
	$redeem = $promotion->alreadyRedeemed((int)$_POST["promotion_id"]);

	//result
	//0 => ok
	//1 => already redeem
	//2 => sold out

	if ($redeem) {
		$xml_output  .= "<result>1</result>";
		$xml_output  .= "<redeem_code>".$redeem."</redeem_code>";
		$xml_output  .= "</entry>\n";
		$xml_output  .= "</ObjectData>\n";
		$xml_output  .= "</eDirectoryData>\n";
		//$xml_output  .= "</feed>";
		echo $xml_output;
		return;
	}

	if (!$promotion->amount){
		$xml_output  .= "<result>2</result>";
		$xml_output  .= "</entry>\n";
		$xml_output  .= "</ObjectData>\n";
		$xml_output  .= "</eDirectoryData>\n";
		//$xml_output  .= "</feed>";
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

	$xml_output  .= "<result>0</result>";
	$xml_output  .= "<redeem_code>".$redeem_code."</redeem_code>";
	$xml_output  .= "</entry>\n";
	$xml_output  .= "</ObjectData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .= "</feed>";
	echo $xml_output;

?>