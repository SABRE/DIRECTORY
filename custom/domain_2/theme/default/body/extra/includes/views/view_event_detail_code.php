<style>
    span.eventurl
    {
        padding-left:10px !important;
        padding-top:5px !important;
    }
    span.eventurl a
    {
        float:left !important;
        margin-top:10px !important;
        padding-left:10px !important;
    }
</style>
<div class="list-title-link-block">
	<div class="listing-title">
		<?=$event_title;?>
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
		<? if ($event_category_tree) { ?>
			<?=$event_category_tree?>
		<? } ?>
	</div>
	<div style="clear:both;"></div>
	<div style="clear:both;"></div>
	<div class="location-address">
		<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/direction.png';?>" style="display:inline;" />
                
		<? if (($location) || ($event_address) || ($event_address2)) echo "<address>\n";  ?>
			<? if($event_address) { ?>
				<span><?=nl2br($event_address)?></span>
			<? } ?>
			<? if($event_address2) { ?>
				<span><?=nl2br($event_address2)?></span>
			<? } ?>
			<? if($location) { ?>
				<span><?=$location?></span>
			<? } ?>
                        <? if($event_location) { ?>
				<br/><span style="font-size: 13px;"><?=$event_location?></span>
			<? } ?>
                                
		<? if (($location) || ($event_address) || ($event_address2)) echo "</address>\n";  ?>
		<? if ($location_map) { ?>
			<? if ($user) { ?>
				<span id="map-link"><a href="<?=$map_link?>" target="_blank">Show on map</a></span>
			<? } else { ?>
				<span id="map-link"><a href="javascript:void(0);" style="cursor:default">Show on map</a></span>
			<? } ?>
		<? } ?>
	</div>
	<?if($event_phone){?>
		<div style="clear:both;"></div>
		<div class="contact-number">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
			<span id="contact-no">
				<?=$event_phone?>
			</span>
		</div>
	<?}?>
	<?if($event_email){?>
		<div style="clear:both;"></div>
		<div class="email">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/sms.png';?>"  style="display:inline;"/>
			<span id="email-link">
                            <?=$event_email?>
                            <span class="eventurl"><a href="<?=$event_url?>" target="_blank"><?=$event_url?></a></span>
                        </span>
                </div>
	<?}?>
	<?if($event_facebook_buttons){?>
		<div style="clear:both;"></div>
		<div class="listing-facebook-button">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/check_icon.png';?>"  style="display:inline;"/>
			<span id="facebook-link">
				<?=$event_facebook_buttons?>
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
	<? if($eventGallery) { ?>
    	<div class="gallery">
    		<?=$eventGallery?>
        </div>
	<? } ?>
</div>
<div class="content">
	<div class="content-main">
		<?if($event_summarydesc || $str_time ){?>	
		<div class="overview-hour-operation">
			<div class="overview-box">
					<? if ($event_summarydesc) { ?>
						<span id="overview-title"><?=system_showText(LANG_LABEL_OVERVIEW);?></span>
						<div class="content-box">
							<?=$event_summarydesc?>
						</div>
					<? }?>
			</div>
			<div class="operation-hour-box">
                            	<? if (!empty($event_start_date) || !empty($event_end_date)) { ?>
					<span id="operation-hour-box-title">Date of Operation</span>
                                        <?php if(!empty($event_start_date)): ?>
                                            <div class="content-box">From: <?php echo $event_start_date; ?></div>
                                        <?php endif; ?>
                                        <?php if(!empty($event_end_date)): ?>
                                            <div class="content-box">To: <?php echo $event_end_date; ?></div>
                                        <?php endif; ?>    
				<? } ?>
				<? if ($str_time) { ?>
					<span id="operation-hour-box-title">Hours of Operation</span>
					<div class="content-box">			
						<?=$str_time?>
					</div>
				<? } ?>
			</div>
		</div>
		<?}?>
		<div style="clear:both;"></div>
		<? if ($event_description) { ?>
			<div class="detail-description">
				<span id="description-title"><?=system_showText(LANG_LABEL_DESCRIPTION);?></span>
				<div class="content-box">
				<?=$event_description?>
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
	<? include(system_getFrontendPath("detail_upper_section.php", "frontend", false, EVENT_EDIRECTORY_ROOT)); ?>
	</div>
	<? include(system_getFrontendPath("banner_bottom.php")); ?>
	<?}?>
</div>
			

	