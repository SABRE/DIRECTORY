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

	# ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
	include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");

	# ----------------------------------------------------------------------------------------------------
	# QUERY STRING
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORYM_DOCUMENTROOT."/query_string.php");

	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	if (!$_GET["promotion_id"])
		$_GET["promotion_id"] = 0;	

	$dbObj = db_getDBObject();			
			
			$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
			//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";

			$xml_output  .= "<eDirectoryData object=\"Listing\" >\n";
			$xml_output  .= "<ObjectData>\n";
			$xml_output  .= "<entry>\n";	
												
			$dealObj = new Promotion($_GET["promotion_id"]);					
				
			$xml_output  .= "<promotionID>".$_GET["promotion_id"]."</promotionID>";							
			$xml_output  .= "<promotionName><![CDATA[".$dealObj->name."]]></promotionName>";
			$xml_output  .= "<promotionRealValue>".$dealObj->realvalue."</promotionRealValue>";
			$xml_output  .= "<promotionDealValue>".$dealObj->dealvalue."</promotionDealValue>";
			$xml_output  .= "<promotionAmount>".$dealObj->amount."</promotionAmount>";
			$xml_output  .= "<promotionDescription><![CDATA[".$dealObj->description."]]></promotionDescription>";
			$xml_output  .= "<promotionConditions>".$dealObj->conditions."</promotionConditions>";
			$xml_output  .= "<promotionVisibilityStart>".$dealObj->visibility_start."</promotionVisibilityStart>";
			$xml_output  .= "<promotionVisibilityEnd>".$dealObj->visibility_end."</promotionVisibilityEnd>";
			$xml_output  .= "<promotionStart>".$dealObj->start_date."</promotionStart>";			
			$xml_output  .= "<promotionEnd>".$dealObj->end_date." 23:59:59"."</promotionEnd>";
			$xml_output  .= "<promotionFriendlyURL>".DEFAULT_URL."/".PROMOTION_FEATURE_NAME."/".$dealObj->friendly_url.".html"."</promotionFriendlyURL>";
		
			$promotionDeals=$dealObj->getDealInfo();
			$xml_output  .= "<promotionDeals>".$promotionDeals['sold']."</promotionDeals>";
			
			$xml_output  .= "</entry>\n";	
			$xml_output  .= "</ObjectData>\n";
			$xml_output  .= "</eDirectoryData>\n";
			//$xml_output  .="</feed>";
	
			
	header("Content-type: text/xml");
	print($xml_output);

?>