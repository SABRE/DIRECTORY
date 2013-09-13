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
	# * FILE: /frontend/category_banner.php
	# ----------------------------------------------------------------------------------------------------
	
	//setting_get("domain".SELECTED_DOMAIN_ID."_category_banner", $is_category_banner_on);
	//if($is_category_banner_on!="on")
	//{
	//	return;
	//}
        
	front_getBannerInfo($category_item_id, $banner_section);

	$location_sql = "";
	$connect = "";
	$only_location1 = true;
	if(isset($_GET["location_1"]) && $_GET["location_1"]!="" && $_GET["location_1"]>0)
	{   
		$connect = $location_sql==""?"":" AND";
		$location_sql .= $connect." feature_onlocation1 = ".$_GET["location_1"]; 
	}
	if(isset($_GET["location_2"]) && $_GET["location_2"]!="" && $_GET["location_2"]>0)
	{
		$connect = $location_sql==""?"":" AND";
		$location_sql .= $connect." feature_onlocation2 = ".$_GET["location_2"]; 
		echo $only_location1 = false;
	}
	if(isset($_GET["location_3"]) && $_GET["location_3"]!="" && $_GET["location_3"]>0)
	{
		$connect = $location_sql==""?"":" AND";
		$location_sql .= $connect." feature_onlocation3 = ".$_GET["location_3"]; 
		echo $only_location1 = false;
	}
	
     $banner = system_showBanner("CATEGORY BANNER", $category_item_id, $banner_section, 1, ($only_location1?"":$location_sql));
	
	if ($banner) { ?>
		<div class="advertisement advertisement-category">
			<div class="banner"><?=$banner?></div>
			<div class="info">
				<p>
					<a href="<?=((SSL_ENABLED == "on" && FORCE_MEMBERS_SSL == "on" && FORCE_ORDER_SSL == "on") ? SECURE_URL : NON_SECURE_URL)?>/order_banner.php?type=4">
						<?//=system_showText(LANG_DOYOUWANT_ADVERTISEWITHUS);?>
					</a>
				</p>
			</div>
		</div>			
	<? } ?>