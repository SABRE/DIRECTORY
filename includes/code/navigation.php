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
	# * FILE: /includes/code/navigation.php
	# ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {
        
        /**
         * Validate reset by Ajax
         */
        if ($_POST["resetNavigation"] == "reset") {
            $navigationObj = new Navigation();
            $navigationObj->ResetNavbar($_POST["area"]);
            
            header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/content/navigation.php?successMessage=1&navigation_area=".$_POST["area"]);
            exit;
        }

		if (validate_form("navigation", $_POST, $errorMessage)) {
            
            /**
             * Get order
             */
            unset($array_nav_order, $new_navigation, $navbarObj);
            $array_nav_order = explode(",", $_POST["order_options"]);
            
            $navbarObj = new Navigation();
            $navbarObj->ClearNavigation($_POST["navigation_area"]);
            
            for ($i = 0; $i < count($array_nav_order); $i++) {
                
                unset($new_navigation);
                $new_navigation["order"] = $i;
                $new_navigation["label"] = $_POST["navigation_text_".$array_nav_order[$i]];
                if ($_POST["dropdown_link_to_".$array_nav_order[$i]] == "custom") {
                    
                    if (string_strpos($_POST["custom_link_".$array_nav_order[$i]], "://") === false) {
                        $_POST["custom_link_".$array_nav_order[$i]] = "http://".$_POST["custom_link_".$array_nav_order[$i]];
                    }
                    
                    $new_navigation["link"] = $_POST["custom_link_".$array_nav_order[$i]];
                    $new_navigation["custom"] = "y";
                } else {
                    $new_navigation["link"] = $_POST["dropdown_link_to_".$array_nav_order[$i]];
                    $new_navigation["custom"] = "n";
                }
                $new_navigation["area"] = $_POST["navigation_area"];
                
                $navbarObj->makeFromRow($new_navigation);
                $navbarObj->Save();
            }
            
            $navbarObj->WriteNavBar($_POST["navigation_area"]);
                
            /**
            * Validate to "View the Site"
            */
            if ($_POST["SaveByAjax"] == "true") {
                
                header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);
                header("Accept-Encoding: gzip, deflate");
                header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check", FALSE);
                header("Pragma: no-cache");
                
                echo "ok";
                exit;
            } else {
                header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/content/navigation.php?successMessage=1&navigation_area=".$_POST["navigation_area"]);
                exit;
            }
           
		} else {
            
            /**
             * Recreating options
             */
            unset($arrayOptions);
            $array_nav_order = explode(",", $_POST["order_options"]);
            for ($i = 0; $i < count($array_nav_order); $i++) {
                
                $arrayOptions[$i]["label"] = $_POST["navigation_text_".$array_nav_order[$i]];
                
                if ($_POST["dropdown_link_to_".$array_nav_order[$i]] == "custom") {
                    $arrayOptions[$i]["link"] = $_POST["custom_link_".$array_nav_order[$i]];
                    $arrayOptions[$i]["custom"] = "y";
                } else {
                    $arrayOptions[$i]["link"] = $_POST["dropdown_link_to_".$array_nav_order[$i]];
                    $arrayOptions[$i]["custom"] = "n";
                }
            }
            
            /**
            * Validate to "View the Site" and show the error
            */
           if ($_POST["SaveByAjax"] == "true") {
               
                header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);
                header("Accept-Encoding: gzip, deflate");
                header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check", FALSE);
                header("Pragma: no-cache");
               
               echo $errorMessage;
               exit;
           }
        }  
        
		// removing slashes added if required
		$_POST = format_magicQuotes($_POST);
		$_GET  = format_magicQuotes($_GET);
	}

    extract($_POST);
    extract($_GET);
    
	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
    if (!$arrayOptions) {
        /*
         * Get configuration from navigation
         */
        if (!$navigation_area) {
            $navigation_area = "header";
        }
        unset($navbarObj, $arrayOptions);
        Navigation::getNavbar($arrayOptions, $navigation_area);

        if (!$arrayOptions) {
            unset($navbarObj);
            $navbarObj = new Navigation();
            $navbarObj->ResetNavbar($navigation_area);
            $navbarObj->getNavbar($arrayOptions,$navigation_area);
        }
    }
    
    $domainObj = new Domain(SELECTED_DOMAIN_ID);
    $domainURL = "http://".$domainObj->getString("url").$domainObj->getString("subfolder");
    
    /**
     * Array with Modules and URL
     */
    $array_modules[] = array("name" => LANG_SITEMGR_NAVIGATION_CUSTOM_LINK, "url" => "custom");
    
    $array_modules[] = array("name" => LANG_MENU_HOME, "url" => "NON_SECURE_URL");
    
    $array_modules[] = array("name" => LANG_SITEMGR_LISTING, "url" => "LISTING_DEFAULT_URL");
    
    if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") {
        $array_modules[] = array("name" => LANG_SITEMGR_EVENT, "url" => "EVENT_DEFAULT_URL");
    }
    
    if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") {
        $array_modules[] = array("name" => LANG_SITEMGR_CLASSIFIED, "url" => "CLASSIFIED_DEFAULT_URL");
    }
    
    if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") {
        $array_modules[] = array("name" => LANG_SITEMGR_ARTICLE, "url" => "ARTICLE_DEFAULT_URL");
    }
    
    if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") {
        $array_modules[] = array("name" => LANG_SITEMGR_PROMOTION, "url" => "PROMOTION_DEFAULT_URL");
    }
    
    if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") {
        $array_modules[] = array("name" => LANG_SITEMGR_BLOG, "url" => "BLOG_DEFAULT_URL");
    }
    
    $array_modules[] = array("name" => LANG_MENU_ADVERTISE, "url" => "ALIAS_ADVERTISE_URL_DIVISOR");
    $array_modules[] = array("name" => LANG_MENU_CONTACT, "url" => "ALIAS_CONTACTUS_URL_DIVISOR");
    
    
    $aux_selectModuleLink = ""; 
    
    for ($j = 0; $j < count($array_modules); $j++) {
        $aux_selectModuleLink .= "<option value=".$array_modules[$j]["url"].">".string_ucwords($array_modules[$j]["name"])."</option>"; 
    }
    
    $aux_LI_code = "<li class=\"ui-state-default\" id=\"LI_ID\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" rules=\"0\" width=\"100%\">
                            <tr>
                                <td class=\"sortable-lorder\">
                                    &nbsp;
                                </td>
                                <td class=\"sortable-ltext\">
                                    <input type=\"text\" name=\"navigation_text_LI_ID\" id=\"navigation_text_LI_ID\" value=\"\" />
                                </td>
                                <td class=\"sortable-llinks\">
                                    <select name=\"dropdown_link_to_LI_ID\" id=\"dropdown_link_to_LI_ID\" onchange=\"enableCustomLink(LI_ID)\">".$aux_selectModuleLink."
                                    </select>
                                </td>
                                <td class=\"sortable-lcustom\">
									<input type=\"\" name=\"custom_link_LI_ID\" id=\"custom_link_LI_ID\" value=\"\" disabled=\"disabled\" />
                                </td>
                                <td class=\"sortable-lremove\" align=\"center\">
                                    <a class=\"sortable-remove\" href=\"javascript:void(0)\" onclick=\"javascript:removeItem(LI_ID)\">&nbsp;</a>
                                </td>								
                            </tr>
                        </table>
                    </li>";
?>