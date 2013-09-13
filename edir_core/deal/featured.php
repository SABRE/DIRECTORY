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
	# * FILE: /edir_core/deal/featured.php
	# ----------------------------------------------------------------------------------------------------

	// Preparing markers to Full Cache
	?>
	<!--cachemarkerFeaturedDeal2-->
	<?

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/featured_promotion.php");
	
	if (is_array($array_show_promotions)) { ?>

		<h2>
			<span><?=system_showText(LANG_FEATURED_PROMOTION)?></span>
			<? if ($seeAllTextLink && $seeAllText) { ?>
				<a class="view-more" href="<?=$seeAllTextLink?>"><?=$seeAllText;?></a>
			<? } ?>
		</h2>
		
		<div class="featured featured-deal">
			
            <?
			$countSpecialItem = 0;
            $lastItemStyle = 0;
			for ($i = 0; $i < count($array_show_promotions); $i++) {
				
				$lastItemStyle++;

				if ($countSpecialItem < $specialItem) {
					if ($countSpecialItem == 0) { ?>
                        <div class="left">
                    <? }
					
					if (($lastItemStyle % 2) && ($lastItemStyle != 1)) { ?>
						<br class="clear" />
                    <? } ?>
					
					<div class="featured-item featured-item-special">
					
						<div class="<?=$priceClass?>">
							<div class="deal-tag">
                                <?=CURRENCY_SYMBOL.$array_show_promotions[$i]["deal_price"]?><? if ($array_show_promotions[$i]["deal_cents"]) { ?><span class="cents"><?=$array_show_promotions[$i]["deal_cents"]?></span><? } ?>
                            </div>
							<div class="deal-discount">
                                <?=$array_show_promotions[$i]["offer"]." OFF"?>
                            </div>
						</div>
						
						<div class="<?=$contentClass?>">
                            <?=$imageOpenDiv?>
                            <a href="<?=$array_show_promotions[$i]["detailLink"]?>" class="image">
                                <? if ($array_show_promotions[$i]["image_tag"]) { ?>
                                    <?=$array_show_promotions[$i]["image_tag"]?>
                                <? } else { ?>
                                    <span class="no-image"></span>
                                <? } ?>
                            </a>
                            <?=$imageCloseDiv?>
							
                            <h3>
                                <a href="<?=$array_show_promotions[$i]["detailLink"]?>">
                                    <?=$array_show_promotions[$i]["title"]?>
                                </a>
                            </h3>

                            <? if ($array_show_promotions[$i]["listing_title"]) { ?>
                                <p>
                                    <?=system_showText(LANG_BY)?> 
                                    <a href="<?=$array_show_promotions[$i]["listing_link"]?>" title="<?=string_htmlentities($array_show_promotions[$i]["listing_title"])?>">
                                        <?=$array_show_promotions[$i]["listing_title"]?>
                                    </a>
                                </p>
                            <? } ?>

						</div>
						
					</div>
					
					<? if ($countSpecialItem == ($specialItem-1) || (count($array_show_promotions) == $countSpecialItem +1)) { ?>
						</div>
                    <? }
					
					$countSpecialItem++;
					
				} else { ?>

					<div class="featured-item <?=$array_show_promotions[$i]["itemStyle"]?>">
									
                        <div class="deal-tag">
                            <?=CURRENCY_SYMBOL.$array_show_promotions[$i]["deal_price"]?><? if ($array_show_promotions[$i]["deal_cents"]) { ?><span class="cents"><?=$array_show_promotions[$i]["deal_cents"]?></span><? } ?>
                        </div>
                        
                        <h3>
                            <a href="<?=$array_show_promotions[$i]["detailLink"]?>">
                                <?=$array_show_promotions[$i]["title"]?>
                            </a>
                        </h3>
                        
                        <? if ($array_show_promotions[$i]["listing_title"]) { ?>
                            <p>
                                <?=system_showText(LANG_BY)?> 
                                <a href="<?=$array_show_promotions[$i]["listing_link"]?>" title="<?=string_htmlentities($array_show_promotions[$i]["listing_title"])?>">
                                    <?=$array_show_promotions[$i]["listing_title"]?>
                                </a>
                            </p>
                        <? } ?>
					
					</div>
				<? }

			} ?>
            </div>
<? } 
    // Preparing markers to Full Cache
?>
	<!--cachemarkerFeaturedDeal2-->