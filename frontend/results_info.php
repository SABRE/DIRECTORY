<!--CSS to change the design layout on 24-06-2013 -->
<style>
    .browse-category-box{
        background: none repeat scroll 0 0 #E6E4E1 !important;
        margin-top: 10px !important;
        padding: 10px;
        width: 690px;
    }
    
    .browse-category-box .browse-category{
        background: none repeat scroll 0 0 #E6E4E1 !important;
        border-bottom: 1px dashed #818181 !important;
        margin-bottom: 0 !important;
        padding: 10px !important;
        width: 670px !important;
    }
    
    .browse-category-box h2{
        border: medium none !important;
        color: #818181 !important;
        font-size: 13px !important;
        margin-bottom: 0 !important;
        line-height: 12px !important;
    }
    
    .browse-category-box h2 .border-right{
        border-bottom: 1px dashed;
        float: right;
        padding-top: 5px;
        width: 520px;
    }
    
    .browse-category-box .browse-category li{
        padding: 0 5px 5px !important;
        width: 155px !important;
        font-size:13px !important;
        font-weight:normal !important;
    }
    
    .browse-category-box .browse-category li a{
        color:#186085 !important;
    }
    
    .browse-category-box .browse-category li span{
        color: #818181 !important;
        font-style: normal !important;
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
	if($show_results){ ?>
            <? if ($str_search) {?>
                <!--<h2 class="search-info">
                        <?=system_showText(LANG_SEARCHRESULTS)?> <?=$str_search?>
                </h2>-->
            <?  }
		/*
		 * These variables are prepared on MODULE/results.php file
		 */
		if ($category_id && $aux_CategoryObj && $aux_CategoryModuleURL && $aux_CategoryNumColumn) {
			$objCache = new cache("{$aux_CategoryObj}_results_category_{$category_id}.php");
                        if ($aux_CategoryObj == "PromotionCategory"){
                            $aux_CategoryObj = "ListingCategory";
                        }
			if ($objCache->caching) {
                            include(system_getFrontendPath("results_browsebycategory.php"));
			}
			$objCache->close();
		}

		if($aux_module_items){
			$itemRSSSection = $aux_module_itemRSSSection;
		}
	} 
?>
