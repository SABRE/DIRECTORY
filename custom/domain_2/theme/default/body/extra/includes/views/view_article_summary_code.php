<?
    /* ==================================================================*\
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
    \*================================================================== */

    # ----------------------------------------------------------------------------------------------------
    # * FILE: /includes/views/view_article_summary_code.php
    # ----------------------------------------------------------------------------------------------------
?>
	<?if($article->level == 50){?>
		<div id="article_summary_<?=$article->getNumber("id");?>" class="summary">
			<div class="left">
				<?  if ($summaryImage) { ?>
					<div class="image">
						<?=$summaryImage?>
	            	</div>  
	            <? } ?>
			</div>
			<div class="right">
				<div class="deal">
					<div class="deal-details">
						<div class="deal-details-info">
							<div class="deal-title">
								<?=$summaryTitle;?>
							</div>
							<div class="deal-review">
								<? if ($item_review) { ?>
									<?=$item_review?>
								<? } ?>
							</div>
						</div>
						<div class="images-link">
							<div class="images-link-upper">
								<div id="forward">
									<?=$shareTemplate;?>
								</div>
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
						<? if ($summaryDescription) { ?>
							<?=$summaryDescription?>
						<? } ?>
					</div>
				</div>
				<div class="deal-info">
					<div class="address-map"></div>
					<div class="contact-info">
						<div class="contact-no">
						</div>
						<div class="contact-email">
							<? if ($emailTemplate) { ?>
								<p>
									<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/sms.png';?>" style="display:inline;" />
									<span id="contact-email-detail">
										<?=$emailTemplate?>
									</span>
								</p>
							<? } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?}else{?>
			<div class="deal-box">
				<div class="first-box">
					<div class="deal-title">
						<?=$summaryTitle?>
					</div>
					<div class="deal-review">
						<? if ($item_review) { ?>
							<?=$item_review?>
						<? } ?>
					</div>
				</div>
				<div class="second-box">
					<div class="address-info"></div>
				</div>
				<div class="third-box">
					<div class="image-links">
						<div id="favourites">
							<?=$favoriteTemplate;?>
						</div>
						<div id="forward">
							<?=$shareTemplate;?>
						</div>
					</div>
					<div class="contact-no"></div>
				</div>
			</div>
	<?}?>