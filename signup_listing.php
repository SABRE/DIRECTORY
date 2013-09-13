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
	# * FILE: /signup_listing.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	
    $contentObj = new Content();
	
	$sitecontentSection = "Listing Advertisement";
	$content = $contentObj->retrieveContentByType($sitecontentSection);
	if ($content) {
		echo "<blockquote>";
			echo "<div class=\"content-custom\">".$content."</div>";
		echo "</blockquote>";
	}
	
    $listing = new Listing();
    unset($levelObj);
	$levelObj = new ListingLevel();
	$level = $levelObj;
	$activeLevels = $levelObj->getLevelValues();
   
    $arrayLevelLinks = array();
    $countLevels = 0;
    foreach ($activeLevels as $levelValue) {
        $countLevels++;
        $arrayLevelLinks[] = "<li id=\"tabListingLevel_".$levelValue."\" ".($countLevels == 1 ? "class=\"active\"" : "")." onclick=\"showTabLevels('Listing', ".$levelValue.");\"><h2 class=\"level-name\">".$level->getName($levelValue)."</h2></li>";
        $arrayLevelLinks[] = "<li> | </li>";
    }
    array_pop($arrayLevelLinks);
    
?>
    <ul class="tabsLevels tabsLevelsListing"><?=implode("", $arrayLevelLinks);?></ul>
