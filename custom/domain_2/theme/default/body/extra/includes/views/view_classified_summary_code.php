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
	# * FILE: /includes/views/view_classified_summary_code.php
	# ----------------------------------------------------------------------------------------------------
	
	
?>

	<? if ($friendly_url) { ?>
		<a name="<?=$friendly_url;?>"></a>
	<? } ?>
	<? if($classified->level == 10){?>
		<div <?=$classified->getNumber("id") ? "id=\"classified_summary_".$classified->getNumber("id")."\"" : ""?> class="summary summary-big">
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
								<? if($show_map){ ?>
									<span id="summaryNumberID<?=$mapNumber;?>" class="map <?=(($_COOKIE['showMap'] == 0) ? ('isVisible') : ('isHidden'))?>">
										<a class="map-link" href="javascript:void(0);" onclick="javascript:myclick(<?=($mapNumber);?>);scrollPage();">
											<?=$mapNumber;?>.
										</a>
									</span>
								<? } ?>
								<?=$title?>
							</div>
							<div class="deal-url">
								<? if ($display_url) { ?>
									<?=$display_url?>
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
						  	<? if ($summaryDescription){?>
								<p><?=$summaryDescription?></p>
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
							<? if ($contact_email) { ?>
								<p>
									<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/sms.png';?>" style="display:inline;" />
									<span id="contact-email-detail">
										<a href="<?=$contact_email?>" class="<?=!$tPreview? "iframe fancy_window_tofriend": "";?>" style="<?=$contact_email_style?>">Send an email</a>
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
						<? if($show_map){ ?>
						<span id="summaryNumberID<?=$mapNumber;?>" class="map <?=(($_COOKIE['showMap'] == 0) ? ('isVisible') : ('isHidden'))?>">
							<a class="map-link" href="javascript:void(0);" onclick="javascript:myclick(<?=($mapNumber);?>);scrollPage();">
								<?=$mapNumber;?>.
							</a>
						</span>
						<? } ?>
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