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
	# * FILE: /edir_core/blog/detailview.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	if (!$postMsg) {
		$user = true;
        
        $detailFileName = INCLUDES_DIR."/views/view_post_detail.php";
        $themedetailFileName = INCLUDES_DIR."/views/view_post_detail_".EDIR_THEME.".php";

        if (file_exists($themedetailFileName)){
            include($themedetailFileName);
        } else {
            include($detailFileName);
        }
	} else { ?>
		<p class="errorMessage"><?=$postMsg?></p>
    <? }
?>