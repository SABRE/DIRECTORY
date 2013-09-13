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
	# * FILE: /includes/code/browsebylocation.php
	# ----------------------------------------------------------------------------------------------------

    if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) {
        $cacheType = "sidebar_location_listing";
        $moduleURL = LISTING_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
        $cacheType = "sidebar_location_classified";
        $moduleURL = CLASSIFIED_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
        $cacheType = "sidebar_location_event";
        $moduleURL = EVENT_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
        $cacheType = "sidebar_location_promotion";
        $moduleURL = PROMOTION_DEFAULT_URL;
    } else {
        $cacheType = "sidebar_location_listing";
        $moduleURL = LISTING_DEFAULT_URL;
    }

	if (FEATURED_LOCATION == "on") {

        $locationsToShow = system_retrieveLocationsToShow($type = "array");

        if ($locationsToShow) {

            $_locations = explode(",", EDIR_LOCATIONS);

            $found_featLocations = false;
            $defaultLocationsToShow = false;
            $nonDefaultLocationsToShow = false;
            $flag_showNonFeatures = false;
            $last_default_level = false;
            $last_default_id = false;

            if (EDIR_DEFAULT_LOCATIONS) {
                $defaultLocations = explode (",", EDIR_DEFAULT_LOCATIONS);
                $defaultLocationsToShow = array_intersect($defaultLocations, $locationsToShow);
                $nonDefaultLocationsToShow = array_diff($locationsToShow, $defaultLocationsToShow);
                if ($defaultLocationsToShow) {
                    $count_locationFeatLevels = 0;
                    foreach($defaultLocationsToShow as $_location_level) {
                        $count_locationFeatLevels++;
                        if ($count_locationFeatLevels <= FEATUREDLOCATION_LEVEL_AMOUNT) {
                            $objLocationLabel = "Location".$_location_level;
                            ${"Location".$_location_level} = new $objLocationLabel;
                            $locations_info = db_getFromDB("settinglocation", "id", $_location_level, 1, "", "array", SELECTED_DOMAIN_ID);

                            ${"locations".$_location_level} = ${"Location".$_location_level}->retrieveFeatureds($_locations, $locations_info["default_id"]);
                            if (count(${"locations".$_location_level})) {
                                if (!$found_featLocations) {
                                    $found_featLocations = true;
                                }
                            }
                        }
                    }
                    $found_featLocations = true;
                }
            } else {
                $nonDefaultLocationsToShow = $locationsToShow;
            }

            system_retrieveLastDefaultLevel($last_default_level, $last_default_id);

            if ($nonDefaultLocationsToShow) {
                $count_locationFeatLevels = ($defaultLocationsToShow?count($defaultLocationsToShow):0);
                foreach($nonDefaultLocationsToShow as $_location_level) {
                    $count_locationFeatLevels++;
                    if ($count_locationFeatLevels <= FEATUREDLOCATION_LEVEL_AMOUNT) {
                        $objLocationLabel = "Location".$_location_level;
                        ${"Location".$_location_level} = new $objLocationLabel;
                        ${"locations".$_location_level} = ${"Location".$_location_level}->retrieveFeatureds($_locations, false, $last_default_level, $last_default_id);
                        if (count(${"locations".$_location_level})) {
                            if (!$found_featLocations) {
                                $found_featLocations = true;
                            }
                        }
                    }
                }
            }
        }
	}
?>