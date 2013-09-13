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



	$sql  = "select * from CheckIn ";
	$sql .= " where item_id = ".$_GET["item_id"];
	$sql .= " order by id desc limit 10 ";

	$result = $dbObj->query($sql);
	if ($result) {
		while ($checkin = mysql_fetch_assoc($result)) {


			unset($Profile);
			$Profile = new Profile($checkin["member_id"]);

			if( $profile->facebook_uid ){
				$imageURL = $Profile->facebook_image;
			}else{
				unset($imageObj);
				$imageObj = new Image($Profile->image_id, true);

				if ($imageObj->imageExists()) {
					$imageURL = strtolower(DEFAULT_URL . "/custom/profile/".$Profile->account_id."_photo_" . $imageObj->id . "." . $imageObj->type);
				}else{
					$imageURL = DEFAULT_URL."/images/profile_noimage.gif";
				}
			}


			$xml_output .= "<entry>";
			$xml_output .= "<id>".$checkin["id"]."</id>";
			$xml_output .= "<item_id>".$checkin["item_id"]."</item_id>";
			$xml_output .= "<member_id>".$checkin["member_id"]."</member_id>";
			$xml_output .= "<added>".$checkin["added"]."</added>";
			$xml_output .= "<ip>".$checkin["ip"]."</ip>";
			$xml_output .= "<quick_tip><![CDATA[".$checkin["quick_tip"]."]]></quick_tip>";
			$xml_output .= "<checkin_name>".$checkin["checkin_name"]."</checkin_name>";
			$xml_output .= "<profileImageURL>".$imageURL."</profileImageURL>";

			$xml_output .= "</entry>\n";
		}
	}


	$xml_output  .= "</ListingData>\n";
	$xml_output  .= "</eDirectoryData>\n";
	//$xml_output  .="</feed>";


	echo $xml_output;
?>