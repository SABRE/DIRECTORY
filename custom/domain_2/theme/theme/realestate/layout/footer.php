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
    # * FILE: /theme/realestate/layout/footer.php
    # ----------------------------------------------------------------------------------------------------

    //Links to twitter, facebook and linkedin
    setting_get("twitter_account", $setting_twitter_link);
    setting_get("setting_facebook_link", $setting_facebook_link);
    setting_get("setting_linkedin_link", $setting_linkedin_link);

?>
	
        </div>

        <div id="footer-wrapper">

            <div id="footer">
                <!-- The code below controls the social network links of the footer navigation. -->
                <? if ($setting_twitter_link || $setting_facebook_link || $setting_linkedin_link) { ?>
                <div class="social-buttons">
                    
                    <? if ($setting_twitter_link) { ?>
                        <a href="http://www.twitter.com/<?=$setting_twitter_link?>" target="_blank" title="<?=system_showText(LANG_FOLLOW_US_TWITTER)?>">
                            <img src="<?=DEFAULT_URL?>/theme/<?=EDIR_THEME?>/images/iconography/icon-share-twitter.png" alt="" />
                        </a>
                    <? } ?>
                    
                    <? if ($setting_facebook_link) { ?>
                        <a href="<?=$setting_facebook_link?>" target="_blank" title="<?=system_showText(LANG_ALT_FACEBOOK)?>">
                            <img src="<?=DEFAULT_URL?>/theme/<?=EDIR_THEME?>/images/iconography/icon-share-facebook.png" alt="" />
                        </a>
                    <? } ?>
                    
                    <? if ($setting_linkedin_link) { ?>
                        <a href="<?=$setting_linkedin_link?>" target="_blank" title="<?=system_showText(LANG_ALT_LINKEDIN)?>">
                            <img src="<?=DEFAULT_URL?>/theme/<?=EDIR_THEME?>/images/iconography/icon-share-linkedin.png" alt="" />
                        </a>
                    <? } ?>
                    
                </div>
                <? } ?>
                
                <!-- The code below controls the Copyright info  -->
                <? front_getCopyright($footer); ?>
                
                <p class="copyright">
                    <?=$footer?>
                </p>
                
                <p class="site-url"><?=DEFAULT_URL?></p>
            </div>
                
        </div>

        <!-- Search statistic report - DO NOT REMOVE THIS CODE  -->
        <?=front_statisticReport($banner_section);?>

        <!-- Google maps for results page - DO NOT REMOVE THIS CODE  -->
        <?=front_googleMaps($itemRSSSection, $listings, $classifieds, $events, $promotions, $levelObj);?>

        <!-- Google analytics code - DO NOT REMOVE THIS CODE  -->
        <?=front_googleAnalytics();?>

        <!-- Include all js and css files (minimized) - DO NOT REMOVE THIS CODE  -->
        <? script_loader($js_fileLoader, $pag_content, $aux_module_per_page, $id, $aux_show_twitter); ?>
        
        <!-- Pinterest Button - DO NOT REMOVE THIS CODE  -->
        <?=front_pinterestButton();?>
    </body>
</html>