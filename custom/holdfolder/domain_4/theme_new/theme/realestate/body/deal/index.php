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
	# * FILE: /theme/realestate/body/deal/index.php
	# ----------------------------------------------------------------------------------------------------
        
?>

	<div class="content-center">

		<? include(system_getFrontendPath("breadcrumb.php")); ?>
        
        <? include(system_getFrontendPath("special_deal.php", "frontend", false, PROMOTION_EDIRECTORY_ROOT)); ?>
    
        <div class="content">
        
            <div class="content-top">
                <? include(system_getFrontendPath("sitecontent_top.php")); ?>
            </div>
            
            <div class="content-main">	
                <? include(system_getFrontendPath("featured.php", "frontend", false, PROMOTION_EDIRECTORY_ROOT)); ?>
                <? include(system_getFrontendPath("browsebycategory.php", "frontend", false, PROMOTION_EDIRECTORY_ROOT)); ?>
                <? include(system_getFrontendPath("featured_promotion_review.php")); ?>
                <? include(system_getFrontendPath("sitecontent_bottom.php")); ?>
            </div>
            
            <? include(system_getFrontendPath("banner_bottom.php")); ?>
            
        </div>
    
        <div class="sidebar">
            <? include(system_getFrontendPath("searchSidebar.php")); ?>
            <? include(system_getFrontendPath("browsebylocation.php", "frontend", false, PROMOTION_EDIRECTORY_ROOT)); ?>
            <? include(system_getFrontendPath("banner_featured.php")); ?>
            <? include(system_getFrontendPath("banner_sponsoredlinks.php")); ?>
            <? include(system_getFrontendPath("googleads.php")); ?>
        </div>
        
	</div>