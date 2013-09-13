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
	# * FILE: /frontend/browsebycategory_listings.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
    include_once(EDIRECTORY_ROOT."/includes/code/browsebycategory_listings.php");
    
	if (is_array($array_item_categories)) { ?>
		
		<div class="listing_categories">
		<ul class="listingcategories">
		
		<? for ($i = 0; $i < count($array_item_categories); $i++) { ?>
            
            <li <?=$array_item_categories[$i]["liClass"]?>>
                
                <a href="<?=$array_item_categories[$i]["categoryLink"]?>">
                    <?=$array_item_categories[$i]["title"]?>
                </a>
                
                <? if ($categoryCount == "on") { ?>
                    <span>
                        (<?=$array_item_categories[$i]["active_".($module == "blog" ? "post" : $module)]?>)
                    </span>
                <? }

                if (count($array_item_categories[$i]["subcategories"])) { ?>
                    <ul>
                        <? for ($j = 0; $j < count($array_item_categories[$i]["subcategories"]); $j++) { ?>
                            <li>
                                <a href="<?=$array_item_categories[$i]["subcategories"][$j]["subCategoryLink"]?>">
                                    <?=$array_item_categories[$i]["subcategories"][$j]["subCategoryTitle"]?>
                                </a>
                                <? if ($categoryCount == "on") { ?>
                                    <span>
                                        (<?=$array_item_categories[$i]["subcategories"][$j]["active_".($module == "blog" ? "post" : $module)]?>)
                                    </span>
                                <? } ?>
                            </li>    
                        <? } ?>
                    </ul>    
                <? } ?>
            </li> 
            
            <?=$array_item_categories[$i]["auxLi"]?>
            
        <? } ?>
            <? if ($viewMoreLink) { ?>
                <li><?=$viewMoreLink?></li>
            <? } ?>
        </ul> 

			</div>
<? } ?>