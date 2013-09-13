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
	# * FILE: /frontend/browsebycategory.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
    include_once(EDIRECTORY_ROOT."/includes/code/browsebycategory.php");
   	if (is_array($array_item_categories)) { ?>
		<!--<h2>
			<span>
                <?=system_showText(LANG_BROWSEBYCATEGORY)?>
            </span>
		</h2>-->
		<div class="browse-category-box">
		<ul class="browse-category">
		<? for ($i = 0; $i < count($array_item_categories); $i++) { ?>
			<li>
            	 <div class="main_cat">
				 <?php /*	<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/plus.png'?>" /> */ ?>
            	</div>
           		<a href="<?=$array_item_categories[$i]["categoryLink"]?>">
                    <?=$array_item_categories[$i]["title"]?>
                </a>
                
                <? if ($categoryCount == "on") { ?>
                    <span>
                        (<?=$array_item_categories[$i]["active_".($module == "blog" ? "post" : $module)]?>)
                    </span>
                <? }

                if (count($array_item_categories[$i]["subcategories"])) { ?>
                	<div class="sub_cat" style="display:none;">
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
                    </div>    
                <? } ?>
            </li> 
            <?//=$array_item_categories[$i]["auxLi"]?>
            
        <? } ?>
            <? if ($viewMoreLink) { ?>
                <li class="view-all-cat"><?=$viewMoreLink?></li>
            <? } ?>
        </ul> 
        </div>     
<? } ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".main_cat, .main_cat2").bind("click", function(Event){
		if($(Event.target).hasClass("main_cat")){
			$('.browse-category').find(".sub_cat").slideUp("slow");
			$('.browse-category').find(".main_cat2").removeClass("main_cat2").addClass("main_cat");
			$(Event.target).removeClass("main_cat").addClass("main_cat2");
			$(Event.target).parent("li").children("div.sub_cat").slideDown("slow");
		}else{
			$('.browse-category').find(".sub_cat").slideUp("slow");
			$('.browse-category').find(".main_cat2").removeClass("main_cat2").addClass("main_cat");
			$(Event.target).removeClass("main_cat2").addClass("main_cat");
			$(Event.target).parent("li").children("div.sub_cat").slideUp("slow");
		}
		return false;	
	})
});

</script>
