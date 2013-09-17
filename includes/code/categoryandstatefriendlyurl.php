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
	# * FILE: /includes/code/banner.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# AUX
	# ----------------------------------------------------------------------------------------------------
	
	// fixing url field if needed.
	if($_POST["destination_url"]) {
		if ((string_strpos($destination_url,"http://")!==false) || (string_strpos($destination_url,"https://")!==false) || (string_strpos($destination_url,"ftp://")!==false)) {
			if (string_strpos($_POST["destination_url"], "http://") === 0) $_POST["destination_url"] = string_substr($_POST["destination_url"], 7);
			if (string_strpos($_POST["destination_url"], "https://") === 0) $_POST["destination_url"] = string_substr($_POST["destination_url"], 8);
			if (string_strpos($_POST["destination_url"], "ftp://") === 0) $_POST["destination_url"] = string_substr($_POST["destination_url"], 6);
		}
	}

	

	extract($_POST);
	extract($_GET);

	
	/**
	* Delete operation
	****************************************************************************/
	if ($operation == "delete" ) {

        $message = 0;
        
		$bannerObj = new Banner($id);
		$bannerObj->Delete();
		unset($bannerObj);

		header("Location: ".$url_redirect."/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "")."");
		exit;

	}
    
	/**
	* Insert Operation
	****************************************************************************/
	if ($operation == "add") {

		$_POST["caption"] = trim($_POST["caption"]);
		if ((validate_form("banner", $_POST, $val_message, $error_size)) && is_valid_discount_code($_POST["discount_id"], "banner", $_POST["id"], $val_message, $discount_error_num)) {

			if (($uploadObj->error_type == 0) || ($uploadObj->error_type == 6)) {
				$message = "";
			}
			$error_message .= $val_message."<br />";
			$message = 1;

			$emailNotification = true;

			// Saving Banner
			$bannerObj = new Banner($_POST);
			if (string_strpos($url_base, "/".SITEMGR_ALIAS."")) {
				$bannerObj->setDate("renewal_date", $_POST['renewal_date']); // set date of correct format
			}
			if (!$bannerObj->hasImpressions()) {
				$bannerObj->setNumber("unpaid_impressions", 0);
				$bannerObj->setString("unlimited_impressions", "y");
			} else {
				$bannerObj->setString("unlimited_impressions", "n");
			}

			$bannerObj->Save();
			$id = $bannerObj->getString("id");
			$domain	 = new Domain(SELECTED_DOMAIN_ID);
			if ((sess_isAccountLogged()) && (string_strpos($url_base, "/".MEMBERS_ALIAS.""))) {
                
                // site manager warning message /////////////////////////////////////
				$domain_url = ((SSL_ENABLED == "on" && FORCE_SITEMGR_SSL == "on") ? SECURE_URL : NON_SECURE_URL);
				$domain_url = str_replace($_SERVER["HTTP_HOST"], $domain->getstring("url"), $domain_url);
				
                $acctId = sess_getAccountIdFromSession();
				$accountObj = new Account($acctId);
				$contactObj = new Contact($acctId);
                
				setting_get("sitemgr_banner_email", $sitemgr_banner_email);
				$sitemgr_banner_emails = explode(",", $sitemgr_banner_email);
                
                setting_get("new_banner_email", $new_banner_email);
                
                $emailSubject = system_showText(LANG_NOTIFY_BANNER);
				$sitemgr_msg = system_showText(LANG_LABEL_SITE_MANAGER).",<br /><br />";
				$sitemgr_msg .= ucfirst(system_showText(LANG_BANNER_FEATURE_NAME))." \"".$bannerObj->getString("caption")."\" ".system_showText(LANG_NOTIFY_ITEMS_2)." \"".system_showAccountUserName($accountObj->getString("username"))."\" ".system_showText(LANG_NOTIFY_ITEMS_3)."<br /><br />";
				$sitemgr_msg .= "<a href=\"".$domain_url."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/view.php?id=".$bannerObj->getNumber("id")."\" target=\"_blank\">".$domain_url."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/view.php?id=".$bannerObj->getNumber("id")."</a><br /><br />";
				$sitemgr_msg .= EDIRECTORY_TITLE;
                $error = false;

				if ($new_banner_email){ 
                    system_notifySitemgr($sitemgr_banner_emails, $emailSubject, $sitemgr_msg);
                }
			}

			if ($_POST["account_id"] > 0) {
				$accountObj = new Account($_POST["account_id"]);
				$contactObj = new Contact($_POST["account_id"]);
				if($emailNotificationObj = system_checkEmail(SYSTEM_NEW_BANNER)) {
                    
                    setting_get("sitemgr_email", $sitemgr_email);
                    $sitemgr_emails = explode(",", $sitemgr_email);
                    setting_get("sitemgr_banner_email", $sitemgr_banner_email);

					if ($sitemgr_banner_email) {
						$sitemgr_email = $sitemgr_banner_email;
					}

					if ($sitemgr_emails[0]) $sitemgr_email = $sitemgr_emails[0];
					$subject = $emailNotificationObj->getString("subject");
					$body    = $emailNotificationObj->getString("body");
					$body    = system_replaceEmailVariables($body,$id,'banner');
					$body	 = str_replace($_SERVER["HTTP_HOST"], $domain->getstring("url"), $body);
					$subject = system_replaceEmailVariables($subject,$id,'banner');
					$body    = str_replace("DEFAULT_URL", DEFAULT_URL, $body);
					$domain = new Domain(SELECTED_DOMAIN_ID);
					$body	 = str_replace($_SERVER["HTTP_HOST"], $domain->getstring("url"), $body);
					$body = html_entity_decode($body);
					$subject = html_entity_decode($subject);
					system_mail($contactObj->getString("email"), $subject, $body, EDIRECTORY_TITLE." <$sitemgr_email>", $emailNotificationObj->getString("content_type"), "", $emailNotificationObj->getString("bcc"), $error);
				}
			}

			$newest = "1";
			
			setting_get("banner_approve_free", $banner_approve_free);
			
			if (!$banner_approve_free && !$bannerObj->needToCheckOut()){
				$bannerObj->setString("status", "A");
				$bannerObj->save();
			}

			unset($bannerObj);

			if (string_strpos($url_base, "/".MEMBERS_ALIAS."")) header("Location: ".$url_redirect."/index.php?message=".$message."&newest=".$newest);
			else header("Location: ".$url_redirect."/".(($search_page) ? "search.php" : "index.php")."?message=".$message."&newest=".$newest."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "")."");
			exit;

		} else {

			$imageObj = new Image($_POST["image_id"]);
			$imageObj->Delete();
			unset($imageObj);

		}

		$error_message .= $val_message."<br />";
		// removing slashes added if required
		$_POST = format_magicQuotes($_POST);
		$_GET  = format_magicQuotes($_GET);

		extract($_POST);
		extract($_GET);

	}

	/**
	* Update Operation
	****************************************************************************/
	if ($operation == "update") {

		$_POST["caption"] = trim($_POST["caption"]);

		if ((validate_form("banner", $_POST, $val_message, $error_size)) && is_valid_discount_code($_POST["discount_id"], "banner", $_POST["id"], $val_message, $discount_error_num)) {

			// adding new locations if posted
			if ($_POST["new_location2_field"] != "" || $_POST["new_location3_field"] != "" || $_POST["new_location4_field"] != "" || $_POST["new_location5_field"] != "") {

				$locationsToSave = array();

				$_locations = explode(",", EDIR_LOCATIONS);
				$_defaultLocations = explode (",", EDIR_DEFAULT_LOCATIONS);
				$_nonDefaultLocations = array_diff_assoc($_locations, $_defaultLocations);

				foreach ($_defaultLocations as $defLoc)
					$locationsToSave[$defLoc] = $_POST["location_".$defLoc];

				$stop_insert_location = false;

				foreach ($_nonDefaultLocations as $nonDefLoc) {
					if (trim($_POST["location_".$nonDefLoc])!="")
						$locationsToSave[$nonDefLoc] = $_POST["location_".$nonDefLoc];
					else {
						if (!$stop_insert_location) {
							if (!$_POST['new_location'.$nonDefLoc.'_field']) {
								$stop_insert_location = true;
							} else {
								$objNewLocationLabel = "Location".$nonDefLoc;
								$objNewLocation = new $objNewLocationLabel;

								foreach ($locationsToSave as $level => $value)
									$objNewLocation->setString("location_".$level, $value);

								$objNewLocation->setString("name", $_POST['new_location'.$nonDefLoc.'_field']);
								$objNewLocation->setString("friendly_url", $_POST['new_location'.$nonDefLoc.'_friendly']);
								$objNewLocation->setString("default", "n");
								$objNewLocation->setString("featured", "n");
								
								$newLocationFlag = $objNewLocation->retrievedIfRepeated($_locations);
								if ($newLocationFlag) $objNewLocation->setNumber("id", $newLocationFlag);
								else $objNewLocation->Save();
								$_POST["location_".$nonDefLoc] = $objNewLocation->getNumber("id");
								$locationsToSave[$nonDefLoc]=$_POST["location_".$nonDefLoc];
							}
						}
					}
				}
			}	
			
			if (($uploadObj->error_type == 0) || ($uploadObj->error_type == 6)) {
				$message = "";
			}
			$error_message .= $val_message;
            $message = 2;

			$status = new ItemStatus();
			$bannerObj = new Banner($id); // Loading banner info into object
			$last_status = $bannerObj->getString("status");
			
			// Change or not status to Pending and define renew_date
			if (string_strpos($url_base, "/".SITEMGR_ALIAS."")) { 
				$_POST["status"] = $bannerObj->getString("status");

				if (!$result && $_POST["account_id"]!=$bannerObj->account_id){
					$image_idB = $bannerObj->getNumber("image_id");

                    if ($image_idB){

                        $imageChange = new Image($image_idB);
                        if ($imageChange->imageExists()) {
                            $oldPrefix = $imageChange->getString("prefix");
                            $newPrefix = $_POST["account_id"] ? $_POST["account_id"]."_" : "sitemgr_";

                            $img_type = string_strtolower($imageChange->getString("type"));
                            $imageChange->setString("prefix",$newPrefix);
                            $imageChange->Save();

                            $dir = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/image_files";
                            $imageOld = $dir."/".$oldPrefix."photo_".$image_idB.".".$img_type;
                            $imageNew = $dir."/".$newPrefix."photo_".$image_idB.".".$img_type;
                            rename($imageOld, $imageNew);
                        }
                    }
				}

			} else {
				$bannerStatusObj = new ItemStatus();
				if ($bannerObj->getNumber("type") != $_POST["type"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getNumber("section") != $_POST["section"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getNumber("category_id") != $_POST["category_id"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getString("target_window") != $_POST["target_window"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }

				if ($bannerObj->getString("caption") != $_POST["caption"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }

                if ($bannerObj->getString("discount_id") != $_POST["discount_id"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getString("destination_protocol") != $_POST["destination_protocol"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getString("destination_url") != $_POST["destination_url"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getString("display_url") != $_POST["display_url"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }

				if ($bannerObj->getString("content_line1") != $_POST["content_line1"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
				if ($bannerObj->getString("content_line2") != $_POST["content_line2"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }

                if ($_POST["image_id"]) { $_POST["status"] = $bannerStatusObj->getDefaultStatus(); $changed = true; }
			}

			if (!$bannerObj->hasRenewalDate()) {
				$_POST["renewal_date"] = "0000-00-00";
			}
			if (!$bannerObj->hasImpressions()) {
				$_POST["unpaid_impressions"] = 0;
				$_POST["unlimited_impressions"] = "y";
			} else {
				$_POST["unlimited_impressions"] = "n";
			}

			// member can create a banner free and check out it
			// aftet, renewal date will to some periods or impressions will to some blocks
			// because banner is free, member can change his banner type any time
			// if he change his banner type, he MUST pay for this new banner type (it isnt free anymore)
			// any change in banner type, renewal date and impressions go to like new banner
			// ps: just for the case new banner type
			if ($bannerObj->getNumber("type") != $_POST["type"]) {
				$_POST["renewal_date"] = "00/00/0000";
				$_POST["impressions"] = 0;
			}

			if(($bannerObj->getString("approve_feature")=="O"||$bannerObj->getString("approve_feature")=="D") && $bannerObj->getString("section")== $_POST["section"])
			{
				$_POST["approve_feature"] = $bannerObj->getString("approve_feature");
				if($bannerObj->getNumber("feature_onlocation1") != $_POST["feature_onlocation1"])
					$_POST["approve_feature"] = "P";
				if($bannerObj->getNumber("feature_onlocation2") != $_POST["feature_onlocation2"])
					$_POST["approve_feature"] = "P";
				if($bannerObj->getNumber("feature_onlocation3") != $_POST["feature_onlocation3"])
					$_POST["approve_feature"] = "P";
				if($bannerObj->getNumber("category_id") != $_POST["category_id"])
					$_POST["approve_feature"] = "P";
				
			}
			$bannerObj->makeFromRow($_POST); // Loading new info into banner

			if($_POST["type"] < 50) { // Image banners don't have following fields.
				$bannerObj->setString("content_line1","");
				$bannerObj->setString("content_line2","");
			} else { // Text banners don't have images.
				$imageObj = New Image($bannerObj->getNumber("image_id"));
				$imageObj->Delete();
				$bannerObj->setNumber("image_id", "0");
			}

			$bannerObj->Save(); // Saving Banner

			if ((sess_isAccountLogged() && $changed) && (string_strpos($url_base, "/".MEMBERS_ALIAS.""))) {

                // site manager warning message /////////////////////////////////////
                $domain	 = new Domain(SELECTED_DOMAIN_ID);
                $domain_url = ((SSL_ENABLED == "on" && FORCE_SITEMGR_SSL == "on") ? SECURE_URL : NON_SECURE_URL);
				$domain_url = str_replace($_SERVER["HTTP_HOST"], $domain->getstring("url"), $domain_url);
                
				$acctId = sess_getAccountIdFromSession();
				$accountObj = new Account($acctId);
				$contactObj = new Contact($acctId);
                
				setting_get("sitemgr_banner_email",$sitemgr_banner_email);
				$sitemgr_banner_emails = explode(",",$sitemgr_banner_email);
                
                setting_get("update_banner_email", $update_banner_email);

                $error = false;
				
                $emailSubject = system_showText(LANG_NOTIFY_BANNER);
				$sitemgr_msg = system_showText(LANG_LABEL_SITE_MANAGER).",<br /><br />";
				$sitemgr_msg .= ucfirst(system_showText(LANG_BANNER_FEATURE_NAME))." \"".$bannerObj->getString("caption")."\" ".system_showText(LANG_NOTIFY_ITEMS_1)." \"".system_showAccountUserName($accountObj->getString("username"))."\" ".system_showText(LANG_NOTIFY_ITEMS_3)."<br /><br />";
				$sitemgr_msg .= "<a href=\"".$domain_url."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/view.php?id=".$bannerObj->getNumber("id")."\" target=\"_blank\">".$domain_url."/".SITEMGR_ALIAS."/".BANNER_FEATURE_FOLDER."/view.php?id=".$bannerObj->getNumber("id")."</a><br /><br />";
				$sitemgr_msg .= EDIRECTORY_TITLE;
                $error = false;

				if ($update_banner_email){ 
                    system_notifySitemgr($sitemgr_banner_emails, $emailSubject, $sitemgr_msg);
                }

			}
			
			if (string_strpos($url_base, "/".MEMBERS_ALIAS."")) {
				setting_get("banner_approve_updated", $banner_approve_updated);
				if ($last_status == "A" && !$bannerObj->needToCheckOut() && !$banner_approve_updated && $process != "signup"){
					$bannerObj->setString("status", "A");
					$bannerObj->save();
				}else if ($process == "signup"){
					$bannerObj->setString("status", $last_status);
					$bannerObj->save();
				}
			}

			unset($bannerObj);

			header("Location: ".$url_redirect."/".(($search_page) ? "search.php" : "index.php")."?process=".$process."&newest=".$newest."&message=".$message."&screen=$screen&letter=$letter".(($url_search_params) ? "&$url_search_params" : "")."");
			exit;

		}

		$error_message .= $val_message."<br />";

		$_POST = format_magicQuotes($_POST);
		$_GET  = format_magicQuotes($_GET);

		extract($_POST);
		extract($_GET);

	}


	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------

	// Location General Defines
	$_non_default_locations = "";
	$_default_locations_info = "";
	if (EDIR_DEFAULT_LOCATIONS) {

		system_retrieveLocationsInfo ($_non_default_locations, $_default_locations_info);

		$last_default_location	  =	$_default_locations_info[count($_default_locations_info)-1]['type'];
		$last_default_location_id = $_default_locations_info[count($_default_locations_info)-1]['id'];

		if ($_non_default_locations) {
			$objLocationLabel = "Location".$_non_default_locations[0];
			${"Location".$_non_default_locations[0]} = new $objLocationLabel;
			${"Location".$_non_default_locations[0]}->SetString("location_".$last_default_location, $last_default_location_id);
			${"locations".$_non_default_locations[0]} = ${"Location".$_non_default_locations[0]}->retrieveLocationByLocation($last_default_location);
		}

	} else {
		$_non_default_locations = explode(",", EDIR_LOCATIONS);
		$objLocationLabel = "Location".$_non_default_locations[0];
		${"Location".$_non_default_locations[0]} = new $objLocationLabel;
		${"locations".$_non_default_locations[0]}  = ${"Location".$_non_default_locations[0]}->retrieveAllLocation();
	}
	// End Location General Defines
	/**
	* Field values
	****************************************************************************/
	if ($id) {

		$bannerObj    = new Banner($id);
		$banner_types = $bannerObj->GetString("banner_types");

		// Making local vars from banner object.
		$destination_url		= ($_POST["destination_url"])		? $_POST["destination_url"]			: $bannerObj->getString("destination_url", true, 0, "", false);
		$display_url			= ($_POST["display_url"])			? $_POST["display_url"]				: $bannerObj->getString("display_url", true, 0, "", false);
		$destination_protocol	= ($_POST["destination_protocol"])	? $_POST["destination_protocol"]	: $bannerObj->getString("destination_protocol");

		$caption				= ($_POST["caption"])				? $_POST["caption"]                 : $bannerObj->getString("caption", true, 0, "", false);

        $discount_id			= ($_POST["discount_id"])			? $_POST["discount_id"]				: $bannerObj->getString("discount_id", true, 0, "", false);
		$id						= $bannerObj->getString("id");

		$image_id				= ($_POST["image_id"])				? $_POST["image_id"]				: $bannerObj->getNumber("image_id");

		$type					= ($_POST["type"])					? $_POST["type"]					: $bannerObj->getString("type");
		$feature_onlocation1	= ($_POST["feature_onlocation1"])	? $_POST["feature_onlocation1"]		: $bannerObj->getString("feature_onlocation1");
		$feature_onlocation2	= ($_POST["feature_onlocation2"])	? $_POST["feature_onlocation2"]		: $bannerObj->getString("feature_onlocation2");
		$feature_onlocation3	= ($_POST["feature_onlocation3"])	? $_POST["feature_onlocation3"]		: $bannerObj->getString("feature_onlocation3");
		$location_1	= $feature_onlocation1;
		$location_2	= $feature_onlocation2;
		$location_3	= $feature_onlocation3;
		$approve_feature		= ($_POST["approve_feature"])		? $_POST["approve_feature"]			: $bannerObj->getString("approve_feature");
		$section				= ($_POST["section"])				? $_POST["section"]					: $bannerObj->getString("section");
		$account_id				= ($_POST["account_id"])			? $_POST["account_id"]				: $bannerObj->getString("account_id");
		$category_id			= ($_POST["category_id"])			? $_POST["category_id"]				: $bannerObj->getString("category_id");
		$renewal_date			= ($_POST["renewal_date"])			? $_POST["renewal_date"]			: $bannerObj->getDate("renewal_date");
		$target_window			= ($_POST["target_window"])			? $_POST["target_window"]			: $bannerObj->getNumber("target_window");

		$content_line1			= ($_POST["content_line1"])		? $_POST["content_line1"]			: $bannerObj->getNumber("content_line1", true, 0, "", false);

		$content_line2			= ($_POST["content_line2"])		? $_POST["content_line2"]			: $bannerObj->getNumber("content_line2", true, 0, "", false);

		$expiration_setting		= ($_POST["expiration_setting"])	? $_POST["expiration_setting"]		: $bannerObj->getNumber("expiration_setting");
		$unpaid_impressions		= ($_POST["unpaid_impressions"])	? $_POST["unpaid_impressions"]		: (($_POST["type"] == $bannerObj->getNumber("type") || !$_POST["type"]) ? $bannerObj->getNumber("unpaid_impressions") : "0");
		$impressions			= ($_POST["impressions"])			? $_POST["impressions"] 			: $bannerObj->getNumber("impressions");
		$show_type				= ($_POST["show_type"])				? $_POST["show_type"] 				: $bannerObj->getNumber("show_type");
		$script					= ($_POST["script"])				? $_POST["script"] 					: $bannerObj->getString("script", true, 0, "", false);

		unset($bannerObj);

		$thisBannerObject = new Banner($id);
		
		// Location defines begin for edit listing
		$stop_search_locations = false;
		//if there is at least one non default location
		if ($_non_default_locations) {
			foreach($_non_default_locations as $_location_level) {
				system_retrieveLocationRelationship ($_non_default_locations, $_location_level, $_location_father_level, $_location_child_level);
				if (${'location_'.$_location_level} && $_location_child_level) {
					if (!$stop_search_locations) {
						$objLocationLabel = "Location".$_location_child_level;
						${"Location".$_location_child_level} = new $objLocationLabel;
						${"Location".$_location_child_level}->SetString("location_".$_location_level, ${"location_".$_location_level});
						${"locations".$_location_child_level} = ${"Location".$_location_child_level}->retrieveLocationByLocation($_location_level);
					} else 	${"locations".$_location_child_level} = "";
				} else $stop_search_locations = true;
			}
			unset ($_location_father_level);
			unset ($_location_child_level);
			unset ($_location_level);
		}
		// End Locations
	}
	else 
	{
	
	// Location defines begin for add listing
		$stop_search_locations = false;
		//if there is at least one non default location
		if ($_non_default_locations) {
			foreach($_non_default_locations as $_location_level) {
				if ($_POST["location_".$_location_level])
					${"location_".$_location_level} = $_POST["location_".$_location_level];
				else
					$stop_search_locations = true;
				system_retrieveLocationRelationship ($_non_default_locations, $_location_level, $_location_father_level, $_location_child_level);
				if (${'location_'.$_location_level} && $_location_child_level) {
					if (!$stop_search_locations) {
						$objLocationLabel = "Location".$_location_child_level;
						${"Location".$_location_child_level} = new $objLocationLabel;
						${"Location".$_location_child_level}->SetString("location_".$_location_level, ${"location_".$_location_level});
						${"locations".$_location_child_level} = ${"Location".$_location_child_level}->retrieveLocationByLocation($_location_level);
					} else 	${"locations".$_location_child_level} = "";
				} else $stop_search_locations = true;
			}
			unset ($_location_father_level);
			unset ($_location_child_level);
			unset ($_location_level);
		}
		// End Locations
	}
	/**
	* Banner Drop Down
	****************************************************************************/
	$bannerObj = new Banner();
    $bannerLevel = new BannerLevel(true);

	$nameArray  = array();
	$valueArray = array();

	foreach($bannerObj->banner_types as $each_type => $each_value){

		$bannerLevelObj = new BannerLevel();
        if($bannerLevelObj->getActive($each_value)) {
		    $banner_size = "(".$bannerLevelObj->getWidth($each_value)."px x ".$bannerLevelObj->getHeight($each_value)."px)";

		    $nameArray[]  = string_ucwords($bannerLevel->getDisplayName($each_value))." ".$banner_size;
		    $valueArray[] = $each_value;
        }

	}
    $forceTextForm = false;
    if (count($valueArray) == 1 && $valueArray[0] >= 50){
        $forceTextForm = true;
    }

	$type = (int)$type==0 ? "1" : $type;
	$banner_script = (string_strpos($url_base, "/".SITEMGR_ALIAS."")) ? "onchange=\"bannerCheckType(this.value)\"" : "onchange=\"bannerFillSelect('".DEFAULT_URL."',this.form.unpaid_impressions, this.value,".SELECTED_DOMAIN_ID.");bannerCheckType(this.value);\"";
	$bannerTypeDropDown = html_selectBox("type", $nameArray, $valueArray, $type, $banner_script, "class='input-dd-form-banner'", "-- ".system_showText(LANG_LABEL_SELECT_TYPE)." --");

	unset($bannerObj);

	/**
	* Impressions Drop Down
	****************************************************************************/
	$nameArray  = array();
	$valueArray = array();

	for($i=0; $i < 50; $i++){
		$bannerLevelObj = new BannerLevel(true);
		$type = ($type) ? $type : $bannerLevelObj->getDefaultLevel();
		$nameArray[]  = $bannerLevelObj->getImpressionBlock($type)*$i;
		$valueArray[] = $bannerLevelObj->getImpressionBlock($type)*$i;
	}
	$disabled = (!$expiration_setting || $expiration_setting != BANNER_EXPIRATION_IMPRESSION) ? "disabled=true" : "";
	$bannerImpressionDropDown = html_selectBox("unpaid_impressions", $nameArray, $valueArray, $unpaid_impressions, "id='unpaid_impressions' $disabled", "style=\" width: 120px;\"");

	unset($bannerLevelObj);

	/**
	* Category Drop Down
	****************************************************************************/
	$nameArray  = array();
	$valueArray = array();
	$categoryScript = "onchange=\"checkSkip(this);\"";
	if (!$section || $section == "general") {
		array_push($nameArray, system_showText(LANG_ALLPAGESBUTITEMPAGES));
		$categoryDropDown = html_selectBox("category_id", $nameArray, $valueArray, $category_id, $categoryScript, "class='input-dd-form-banner' style='width: 350px;'", system_showText(LANG_ALLPAGESBUTITEMPAGES));
	} elseif (!$section || $section == "global") {
		array_push($nameArray, system_showText(LANG_ALLPAGES));
        $categoryDropDown = html_selectBox("category_id", $nameArray, $valueArray, $category_id, $categoryScript, "class='input-dd-form-banner' style='width: 350px;'", system_showText(LANG_ALLPAGES));
    } else {
		if ($section == "listing" || $section == "promotion") $tableCategory = "listingcategory";
		elseif ($section == "event") $tableCategory = "eventcategory";
		elseif ($section == "classified") $tableCategory = "classifiedcategory";
		elseif ($section == "article") $tableCategory = "articlecategory";
		elseif ($section == "blog") $tableCategory = "blogcategory";

        $categoryScalability = @constant(string_strtoupper(($section == "promotion" ? "listing" : $section))."CATEGORY_SCALABILITY_OPTIMIZATION");
		unset($where);
		$where = "category_id = 0 AND enabled = 'y'";
		$categories = db_getFromDB($tableCategory, "", "", MAX_SHOW_ALL_CATEGORIES, "title", "object", SELECTED_DOMAIN_ID, false, "*", $where);
		if ($categories) {
			foreach ($categories as $category) {
				if ($category->getString("title") && $category->getString("enabled") == "y") {
					if ($categoryScalability != "on") {
						$valueArray[]  = "";
						$nameArray[]   = "--------------------------------------------------";
					}
					$valueArray[]  = $category->getNumber("id");
					$nameArray[]   = $category->getString("title");
					$where = "category_id = ".$category->getNumber("id")." AND enabled = 'y'";
					$subcategories = db_getFromDB($tableCategory, "", "", MAX_SHOW_ALL_CATEGORIES, "title", "object", SELECTED_DOMAIN_ID, false, "*", $where);
					if ($subcategories && $categoryScalability != "on") {
						foreach ($subcategories as $subcategory) {
							if ($subcategory->getString("title") && $subcategory->getString("enabled") == "y") {
								$valueArray[] = $subcategory->getNumber("id");
								$nameArray[]  = "- ".$subcategory->getString("title");
								$where = "category_id = ".$subcategory->getNumber("id")." AND enabled = 'y'";
								$subcategories2 = db_getFromDB($tableCategory, "", "", MAX_SHOW_ALL_CATEGORIES, "title", "object", SELECTED_DOMAIN_ID, false, "*", $where);
								if ($subcategories2) {
									foreach ($subcategories2 as $subcategory2) {
										if ($subcategory2->getString("title") && $subcategory2->getString("enabled") == "y") {
											$valueArray[] = $subcategory2->getNumber("id");
											$nameArray[]  = "-- ".$subcategory2->getString("title");
											$where = "category_id = ".$subcategory2->getNumber("id")." AND enabled = 'y'";
											$subcategories3 = db_getFromDB($tableCategory, "", "", MAX_SHOW_ALL_CATEGORIES, "title", "object", SELECTED_DOMAIN_ID, false, "*", $where);
											if ($subcategories3) {
												foreach ($subcategories3 as $subcategory3) {
													if ($subcategory3->getString("title") && $subcategory3->getString("enabled") == "y") {
														$valueArray[] = $subcategory3->getNumber("id");
														$nameArray[]  = "--- ".$subcategory3->getString("title");
														$where = "category_id = ".$subcategory3->getNumber("id")." AND enabled = 'y'";
														$subcategories4 = db_getFromDB($tableCategory, "", "", MAX_SHOW_ALL_CATEGORIES, "title", "object", SELECTED_DOMAIN_ID, false, "*", $where);
														if ($subcategories4) {
															foreach ($subcategories4 as $subcategory4) {
																if ($subcategory4->getString("title") && $subcategory4->getString("enabled") == "y") {
																	$valueArray[] = $subcategory4->getNumber("id");
																	$nameArray[]  = "---- ".$subcategory4->getString("title");
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if ($categoryScalability != "on") {
			$valueArray[]  = "";
			$nameArray[]   = "--------------------------------------------------";
		}
		$categoryDropDown = html_selectBox("category_id", $nameArray, $valueArray, $category_id, $categoryScript, "class='input-dd-form-banner' style='width:350px;'", system_showText(LANG_NONCATEGORYSEARCH));
	}
	
	$bannerApprObj = new Banner();
	$skipArr = $bannerApprObj->getAllApprovedCategoryLocation(); 
	unset($bannerApprObj);
	if($skipArr)
		foreach($skipArr as $skip)
		{
			$skipItem[$skip["section"]][] = array($skip["category_id"],$skip["feature_onlocation3"]);
		}
	
?>