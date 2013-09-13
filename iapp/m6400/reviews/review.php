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


	/*
	$reviewObj->setString("reviewer_name", $reviewer_name);
	$reviewObj->setString("reviewer_email", $reviewer_email);
	$reviewObj->setString("reviewer_location", $reviewer_location);
	*/
	unset($xml_output);
	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	////$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\">";



	$xml_output  .= "<eDirectoryData>\n";
	$xml_output  .= "<ListingData>\n";
	
	$xml_output  .= "<entry>";
	$xml_output  .= "<review_id>".$_GET["id"]."</review_id>";
	$xml_output  .= "</entry>\n";
	
	$xml_output  .= "</ListingData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	////$xml_output  .="</feed>";
	
	header("Content-type: text/xml"); 

	echo $xml_output;  
	
?>