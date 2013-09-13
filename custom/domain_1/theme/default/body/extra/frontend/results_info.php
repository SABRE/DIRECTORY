<style>
    .search-info .letters-div
    {
        background:none !important;
        padding:0px !important;
        float:right !important;
    }
    
    .search-info .letters-div li
    {
        line-height:30px !important;
        font-size: 11px !important;
    }
    
    .search-info .letters-div li a:link, 
    .search-info .letters-div li a:visited, 
    .search-info .letters-div li a:active, 
    .search-info .letters-div li a:hover 
    {
        display: table-cell !important;
        padding: 0 2px !important;
        color: #818181 !important;
    }
    
    .search-info .letters-div span
    {
        display: table-cell !important;
        padding: 0 2px !important;
        color: #818181 !important;
        float:none !important;
    }
    .sorting-div .border-left{
        border-bottom: 1px dashed #818181;
        float: left;
        margin-right: 10px;
        padding-top: 7px;
        width: 506px;
    }
    .sorting-div .sorting-list 
    {
        background: url("<?=DEFAULT_URL.'/custom/domain_'.SELECTED_DOMAIN_ID.'/theme/default/schemes/default'?>/images/newImages/sort.png") no-repeat scroll 0 0 transparent !important;
        height: 15px !important;
        width: 172px !important;
        margin-bottom: 10px !important;
    }
    .sorting-div .sorting-list .sort-value {
        width: 172px !important;
        font-size: 10px !important;
        font-style: normal !important;
        line-height: normal !important;
        font-weight:bold !important;
    }
    .sorting-div .sorting-list .sort-value-first 
    {
        float: left;
        padding: 0 0 0 6px;
        width: 34px;
    }
    
    .sorting-div .sorting-list .sort-value-second 
    {
        float: left;
        padding: 0 0 0 6px;
        width: 50px;
    }
    
    .sorting-div .sorting-list .sort-value-third 
    {
        float: left;
        padding: 0 0 0 3px;
        width: 72px;
    }
    .sorting-div .sorting-list .sort-value  a:link, 
    .sorting-div .sorting-list .sort-value  a:visited, 
    .sorting-div .sorting-list .sort-value  a:active, 
    .sorting-div .sorting-list .sort-value  a:hover {
        color: #818181 !important;
        text-transform: uppercase !important;
    }
    
    .sorting-div .rss {
        margin-top: 0px !important;
    }
    
    .sorting-div .rss-feed {
        margin: 0 0 0 5px !important;
    }
</style>
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
	if ($aux_module_items){
		$itemRSSSection = $aux_module_itemRSSSection;
	}
	
	if($show_results && $itemRSSSection){ 
		include(EDIRECTORY_ROOT."/includes/code/rss.php");
	}
	if($show_results){ ?>
		<? if ($str_search) {
			?>
			<h2 class="search-info">
                            <strong><?=ucwords($str_search)?></strong>
			<?
		}
	}
        include(system_getFrontendPath("results_letters.php"));?>
	</h2>
        <? if ($aux_module_items && !$hideResults) { ?> 
            <div class='sorting-div'>
                <div class="border-left"></div>
            <!--<span id='sort'>Sort</span>-->
		<div class="sorting-list">
			<div class="sort-value">
                            <?=$orderbyDropDown?>
			</div>
		</div>
                <div class="rss">
			<?if(is_array($aux_array_rss)){?>
			<a title="<?=LANG_LABEL_SUBSCRIBERSS?>" class="rss-feed" target="_blank" href="<?=$aux_array_rss["link"]?>">
				<?=LANG_LABEL_SUBSCRIBERSS?>
			</a>
			<?}?>
		</div>
	</div>
	<?}?>

