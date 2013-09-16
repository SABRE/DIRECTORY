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
	# * FILE: /frontend/results_filter.php
	# ----------------------------------------------------------------------------------------------------

	/*
	 * Prepare $aux_array_rss to RSS 
	 */

	if($show_results && $itemRSSSection){
			include(EDIRECTORY_ROOT."/includes/code/rss.php");
	}
		

	if ($aux_module_items && !$hideResults) { ?>
		<div class="filter">
			<div class="left">
				<?//=$orderbyDropDown?>
				<?=$array_pages_code["current"]?>&nbsp;-&nbsp;<?=$array_pages_code["last_page"]?>&nbsp;<?=system_showText(LANG_PAGING_PAGEOF)?>&nbsp;<?=$array_pages_code["total"]?>&nbsp;Results
			</div>
			<div class="right">
				<?
				/*if(is_array($aux_array_rss)){
					?>
					<a title="<?=LANG_LABEL_SUBSCRIBERSS?>" class="rss-feed" target="_blank" href="<?=$aux_array_rss["link"]?>">
						<?=LANG_LABEL_SUBSCRIBERSS?>
					</a>
					<?
				}*/
				if (CACHE_FULL_FEATURE != "on"){
				?>
				<!-- <form class="form" method="post" action="<?=DEFAULT_URL.str_replace("&", "&amp;", $_SERVER["REQUEST_URI"])?>">
					<label><?=system_showText(LANG_PAGING_RESULTS_PER_PAGE);?>:</label>
					<select class="select" name="results_per_page" id="results_per_page" disabled="disabled">
						<option <?=($aux_items_per_page == 10 ? "selected=\"selected\"" : "")?>>10</option>
						<option <?=($aux_items_per_page == 20 ? "selected=\"selected\"" : "")?>>20</option>
						<option <?=($aux_items_per_page == 30 ? "selected=\"selected\"" : "")?>>30</option>
						<option <?=($aux_items_per_page == 40 ? "selected=\"selected\"" : "")?>>40</option>
					</select>
				</form>-->
				Results per page:&nbsp;
				<a href="" onclick="selectRecord('<?=DEFAULT_URL.str_replace("&", "&amp;", $_SERVER["REQUEST_URI"])?>',10);" <?=($aux_items_per_page==10 ? "class=\"records-per-page\"" : "")?>>10</a>&nbsp;|&nbsp;
				<a href="" onclick="selectRecord('<?=DEFAULT_URL.str_replace("&", "&amp;", $_SERVER["REQUEST_URI"])?>',20);" <?=($aux_items_per_page==20 ? "class=\"records-per-page\"" : "")?>>20</a>&nbsp;|&nbsp;
				<a href="" onclick="selectRecord('<?=DEFAULT_URL.str_replace("&", "&amp;", $_SERVER["REQUEST_URI"])?>',30);" <?=($aux_items_per_page==30 ? "class=\"records-per-page\"" : "")?>>30</a>&nbsp;|&nbsp;
				<a href="" onclick="selectRecord('<?=DEFAULT_URL.str_replace("&", "&amp;", $_SERVER["REQUEST_URI"])?>',40);" <?=($aux_items_per_page==40 ? "class=\"records-per-page\"" : "")?>>40</a>
				<? } ?>
			</div>
		</div>
	<?}?>
	<script type="text/javascript">
	function selectRecord(url,records)
	{
		var module = '<?=ACTUAL_MODULE_FOLDER?>';
		if(module.length == 0){
			module = 'listing';
		}
		//<![CDATA[
		$ = jQuery.noConflict();
		$(document).ready(function() {
			//$.cookie(module+'_results_per_page',records,{path:'/edirectory/'});
			$.cookie(module+'_results_per_page',records);
			$(location).attr('href',url);
		});
		//]]>
	}
	</script>