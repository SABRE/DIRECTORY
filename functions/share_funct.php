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
	# * FILE: /functions/share_function.php
	# ----------------------------------------------------------------------------------------------------

    /**
    * Returns Facebook Share Button for all modules
    *
    * @param boolean $getLikeObj
    * @param string $likeObj
    * @param string $tPreview
    * @param boolean $user
    * @return string $facebook_button
    */
    function share_getFacebookButton($getLikeObj = false, $likeObj = "", $tPreview = "", $user = "", $shareLink = "") {
        
        $facebook_button = "";
        
        if ($getLikeObj) {
            
            if (string_strpos($_SERVER["REQUEST_URI"], ".html") !== false && defined("ACTUAL_MODULE_FOLDER") && ACTUAL_MODULE_FOLDER != "") {
                $params = array (
                    "href" => $shareLink,
                    "send" => "true",
                    "layout" => "button_count",
                    "show_faces" => "false",
                    "font" => ""
                );
                $facebook_button = Facebook::getButtonCode("like", $params);
            }
            
        } else {
            if ($tPreview || !$user) {
                $facebook_button = "<div style=\"float: left; width: 131px;\">";
                $facebook_button .= "<img src=\"".DEFAULT_URL."/images/content/bt-facebook-like-sample.png\" alt=\"\" title=\"\" style=\"float: left; margin-right: 15px;\" />";
                $facebook_button .= "<img src=\"".DEFAULT_URL."/images/content/bt-facebook-send-sample.png\" alt=\"\" title=\"\" />";
                $facebook_button .= "</div>";
            } else {
                $facebook_button = "
                <div id=\"fb-root\"></div>
                <script language=\"javascript\"  type=\"text/javascript\">
                    (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = \"http://connect.facebook.net/".EDIR_LANGUAGEFACEBOOK."/all.js#xfbml=1\";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                </script>
                <script language='javascript' type='text/javascript'>
                    //<![CDATA[
                    document.write('".$likeObj."');
                    //]]>
                </script>
                ";
            }
        }
        return $facebook_button;
        
    }
    
    /**
    * Returns Google+ Button for all modules
    *
    * @param string $tPreview
    * @param boolean $user
    * @return string $google_button
    */
    function share_getGoogleButton($tPreview = "", $user = "", $prepareButton = false, $prepareOption = "", $prepareURL = false) {
        
        $google_button = "";
        
        if ($prepareButton) {
            
            if ($prepareOption == "language") {
                
                /*
                * Array with languages to google+
                */
                unset($array_googleplus_lang);
                $array_googleplus_lang["en_us"] = "en-US";
                $array_googleplus_lang["pt_br"] = "pt-BR";
                $array_googleplus_lang["es_es"] = "es";
                $array_googleplus_lang["fr_fr"] = "fr";
                $array_googleplus_lang["it_it"] = "it";
                $array_googleplus_lang["ge_ge"] = "de";
                $array_googleplus_lang["tr_tr"] = "tr";

                if (array_key_exists(EDIR_LANGUAGE, $array_googleplus_lang)) {
                    $google_button .= "{lang: '".$array_googleplus_lang[EDIR_LANGUAGE]."'}";
                } else {
                    $google_button .= "{lang: '".$array_googleplus_lang["en_us"]."'}";
                }

            } elseif (($prepareOption == "button") && $prepareURL) {
                $google_button = "<g:plusone size=\"small\" href=\"".$prepareURL."\"></g:plusone>";
            }

            if ($google_button) {
                return $google_button;		 
            } else {
                return false;	
            }
            
        } else {
        
            if ($tPreview || !$user) {
                $google_button = "<div style=\"float: left; width: 70px;\">";
                $google_button .= "<img src=\"".DEFAULT_URL."/images/content/bt-google-plus-sample.png\" alt=\"\" title=\"\" />";
                $google_button .= "</div>";
            } else {
                $aux_googleplus_button = share_getGoogleButton("", "", true, "button", DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]));

                if ($aux_googleplus_button) {

                    if (GOOGLEPLUS_ON_IE8 == "on") {
                        $google_button = $aux_googleplus_button;
                    } else {
                        $google_button = "
                            <script language='javascript' type='text/javascript'>
                                //<![CDATA[
                                document.write('".$aux_googleplus_button."');
                                //]]>
                            </script>
                            ";
                    }

                } else {
                    $google_button = ""; 
                }
            }
        
        }
        
        return $google_button;
    }

    /**
    * Returns Pinterest Share Button for all modules
    *
    * @param string $itemImage
    * @param string $itemURL
    * @param string $itemSummary
    * @param string $itemTitle
    * @param string $tPreview
    * @param boolean $user
    * @return string $pinterest_button
    */
    function share_getPinterestButton($itemImage, $itemURL, $itemSummary, $itemTitle, $tPreview, $user) {
        
        $pinterest_button = "";
        $pinDesc = "";
        if (($itemImage || $tPreview || !$user) && PINTEREST_PLUGIN == "on") {
            if ($tPreview || !$user) {
                $pinterest_button = "<div class=\"pinterestButton\">";
                $pinterest_button .= "<img src=\"".DEFAULT_URL."/images/content/bt-pinterest-sample.png\" alt=\"\" title=\"\" />";
                $pinterest_button .= "</div>";
            } else {
                $pinDesc = ($itemSummary ? $itemTitle." - ".$itemSummary : $itemTitle);
                $pinterest_button = "   <div class=\"pinterestButton\">
                                            <script language='javascript' type='text/javascript'>
                                                //<![CDATA[
                                                document.write('<a href=\"http://pinterest.com/pin/create/button/?url=".urlencode($itemURL)."&amp;media=".urlencode($itemImage)."&amp;description=".urlencode($pinDesc)."\" class=\"pin-it-button\" count-layout=\"horizontal\"><img border=\"0\" src=\"//assets.pinterest.com/images/PinExt.png\" alt=\"Pin It\" title=\"Pin It\" /></a>');
                                                //]]>
                                            </script>
                                        </div>";
            }
        }
        return $pinterest_button;
     } 

?>