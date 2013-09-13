<!-- STYLE ADD ON -06-09-2013 FOR locationtemplate AND attachmenttemplate-->
<style>
        .locationtemplate
        {
            color:#5E5E5E;
            font-size:16px;
            font-style:normal;
            font-weight: bold;
            padding-left:25px;
            padding-top:10px;
        }
        
        .attachmenttemplate
        {
            color:#5E5E5E;
            font-size:16px;
            font-style:normal;
            font-weight: bold;
            padding-left:25px;
            padding-top:10px;
        }
        .attachmenttemplate a
        {
            color: #004276;
            font-size: 15px !important;
            font-weight: normal !important;
            line-height: 15px;
        }
        
</style>
<!--STYLE ADDED ON THE -06-09-2013-->
<div class="list-title-link-block">
	<div class="listing-title">
		<?=$listingtemplate_title;?>
	</div>
	<div class="images-link">
		<div id="forward">
			<?=$shareListingTemplate;?>
		</div>
		<div id="favourites">
			<?=$favoriteListingTemplate;?>
		</div>
		<div id="sendPhone">
			<a href="<?=$listingtemplate_twilioSMS?>" <?=$twilioSMS_style?> title="Send to SMS" ></a>
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
		<? if ($listingtemplate_category_tree) { ?>
			<?=$listingtemplate_category_tree?>
		<? } ?>
	</div>
	<div style="clear:both;"></div>
	<div class="review-detail">
            <?=$listingtemplate_summary_review;?>
	</div>
        <div class="claim-link">
            <?=$listingtemplate_claim?>
        </div>
         <?if($listingtemplate_url){?>
            <div style="clear:both;"></div>
            <div class="url-link">
                <?=$listingtemplate_url;?>
            </div>
        <?}?>
	<div style="clear:both;"></div>
	<div class="location-address">
		<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/direction.png';?>" style="display:inline;" />
		<? if(($listingtemplate_address) || ($listingtemplate_address2) || ($listingtemplate_location)) echo "\n<address>\n"; ?>

		<? if ($listingtemplate_address) { ?>
			<span><?=$listingtemplate_address?></span>
		<? } ?>

		<? if ($listingtemplate_address2) { ?>
			<span><?=$listingtemplate_address2?></span>
		<? } ?>

		<? if ($listingtemplate_location) { ?>
			<span><?=$listingtemplate_location?></span>
		<? } ?>

		<? if(($listingtemplate_address) || ($listingtemplate_address2) || ($listingtemplate_location)) echo "</address>\n"; ?>
		<?if($mapListingTemplateDetail)?>
			<span id="map-link"><?=$mapListingTemplateDetail?></span>
                <!--Code 06-09-2013 for Location and attachment on the detail page-->
                <?if($locationTemplate)?>
                    <div class="locationtemplate">
                        <?=$locationTemplate?>
                    </div>
                <?if($attachmentTemplate)?>
                    <div class="attachmenttemplate">
                        <?=$attachmentTemplate?>
                    </div>
                <!--Code End -06-09-2013-->
	</div>
	<?if($listingtemplate_phone){?>
		<div style="clear:both;"></div>
		<div class="contact-number">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/call.png';?>"  style="display:inline;"/>
			<span id="contact-no">
				<?=$listingtemplate_phone?>
			</span>
		</div>
	<?}?>
	<?if($listingtemplate_email){?>
		<div style="clear:both;"></div>
		<div class="email">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/sms.png';?>"  style="display:inline;"/>
			<span id="email-link">
				<?=$listingtemplate_email?>
			</span>
		</div>
	<?}?>
	<?if($listingtemplate_facebook_buttons){?>
		<div style="clear:both;"></div>
		<div class="listing-facebook-button">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/check_icon.png';?>"  style="display:inline;"/>
			<span id="facebook-link">
				<?=$listingtemplate_facebook_buttons?>
			</span>
		</div>
	<?}?>
</div>
<div class="gallery-box">
	<? if ($listingtemplate_image) { ?>
    	<div class="image">
			<?=$listingtemplate_image?>
        </div>
	<? } ?>
	<? if($listingtemplate_gallery) { ?>
    	<div class="gallery">
    		<?=$listingtemplate_gallery?>
        </div>
	<? } ?>
