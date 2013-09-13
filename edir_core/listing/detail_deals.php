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
	# * FILE: /edir_core/listing/detail_deals.php
	# ----------------------------------------------------------------------------------------------------

	if ($hasDeal) { ?>
	
		<h2><?=string_ucwords(system_showText(LANG_PROMOTION_FEATURE_NAME))?></h2>

		<div class="featured featured-deal">
			<div class="featured-item featured-item-special">
				<div class="left">
					<div class="deal-tag"><?=CURRENCY_SYMBOL.$promotionInfo['price'].($promotionInfo['cents'] ? "<span class=\"cents\">".$promotionInfo['cents']."</span>" : "")?></div>
					<div class="deal-discount"><?=$promotionInfo['summary_offer']?></div>
				</div>
				<div class="right">
					<?=$promotionInfo['image']?>
					<h3><a href="<?=$promotionInfo['url']?>" <?=$promotionInfo['style']?>><?=$promotionInfo['name']?></a></h3>
				</div>
			</div>		
		</div>
	<? } ?>