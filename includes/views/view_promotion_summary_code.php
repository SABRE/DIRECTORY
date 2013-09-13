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
	# * FILE: /includes/views/view_promotion_summary_code.php
	# ----------------------------------------------------------------------------------------------------
	
?>
	<a name="<?=$friendly_url;?>"></a>
	
	<div class="summary summary-deal">
	
		<div class="left">

			<div class="tag">
				<? if ($sold_out) { ?>
                    <div class="deal-tag deal-tag-sold-out"><?=system_showText(DEAL_SOLDOUT);?></div>
				<? } else { ?>
                    <div class="deal-tag"><?=CURRENCY_SYMBOL.$deal_price.($deal_cents ? "<span class=\"cents\">".$deal_cents."</span>" : "");?></div>
				<? } ?>
				<div class="deal-discount"><?=$offer." ".OFF?></div>
				<?=$deal_icon_navbar;?>
			</div>
	
			<div class="image">
			
				<?=$imageTag;?>
				
				<?=$promotion_review;?>

			</div>
			
		</div>
		
		<div class="right">
		
			<div class="title">
			
				<h3>
					<? if ($show_map) { ?>
						<span id="summaryNumberID<?=$mapNumber;?>" class="map <?=(($_COOKIE['showMap'] == 0) ? ('isVisible') : ('isHidden'))?>">
							<a class="map-link" href="javascript:void(0)" onclick="javascript:myclick(<?=($mapNumber);?>);scrollPage();">
								<?=$mapNumber;?>.
							</a>
						</span>
					<? } ?>
					
					<a href="<?=$promotionLink?>" <?=$promotionStyle?> title="<?=$promotion->getString("name")?>"><?=$promotion->getString("name", true, false).$promotionDistance?></a>
				</h3>
				
				<? if ($listingTitle) {?>
                    <p><?=system_showText(LANG_BY)?> <a href="<?=$listing_link?>" <?=$promotionStyle?> title="<?=string_htmlentities($listingTitle)?>"><?=$listingTitle?></a></p>
				<? } ?>
				
			</div>
			
			<p><?=$promotion_desc;?></p>
			
		</div>
		
	</div>	