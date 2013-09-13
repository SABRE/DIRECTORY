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
	# * FILE: /frontend/results_pagination.php
	# ----------------------------------------------------------------------------------------------------

?>

<? if (($showLetter || ($array_pages_code["total"] > $aux_items_per_page)) && !$hideResults) { ?>
	<div class="letters-div">
		<?
		if($showLetter && $letters_menu){
			?>
			<ul class="letters">
				<?=$letters_menu?>
			</ul>
			<?
		}
		?>
	</div>
<? } ?>