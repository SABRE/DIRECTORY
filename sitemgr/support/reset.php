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
	# * FILE: /sitemgr/support/reset.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# THIS PAGE IS ONLY USED BY THE SUPPORT
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
	# LOAD CONFIG
	# ----------------------------------------------------------------------------------------------------
	include("../../conf/loadconfig.inc.php");

	# ----------------------------------------------------------------------------------------------------
	# SESSION
	# ----------------------------------------------------------------------------------------------------
	sess_validateSMSession();

	if (!sess_getSMIdFromSession()){
		header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
        exit;
	} else {
		$dbMain = db_getDBObject(DEFAULT_DB, true);
		$sql = "SELECT username FROM SMAccount WHERE id = ".sess_getSMIdFromSession();
		$row = mysql_fetch_assoc($dbMain->query($sql));
		if ($row["username"] != "arcalogin"){
			header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/");
            exit;
		} 
	}
    
    $url_redirect = DEFAULT_URL."/".SITEMGR_ALIAS."/support/reset.php";
    extract($_GET);
    extract($_POST);

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    if ($_GET["action"]) {
        if ($action == "langFiles") {
            
            $langObj = new Lang();
            $langObj->writeLanguageFile();
            
            if (!setting_set("configChecker_lang", "on")) {
				if (!setting_new("configChecker_lang", "on")) {
					$error = true;
				}
			}
        } elseif ($action == "cacheFiles") {
            
            //cache full
            cachefull_cleanup();
            
            //cache partial
            cachepartial_removecache('index_sidebar_categories');
            cachepartial_removecache('index_sidebar_reviews');
            cachepartial_removecache('listing_index_categories', 'ListingCategory_results_(.*)', 'promotion_results_(.*)', 'promotion_index_categories');
            cachepartial_removecache('article_index_categories', 'ArticleCategory_results_(.*)');
            cachepartial_removecache('classified_index_categories', 'ClassifiedCategory_results_(.*)');
            cachepartial_removecache('event_index_categories', 'EventCategory_results_(.*)');
            cachepartial_removecache('blog_index_categories', 'BlogCategory_results_(.*)');
            cachepartial_removecache("sidebar_location_listing");
            cachepartial_removecache("sidebar_location_event");
            cachepartial_removecache("sidebar_location_classified");
            cachepartial_removecache("sidebar_location_promotion");
            cachepartial_removecache('footer');
            
            if (!setting_set("configChecker_cache", "on")) {
				if (!setting_new("configChecker_cache", "on")) {
					$error = true;
				}
			}
            
            
        } elseif ($action == "Theme") {
            
            //Update images folder
            $src = EDIRECTORY_ROOT."/images";
			$dst = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/images";
			if ((int)system_checkPerm($dst) >= (int)PERMISSION_CUSTOM_FOLDER) {
				$domain = new Domain(SELECTED_DOMAIN_ID);
				$domain->copyThemeToDomain($src, $dst);
			} else {
				$errorFolder = true;
			}
            
            //Update theme folder
            $auxThemes = explode(",", EDIR_THEMES);
            $customThemeFolder = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme";
            if ((int)system_checkPerm($customThemeFolder) < (int)PERMISSION_CUSTOM_FOLDER) {
                $stepError = true;
            }

            if (!$stepError) {

                unset($themes);
                $customThemeFolder = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme";
                $dir = opendir($customThemeFolder);
                while ($theme_folder = readdir($dir)) {
                    if (in_array($theme_folder,$auxThemes))
                        $themes[] = $theme_folder;
                }

                foreach ($themes as $theme){
                    $src = EDIRECTORY_ROOT."/theme/$theme";
                    $dst = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/theme/".$theme;
                    if ((int)system_checkPerm($dst) >= (int)PERMISSION_CUSTOM_FOLDER) {
                        $domain = new Domain(SELECTED_DOMAIN_ID);
                        $domain->copyThemeToDomain($src, $dst);
                    } else {
                        $errorFolder = true;
                    }
                }

            } else {
                $errorFolder = true;
            }
            
            if (!setting_set("configChecker_theme", "on")) {
				if (!setting_new("configChecker_theme", "on")) {
					$error = true;
				}
			}
        } elseif ($action == "signIn") {
            
            if (!setting_set("foreignaccount_openid", ""))
				if (!setting_new("foreignaccount_openid", ""))
					$error = true;
				
			if (!setting_set("foreignaccount_google", ""))
				if (!setting_new("foreignaccount_google", ""))
					$error = true;

            if (!setting_set("foreignaccount_facebook", ""))
                if (!setting_new("foreignaccount_facebook", ""))
                    $error = true;

            if (!setting_set("foreignaccount_facebook_apisecret", ""))
                if (!setting_new("foreignaccount_facebook_apisecret", ""))
                    $error = true;

            if (!setting_set("foreignaccount_facebook_apiid", ""))
                if (!setting_new("foreignaccount_facebook_apiid", ""))
                    $error = true;
                
            if (!setting_set("configChecker_signIn", "on")) {
				if (!setting_new("configChecker_signIn", "on")) {
					$error = true;
				}
			}
            
        } elseif ($action == "twitter") {
            
            if (!setting_set("foreignaccount_twitter_apikey", ""))
				if (!setting_new("foreignaccount_twitter_apikey", ""))
					$error = true;

			if (!setting_set("foreignaccount_twitter_apisecret", ""))
				if (!setting_new("foreignaccount_twitter_apisecret", ""))
					$error = true;
                
            if (!setting_set("foreignaccount_twitter_mobile_apikey", ""))
				if (!setting_new("foreignaccount_twitter_mobile_apikey", ""))
					$error = true;

			if (!setting_set("foreignaccount_twitter_mobile_apisecret", ""))
				if (!setting_new("foreignaccount_twitter_mobile_apisecret", ""))
					$error = true;
            
            if (!setting_set("twitter_account", "")) {
                if(!setting_new("twitter_account", "")) {
                    $error = true;
                }
            }
            
            if (!setting_set("configChecker_twitter", "on")) {
				if (!setting_new("configChecker_twitter", "on")) {
					$error = true;
				}
			}
        } elseif ($action == "fbComments") {
            
            if (!setting_set("commenting_fb", "")) {
                if (!setting_new("commenting_fb", "")) {
                    $error = true;
                }
            }
            
            if (!setting_set("foreignaccount_facebook_apiid", "")) {
                if (!setting_new("foreignaccount_facebook_apiid", "")) {
                    $error = true;
                }
            }
            
            if (!setting_set("commenting_fb_user_id", "")) {
                if (!setting_new("commenting_fb_user_id", "")) {
                    $error = true;
                }
            }
            
            if (!setting_set("configChecker_fbComments", "on")) {
				if (!setting_new("configChecker_fbComments", "on")) {
					$error = true;
				}
			}
        } elseif ($action == "twilio") {
            
            if (!setting_set("twilio_enabled_sms", ""))
                if (!setting_new("twilio_enabled_sms", ""))
                    $error = true;

            if (!setting_set("twilio_enabled_call", ""))
                if (!setting_new("twilio_enabled_call", ""))
                    $error = true;

            if (!setting_set("twilio_account_sid", ""))
                if (!setting_new("twilio_account_sid", ""))
                    $error = true;

            if (!setting_set("twilio_auth_token", ""))
                if (!setting_new("twilio_auth_token", ""))
                    $error = true;

            if (!setting_set("twilio_number", ""))
                if (!setting_new("twilio_number", ""))
                    $error = true;
                
            if (!setting_set("configChecker_twilio", "on")) {
				if (!setting_new("configChecker_twilio", "on")) {
					$error = true;
				}
			}
            
        } elseif ($action == "gmaps") {
            
            $googleSettingObj = new GoogleSettings(GOOGLE_MAPS_SETTING);
            $googleSettingObj->setString("value", "");
            $googleSettingObj->Save();
            
            $googleStatus = new GoogleSettings(GOOGLE_MAPS_STATUS);
            $googleStatus->setString("value", "off");
            $googleStatus->Save();
            
            if (!setting_set("configChecker_gmaps", "on")) {
				if (!setting_new("configChecker_gmaps", "on")) {
					$error = true;
				}
			}
            
        } elseif ($action == "gads") {
            
            $googleSettingObj = new GoogleSettings(GOOGLE_ADS_SETTING);
            $googleSettingObj->setString("value", "");
            $googleSettingObj->Save();
            
            $googleSettingObj_Status = new GoogleSettings(GOOGLE_ADS_STATUS);
            $googleSettingObj_Status->setString("value", "off");
            $googleSettingObj_Status->Save();
            
            if (!setting_set("configChecker_gads", "on")) {
				if (!setting_new("configChecker_gads", "on")) {
					$error = true;
				}
			}
            
        } elseif ($action == "ganalytics") {
            
            $googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_SETTING);
            $googleSettingObj->setString("value", "");
            $googleSettingObj->Save();

            $googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_FRONT_SETTING);
            $googleSettingObj->setString("value", "");
            $googleSettingObj->Save();

            $googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_MEMBERS_SETTING);
            $googleSettingObj->setString("value", "");
            $googleSettingObj->Save();

            $googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_SITEMGR_SETTING);
            $googleSettingObj->setString("value", "");
            $googleSettingObj->Save();
            
            if (!setting_set("configChecker_ganalytics", "on")) {
				if (!setting_new("configChecker_ganalytics", "on")) {
					$error = true;
				}
			}
            
        } elseif ($action == "footer") {
            
            if (!setting_set("setting_linkedin_link", "")) {
				if (!setting_new("setting_linkedin_link", "")) {
					$error = true;
				}
			}
            if (!setting_set("setting_facebook_link", "")) {
				if (!setting_new("setting_facebook_link", "")) {
					$error = true;
				}
			}
            
            if (!setting_set("configChecker_footer", "on")) {
				if (!setting_new("configChecker_footer", "on")) {
					$error = true;
				}
			}
        } elseif ($action == "systemEmail") {
            
            if (!setting_set("sitemgr_email", "")) {
                if (!setting_new("sitemgr_email", "")) {
                    $error = true;
                }
            }
            
            if (!setting_set("sitemgr_send_email", "")) {
                if (!setting_new("sitemgr_send_email", "")) {
                    $error = true;
                }
            }
            
            if (!setting_set("configChecker_systemEmail", "on")) {
				if (!setting_new("configChecker_systemEmail", "on")) {
					$error = true;
				}
			}
                       
        } elseif ($action == "smtpEmail") {
            
            if (!setting_set("phpMailer_error", "1")) {
                if (!setting_new("phpMailer_error", "1")) {
                    $error = true;
                }
            }
            
            if (!setting_set("emailconf_method", "")) {
                if (!setting_new("emailconf_method", "")) {
                    $error = true;
                }
            }

            if (!setting_set("emailconf_host", "")) {
                if (!setting_new("emailconf_host", "")) {
                    $error = true;
                }
            }

            if (!setting_set("emailconf_port", "")) {
                if (!setting_new("emailconf_port", "")) {
                    $error = true;
                }
            }

            if (!setting_set("emailconf_auth", "")) {
                if (!setting_new("emailconf_auth", "")) {
                    $error = true;
                }
            }

            if (!setting_set("emailconf_email", "")) {
                if (!setting_new("emailconf_email", "")) {
                    $error = true;
                }
            }

            if (!setting_set("emailconf_username", "")) {
                if (!setting_new("emailconf_username", "")) {
                    $error = true;
                }
            }

            if (!setting_set("emailconf_password", "")) {
                if (!setting_new("emailconf_password", "")) {
                    $error = true;
                }
            }	
            
            if (!setting_set("configChecker_smtpEmail", "on")) {
				if (!setting_new("configChecker_smtpEmail", "on")) {
					$error = true;
				}
			}

        }
        
        if ($error) {
            $errorMessage = "System error!";
        } elseif($errorFolder) { 
            $errorMessage = "Wrong permissions on custom folder!";
        } else {
            header("Location: ".$url_redirect."?message=ok");
            exit;
        }
    }

	# ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
    setting_get("configChecker_lang", $configChecker_lang);
    setting_get("configChecker_cache", $configChecker_cache);
    setting_get("configChecker_theme", $configChecker_theme);
    setting_get("configChecker_signIn", $configChecker_signIn);
    setting_get("configChecker_twitter", $configChecker_twitter);
    setting_get("configChecker_fbComments", $configChecker_fbComments);
    setting_get("configChecker_twilio", $configChecker_twilio);
    setting_get("configChecker_gmaps", $configChecker_gmaps);
    setting_get("configChecker_gads", $configChecker_gads);
    setting_get("configChecker_ganalytics", $configChecker_ganalytics);
    setting_get("configChecker_footer", $configChecker_footer);
    setting_get("configChecker_systemEmail", $configChecker_systemEmail);
    setting_get("configChecker_smtpEmail", $configChecker_smtpEmail);
    
    //SignIn Options
    setting_get("foreignaccount_openid", $foreignaccount_openid);
    setting_get("foreignaccount_facebook", $foreignaccount_facebook);
    setting_get("foreignaccount_facebook_apisecret", $foreignaccount_facebook_apisecret);
    setting_get("foreignaccount_facebook_apiid", $foreignaccount_facebook_apiid);
    setting_get("foreignaccount_google", $foreignaccount_google);
    
    //Twitter Options
    setting_get("foreignaccount_twitter_apikey", $foreignaccount_twitter_apikey);
    setting_get("foreignaccount_twitter_apisecret", $foreignaccount_twitter_apisecret);
    setting_get("foreignaccount_twitter_mobile_apikey", $foreignaccount_twitter_mobile_apikey);
    setting_get("foreignaccount_twitter_mobile_apisecret", $foreignaccount_twitter_mobile_apisecret);
    setting_get("twitter_account", $twitter_account);
    
    //Facebook comments options
    setting_get("commenting_fb", $commenting_fb);
    setting_get("foreignaccount_facebook_apiid", $foreignaccount_facebook_apiid);
    setting_get("commenting_fb_user_id", $fb_user_id);
    
    //Twilio Options
    setting_get("twilio_enabled_sms", $twilio_enabled_sms);
	setting_get("twilio_enabled_call", $twilio_enabled_call);
	setting_get("twilio_account_sid", $twilio_account_sid);
	setting_get("twilio_auth_token", $twilio_auth_token);
	setting_get("twilio_number", $twilio_number);
    
    //Google Maps
    $googleSettingObj = new GoogleSettings(GOOGLE_MAPS_SETTING);
    $google_maps_key = $googleSettingObj->getString("value");
    $googleStatus = new GoogleSettings(GOOGLE_MAPS_STATUS);
    $google_maps = $googleStatus->getString("value") == "on" ? "on" : "";
    
    //Google Ads
    $googleSettingObj = new GoogleSettings(GOOGLE_ADS_SETTING);	
	$google_ad_client = $googleSettingObj->getString("value");
    $googleSettingObj_Status = new GoogleSettings(GOOGLE_ADS_STATUS);	
	$google_ad_status = $googleSettingObj_Status->getString("value") == "on" ? "on" : "";
    
    //Google Analytics
    $googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_SETTING);	
	$google_analytics_account = $googleSettingObj->getString("value");
	$googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_FRONT_SETTING);
	$google_analytics_front = $googleSettingObj->getString("value");
	$googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_MEMBERS_SETTING);
	$google_analytics_members = $googleSettingObj->getString("value");
	$googleSettingObj = new GoogleSettings(GOOGLE_ANALYTICS_SITEMGR_SETTING);
	$google_analytics_sitemgr = $googleSettingObj->getString("value");
    
    //Footer Links
    setting_get("setting_linkedin_link", $setting_linkedin_link);
	setting_get("setting_facebook_link", $setting_facebook_link);
    
    //Sitemgr General E-mail
    setting_get("sitemgr_email", $sitemgr_email);
    setting_get("sitemgr_send_email", $send_email);
    
    //E-Mail Sending Configuration
    setting_get("emailconf_method", $emailconf_method);
	setting_get("emailconf_host", $emailconf_host);
	setting_get("emailconf_port", $emailconf_port);
	setting_get("emailconf_auth", $emailconf_auth);
	setting_get("emailconf_email", $emailconf_email);
	setting_get("emailconf_username", $emailconf_username);
	setting_get("emailconf_password", $emailconf_password);
    
    if (!$configChecker_lang) {
        $onclickLang = "onclick=\"resetOption('".$url_redirect."?action=langFiles');\"";
        $classLang = "";
    } else { 
        $onclickLang = "onclick=\"javascript: void(0);\"";
        $classLang = "setup_done";
    }
    
    if (!$configChecker_cache) {
        $onclickCache = "onclick=\"resetOption('".$url_redirect."?action=cacheFiles');\"";
        $classCache = "";
    } else { 
        $onclickCache = "onclick=\"javascript: void(0);\"";
        $classCache = "setup_done";
    }
    
    if (!$configChecker_theme) {
        $onclickTheme = "onclick=\"resetOption('".$url_redirect."?action=Theme');\"";
        $classTheme = "";
    } else { 
        $onclickTheme = "onclick=\"javascript: void(0);\"";
        $classTheme = "setup_done";
    }
    
    if (!$configChecker_signIn) {
        $onclicksignIn = "onclick=\"resetOption('".$url_redirect."?action=signIn');\"";
        $classsignIn = "";
    } else { 
        $onclicksignIn = "onclick=\"javascript: void(0);\"";
        $classsignIn = "setup_done";
    }
    
    if (!$configChecker_twitter) {
        $onclicktwitter = "onclick=\"resetOption('".$url_redirect."?action=twitter');\"";
        $classtwitter = "";
    } else { 
        $onclicktwitter = "onclick=\"javascript: void(0);\"";
        $classtwitter = "setup_done";
    }
    
    if (!$configChecker_fbComments) {
        $onclickfbComments = "onclick=\"resetOption('".$url_redirect."?action=fbComments');\"";
        $classfbComments = "";
    } else { 
        $onclickfbComments = "onclick=\"javascript: void(0);\"";
        $classfbComments = "setup_done";
    }
    
    if (!$configChecker_twilio) {
        $onclicktwilio = "onclick=\"resetOption('".$url_redirect."?action=twilio');\"";
        $classtwilio = "";
    } else { 
        $onclicktwilio = "onclick=\"javascript: void(0);\"";
        $classtwilio = "setup_done";
    }
    
    if (!$configChecker_gmaps) {
        $onclickgmaps = "onclick=\"resetOption('".$url_redirect."?action=gmaps');\"";
        $classgmaps = "";
    } else { 
        $onclickgmaps = "onclick=\"javascript: void(0);\"";
        $classgmaps = "setup_done";
    }
    
    if (!$configChecker_gads) {
        $onclickgads = "onclick=\"resetOption('".$url_redirect."?action=gads');\"";
        $classgads = "";
    } else { 
        $onclickgads = "onclick=\"javascript: void(0);\"";
        $classgads = "setup_done";
    }
    
    if (!$configChecker_ganalytics) {
        $onclickganalytics = "onclick=\"resetOption('".$url_redirect."?action=ganalytics');\"";
        $classganalytics = "";
    } else { 
        $onclickganalytics = "onclick=\"javascript: void(0);\"";
        $classganalytics = "setup_done";
    }
    
    if (!$configChecker_footer) {
        $onclickfooter = "onclick=\"resetOption('".$url_redirect."?action=footer');\"";
        $classfooter = "";
    } else { 
        $onclickfooter = "onclick=\"javascript: void(0);\"";
        $classfooter = "setup_done";
    }
    
    if (!$configChecker_systemEmail) {
        $onclicksystemEmail = "onclick=\"resetOption('".$url_redirect."?action=systemEmail');\"";
        $classsystemEmail = "";
    } else { 
        $onclicksystemEmail = "onclick=\"javascript: void(0);\"";
        $classsystemEmail = "setup_done";
    }
    
    if (!$configChecker_smtpEmail) {
        $onclicksmtpEmail = "onclick=\"resetOption('".$url_redirect."?action=smtpEmail');\"";
        $classsmtpEmail = "";
    } else { 
        $onclicksmtpEmail = "onclick=\"javascript: void(0);\"";
        $classsmtpEmail = "setup_done";
    }
    
	# ----------------------------------------------------------------------------------------------------
	# HEADER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/header.php");

	# ----------------------------------------------------------------------------------------------------
	# NAVBAR
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/navbar.php");

