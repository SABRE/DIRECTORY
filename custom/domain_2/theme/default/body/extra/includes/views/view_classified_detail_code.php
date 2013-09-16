<div class="list-title-link-block">
	<div class="listing-title">
		<?=$classified_title;?>
	</div>
	<div class="images-link">
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
<div style="clear:both;"></div>
<div class="listing-info-box">
	<div class="category-tree">
		<? if ($classified_category_tree) { ?>
			<?=$classified_category_tree?>
		<? } ?>
	</div>
	<div style="clear:both;"></div>
	<div class="location-address">
		<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/direction.png';?>" style="display:inline;" />
		<? if (($location) || ($classified_address) || ($classified_address2)) echo "<address>\n";  ?>
			<? if($classified_address) { ?>
				<span><?=nl2br($classified_address)?></span>
			<? } ?>

			<? if($classified_address2) { ?>
				<span><?=nl2br($classified_address2)?></span>
			<? } ?>

			<? if($location) { ?>
				<span><?=$location?></span>
			<? } ?>
		<? if (($location) || ($classified_address) || ($classified_address2)) echo "</address>\n";  ?>
	</div>
	<?if($classified_phone){?>
		<div style="clear:both;"></div>
		<div class="contact-number">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
			<span id="contact-no">
				<?=$classified_phone?>
			</span>
		</div>
	<?}?>
	<?if($classified_email){?>
		<div style="clear:both;"></div>
		<div class="email">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/sms.png';?>"  style="display:inline;"/>
			<span id="email-link">
				<?=$classified_email?>
			</span>
		</div>
	<?}?>
	<?if($classified_facebook_buttons){?>
		<div style="clear:both;"></div>
		<div class="listing-facebook-button">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/check_icon.png';?>"  style="display:inline;"/>
			<span id="facebook-link">
				<?=$classified_facebook_buttons;?>
			</span>
		</div>
	<?}?>
</div>
<div class="gallery-box">
	<? if ($imageTag) { ?>
    	<div class="image">
			<?=$imageTag?>
        </div>
	<? } ?>
	<? if($classifiedGallery) { ?>
    	<div class="gallery">
    		<?=$classifiedGallery?>
        </div>
	<? } ?>
</div>
<div class="content">
	<div class="content-main">
		<?if($classified_summary || $classified_price ){?>	
		<div class="overview-hour-operation">
			<div class="overview-box">
					<? if ($classified_summary) { ?>
						<span id="overview-title"><?=system_showText(LANG_LABEL_OVERVIEW);?></span>
						<div class="content-box">
							<?=$classified_summary?>
						</div>
					<? }?>
			</div>
			<div class="operation-hour-box">
				<? if ($classified_price) { ?>
					<span id="operation-hour-box-title">Price</span>
					<div class="content-box">			
						<?='$'.$classified_price?>
					</div>
				<? } ?>
			</div>
		</div>
		<?}?>
		<div style="clear:both;"></div>
		<? if ($classified_description) { ?>
			<div class="detail-description">
				<span id="description-title"><?=system_showText(LANG_LABEL_DESCRIPTION);?></span>
				<div class="content-box">
				<?=$classified_description?>
				</div>
			</div>
		<? } ?>
	</div>
	<div style="clear:both;"></div>
	<? 
		$url = $_SERVER['REQUEST_URI'];
		$fileName = basename($url,".php");
		if($fileName != 'advertise'){
	?>
	<div class="upper-section" style="border-top:1px solid #BFBFBF;border-bottom:none;margin-top:20px;padding-top:20px;">
	<? include(system_getFrontendPath("detail_upper_section.php", "frontend", false, CLASSIFIED_EDIRECTORY_ROOT)); ?>
	</div>
	<? include(system_getFrontendPath("banner_bottom.php")); ?>
	<?}?>
</div>
			

	