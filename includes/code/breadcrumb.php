<?

	/* ==================================================================*\
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
	\*================================================================== */

	# ----------------------------------------------------------------------------------------------------
	# * FILE: /includes/code/breadcrumb.php
	# ----------------------------------------------------------------------------------------------------
	$queryString = $_SERVER["REQUEST_URI"];
	$type_array = explode("/", $queryString);
	$sub_folder = 0;
    $type = system_retriveModuleByAliasURL();
    
	if (EDIRECTORY_FOLDER != '') {
		$sub_folder = string_substr(EDIRECTORY_FOLDER, 1);
		$countAux = string_substr_count($sub_folder, "/");
		$count = 0;
		$count = 2;
		$page = $type_array[3 + $countAux];
	} else {
		$page = $type_array[2];
	}

	if ($_SERVER["REQUEST_METHOD"] == "GET") {

		extract($_GET);
		
		/**
		 * @categories
		 */
		if ($_GET["category_id"] && !$section) {
			$section = "category";
			$item_id = $category_id;
			if ($type == PROMOTION_FEATURE_FOLDER) {
				$typeObj = "ListingCategory";
            } else {
				$typeObj = ucfirst($type) . "Category";
            }
			$category = new $typeObj($item_id);
			$category_name = $category->title;
			$category_url = "/$type/results.php?category_id=$item_id";
		}

		/**
		 * @locations
		 */
		if (!$section && ($location_1 || $location_2 || $location_3 || $location_4 || $location_5 || $dist)) {
			$section = "location";
			$item_id = array();
			$location_name = array();
			$location_url = "/$type/results.php?qr=";

			$_locations = explode(",", EDIR_LOCATIONS);
			$locations_default = explode(",", EDIR_DEFAULT_LOCATIONSHOW);
			$j = 0;
			foreach ($_locations as $_location) {

				if ($location_1 && $_location == 1)
					$id = $location_1;
				elseif ($location_2 && $_location == 2)
					$id = $location_2;
				elseif ($location_3 && $_location == 3)
					$id = $location_3;
				elseif ($location_4 && $_location == 4)
					$id = $location_4;
				elseif ($location_5 && $_location == 5)
					$id = $location_5;
				$j++;
				if ($id) {
					$class = "Location" . $_location;
					$locationObj = new $class($id);
					$item_id[] = $id;
					$location_name[] = $locationObj->name;
					$location_url .= "&location_" . $_location . "=$id";
				}
				unset($id);
			}
		}

		if ($page == ALIAS_ALLLOCATIONS_URL_DIVISOR.".php")
			$section = "alllocations";
		if ($page == ALIAS_ALLCATEGORIES_URL_DIVISOR.".php")
			$section = "allcategories";
		if (($type == '') || (string_strpos($type, ".php") != ''))
			$section = "home";
	}
	if (($sitecontentSection == "Error Page") || ($type == '') || (string_strpos($type, ".php") != ''))
		$section = "home";
    # ----------------------------------------------------------------------------------------------------