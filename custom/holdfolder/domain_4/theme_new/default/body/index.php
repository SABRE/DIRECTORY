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
	# * FILE: /theme/default/body/index.php
	# ----------------------------------------------------------------------------------------------------

?>

	<div class="contentLeft"></div>
	<div class="document">
	<div class="content">
		<? include(system_getFrontendPath("slider.php")); ?>
		<div class="content-main">
			<? include(system_getFrontendPath("sitecontent_top.php")); ?>
			<? include(system_getFrontendPath("featured_listing.php")); ?>
			<? include(system_getFrontendPath("featured_promotion.php")); ?>
			<? include(system_getFrontendPath("featured_classified.php")); ?>
			<? include(system_getFrontendPath("featured_event.php")); ?>
			<? include(system_getFrontendPath("featured_article.php")); ?>
			<? include(system_getFrontendPath("sitecontent_bottom.php")); ?>		
		</div>
		
		<? include(system_getFrontendPath("banner_bottom.php")); ?>
		
	</div>

	<div class="sidebar">
		
		<? include(THEMEFILE_DIR."/".EDIR_THEME."/layout/navbuttons.php");?>
		
		<? include(system_getFrontendPath("join.php")); ?>
		<? //setting_get("domain".SELECTED_DOMAIN_ID."_filter", $is_filter_on);
		//if($is_filter_on=="on")
		//{
			include(system_getFrontendPath("result_filters.php"));
		//}?>
        <!--I am commenting out the accordion on the home page remove comment tag to bring it back. -->  
		<!-- <div id="sidebar_ajax"></div> -->
		
        <? include(system_getFrontendPath("banner_featured.php")); ?>
		<? include(system_getFrontendPath("banner_sponsoredlinks.php")); ?>
		<? include(system_getFrontendPath("googleads.php")); ?>
	</div>
</div>
<div class="contentRight"></div>