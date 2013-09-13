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
	# * FILE: /theme/default/body/event/detail.php
	# ----------------------------------------------------------------------------------------------------

?>
	<div class="content">
		
		<div class="content-main">	
			<? include(system_getFrontendPath("detailview.php", "frontend", false, EVENT_EDIRECTORY_ROOT)); ?>
		</div>
		
		<? include(system_getFrontendPath("banner_bottom.php")); ?>
		
	</div>

	<div class="sidebar">
		<? include(system_getFrontendPath("join.php", "frontend", false, EVENT_EDIRECTORY_ROOT)); ?>
                <? //setting_get("domain".SELECTED_DOMAIN_ID."_filter", $is_filter_on);
		//if($is_filter_on=="on")
		//{
			include(system_getFrontendPath("result_filters.php"));
		//}?>
		<? include(system_getFrontendPath("detail_maps.php", "frontend", false, EVENT_EDIRECTORY_ROOT)); ?>
        <? include(system_getFrontendPath("event_calendar.php")); ?>
        <? include(system_getFrontendPath("banner_featured.php")); ?>
		<? include(system_getFrontendPath("banner_sponsoredlinks.php")); ?>
		<? include(system_getFrontendPath("googleads.php")); ?>
	</div>