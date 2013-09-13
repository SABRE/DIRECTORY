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
<? if($deal->level == 10){?>
	<div class="summary summary-deal">
		<div class="left">
			<?  if ($imageTag) { ?>
			<div class="image">
				<?=$imageTag?>
            </div>  
            <? } ?>
		</div>
		<div class="right">
			<div class="deal">
				<div class="deal-details">
					<div class="deal-details-info">
						<div class="deal-title">
							<?=$promotion->getString("name");?>
						</div>
						<div class="deal-url">
							<a href="<?=$promotionLink?>" <?=$promotionStyle?> title="<?=$promotion->getString("name")?>"><?=$promotion->getString("name", true, false).$promotionDistance?></a>
						</div>
						<div class="deal-review">
							<? if ($$promotion_review) { ?>
								<?=$$promotion_review?>
							<? } ?>
						</div>
					</div>
					<div class="images-link">
						<div class="images-link-upper">
							<div id="favourites">
								<?=$favoriteTemplate;?>
							</div>
							<div id="social-links">
								<?=$facebookL;?>
							</div>
							<div id="social-links">
								<?=$twitterL;?>
							</div>
						</div>
					</div>
				</div>
				<div class="deal-description">
					<? if ($promotion_desc) { ?>
						<?=$promotion_desc?>
					<? } ?>
				</div>
			</div>
			<div class="deal-info">
				<div class="address-map">
					<div class="address-info"></div>
				</div>
				<div class="contact-info">
					<div class="contact-no">
					</div>
					<div class="contact-email"></div>
				</div>
			</div>
		</div>
	</div>
<?}else{?>
<?}?>