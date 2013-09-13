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
	# * FILE: /frontend/results_info.php
	# ----------------------------------------------------------------------------------------------------
	

	if($show_results){ ?>
		<? if ($str_search) {
			?>
			<h4 class="upper-section-info">
				<a href="<?=LISTING_DEFAULT_URL?>/">
					<?=((ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER)? 'Directory Home':ucwords(system_showText(ACTUAL_MODULE_FOLDER)).' Home')?>
				</a>
				&nbsp;<arrow>></arrow>&nbsp;
				<strong><?=ucwords($str_search)?></strong>
				&nbsp;<b>(<?=number_format($searchReturn['total_listings'])?>&nbsp;<?=system_showText(LANG_SEARCHRESULTS)?>)</b>
			</h4>
			<?
		}
	} 
?>
