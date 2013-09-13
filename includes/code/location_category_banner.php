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
	# * FILE: /includes/code/location_category_banner.php
	# ----------------------------------------------------------------------------------------------------
	
	$bannerCategoryObj = new Banner();
	$CategoryBanners = $bannerCategoryObj->getAllWithCategoryLocation();
	foreach($CategoryBanners as $banner_cat)
	{
		$location_name = "";
		$connector = "";
		$location_link = "location_1=".$banner_cat['feature_onlocation1'];

		if($show_country)
		{
			$loc1Obj = new Location1($banner_cat['feature_onlocation1']);
			$location_country_name = $loc1Obj->getString("name");
			$location_link_country = $location_link;
			$location_name .= $location_country_name;
			$connector = ",";
		}
		if($banner_cat['feature_onlocation2']>0 && $using_location2)
		{
			$location_link .= "&location_2=".$banner_cat['feature_onlocation2'];
			$loc2Obj = new Location2($banner_cat['feature_onlocation2']);
			$location_region_name = $loc2Obj->getString("name");
			$location_link_region = $location_link;
			$location_name = $location_region_name.",".$location_name;
			$connector = ",";
		}
		if($banner_cat['feature_onlocation3']>0 && $using_location3)
		{
			$location_link .= "&location_3=".$banner_cat['feature_onlocation3'];
			$loc3Obj = new Location3($banner_cat['feature_onlocation3']);
			$location_state_name = $loc3Obj->getString("name");
			$location_name = $location_state_name.$connector.$location_name;
		}
		
		$moduleCat = ucfirst($banner_cat['section'])."Category";
		if($banner_cat['section']=="promotion")
			$moduleCat = "ListingCategory";
		$catObj = new $moduleCat($banner_cat['category_id']);
		$category_name = $catObj->getString("title");
		$category_link ="category_".$banner_cat['section']."_id=".$banner_cat['category_id'];
		
		$location_category = $category_link."&".$location_link;
		
		$status = $banner_cat['approve_feature']=="O"?"O":"P";
		
		$active_banner = $banner_cat['approve_feature']=="O"?$banner_cat['caption']:system_showText(LANG_NA);
		$active_banner_link = $banner_cat['approve_feature']=="O"?"id=".$banner_cat['id']:"";

		$process_array = false;
		if(isset($loc) && isset(${"category_".$banner_cat['section']."_id"}))
		{
			if(($loc == $location_link||$loc == $location_link_country||$loc == $location_link_region) && isset(${"category_".$banner_cat['section']."_id"}) && ${"category_".$banner_cat['section']."_id"}== $banner_cat['category_id'])
			{
				$process_array = true;
				$added_info = ">> (".ucfirst($banner_cat['section']).") ".$category_name." in ".$location_name;
			}
			$choose_banner_to_approve = $banner_cat['section'];
			
			
		}
		else if(isset($loc))
		{
			if($loc == $location_link||$loc == $location_link_country||$loc == $location_link_region)
			{
				$process_array = true;
				$added_info = ">> ".$location_name;
			}
		}
		else if(isset(${"category_".$banner_cat['section']."_id"}))
		{
			if(${"category_".$banner_cat['section']."_id"} == $banner_cat['category_id'])
			{
				$process_array = true;
				$added_info = ">> (".ucfirst($banner_cat['section']).") ".$category_name;
			}
			$choose_banner_to_approve = $banner_cat['section'];
			
		}
		else
		{
			$process_array = true;
		}
			
		if($process_array)
		{
			if(!isset(${"categoy_banner_".$banner_cat['section']}[$location_category]))
			{
				${"categoy_banner_".$banner_cat['section']}[$location_category][0] = $category_name;
				//category_link
				${"categoy_banner_".$banner_cat['section']}[$location_category][1] = $category_link;
				${"categoy_banner_".$banner_cat['section']}[$location_category][2] = 1;
				${"categoy_banner_".$banner_cat['section']}[$location_category][3] = $location_state_name;
				//location_link
				${"categoy_banner_".$banner_cat['section']}[$location_category][4] = $location_link;
				//status
				${"categoy_banner_".$banner_cat['section']}[$location_category][5] = $status;
				//active_banner
				${"categoy_banner_".$banner_cat['section']}[$location_category][6] = $active_banner;
				//active_banner_link
				${"categoy_banner_".$banner_cat['section']}[$location_category][7] = $active_banner_link;
				if($show_country)
				{
					${"categoy_banner_".$banner_cat['section']}[$location_category][8] = $location_country_name;
					${"categoy_banner_".$banner_cat['section']}[$location_category][9] = $location_link_country;
				}
				if($using_location2)
				{
					${"categoy_banner_".$banner_cat['section']}[$location_category][10] = $location_region_name;
					${"categoy_banner_".$banner_cat['section']}[$location_category][11] = $location_link_region;
				}
			}else{
				if(isset(${"categoy_banner_".$banner_cat['section']}[$location_category]))
					${"categoy_banner_".$banner_cat['section']}[$location_category][2]++;
			}
		}
	}
?>