</div>
<div class="content">
	<div class="content-main">	
		<div class="tabs-bar">
			<?include('view_tabs_bar.php');?>
		</div>
		<div style="clear:both;"></div>
		<div id="tab_content">
			<div class="tab_details tabs_details" style="display:block;">
				<?if($listingtemplate_description || $listingtemplate_hours_work ){?>	
					<div class="overview-hour-operation">
						<div class="overview-box">
							<? if ($listingtemplate_description) { ?>
								<span id="overview-title"><?=system_showText(LANG_LABEL_OVERVIEW);?></span>
								<div class="content-box">
									<?=$listingtemplate_description?>
								</div>
							<? }?>
						</div>
						<div class="operation-hour-box">
							<? if ($listingtemplate_hours_work) { ?>
								<span id="operation-hour-box-title">Hours of Operation</span>
								<div class="content-box">			
									<?=$listingtemplate_hours_work?>
								</div>
							<? } ?>
						</div>
					</div>
				<?}?>
				<div style="clear:both;"></div>
				<? if ($listingtemplate_video_snippet) { ?>
					<div class="video-snippet">
						<div class="video">
							<script language="javascript" type="text/javascript">
							//<![CDATA[
							document.write("<?=str_replace("\"","'",$listingtemplate_video_snippet)?>");
							//]]>
							</script>
						</div>
					</div>
				<?}?>
				<? if ($listingtemplate_long_description) { ?>
					<div class="detail-description">
						<span id="description-title"><?=system_showText(LANG_LABEL_DESCRIPTION);?></span>
						<div class="content-box">
						<?=$listingtemplate_long_description?>
						</div>
					</div>
				<? } ?>
			</div>
			<div class="tab_reviews tabs_details" style="display:none;">
                            <div id="review-first-tab" style="display:block;">
                                <div class="review-counts">
                                    <?	if(count($reviewsArr) > 1 || count($reviewsArr) == 0 ){
                                                    echo count($reviewsArr).' Reviews from the community';
                                            }else{
                                                    echo count($reviewsArr).' Review from the community';
                                            }
                                    ?>
                                </div>
                                <div style="clear:both;"></div>
                                <div class="review_rating_box">
                                    <div class="rating_box">
                                            <div class="review_rating_box_left">
                                                <?=$mainStaticStarImages?>
                                            </div>
                                            <?=$linkReviewLogin?>
                                            <!--<div class="review_rating_box_right">
                                               <?=$reviewLinkDetail?>
                                            </div>-->
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <? if($formReviewLoginForm){?>
                                    <div id="review-second-tab" style="display:none;">
                                        <?=$formReviewLoginForm?>
                                    </div>
                                <? }?>
                                <div class="review-information-box">
                                    <div class="featured featured-review">
                                        <?=$listingtemplate_review_detail?>
                                    </div>
                                </div>
                            </div>
                        </div>
			<div class="tab_contact tabs_details" style="display:none;">
				 <?if ($listing->getString("email") && (is_array($array_fields) && in_array("email", $array_fields)) && LISTING_DETAIL_CONTACT == "on") { ?>
					<div class="contact-form">
						<a name="info"></a>
				        <h2 id="contact-formScroll"><?=system_showText(LANG_LISTING_CONTACTTITLE)?></h2>
						<form id="contactForm" name="contactForm" class="form" method="post" action="<?=system_getFormAction($_SERVER["REQUEST_URI"]."#info")?>">
							<? foreach ($_GET as $key => $value) print "<input type=\"hidden\" name=\"$key\" value=\"$value\" />"; ?>
				            <input type="hidden" name="id" value="<?=$_GET["id"]?>" />
				            <input type="hidden" name="id" value="<?=$_GET["id"]?>" />
				            <input type="hidden" name="to" value="<?=string_htmlentities($listing->getString("email"))?>" />
							<? if ($error) { ?>
								<p class="<?=$message_style?>"><?=$error?></p>
				            <? } ?>
							<div>
	            				<label for="name">* <?=system_showText(LANG_LABEL_NAME);?></label>
    	        				<input class="text" type="text" name="name" id="name" value="<?=$name?>" />
							</div>
							<div>
            					<label for="from">* <?=system_showText(LANG_LABEL_YOUREMAIL)?></label>
	            				<input class="text" type="text" name="from" id="from" value="<?=$from?>" />
							</div>
							<div>
	            				<label for="subject"><?=system_showText(LANG_LABEL_SUBJECT)?></label>
    	        				<input class="text" type="text" name="subject" id="subject" value="<?=$subject?>" />
							</div>
							<div>
	            				<label for="body">* <?=system_showText(LANG_LABEL_ADDITIONALMSG)?></label>
            					<?$body = str_replace("<br />", "", $body);?>
								<textarea class="textarea" name="body" rows="5" id="body" cols=""><?=html_entity_decode($body)?></textarea>
							</div>
							<p><?=system_showText(LANG_CAPTCHA_HELP)?></p>
							<div class="captcha">
							<div>
								<img src="<?=DEFAULT_URL?>/includes/code/captcha.php" border="0" alt="<?=system_showText(LANG_CAPTCHA_ALT)?>" title="<?=system_showText(LANG_CAPTCHA_TITLE)?>" />
								<input class="text" type="text" name="captchatext" value="" />
	            			</div>
						</div>
						<div class="button button-contact">
							<h2><a href="<?=$user ? "javascript: document.contactForm.submit();" : "javascript: void(0);"?>" <?=$user ? "": "style=\"cursor:default\""?>><?=LANG_BUTTON_SEND?> <?=LANG_LABEL_MESSAGE?></a></h2>			
						</div>
					</form>
    			</div>
				<?}?>
			</div>
		</div>
	</div>
	<div style="clear:both;"></div>
	<? 
		$url = $_SERVER['REQUEST_URI'];
		$fileName = basename($url,".php");
		if($fileName != 'advertise'){
	?>
	<div class="upper-section" style="border-top:1px solid #BFBFBF;border-bottom:none;margin-top:20px;padding-top:20px;">
		<? include(system_getFrontendPath("detail_upper_section.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
	</div>
	<? include(system_getFrontendPath("banner_bottom.php")); ?>
	<?}?>	
</div>
<script>
$(document).ready(function(){
	var height = $('.listing-info-box').height();
	if(height > $('.gallery-box').height()){
		$('.gallery-box').height(height+4);
	}

	$(".tabs-bar a").click(function(e){
		e.preventDefault();
		var obj = this;
		var tab = $(obj).attr('tab');
		var tab_to_open = "tab_"+tab;
		$(".tabs_details").hide();
		$("."+tab_to_open).show();
		$(".tabs-bar li").removeClass("active");
		$(obj).parent().addClass("active");
		 	
	});
        
         $(".review_link").click(function(){
            $("#review-second-tab").slideToggle('slow');
        });
        
        $(".tabs-bar a").click(function(){
           $("#review-second-tab").slideUp('slow');
        });
});
</script>