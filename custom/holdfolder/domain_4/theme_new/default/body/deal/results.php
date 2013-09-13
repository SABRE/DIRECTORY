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
	# * FILE: /theme/default/body/deal/results.php
	# ----------------------------------------------------------------------------------------------------

	/*
	 * Prepare content to results
	 */
	include(PROMOTION_EDIRECTORY_ROOT."/searchresults.php");

?>

	<div class="contentLeft"></div>
	<div class="document">
	<div class="content">
		
		<? include(system_getFrontendPath("breadcrumb.php")); ?>
		
		<div class="content-top">
			<? include(system_getFrontendPath("sitecontent_top.php")); ?>
			<? include(system_getFrontendPath("results_info.php")); ?>
		</div>

		<? include(system_getFrontendPath("results_filter.php")); ?>
		<? include(system_getFrontendPath("results_pagination.php")); ?>
		
		<? include(system_getFrontendPath("category_banner.php")); ?>
		
		<div class="content-main">
			<? include(PROMOTION_EDIRECTORY_ROOT."/results_deals.php"); ?>
		</div>
		
		<? 
		$pagination_bottom = true;
		include(system_getFrontendPath("results_pagination.php"));
		?>
		<? include(system_getFrontendPath("sitecontent_bottom.php")); ?>
		
		<? include(system_getFrontendPath("banner_bottom.php")); ?>
	
	</div>

	<div class="sidebar">
		<? include(system_getFrontendPath("join.php", "frontend", false, PROMOTION_EDIRECTORY_ROOT)); ?>
		<? //setting_get("domain".SELECTED_DOMAIN_ID."_filter", $is_filter_on);
		//if($is_filter_on=="on")
		//{
			include(system_getFrontendPath("result_filters.php"));
		//}?>
		<? include(system_getFrontendPath("results_maps.php")); ?>
		<? include(system_getFrontendPath("relatedcategories.php", "frontend", false, PROMOTION_EDIRECTORY_ROOT)); ?>
        <? include(system_getFrontendPath("featured_promotion_review.php")); ?>
		<? include(system_getFrontendPath("banner_featured.php")); ?>
		<? include(system_getFrontendPath("banner_sponsoredlinks.php")); ?>
		<? include(system_getFrontendPath("googleads.php")); ?>
	</div>
</div>
<div class="contentRight"></div>