<?
	
	$tPreview = "preview";
	
	$arrListingAux['title'] = system_showText(LANG_LABEL_ADVERTISE_LISTING_TITLE);
	$arrListingAux['email'] = system_showText(LANG_LABEL_ADVERTISE_ITEM_EMAIL);
    $arrListingAux['url'] = system_showText(LANG_LABEL_ADVERTISE_ITEM_SITE);
    $arrListingAux['address'] = system_showText(LANG_LABEL_ADVERTISE_ITEM_ADDRESS);
    $arrListingAux['zip_code'] = ucwords(system_showText(LANG_LABEL_ADVERTISE_ITEM_ZIPCODE));
	$arrListingAux['video_snippet'] = "<img src=\"".THEMEFILE_URL."/".EDIR_THEME."/images/imagery/img-video-sample.jpg\" alt=\"\" title=\"\"/>";
    $arrListingAux['phone'] = '000.000.0000';
    $arrListingAux['fax'] = '000.000.0000';
    $arrListingAux['description'] = 'Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas.';
    $arrListingAux['long_description'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque luctus enim ac diam malesuada vestibulum vitae at tortor. Nullam nec porttitor arcu. Pellentesque laoreet lorem egestas felis lobortis eu tincidunt nulla tempor. Phasellus adipiscing fringilla tempus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sed sapien ut eros porta volutpat et quis leo. Aenean tincidunt ipsum quis nisl blandit nec placerat eros consectetur. Morbi convallis, est quis venenatis fermentum, sapien nibh auctor arcu, auctor mattis justo nisi tincidunt neque. Quisque cursus luctus congue. Quisque vel nulla vitae arcu faucibus placerat. Curabitur iaculis molestie sagittis.';
    $arrListingAux['clicktocall_number'] = '000';
	$arrListingAux['hours_work'] = system_showText(LANG_HOURWORK_SAMPLE_1);;
	$arrListingAux['locations'] = 'Lorem ipsum dolor sit amet, consectetur.';
	$arrCheckinAux["checkin_name"] = system_showText(LANG_LABEL_ADVERTISE_VISITOR);
	$arrCheckinAux["added"] = date("Y-m-d")." ".date("H:m:s");
	$arrCheckinAux["quick_tip"] = "Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica formas.";
	$checkinsArr[] = new CheckIn($arrCheckinAux);
	$checkinsArr[] = new CheckIn($arrCheckinAux);
	$checkinsArr[] = new CheckIn($arrCheckinAux);
    /**
     * This variable is used on view_listing_summary.php
     */
	  
    if (TWILIO_APP_ENABLED == "on"){
        if (TWILIO_APP_ENABLED_SMS == "on"){
            $levelsWithSendPhone = system_retrieveLevelsWithInfoEnabled("has_sms");
        }else{
            $levelsWithSendPhone = false;
        }
        if (TWILIO_APP_ENABLED_CALL == "on"){
            $levelsWithClicktoCall = system_retrieveLevelsWithInfoEnabled("has_call");
        }else{
            $levelsWithClicktoCall = false;
        }
    }else{
        $levelsWithSendPhone = false;
        $levelsWithClicktoCall = false;
    }
	unset($arrCheckinAux);
    
	$countLevels = 0;
	foreach ($activeLevels as $levelValue) {
        $countLevels++;
		$arrListingAux['level'] = $levelValue;
		$listing->makeFromRow($arrListingAux);
		$listingObj = $listing;
		
		if ($level->getPrice($levelValue) > 0) {
			$price = CURRENCY_SYMBOL.$level->getPrice($levelValue)." ".system_showText(LANG_PER)." ";
			if (payment_getRenewalCycle("listing") > 1) {
				$price .= payment_getRenewalCycle("listing")." ";
				$price .= payment_getRenewalUnitNamePlural("listing");
			}else {
				$price .= payment_getRenewalUnitName("listing");
			}
			if ($payment_tax_status == "on") {
				$price .= "<br />+".$payment_tax_value."% ".$payment_tax_label;
				$price .= " (".CURRENCY_SYMBOL.payment_calculateTax($level->getPrice($levelValue), $payment_tax_value).")";
			}
		} else {
			$price = CURRENCY_SYMBOL.system_showText(LANG_FREE);
		}
		
		?>
		
		<div class="level levelListing" id="contentListingLevel_<?=$levelValue?>" <?=$countLevels == 1 ? "style=\"\"": "style=\"display: none;\"";?>>
		
			<div class="level-info">
				
				<p><?=nl2br(strip_tags($level->getContent($levelValue)));?></p>
				<p class="price"><?=$price;?></p>
				<div class="button button-profile">
					<h2><a href="<?=DEFAULT_URL?>/order_listing.php?level=<?=$levelValue?>"><?=system_showText(LANG_BUTTON_SIGNUP);?></a></h2>			
				</div>
			</div>
			
			<div class="level-summary">				
			
				<p class="preview-desc"><?=system_showText(LANG_LABEL_ADVERTISE_SUMMARYVIEW);?><span><?="* ".system_showText(LANG_LABEL_ADVERTISE_CONTENTILLUSTRATIVE);?></span></p>
				
				<? 	$includeUrl = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/default/body/extra/";
					include($includeUrl."includes/views/view_listing_summary.php"); 
					//include(INCLUDES_DIR."/views/view_listing_summary.php"); 
				?>
				
			</div>
			
			<?
			$listing = $listingObj;
			if ($levelObj->getDetail($listing->getNumber("level")) == "y") {
				$typePreview = "detail"; 
            ?>
				<!-- <div class="level-detail">-->
					<p class="preview-desc"><?=system_showText(LANG_LABEL_ADVERTISE_DETAILVIEW);?><span><?="* ".system_showText(LANG_LABEL_ADVERTISE_CONTENTILLUSTRATIVE);?></span></p>
					<!-- <div class="content"> -->
						<?	if(SELECTED_DOMAIN_ID > 0){
								echo "<div class='detail-page'>";
								echo "<div class='below-section'>";
								include($includeUrl."includes/views/view_listing_detail.php");
								echo "</div>"
						?>
								<div class="sidebar">
                        		<? $signUpListing = true; include(system_getFrontendPath("detail_info.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_maps.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_deals.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_reviews.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								<? $signUpListing = true; include(system_getFrontendPath("detail_checkin.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
								</div>
						<?		echo "</div>";
							}else{
								include(INCLUDES_DIR."/views/view_listing_detail.php");
							} 
						?>
					<!-- 
						<? //include(INCLUDES_DIR."/views/view_listing_detail.php");?>
					</div>
					<div class="sidebar">
	                    <? $signUpListing = true; include(system_getFrontendPath("detail_info.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
						<? $signUpListing = true; include(system_getFrontendPath("detail_maps.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
						<? $signUpListing = true; include(system_getFrontendPath("detail_deals.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
						<? $signUpListing = true; include(system_getFrontendPath("detail_reviews.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
						<? $signUpListing = true; include(system_getFrontendPath("detail_checkin.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
					</div> -->
				<!-- </div> -->
			<? 
			 unset($typePreview);
			} 
			?>
		
		</div>		
		
<? } ?>