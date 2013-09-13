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
    # * FILE: /layout/footer.php
    # ----------------------------------------------------------------------------------------------------

    //Links to facebook and linkedin
    setting_get("setting_linkedin_link", $setting_linkedin_link);
    setting_get("setting_facebook_link", $setting_facebook_link);

?>
        </div>

        <div id="footer-wrapper">
			<div id="centerfooter">
			<div id="footerleft"></div>
            
			<div id="footer">

                <div class="left">
                    <!-- The code below controls the social network links of the footer navigation. -->
                    <h3>
                        <span><?=system_showText(LANG_FOOTER_CONTACT)?></span>
                        <? if ($setting_linkedin_link){ ?>
                            <a class="link linkedin" href="<?=$setting_linkedin_link?>" target="_blank" title="<?=system_showText(LANG_ALT_LINKEDIN)?>">
                                Linked In
                            </a>
                        <? } ?>
                            
                        <? if ($setting_facebook_link) { ?>
                            <a class="link facebook" href="<?=$setting_facebook_link?>" target="_blank" title="<?=system_showText(LANG_ALT_FACEBOOK)?>">
                                Facebook
                            </a>
                        <? } ?>
                    </h3>
                    
                    <!-- The "navbar-footer" below controls all the navigation in the footer. If you want to change a link, it's better to edit the corresponding language file - example, for English you would use the EN_US.php file.-->
                    <ul class="navbar-footer">
                        <!-- The code below controls the 'Home' section of the footer navigation, use the EN_US.php file. -->
                        <li>
                            <a href="<?=NON_SECURE_URL?>">
                                <?=system_showText(LANG_MENU_HOME);?>
                            </a>
                        </li>
                        
                        <!-- The code below controls the 'Advertise With Us' section of the footer navigation, use the EN_US.php file. -->
                        <li>
                            <a href="<?=NON_SECURE_URL?>/<?=ALIAS_ADVERTISE_URL_DIVISOR?>.php">
                                <?=system_showText(LANG_MENU_ADVERTISE);?>
                            </a>
                        </li>
                        
                        <!-- The code below controls the 'FAQ' section of the footer navigation, use the EN_US.php file. -->
                        <li>
                            <a href="<?=NON_SECURE_URL?>/<?=ALIAS_FAQ_URL_DIVISOR?>.php">
                                <?=system_showText(LANG_MENU_FAQ);?>
                            </a>
                        </li>
                        
                        <!-- The code below controls the 'Sitemap' section of the footer navigation, use the EN_US.php file. -->
                        <li>
                            <a href="<?=NON_SECURE_URL?>/<?=ALIAS_SITEMAP_URL_DIVISOR?>.php">
                                <?=system_showText(LANG_MENU_SITEMAP);?>
                            </a>
                        </li>
                        
                        <!-- The code below controls the 'Contact Us' section of the footer navigation, use the EN_US.php file. -->
                        <li>
                            <a href="<?=NON_SECURE_URL?>/<?=ALIAS_CONTACTUS_URL_DIVISOR?>.php">
                                <?=system_showText(LANG_MENU_CONTACT);?>
                            </a>
                        </li>
                    </ul>
                </div>
				
                <div class="left">
                    <h3><?=system_showText(LANG_LINKS)?></h3>
                    <ul class="navbar-footer">
                        <? include(system_getFrontendPath("footer_menu.php", "layout")); ?>
                    </ul>
                </div>
				
                <?
                //This function returns the site manager's Twitter Account and Last Tweets structure.
                front_twitterFooter($twitterAccount, $timeLine);
                if ($twitterAccount) {
                ?>
                    <div class="left">
                        <div class="last-tweets">
                            <h3>
                                <span><?=system_showText(LANG_TWITTER)?></span>
                                <a class="follow twitter" href="http://www.twitter.com/<?=$twitterAccount;?>" target="_blank" title="<?=system_showText(LANG_FOLLOW_US_TWITTER)?>">
                                    <?=system_showText(LANG_FOLLOW_US)?> +
                                </a>
                            </h3>
                            
                            <?=$timeLine;?>
                        </div>
                    </div>
                <? } ?>
                
                <!-- The code below controls the Copyright info  -->
                <div class="right">
                    <? front_getCopyright($footer); ?>
					
                    <p class="copyright">
                        <?=$footer?>
                    </p>
                </div>
			<!-- this includes the javascript that injects a background image -->	
            
			<? include(EDIRECTORY_ROOT."/layout/detectbackground.php"); ?>   
			 
            </div>
			
			<div id="footerright"></div>
			</div>
        </div>

        <!-- Search statistic report - DO NOT REMOVE THIS CODE  -->
        <?=front_statisticReport($banner_section);?>
		<!-- Google maps for results page - DO NOT REMOVE THIS CODE  -->
        <?php front_googleMaps($itemRSSSection, $listings, $classifieds, $events, $promotions, $levelObj);?>

        <!-- Google analytics code - DO NOT REMOVE THIS CODE  -->
        <?=front_googleAnalytics();?>

        <!-- Include all js and css files (minimized) - DO NOT REMOVE THIS CODE  -->
        <? script_loader($js_fileLoader, $pag_content, $aux_module_per_page, $id, $aux_show_twitter); ?>
        
        <!-- Pinterest Button - DO NOT REMOVE THIS CODE  -->
        <?=front_pinterestButton();?>
    </body>
</html>