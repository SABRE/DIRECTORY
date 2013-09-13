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
	# * FILE: /includes/tables/table_category_location.php
	# ----------------------------------------------------------------------------------------------------

?>

	<? 
	
	if(isset($bannerList) && count($bannerList[0])>0)
		$pagesDropDown = $pageObj->getPagesDropDown($_GET, $paging_url, $screen, system_showText(LANG_SITEMGR_PAGING_GOTOPAGE)." ", "this.form.submit();","",false,true,((isset($bannerList[0]) && count($bannerList[0])>0)?$bannerList:false));
	
	include(INCLUDES_DIR."/tables/table_paging.php"); 
	
	?>

	<table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">

		<?if(isset($bannerList[0]) && count($bannerList[0])>0){?>
			<tr>
				<th><?=string_ucwords(system_showText(LANG_SITEMGR_CATEGORY))?> <?=string_ucwords(system_showText(LANG_SITEMGR_TITLE))?></th>
				<?if($show_country){?><th><?=string_ucwords(system_showText(LANG_SITEMGR_LOCATION_COUNTRY))?></th><?}?>
				<?if($using_location2){?><th><?=string_ucwords(system_showText(LANG_SITEMGR_LOCATION_REGION))?></th><?}?>
				<?if($using_location3){?><th><?=string_ucwords(system_showText(LANG_SITEMGR_LOCATION_STATE))?></th><?}?>
				<th><?=string_ucwords(system_showText(LANG_SITEMGR_LOCATION_APPLICATIONS))?></th>
				<th><?=string_ucwords(system_showText(LANG_SITEMGR_FEATURE_STATUS))?></th>
				<th><?=string_ucwords(system_showText(LANG_SITEMGR_ACTIVE))?> <?=string_ucwords(system_showText(LANG_SITEMGR_BANNER))?></th>
			</tr>
			<? foreach ($bannerList[0] as $banner_item){
				$status = new ItemStatus();
			?>
				<tr>
					<td><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/featured.php?<?=$banner_item[1].$url_search_params?>"><?=$banner_item[0]?></a></td>
					<?if($show_country){?><td><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/featured.php?<?=$banner_item[9].$url_search_params?>"><?=$banner_item[8]?></a></td><?}?>
					<?if($using_location2){?><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/featured.php?<?=$banner_item[11].$url_search_params?>"><td><?=$banner_item[10]?></a></td><?}?>
					<?if($using_location3){?><td><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/featured.php?<?=$banner_item[4].$url_search_params?>"><?=$banner_item[3]?></a></td><?}?>
					<td><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/featured.php?banner_to_approve=1&<?=$banner_item[1]?>&<?=($banner_item[4]?$banner_item[4]:($banner_item[11]?$banner_item[11]:$banner_item[9])).$url_search_params?>"><?=$banner_item[2]?></a></td>
					<td><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/featured.php?banner_to_approve=1&<?=$banner_item[1]?>&<?=($banner_item[4]?$banner_item[4]:($banner_item[11]?$banner_item[11]:$banner_item[9])).$url_search_params?>"><?=$status->getStatusWithStyle($banner_item[5])?></a></td>
					<td><? if($banner_item[6]!=system_showText(LANG_NA)){?><a href="<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/<?=BANNER_FEATURE_FOLDER;?>/view.php?<?=$banner_item[7]?>"><?}?><?=$banner_item[6]?><? if($banner_item[6]!=system_showText(LANG_NA)){?></a><?}?></td>
				</tr>
				
			<? } ?>
		<? } else { ?>
			<p class="informationMessage"><?=system_showText(LANG_SITEMGR_LOCATION_CATEGORY_NORECORD)?></p>
		<? } ?>

	</table>

