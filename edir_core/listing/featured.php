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
	# * FILE: /edir_core/listing/featured.php
	# ----------------------------------------------------------------------------------------------------

	// Preparing markers to Full Cache
	?>
	<!--cachemarkerFeaturedListing2-->
	<?

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/featured_listing.php");
	
    if (is_array($array_show_listings)) { ?>

		<h2>
			<span><?=system_showText(LANG_FEATURED_LISTING)?></span>
            <? if ($seeAllTextLink && $seeAllText) { ?>
				<a class="view-more" href="<?=$seeAllTextLink?>"><?=$seeAllText;?></a>
            <? } ?>
		</h2>
		
		<div class="featured featured-listing">

		<?
		$countSpecialItem = 0;
        $lastItemStyle = 0;
        for ($i = 0; $i < count($array_show_listings); $i++) {

        $lastItemStyle++;

            if ($countSpecialItem < $specialItem) {
                if ($countSpecialItem == 0) { ?>
                    <div class="left">
                <? }

                if (($lastItemStyle % 2) && ($lastItemStyle != 1) && !ITEM_RESULTS_CLEAR){ ?>
                    <br class="clear" />
                <? } ?>

                <div class="featured-item featured-item-special">

                    <div class="image">
                        <a href="<?=$array_show_listings[$i]["detailLink"]?>" class="image">
                            <? if ($array_show_listings[$i]["image_tag"]) { ?>
                                <?=$array_show_listings[$i]["image_tag"]?>
                            <? } else { ?>
                                <span class="no-image"></span>
                            <? } ?>
                        </a>
                    </div>

                    <h3>
                        <a href="<?=$array_show_listings[$i]["detailLink"]?>">
                            <?=$array_show_listings[$i]["title"]?>
                        </a>
                    </h3>

                    <? if (LISTING_SCALABILITY_OPTIMIZATION == "on") { ?>

                        <p id="showCategory_listing<?=$array_show_listings[$i]["id"]?>">
                            <a href="javascript: void(0);" onclick="showCategory(<?=$array_show_listings[$i]["id"]?>, 'listing', true, <?=$array_show_listings[$i]["account_id"]?>, true)">
                                <?=system_showText(LANG_VIEWCATEGORY)?>
                            </a>
                        </p>

                    <? } else { ?>

                        <p> 
                            <?=$array_show_listings[$i]["categories"]?> <?=$array_show_listings[$i]["author_string"]?>
                        </p>

                    <? } ?>
                </div>
                    
                <?
                if (!(($lastItemStyle-3)%3) && ITEM_RESULTS_CLEAR) { ?>
                    <br class="clear" />
                <? }

                if ($countSpecialItem == ($specialItem-1) || (count($array_show_listings) == $countSpecialItem +1)) { ?>
                    </div>
                <? }

                $countSpecialItem++;

            } else { ?>

                <div class="featured-item <?=$array_show_listings[$i]["itemStyle"]?>">

                    <h3>
                        <a href="<?=$array_show_listings[$i]["detailLink"]?>">
                            <?=$array_show_listings[$i]["title_truncated"]?>
                        </a>
                    </h3>

                    <? if (LISTING_SCALABILITY_OPTIMIZATION == "on") { ?>

                        <p id="showCategory_listing<?=$array_show_listings[$i]["id"]?>">
                            <a href="javascript: void(0);" onclick="showCategory(<?=$array_show_listings[$i]["id"]?>, 'listing', true, <?=$array_show_listings[$i]["account_id"]?>, true)">
                                <?=system_showText(LANG_VIEWCATEGORY)?>
                            </a>
                        </p>

                    <? } else { ?>

                        <p>
                            <?=$array_show_listings[$i]["categories"];?> <?=$array_show_listings[$i]["author_string"];?>                 
                        </p>

                    <? } ?>
                </div>
            <? }
        } ?>
        </div>
		
<? }
	// Preparing markers to Full Cache
?>
	<!--cachemarkerFeaturedListing2-->