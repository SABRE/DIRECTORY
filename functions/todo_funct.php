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
	# * FILE: /functions/todo_function.php
	# ----------------------------------------------------------------------------------------------------

    /**
    * Check if sitemgr have already configured all To Do Items
    */
    function todo_validatePage($blockMenu = false) {
        
        $dbObj = db_getDBObJect(DEFAULT_DB, true);
        $dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj);
        $sql = "SELECT * FROM Setting WHERE name LIKE 'todo_%' AND `value` = 'yes'";
        $result = $dbObjSecond->query($sql);
        $showGetStarted = mysql_num_rows($result);
        unset($dbObj, $dbObjSecond);
        
        if (!DEMO_LIVE_MODE && !$_SESSION[SESS_SM_ID]) {
            $showGetStartedDemo = true;
        } else {
            $showGetStartedDemo = false;
        }
        
        $allowPages = array();
        $allowPages[] = "sitemgr/getstarted.php";
        $allowPages[] = "sitemgr/manageaccount.php";
        $allowPages[] = "sitemgr/feedback.php";
        $allowPages[] = "sitemgr/faq/faq.php";
        $allowPages[] = "sitemgr/faq/search.php";
        $allowPages[] = "sitemgr/about.php";
        $allowPages[] = "sitemgr/registration.php";
        $allowPages[] = "sitemgr/forgot.php";
        $allowPages[] = "sitemgr/login.php";
        $allowPages[] = "sitemgr/logout.php";
        $allowPages[] = "sitemgr/resetpassword.php";
        $allowPages[] = "sitemgr/setlogin.php";
        $allowPages[] = "sitemgr/prefs/emailconfig.php";
        $allowPages[] = "sitemgr/prefs/email.php";
        $allowPages[] = "sitemgr/emailnotifications";
        $allowPages[] = "sitemgr/googleprefs";
        $allowPages[] = "sitemgr/prefs/pricing.php";
        $allowPages[] = "sitemgr/prefs/invoice.php";
        $allowPages[] = "sitemgr/prefs/tax.php";
        $allowPages[] = "sitemgr/content/content_header.php";
        $allowPages[] = "sitemgr/content/content_noimage.php";
        $allowPages[] = "sitemgr/prefs/theme.php";
        $allowPages[] = "sitemgr/prefs/colorscheme.php";
        $allowPages[] = "sitemgr/prefs/template.php";
        $allowPages[] = "sitemgr/prefs/levels.php";
        $allowPages[] = "sitemgr/prefs/paymentgateway.php";
        $allowPages[] = "sitemgr/prefs/approvalrequirement.php";
        $allowPages[] = "sitemgr/prefs/location.php";
        $allowPages[] = "sitemgr/langcenter/index.php";
        $allowPages[] = "sitemgr/prefs/claim.php";
        
        $allowPage = false;

        if ($showGetStarted > 0 && $showGetStartedDemo && $_COOKIE["skip_todo"] != "true") {
            
            if ($blockMenu) {
                
                return true;
                
            } else {
            
                foreach($allowPages as $page) {
                    if (string_strpos($_SERVER["PHP_SELF"], $page) !== false) {
                        $allowPage = true;
                        break;
                    }
                }

                if (!$allowPage) {

                    //Clean Automatic login to force sitemgr always go back
                    setcookie("automatic_login_sitemgr", "false", time()+60*60*24*30, "".EDIRECTORY_FOLDER."/");
                    setcookie("complementary_info_sitemgr", "", 0, "".EDIRECTORY_FOLDER."/");

                    header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/getstarted.php");
                    exit;
                }
            }
        } elseif ($blockMenu) {
            return false;
        }
        
    }
    
    /**
    * Check if there is any To Do Item left.
    *
    * @param string $itemDone
    * @param boolean $showMessage
    */
    function todo_itensDone($itemDone = "", &$showMessage = false) {
        
        $dbObj = db_getDBObJect(DEFAULT_DB, true);
        $dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj);
        $sql = "SELECT * FROM Setting WHERE name LIKE 'todo_%' AND `value` = 'yes'";
        $result = $dbObjSecond->query($sql);
        $showGetStarted = mysql_num_rows($result);
        unset($dbObj, $dbObjSecond);
        
        if (!DEMO_LIVE_MODE && !$_SESSION[SESS_SM_ID]) {
            $showGetStartedDemo = true;
        } else {
            $showGetStartedDemo = false;
        }
        
        if (($showGetStarted <= 0 || !$showGetStartedDemo)) {
            if ($itemDone) {
                $showMessage = true;
            } else {
                header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS);
                exit;
            }
        }
        
    }
    
    /**
    * Updates To Do Items percentage
    */
    function todo_updatePercentage() {
        
        $dbObj = db_getDBObJect(DEFAULT_DB, true);
        $dbObjSecond = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbObj);
        
        $sql = "SELECT * FROM Setting WHERE name LIKE 'todo_%'";
        $result = $dbObjSecond->query($sql);
        $totalItens = mysql_num_rows($result);
        
        $sql = "SELECT * FROM Setting WHERE name LIKE 'todo_%' AND `value` = 'done'";
        $result = $dbObjSecond->query($sql);
        $doneItens = mysql_num_rows($result);
        
        if ($totalItens) {
            $perc = round((int)($doneItens * 100) / $totalItens);

            if (!setting_set("percentage_todo", $perc)) {
                setting_new("percentage_todo", $perc);
            }
        }
        
        return $perc;
        
    }
    
    /**
    * Update To Do Item and redirect user.
    *
    * @param string $item
    */
    function todo_updateItem($item, $ajax = false) {
        
        setting_get($item, $itemValue);
        
        if ($itemValue == "yes") {
            
            if (!setting_set($item, "done")) {
                if (!setting_new($item, "done")) {
                    $error = true;
                }
            }
            
            $perc = todo_updatePercentage();
            
            if (!$error && !$ajax) {
                header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/getstarted.php?stepDone=1");
                exit;
            } elseif (!$error && $ajax) {
                return $perc;
            }
            
        } else {
            return false;
        }
        
    }
    
    /**
    * Update To Do Items after sitemgr first login.
    */
    function todo_updateItemsFirstLogin() {
        
        setting_set("todo_pricing", "yes");
        if (PAYMENT_FEATURE == "on") {
            if (INVOICEPAYMENT_FEATURE == "on") {
                setting_set("todo_invoice", "yes");
            } else {
                setting_set("todo_invoice", "done");
            }
        } else {
            setting_set("todo_invoice", "done");
        }
        setting_set("todo_email", "yes");
        setting_set("todo_emailnotification", "yes");

        if (GOOGLE_ADS_ENABLED == "on") {
            setting_set("todo_googleads", "yes");
        } else {
            setting_set("todo_googleads", "done");
        }
        if (GOOGLE_MAPS_ENABLED == "on") {
            setting_set("todo_googlemaps", "yes");
        } else {
            setting_set("todo_googlemaps", "done");
        }
        if (GOOGLE_ANALYTICS_ENABLED == "on") {
            setting_set("todo_googleanalytics", "yes");
        } else {
            setting_set("todo_googleanalytics", "done");
        }
        setting_set("todo_headerlogo", "yes");
        setting_set("todo_noimage", "yes");
        if (CLAIM_FEATURE == "on") {
            setting_set("todo_claim", "yes");
        } else {
            setting_set("todo_claim", "done");
        }
        if (MULTILANGUAGE_FEATURE == "on") {
            setting_set("todo_langcenter", "yes");
        } else {
            setting_set("todo_langcenter", "done");
        }
        setting_set("todo_emailconfig", "yes");

        setting_set("todo_theme", "yes");

        setting_set("todo_paymentgateway", "yes");

        setting_set("todo_levels", "yes");

        setting_set("todo_approvalconfig", "yes");

        setting_set("todo_locations", "yes");

        setting_set("sitemgr_first_login", "no");
        
    }

?>