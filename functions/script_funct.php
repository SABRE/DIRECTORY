<?php

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
	# * FILE: /functions/script_funct.php
	# ----------------------------------------------------------------------------------------------------

    function script_loader(&$js_fileLoader, $pag_content, $aux_module_per_page, $id, &$aux_show_twitter) {
        
        if (!isset($js_fileLoader)) { ?>
            <script type="text/javascript">
                <!--
                DEFAULT_URL = "<?=DEFAULT_URL?>";
                -->
            </script>

        <?

            include(EDIRECTORY_ROOT."/includes/code/ie6pngfix.php");

            /*
             * LOAD IN ALL PAGES
             */
            $css_fileLoader = system_scriptColectorCSS("/scripts/jquery/jquery.autocomplete.css", $css_fileLoader);
            system_scriptColectorCSS("/scripts/jquery/fancybox/jquery.fancybox-1.3.4.css", false, false);
            system_scriptColectorCSS("/scripts/jquery/jquery_ui/css/smoothness/jquery-ui-1.7.2.custom.css", false, false);
            system_scriptColectorCSS("/scripts/jquery/jcrop/css/jquery.Jcrop.css", false, false);

            system_scriptColector("/scripts/common.js?v=1.0", false, false, false);
            system_scriptColector(language_getFilePath(EDIR_LANGUAGE, true, true), false, false, false);
            system_scriptColector("/scripts/jquery.js", false, "$.preloadCssImages();", false);
            system_scriptColector("/scripts/jquery/urlencode.js", false, false, false);
            system_scriptColector("/scripts/jquery/jquery.watermark.min.js", false, false, false);

            if (string_strpos($_SERVER["PHP_SELF"], "/profile") !== false) {

                system_scriptColector("/scripts/jquery/jquery_ui/js/jquery-ui-1.7.2.custom.min.js", false, false, false);
                if (EDIR_LANGUAGE != "en_us") {
                    system_scriptColector(language_getDatePickPath(EDIR_LANGUAGE, SELECTED_DOMAIN_ID, true), false, false, false);
                }
                system_scriptColector("/scripts/jquery/jquery.autocomplete.min.js", false, false, false);
                system_scriptColector("/scripts/jquery/fancybox/jquery.fancybox-1.3.4.js", false, false, false);
                system_scriptColector("/scripts/jquery/jquery.cookie.min.js", false, false, false);

                /*
                 * LOAD JUST IN "/profile/edit.php"
                 */
                if (string_strpos($_SERVER["PHP_SELF"], "/edit.php") !== false) {
                    $js_fileLoader = system_scriptColector("/scripts/jquery/jcrop/js/jquery.Jcrop.js", $js_fileLoader);
                    $js_fileLoader = system_scriptColector("/scripts/jquery/jquery.textareaCounter.plugin.js", $js_fileLoader);
                }

                $js_fileLoader = system_scriptColector("/scripts/jquery/jquery.selectbox.js", $js_fileLoader);

                /*
                 * LOAD JUST IN "/profile/add.php"
                 */
                if (string_strpos($_SERVER["PHP_SELF"], "/add.php") !== false) {
                    $js_fileLoader = system_scriptColector("/scripts/checkusername.js", $js_fileLoader);
                    $js_fileLoader = system_scriptColector("/scripts/advancedsearch.js", $js_fileLoader);
                }

                /*
                 * LOAD JUST IN "/profile/index.php"
                 */
                if (string_strpos($_SERVER["PHP_SELF"], "/index.php") !== false) {
                    $js_fileLoader = system_scriptColector("/scripts/contactclick.js", $js_fileLoader);
                    $js_fileLoader = system_scriptColector("/scripts/socialbookmarking.js", $js_fileLoader);

                    if ($pag_content == "favorites" || $pag_content == "reviews") {
                        /**
                         * Script to results per page
                         */
                        $results_per_page_script = "$('#results_per_page').removeAttr('disabled');
                                                    $('#results_per_page').change(function(){
                                                        $.cookie('".$aux_module_per_page."_per_page', $('#results_per_page').val(), {path: '".EDIRECTORY_FOLDER."/'}); 
                                                        $(location).attr('href','".$_SERVER["REQUEST_URI"]."');
                                                    });";
                    }

                    $js_fileLoader = system_scriptColectorOnReady($results_per_page_script, $js_fileLoader, true);
                }
                
                if (THEME_REMOVE_FAVORITES_DIV) {
                    $removeFavorites_script = " $(document).ready(function(){
                                                    $('.image.favoritesGrid').hover(function(){
                                                        $('.coverFavorites', this).stop().animate({top:'0'},{queue:false,duration:160});
                                                    }, function() {
                                                        $('.coverFavorites', this).stop().animate({top:'-37px'},{queue:false,duration:160});
                                                    });
                                                });";

                    $js_fileLoader = system_scriptColectorOnReady($removeFavorites_script, $js_fileLoader, true);
                }
                
            } else {

                if (!SLIDER_USE_NEWEST) {
                    $js_fileLoader = system_scriptColector("/scripts/jquery/easySlider-FadeIn.js", false, false, false);
                } else {
                    $js_fileLoader = system_scriptColector("/scripts/jquery/jquery.easySlider1.7.js", false, false, false);
                }

                /*
                 * GALLERY SCRIPT AND BLOG SCRIPT
                 */
                if (string_strpos($_SERVER['REQUEST_URI'], ".html") !== false && defined("ACTUAL_MODULE_FOLDER") && ACTUAL_MODULE_FOLDER != "") {
                    if (USE_GALLERY_PLUGIN) {
                        $js_fileLoader = system_scriptColector("/scripts/jquery/ad-gallery/jquery.ad-gallery.js", false, false, false);
                    }
                    if (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
                        $js_fileLoader = system_scriptColector("/scripts/blog.js", false, false, false);
                    }
                }

                /*
                 * LOAD IN ALL PAGES EXCEPT IN "/blog" and "/article"
                 */
                //if (ACTUAL_MODULE_FOLDER != BLOG_FEATURE_FOLDER && ACTUAL_MODULE_FOLDER != ARTICLE_FEATURE_FOLDER) {
                    $js_fileLoader = system_scriptColector("/scripts/location.js", $js_fileLoader);
                //}

                /*
                 * LOAD JUST IN "/listing", "/classified", "/event" and "/deal" //will now load for all 23/01/13
                 */
                //if (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER || ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER || ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER || ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER || ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER || ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
                    system_scriptColector("/scripts/advancedsearch.js", false, false, false);
                //}

                system_scriptColector("/scripts/jquery/jquery_ui/js/jquery-ui-1.7.2.custom.min.js", false, false, false);
                if (EDIR_LANGUAGE != "en_us") {
                    system_scriptColector(language_getDatePickPath(EDIR_LANGUAGE, SELECTED_DOMAIN_ID, true), false, false, false);
                }
                system_scriptColector("/scripts/jquery/jquery.autocomplete.min.js", false, false, false);
                system_scriptColector("/scripts/jquery/fancybox/jquery.fancybox-1.3.4.js", false, false, false);
                system_scriptColector("/scripts/jquery/jquery.cookie.min.js", false, false, false);

                $js_fileLoader = system_scriptColector("/scripts/jquery/jquery.selectbox.js", $js_fileLoader);

                /*
                 * LOAD JUST IN "claim" and "/order_*.php"
                 */
                if (string_strpos($_SERVER["REQUEST_URI"], ALIAS_CLAIM_URL_DIVISOR."/") !== false || string_strpos($_SERVER["PHP_SELF"], "/order_") !== false) {
                    $js_fileLoader = system_scriptColector("/scripts/checkusername.js", $js_fileLoader);
                }

                /*
                 * LOAD JUST IN results, detail, reviews page, checkins page and blog home page
                 */
                if (string_strpos($_SERVER["REQUEST_URI"], "/results.php") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false || (string_strpos($_SERVER['REQUEST_URI'], ".html") !== false) || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CHECKIN_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_REVIEW_URL_DIVISOR."/") !== false || ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
                    $js_fileLoader = system_scriptColector("/scripts/socialbookmarking.js", $js_fileLoader);
                    $js_fileLoader = system_scriptColector("/scripts/contactclick.js", $js_fileLoader);
                }

                /*
                 * GOOGLE+ SCRIPT
                 */
                 if (string_strpos($_SERVER['REQUEST_URI'], ".html") !== false && defined("ACTUAL_MODULE_FOLDER") && ACTUAL_MODULE_FOLDER != ""){                     
                    unset($aux_googleplus_script);
                    $aux_googleplus_script = share_getGoogleButton("", "", true, "language");
                    if ($aux_googleplus_script) {
                        system_scriptColector("https://apis.google.com/js/plusone.js", false, $aux_googleplus_script, false, true);
                    }
                }
            }

            $js_fileLoader = system_scriptColector("/scripts/float_layer.js",$js_fileLoader);
            $js_fileLoader = system_scriptColectorOnReady(" $('.headerLogin').fadeIn(50); ", $js_fileLoader, true);

            system_renderCSSs($css_fileLoader);

            /*
             * Script to languages flags, ajax content on front and fancybox
             */
            $aux_script = ' $("#all-languages-button").hover(function() {
                                $(\'.all-languages\').slideDown(\'slow\');
                                    }, function() {
                                $(\'.all-languages\').slideUp(\'slow\');
                            });

                            $(document).ready(function() {

                                $("a.fancy_window").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75
                                });

                                 $("a.fancy_window_default").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : 650,
                                    \'height\'          : 500,
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_login").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_LOGIN_WIDTH.',
                                    \'height\'          : '.FANCYBOX_LOGIN_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                 $("a.fancy_window_login_modal").fancybox({
                                    \'modal\'           : true,
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_LOGIN_WIDTH.',
                                    \'height\'          : '.FANCYBOX_LOGIN_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_tofriend").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_TOFRIEND_WIDTH.',
                                    \'height\'          : '.FANCYBOX_TOFRIEND_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_gallery").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_GALLERY_WIDTH.',
                                    \'height\'          : '.FANCYBOX_GALLERY_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_twilio").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_TWILIO_WIDTH.',
                                    \'height\'          : '.FANCYBOX_TWILIO_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_review").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_REVIEW_WIDTH.',
                                    \'height\'          : '.FANCYBOX_REVIEW_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_redeem").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : '.FANCYBOX_DEAL_WIDTH.',
                                    \'height\'          : '.FANCYBOX_DEAL_HEIGHT.',
                                    \'autoDimensions\'  : false
                                });

                                $("a.fancy_window_terms").fancybox({
                                    \'overlayShow\'     : true,
                                    \'overlayOpacity\'  : 0.75,
                                    \'width\'           : 650,
                                    \'height\'          : 500
                                });

                                $("a.fancy_window_categPath").fancybox({
                                    \'hideOnContentClick\'	: false,
                                    \'overlayShow\'			: true,
                                    \'overlayOpacity\'		: 0.75,
                                    \'width\'               : 300,
                                    \'height\'              : 100
                                });
                            });
                        ';

            $js_fileLoader = system_scriptColectorOnReady($aux_script, $js_fileLoader, true);

            if ((LOAD_MODULE_CSS_HOME == "on") || (ACTUAL_MODULE_FOLDER == "")) {
                /*
                 * Script to accordion and index sidebar
                 */

                $js_fileLoader = system_scriptColector("/scripts/jquery/jquery.accordion.js",$js_fileLoader);

                $sidebar_script = "$(document).ready(function(){
                                        $.get(DEFAULT_URL + \"/includes/code/frontajax.php\", {
                                            type: 'sidebar_index'
                                        }, function (ret) {
                                            $(\"#sidebar_ajax\").html(ret);
                                            $('ul#accordion').accordion();
                                            $('.current').show();
                                        });  
                                    });";

                $js_fileLoader = system_scriptColectorOnReady($sidebar_script, $js_fileLoader, true);
                
            } elseif (string_strpos($_SERVER["REQUEST_URI"], "results.php") || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_REVIEW_URL_DIVISOR."/") || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CHECKIN_URL_DIVISOR."/")) {

                if ((string_strpos($_SERVER["REQUEST_URI"], "results.php") || string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false) && FEATURED_LOCATIONS_SIDEBAR) {
                    /*
                     * Script to accordion 
                     */

                    $js_fileLoader = system_scriptColector("/scripts/jquery/jquery.accordion.js",$js_fileLoader);

                    $sidebar_script = "$(document).ready(function(){
                                            $('ul#accordion').accordion();
                                            $('.current').show(); 
                                        });";

                    $js_fileLoader = system_scriptColectorOnReady($sidebar_script, $js_fileLoader, true);
                }

                /**
                 * Script to results per page
                 */

                $aux_request_uri = $_SERVER["REQUEST_URI"];
                if (string_strpos($aux_request_uri, "page/".$_GET["page"]) !== false && $_GET["page"] && $_GET["url_full"]) {
                    $aux_request_uri = str_replace("page/".$_GET["page"], "page/1", $aux_request_uri);
                } elseif(string_strpos($aux_request_uri, "screen=".$_GET["screen"]) !== false && $_GET["screen"]) {
                    $aux_request_uri = str_replace("screen=".$_GET["screen"], "screen=1", $aux_request_uri);
                }

                $results_per_page_script = "$('#results_per_page').removeAttr('disabled');
                                            $('#results_per_page').change(function(){
                                                $.cookie('".$aux_module_per_page."_results_per_page', $('#results_per_page').val(), {path: '".EDIRECTORY_FOLDER."/'}); 
                                                $(location).attr('href','".$aux_request_uri."');
                                            });";

                $js_fileLoader = system_scriptColectorOnReady($results_per_page_script, $js_fileLoader, true);

            }

            /*
             * Scripts to Last twetts on footer
             */
            unset($twitterObj);
            $twitterObj = new Twitter();
            if ($twitterObj->getRandonAccount()) {

                $tweetInfo = $twitterObj->userInfo();
                if ($tweetInfo["protected"] != "true") {

                    $js_fileLoader = system_scriptColector("/scripts/jquery/twitter/twitter.js",$js_fileLoader);
                    $last_twitts_script = "$('#twitter_update_list_footer').fadeIn(100,function(){ $('#twitter_update_list_footer').load('".DEFAULT_URL."/twitter_updates.php?posts=".MAX_TWEETS_FRONT."&user=".$twitterObj->getString("account")."') }) ; ";
                    $js_fileLoader = system_scriptColectorOnReady($last_twitts_script, $js_fileLoader, true);
                }
            } 

            if (string_strpos($_SERVER["PHP_SELF"], "profile/index.php") == true || string_strpos($_SERVER["PHP_SELF"], "profile/edit.php") == true) {

                $profileObj = new Profile($id);
                if (strlen($profileObj->getString("twitter_account"))) {
                    $aux_show_twitter = true;
                    $js_fileLoader = system_scriptColector("/scripts/jquery/twitter/twitter.js",$js_fileLoader);
                    $last_twitts_script = "$('#twitter_update_list_profile').fadeIn(100,function(){ $('#twitter_update_list_profile').load('".DEFAULT_URL."/twitter_updates.php?posts=".MAX_TWEETS_MEMBERS."&user=".$profileObj->getString("twitter_account")."') }) ;";
                    $js_fileLoader = system_scriptColectorOnReady($last_twitts_script, $js_fileLoader, true);
                }
            }

        } else if (isset($js_fileLoader) && is_array($js_fileLoader)) {
            define("SCRIPTCOLLECTOR_DEBUG", "off");
            system_renderJavascripts($js_fileLoader);
        }
    }
?>