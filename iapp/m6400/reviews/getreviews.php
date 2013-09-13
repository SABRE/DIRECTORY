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
	header("Content-type: text/xml");


	$xml_output  = "<?xml version=\"1.0\" encoding=\"".EDIR_CHARSET."\"?>\n";
	//$xml_output  .="<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:edirectory=\"".DEFAULT_URL."\">";

	$xml_output  .= "<eDirectoryData>\n";
	$xml_output  .= "<ListingData>\n";

	//$objLang = new Lang();
	//$langs = $objLang->getAll();



	unset($items);
	$dbObj = db_getDBObject();



	$sql  = "select * from Review where item_type = 'listing' ";
	$sql .= " and item_id = ".$_GET["item_id"];
	$sql .= " and approved = 1 order by id desc limit 10 ";


	$result = $dbObj->query($sql);
	if ($result) {
		while ($review = mysql_fetch_assoc($result)) {

			$xml_output .= "<entry>";
			$xml_output .= "<id>".$review["id"]."</id>";
			$xml_output .= "<item_id>".$review["item_id"]."</item_id>";
			$xml_output .= "<member_id>".$review["member_id"]."</member_id>";
			$xml_output .= "<added>".$review["added"]."</added>";
			$xml_output .= "<ip>".$review["ip"]."</ip>";
			$xml_output .= "<review_title>".$review["review_title"]."</review_title>";
			$xml_output .= "<review><![CDATA[".$review["review"]."]]></review>";
			$xml_output .= "<reviewer_name>".$review["reviewer_name"]."</reviewer_name>";
			$xml_output .= "<reviewer_email>".$review["reviewer_email"]."</reviewer_email>";
			$xml_output .= "<reviewer_location>".$review["reviewer_location"]."</reviewer_location>";
			$xml_output .= "<rating>".$review["rating"]."</rating>";

			$xml_output .= "</entry>\n";
		}
	}


	$xml_output  .= "</ListingData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";


	echo $xml_output;
?>