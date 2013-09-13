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
	# * FILE: /includes/views/view_event_summary_code.php
	# ----------------------------------------------------------------------------------------------------

?>

	<? if ($friendly_url) { ?>
		<a name="<?=$friendly_url;?>"></a>
	<? } ?>
	<? if($event->level == 10){ ?>
			<div class="summary summary-big">
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
									<?=$title?>
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
							  <? if ($description) { ?>
					                <p><?=nl2br($description)?></p>
					          <? } ?>
						</div>
					</div>
					<div class="deal-info">
						<div class="address-map">
							<div class="address-info">
							<? if ($address1 || $address2 || $location) { ?>
										<address>
											<?=$address1?>
											<?=$address2?>
											<?=$location?>
										</address>
					                <? } ?>
							</div>
						</div>
						<div class="contact-info">
							<div class="contact-no">
								<? if($phone){ ?>
									<p>
										<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
										<span id="contact-no-detail">
											<?=$phone?>
										</span>
									</p>
								<? } ?>
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
						<?=$title?>
					</div>
				</div>
				<div class="second-box">
					<div class="address-info">
						<? if ($address1 || $address2 || $location) { ?>
										<address>
											<?=$address1?>
											<?=$address2?>
											<?=$location?>
										</address>
					     <? } ?>
					</div>		
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
					<div class="contact-no">
						<? if($phone){ ?>
							<p>
								<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
								<span id="contact-no-detail">
									<?=$phone?>
								</span>
							</p>
					 <? } ?>
					</div>
				</div>
			</div>
		
	<?}?>
		