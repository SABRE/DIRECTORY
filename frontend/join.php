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
	# * FILE: /frontend/join.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	include(EDIRECTORY_ROOT."/includes/code/join.php");

	if (SOCIALNETWORK_FEATURE == "on") {
		if (!sess_getAccountIdFromSession()) { ?>
            <div class="button button-profile">
                <h2>
                    <a href="<?=SOCIALNETWORK_URL;?>/add.php"><?=system_showText(LANG_JOIN_PROFILE);?></a>
                </h2>
				<p>Interact and access profile pages, comments, favorites, deals and so much <strong>more!</strong></p>
            </div>
		<? }
	} elseif ($advertiseLabel) { ?>
		<div class="button button-profile">
			<h2>
                <a href="<?=DEFAULT_URL."/".ALIAS_ADVERTISE_URL_DIVISOR.".php".$advertisePath?>"><?=$advertiseLabel;?></a>
            </h2>
			<p>Interact and access profile pages, comments, favorites, deals and so much <strong>more!</strong></p>
		</div>
	<? } 
?>