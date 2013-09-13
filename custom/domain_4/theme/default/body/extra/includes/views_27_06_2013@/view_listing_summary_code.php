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
	# * FILE: /includes/views/view_listing_summary_code.php
	# ----------------------------------------------------------------------------------------------------
	if (is_array($listing)) {
		$aux = $listing;
	} else if (is_object($listing)) {
		$aux = $listing->listing_array;
	}

	if ($listingtemplate_friendly_url){ ?>
		<a name="<?=$listingtemplate_friendly_url;?>"></a>
	<? } ?>
	<?if($levelObj->getDetail(htmlspecialchars($listing["level"])) == "y"){?>
	<div <?=$listing["id"] ? "id=\"listing_summary_".$listing["id"]."\"" : ""?> class="<?=$listing["backlink"] == "y" && BACKLINK_FEATURE == "on" ? "summary summary-backlink" : "summary" ?>">
		<div class="left">
			<?  if ($listingtemplate_image) { ?>
				<div class="image">
					<?=$listingtemplate_image?>
            	</div>  
            <? } ?>
		</div>
		<div class="right">
			<div class="deal">
				<div class="deal-details">
					<div class="deal-details-info">
						<div class="deal-title">
							<?=$listingtemplate_title?>
						</div>
						<div class="deal-url">
							<? if ($listingtemplate_url) { ?>
								<?=$listingtemplate_url?>
							<? } ?>
						</div>
						<div class="deal-review">
							<? if ($listingtemplate_review) { ?>
								<?=$listingtemplate_review?>
							<? } ?>
						</div>
					</div>
					<div class="images-link">
						<div class="images-link-upper">
							<div id="forward">
								<?=$shareListingTemplate;?>
							</div>
							<div id="favourites">
								<?=$favoriteListingTemplate;?>
							</div>
							<div id="sendPhone">
								<a href="<?=$listingtemplate_twilioSMS?>" <?=$twilioSMS_style?> title="Send to SMS"></a>
							</div>
							<div id="social-links">
								<?=$facebookL;?>
							</div>
							<div id="social-links">
								<?=$twitterL;?>
							</div>
							
						</div>
						<div class="images-link-lower">
							<? if ($listingtemplate_claim) { ?>
								<?=$listingtemplate_claim?>
							<? } ?>
						</div>
					</div>
				</div>
				<div class="deal-description">
					<? if ($listingtemplate_description) { ?>
						<?=$listingtemplate_description?>
					<? } ?>
				</div>
			</div>
			<div class="deal-info">
				<div class="address-map">
					<div class="address-info">
						<? if(($listingtemplate_address) || ($listingtemplate_address2) || ($listingtemplate_location)) echo "<address>\n"; ?>
	
						<? if ($listingtemplate_address) {  ?>
							<span><?=$listingtemplate_address?></span>
						<? } ?>
		
						<? if ($listingtemplate_address2) { ?>
							<span><?=$listingtemplate_address2?></span>
						<? } ?>
		
						<? if ($listingtemplate_location) { ?>
							<span><?=$listingtemplate_location?></span>
						<? } ?>
	
						<? if(($listingtemplate_address) || ($listingtemplate_address2) || ($listingtemplate_location)) echo "\n</address>\n"; ?>
					</div>
					<span id="map-info-detail">
						<? if($mapListingTemplate){?>
						<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/direction.png';?>" style="display:inline;" />
						<?=$mapListingTemplate;?>
						<?}?>
					</span>
				</div>
				<div class="contact-info">
					<div class="contact-no">
						<? if ($listingtemplate_phone) { ?>
								<p>
									<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
									<span id="contact-no-detail">
										<?=$listingtemplate_phone?>
									</span>
								</p>
						<? } ?>
					</div>
					<div class="contact-email">
						<? if ($listingtemplate_email) { ?>
							<p>
								<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/sms.png';?>" style="display:inline;" />
								<span id="contact-email-detail">
									<?=$listingtemplate_email?>
								</span>
							</p>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?}else{?>
		<!-- <div <?=$listing["id"] ? "id=\"listing_summary_".$listing["id"]."\"" : ""?> class="<?=$listing["backlink"] == "y" && BACKLINK_FEATURE == "on" ? "deal-box summary-backlink" : "deal-box" ?>">-->
			<div class="deal-box">
				<div class="first-box">
					<div class="deal-title">
						<?=$listingtemplate_title?>
					</div>
					<div class="deal-review">
						<? if ($listingtemplate_review) { ?>
							<?=$listingtemplate_review?>
						<? } ?>
					</div>
				</div>
				<div class="second-box">
					<div class="address-info">
						<? if(($listingtemplate_address) || ($listingtemplate_address2) || ($listingtemplate_location)) echo "<address>\n"; ?>
	
						<? if ($listingtemplate_address) { ?>
							<span><?=$listingtemplate_address?></span>
						<? } ?>
		
						<? if ($listingtemplate_address2) { ?>
							<span><?=$listingtemplate_address2?></span>
						<? } ?>
		
						<? if ($listingtemplate_location) { ?>
							<span><?=$listingtemplate_location?></span>
						<? } ?>
	
						<? if(($listingtemplate_address) || ($listingtemplate_address2) || ($listingtemplate_location)) echo "\n</address>\n"; ?>
					</div>
					<? if($mapListingTemplate){?>
						<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/direction.png';?>" style="display:inline;" />
						<span id="map-info-detail">
							<?=$mapListingTemplate;?>
						</span>
					<?}?>
				</div>
				<div class="third-box">
					<div class="image-links">
						<div id="social-links">
							<?=$facebookL;?>
						</div>
						<div id="social-links">
							<?=$twitterL;?>
						</div>
						<div id="sendPhone">
							<a href="<?=$listingtemplate_twilioSMS?>" <?=$twilioSMS_style?> title="Send to SMS"></a>
						</div>
						
						<div id="favourites">
							<?=$favoriteListingTemplate;?>
						</div>
						<div id="forward">
							<?=$shareListingTemplate;?>
						</div>
						<? if ($listingtemplate_claim) { ?>
								<?=$listingtemplate_claim?>
						<? } ?>
					</div>
					<div class="contact-no">
						<? if ($listingtemplate_phone) { ?>
									<p>
										<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
										<span id="contact-no-detail">
											<?=$listingtemplate_phone?>
										</span>
									</p>
						<? } ?>
					</div>
				</div>
			</div>
		<!-- </div> -->
	<?}?>
<? unset($aux); ?>
