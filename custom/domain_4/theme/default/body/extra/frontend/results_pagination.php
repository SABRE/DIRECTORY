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
	//echo "<pre>";print_r($array_pages_code);echo "</pre>";die;
?>

<? if (($showLetter || ($array_pages_code["total"] > $aux_items_per_page)) && !$hideResults) { ?>
	<div class="pagination <?=(($pagination_bottom==true) ? ("pagination-bottom") : (""))?>">
		 <div class="left">
			<? if ($array_pages_code["previous"]){
				echo $array_pages_code["previous"];
			} ?>	
		</div>
		<div class="middle">
		
		<? if($array_pages_code["first"] || $array_pages_code["pages"] || $array_pages_code["last"]){ ?>
			 <ul class="pages"><li style="width:100%">
				<?=$array_pages_code["first"];?>
				<?=$array_pages_code["pages"];?>
				<?=$array_pages_code["last"];?>
			</li></ul>
		<? } ?>
		
		</div>
		<div class="right">
			<? if ($array_pages_code["next"]){
				echo $array_pages_code["next"];
			} ?>	
		</div>
		
	</div>
<? } ?>