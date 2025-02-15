
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
	# * FILE: /frontend/detail_reviews.php
	# ----------------------------------------------------------------------------------------------------
	if ((ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) || $signUpListing) {
        $moduleMessage = $listingMsg;
        $moduleReviews = $listingtemplate_review;
        $moduleURL = LISTING_DEFAULT_URL;
        $moduleObj = $listing;
        $signUpListing = false;
    } elseif ((ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER)  || $signUpArticle) {
        $moduleMessage = $articleMsg;
        $moduleReviews = $detail_review;
        $moduleURL = ARTICLE_DEFAULT_URL;
        $moduleObj = $article;
        $signUpArticle = false;
        $levelReview = true;
    } elseif ((ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER)) {
        $moduleMessage = "";
        $moduleReviews = $deal_review;
        $moduleURL = PROMOTION_DEFAULT_URL;
        $moduleObj = $promotion;
        $levelReview = true;
    }

	if (!$moduleMessage && !$hideDetail && $review_enabled == "on" && $commenting_edir && $levelReview) {
		
		if ($tPreview || !$user) {
			$reviewsLink = "javascript:void(0);";
			$linkReviewFormPopup = "javascript:void(0);";
			$linkStyle = "style=\"cursor:default;\"";
		}
        ?>
        <div class="pnt-0">
            <h2>
                <span><?=system_showText(LANG_REVIEW_PLURAL)?></span>
                <? if ($review_amount > $numberOfReviews){ ?>
                    <a class="view-more" href="<?=$reviewsLink?>" <?=$linkStyle?>><?=system_showText(LANG_LABEL_VIEW_ALL2)?></a>
                    <span class="split">|</span>
                <? } ?>
                <a class="view-more <?=!$tPreview? $class : "";?> rate-it" href="<?=$linkReviewFormPopup?>" <?=$linkStyle?>>
                    <? if($review_amount > 0) {
                        echo system_showText(LANG_REVIEWRATEIT);
                    } ?>
                </a>
            </h2>

            <div class="featured featured-review">
                <? if ($review_amount == 0) { ?>
                    <a class="<?=!$tPreview? $class : "";?> rate-it-first" href="<?=$linkReviewFormPopup?>" <?=$linkStyle?>><?=system_showText(LANG_REVIEWBETHEFIRST);?></a>
                <? } else {                    
                    echo $moduleReviews;  
                } ?>
            </div>
        </div>

<? } ?>
    
    <? setting_get("commenting_fb", $commenting_fb);
    
	if (!$moduleMessage && !$hideDetail && $commenting_fb && ($user || $tPreview)) { ?>
    	<div class="pnt-0">
		<h2>
			<span><?=system_showText(LANG_LABEL_COMMENTS)?></span>
		</h2>
        
		<?
		if ($user) {

			$detailLink = $moduleURL."/".ALIAS_SHARE_URL_DIVISOR."/".$moduleObj->getString("friendly_url").".html";

			setting_get("commenting_fb_number_comments", $commenting_fb_number_comments);

			?>

			<script language="javascript" type="text/javascript">
				//<![CDATA[
				(function(d){
				  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
				  js = d.createElement('script'); js.id = id; js.async = true;
				  js.src = "//connect.facebook.net/<?=EDIR_LANGUAGEFACEBOOK?>/all.js#xfbml=1";
				  d.getElementsByTagName('head')[0].appendChild(js);
				}(document));

				document.write('<div class="fb-comments" data-href="<?=$detailLink?>" data-num-posts="<?=$commenting_fb_number_comments?>" data-width="<?=FB_COMMENTWIDTH?>"></div>');
				//]]>
			</script>
            
        <? } else { ?>
			<img src="<?=THEMEFILE_URL."/".EDIR_THEME."/schemes/".EDIR_SCHEME."/images/structure/facebook-comment-sample.png"?>" alt="Sample" />
        <? } ?>
        
		</div>
        
    <? } ?>        