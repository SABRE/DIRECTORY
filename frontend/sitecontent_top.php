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
	# * FILE: /frontend/sitecontent_top.php
	# ----------------------------------------------------------------------------------------------------

	/*setting_get("domain".SELECTED_DOMAIN_ID."_filter", $is_filter_on);
	if($is_filter_on=="on")
	{
		include(system_getFrontendPath("result_filters.php"));
	}*/
	if ($sitecontent) { 
		echo "<div class=\"content-custom\">".$sitecontent."</div>";
	}
?>