<?
	if(ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER){
        $spanName = 'Listings';
        $defaultUrl = LISTING_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
    	$spanName = 'Classifieds';
    	$defaultUrl = CLASSIFIED_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
        $spanName = 'Events';
        $defaultUrl = EVENT_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
        $spanName = 'Articles';
        $defaultUrl = ARTICLE_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
        $spanName = 'Promotions';
        $defaultUrl = PROMOTION_DEFAULT_URL;
    } elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
        $spanName = 'Blogs';
        $defaultUrl = BLOG_DEFAULT_URL;
    }
?>
<?php
    $listing_cookie_namespace = ACTUAL_MODULE_FOLDER."_result_cookie"; 
    if(!empty($_SESSION[$listing_cookie_namespace])){
        $http_url = (strpos($listing_cookie_namespace,'http'))?"":"http://";
        $back_url =  $http_url.$_SESSION[$listing_cookie_namespace];        
    }else{
        $back_url =  NON_SECURE_URL."/".ACTUAL_MODULE_FOLDER;
    } 

?>
<? /*$backToSearchUrl*/ ?>
<div class="back-search">
	<a href="<?php echo $back_url;?>"></a>
</div>
<div class="previous-next">
	<span id="module-name"><?=$spanName?></span>
	<div class="previous-next-button">
		<div class="previous-next-list">
                    <div class="previous-link">
                            <?if($prevPageLink){ $prevPageLinkUrl = $defaultUrl."/".htmlspecialchars($prevPageLink).".html";?>
                                    <a href="<?=$prevPageLinkUrl;?>"><span>Previous</span></a>
                            <?}else{?>
                                    <a href="javascript:void(0);"><span>Previous</span></a>
                            <?}?>
                    </div>
                    <div class="next-link">
                        <?if($nextPageLink){ $nextPageLinkUrl = $defaultUrl."/".htmlspecialchars($nextPageLink).".html";?>
                            <a href="<?=$nextPageLinkUrl;?>"><span>Next</span></a>
                        <?}else{?>
                            <a href="javascript:void(0);"><span>Next</span></a>
                        <?}?>
                    </div>	
			
		</div>
	</div>
</div>