?>

    <script type="text/javascript">
        function resetOption(url) {
            location.href = url;
        }
    </script>

    <div id="main-right">
        <div id="top-content">
            <div id="header-content">
                <h1>Config Checker - Reset Settings</h1>
            </div>
        </div>

        <div id="content-content">
            <div class="default-margin">

                <? require(EDIRECTORY_ROOT."/".SITEMGR_ALIAS."/registration.php"); ?>
                <? require(EDIRECTORY_ROOT."/includes/code/checkregistration.php"); ?>
                <? require(EDIRECTORY_ROOT."/frontend/checkregbin.php"); ?>

                <? include(INCLUDES_DIR."/tables/table_support_submenu.php"); ?>

                <br class="clear" />
                
                <? if ($errorMessage) { ?>
                    <p class="errorMessage"><?=$errorMessage?></p>
                <? } elseif ($_GET["message"] == "ok") { ?>
                    <p class="successMessage">Settings changed!</p>
                <? } ?>

                <? include(INCLUDES_DIR."/forms/form_support_reset.php"); ?>

            </div>
        </div>

        <div id="bottom-content">
            &nbsp;
        </div>
    </div>

<?
	# ----------------------------------------------------------------------------------------------------
	# FOOTER
	# ----------------------------------------------------------------------------------------------------
	include(SM_EDIRECTORY_ROOT."/layout/footer.php");
?>