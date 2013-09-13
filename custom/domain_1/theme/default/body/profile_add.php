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
	# * FILE: /theme/default/body/profile_add.php
	# ----------------------------------------------------------------------------------------------------

?>

	<div class="content">
	
        <div class="content-top">
            <? include(system_getFrontendPath("sitecontent_top.php")); ?>
        </div>
		
		<div class="content-main">
			<? include(system_getFrontendPath("socialnetwork/add_account.php")); ?>
			<? include(system_getFrontendPath("sitecontent_bottom.php")); ?>
		</div>
		
		<? include(system_getFrontendPath("banner_bottom.php")); ?>
		
	</div>

	<div class="sidebar">

        <? //setting_get("domain".SELECTED_DOMAIN_ID."_filter", $is_filter_on);
	//if($is_filter_on=="on")
	//{
		include(system_getFrontendPath("result_filters.php"));
	//}?>
		<? include(system_getFrontendPath("banner_featured.php")); ?>
		<? include(system_getFrontendPath("banner_sponsoredlinks.php")); ?>
		<? include(system_getFrontendPath("googleads.php")); ?>
	</div>