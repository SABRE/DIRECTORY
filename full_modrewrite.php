<?
    $failure = false;
    $dbObj = db_getDBObject();
    
    $searchPos_2 = 2;
    $searchPos_3 = 3;
    $searchPos_4 = 4;
    
    if (EDIRECTORY_FOLDER)
    {
        $auxFolder = explode("/", EDIRECTORY_FOLDER);
        $searchPos = count($auxFolder) - 1;
        $searchPos_2 += $searchPos;
        $searchPos_3 += $searchPos;
        $searchPos_4 += $searchPos;
    }
        
    //Modules Home Page
    if (($aux_array_url[$searchPos_2] == "") || ($aux_array_url[$searchPos_2] == "index.php") || (($module_key == BLOG_FEATURE_FOLDER) && (($aux_array_url[$searchPos_2] == "page") && $aux_array_url[$searchPos_3]))) 
    {

        front_validateIndex();
        cachefull_header();
        $closeCacheFull = true;
        verify_maintenanceMode();
        sess_validateSessionFront();
        if (defined(strtoupper($module_key)."_FEATURE") && defined("CUSTOM_".strtoupper($module_key)."_FEATURE") && $module_key != PROMOTION_FEATURE_FOLDER) {
            if (constant(strtoupper($module_key)."_FEATURE") != "on" || constant("CUSTOM_".strtoupper($module_key)."_FEATURE") != "on") { exit; }
        } elseif ($module_key == PROMOTION_FEATURE_FOLDER) {
            if ( PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") { exit; }
        }
        include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
        $sitecontentSection = ucfirst($module_key)." Home";
        $array_HeaderContent = front_getSiteContent($sitecontentSection);
        extract($array_HeaderContent);
        $banner_section = ($module_key != PROMOTION_FEATURE_FOLDER ? $module_key : "promotion");
        $headertag_title = $headertagtitle;
        $headertag_description = $headertagdescription;
        $headertag_keywords = $headertagkeywords;
        if ($module_key == BLOG_FEATURE_FOLDER) 
        {
            $aux_results_number_index = 4;
            $blogHome = true;
            if (($aux_array_url[$searchPos_2] == "page") && $aux_array_url[$searchPos_3]) 
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                $_GET["pn"] = $aux_array_url[$searchPos_3];
            }
        }
        define("LOAD_MODULE_CSS_HOME", "on");
        $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/index.php";
        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/index.php");

    } 
    else 
    { 
        define("LOAD_MODULE_CSS_HOME", "off");
        cachefull_header();
        $closeCacheFull = true;
        verify_maintenanceMode();
        sess_validateSessionFront();
        if ($module_key == LISTING_FEATURE_FOLDER) 
        {  
            if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false))) 
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Listing Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "listing";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if ($browsebycategory || $category_id)
                {
                    if ($category_id) 
                    {
                        $categoryObjHeaderTag = new ListingCategory($category_id);
                        if ($categoryObjHeaderTag->getString("seo_description")) 
                        {
                            $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                        }
                        if ($categoryObjHeaderTag->getString("seo_keywords")) 
                        {
                            $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                        }
                        unset($categoryObjHeaderTag);
                    }
                } 
                elseif ($browsebylocation) 
                {
                    include(INCLUDES_DIR."/code/headertaglocation.php");
                }

                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            elseif ($aux_array_url[$searchPos_3] && (string_strpos($aux_array_url[$searchPos_3], ".html") !== false)) 
            {
                $_GET["listing"] = $aux_array_url[$searchPos_3];
                $friendly_url = str_replace(".html", "", $_GET["listing"]);
                if ($friendly_url && BACKLINK_FEATURE == "on") 
                {
                    $listingObj = db_getFromDB("listing", "friendly_url", db_formatString($friendly_url));
                    $id = $listingObj->getNumber("id");
                    $level = new ListingLevel();
                    $listingHasBacklink = $level->getBacklink($listingObj->getNumber("level"));
                    if ($listingObj->getString("backlink") == "y" && $listingObj->getString("backlink_url") && $listingHasBacklink == "y") 
                    {
                        $redirecLink = LISTING_DEFAULT_URL."/results.php?id=".$id;
                        header("Location: ".$redirecLink);
                        exit;
                    } else 
                    {
                        header("Location: ".LISTING_DEFAULT_URL);
                        exit;
                    }
                } 
                else 
                {
                    header("Location: ".LISTING_DEFAULT_URL);
                    exit;
                }
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) 
            {
                if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) 
                {
                    $_GET["qs"] = str_replace(ALIAS_LISTING_MODULE."_", "", $aux_array_url[$searchPos_3]);
                    $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
                exit;
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) 
            {
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");
                $banner_section = "listing";
                $headertag_title = (($listing->getString("seo_title")) ? ($listing->getString("seo_title")) : ($listing->getString("title")));
                $headertag_description = (($listing->getString("seo_description")) ? ($listing->getString("seo_description")) : ($listing->getString("description")));
                $headertag_keywords = (($listing->getString("seo_keywords")) ? ($listing->getString("seo_keywords")) : (str_replace(" || ", ", ", $listing->getString("keywords"))));
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");
            } 
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) ||
                    ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) ||
                    ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false)
                    ) 
            {
                if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
                } elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
                } else {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
                }
                $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");
            } 
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) ||
                    ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                    ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) ||
                    ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3])) 
            {
                if (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                   ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) {
                    if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                        $sitecontentSection = "Listing View All Locations";
                        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
                    } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                        $sitecontentSection = "Listing View All Categories";
                        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                    }
                    $array_HeaderContent = front_getSiteContent($sitecontentSection);
                    extract($array_HeaderContent);
                    $banner_section = "listing";
                    $headertag_title = $headertagtitle;
                    $headertag_description = $headertagdescription;
                    $headertag_keywords = $headertagkeywords;
                } 
                else 
                {
                    $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                    if (string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) {
                        include(EDIR_CONTROLER_FOLDER."/".$module_key."/checkin.php");
                        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/commentsCheckin.php");
                    } else {
                        include(EDIR_CONTROLER_FOLDER."/".$module_key."/review.php");
                        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/comments.php");
                    }
                    $banner_section = "listing";
                    $headertag_title = system_showText(LANG_REVIEWSOF)." ".(($listingObj->getString("seo_title")) ? ($listingObj->getString("seo_title")) : ($listingObj->getString("title")));
                    $headertag_description = (($listingObj->getString("seo_description")) ? ($listingObj->getString("seo_description")) : ($listingObj->getString("description")));
                    $headertag_keywords = (($listingObj->getString("seo_keywords")) ? ($listingObj->getString("seo_keywords")) : (str_replace(" || ", ", ", $listingObj->getString("keywords"))));
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CLAIM_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) 
            {
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/claim.php");
                $theme_file = EDIRECTORY_ROOT."/".EDIR_CORE_FOLDER_NAME."/".$module_key."/claim.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/claim.php");
            }
            elseif (((string_strpos($aux_array_url[$searchPos_2], "results.php") == false))) 
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Listing Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "listing";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if($_GET['category_id']){
                    $categoryObjHeaderTag = new ListingCategory($_GET['category_id']);

                    if ($categoryObjHeaderTag->getString("seo_description")) {
                        $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                    }
                    if ($categoryObjHeaderTag->getString("seo_keywords")) {
                        $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                    }
                    unset($categoryObjHeaderTag);
                }

                if($_GET['location_3']){
                    $locationObjHeaderTag = new Location3($_GET['location_3']);
                    if ($locationObjHeaderTag) {
                        if ($locationObjHeaderTag->getString("seo_description")){
                            if(!empty($headertag_description))
                                $headertag_description .= $locationObjHeaderTag->getString("seo_description");
                            else
                                $headertag_description = $locationObjHeaderTag->getString("seo_description");
                        }
                        if ($locationObjHeaderTag->getString("seo_keywords")){
                            if(!empty($headertag_keywords))
                                $headertag_keywords .= ','.$locationObjHeaderTag->getString("seo_keywords");
                            else
                                $headertag_keywords = $locationObjHeaderTag->getString("seo_keywords");
                        }
                    }
                    unset($locationObjHeaderTag);
                }

                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            }
            else 
            {
                front_errorPage();
            }
        }
        elseif ($module_key == EVENT_FEATURE_FOLDER) 
        {
            if (string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)
            {
                if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Event Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "event";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if ($browsebycategory || $category_id) {
                    if ($category_id) {
                        $categoryObjHeaderTag = new EventCategory($category_id);
                        if ($categoryObjHeaderTag->getString("seo_description")) $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                        if ($categoryObjHeaderTag->getString("seo_keywords")) $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                        unset($categoryObjHeaderTag);
                    }
                }elseif ($browsebylocation) {
                    include(INCLUDES_DIR."/code/headertaglocation.php");
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            elseif((string_strpos($aux_array_url[$searchPos_2], "rss") !== false))
            {
                if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                    $_GET["qs"] = str_replace(ALIAS_EVENT_MODULE."_", "", $aux_array_url[$searchPos_3]);
                    $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
                exit;
            }
            elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) 
            {
                if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");
                $banner_section = "event";
                $headertag_title = (($event->getString("seo_title")) ? ($event->getString("seo_title")) :( $event->getString("title")));
                $headertag_description = (($event->getString("seo_description")) ? ($event->getString("seo_description")) : ($event->getString("description")));
                $headertag_keywords = (($event->getString("seo_keywords")) ? ($event->getString("seo_keywords")) : (str_replace(" || ", ", ", $event->getString("keywords"))));
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");
            }
            elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false)
            {
                $_GET["friendly_url"]   = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
                $_GET["from"]           = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");
            }
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) 
            {
                if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }

                if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Event View All Locations";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
                } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Event View All Categories";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                }
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "event";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
            } 
            elseif(string_strpos($aux_array_url[$searchPos_2], "results.php") == false) 
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Event Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "event";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if($_GET['category_id']){
                    $categoryObjHeaderTag = new ListingCategory($_GET['category_id']);
                    if ($categoryObjHeaderTag->getString("seo_description")) {
                        $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                    }
                    if ($categoryObjHeaderTag->getString("seo_keywords")) {
                        $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                    }
                    unset($categoryObjHeaderTag);
                }
                if($_GET['location_3']){
                    $locationObjHeaderTag = new Location3($_GET['location_3']);
                    if ($locationObjHeaderTag) {
                        if ($locationObjHeaderTag->getString("seo_description")){
                            if(!empty($headertag_description))
                                $headertag_description .= $locationObjHeaderTag->getString("seo_description");
                            else
                                $headertag_description = $locationObjHeaderTag->getString("seo_description");
                        }
                        if ($locationObjHeaderTag->getString("seo_keywords")){
                            if(!empty($headertag_keywords))
                                $headertag_keywords .= ','.$locationObjHeaderTag->getString("seo_keywords");
                            else
                                $headertag_keywords = $locationObjHeaderTag->getString("seo_keywords");
                        }
                    }
                    unset($locationObjHeaderTag);
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            }
            else 
            {
                front_errorPage();
            }
        }
        elseif ($module_key == CLASSIFIED_FEATURE_FOLDER) 
        {
            if (string_strpos($aux_array_url[$searchPos_2], "results.php") !== false) 
            {
                if (CLASSIFIED_FEATURE != "on" || CUSTOM_CLASSIFIED_FEATURE != "on") { exit; }
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Classified Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "classified";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if ($browsebycategory || $category_id) {
                    if ($category_id) {
                        $categoryObjHeaderTag = new ClassifiedCategory($category_id);
                        if ($categoryObjHeaderTag->getString("seo_description")) $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                        if ($categoryObjHeaderTag->getString("seo_keywords")) $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                        unset($categoryObjHeaderTag);
                    }
                } elseif ($browsebylocation) {
                    include(INCLUDES_DIR."/code/headertaglocation.php");
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) 
            {
                if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                    $_GET["qs"] = str_replace(ALIAS_CLASSIFIED_MODULE."_", "", $aux_array_url[$searchPos_3]);
                    $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
                exit;
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) 
            {
                if (CLASSIFIED_FEATURE != "on" || CUSTOM_CLASSIFIED_FEATURE != "on") { exit; }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");
                $banner_section = "classified";
                $headertag_title = (($classified->getString("seo_title"))?($classified->getString("seo_title")):($classified->getString("title")));
                $headertag_description = (($classified->getString("seo_summarydesc"))?($classified->getString("seo_summarydesc")):($classified->getString("summarydesc")));
                $headertag_keywords = (($classified->getString("seo_keywords"))?($classified->getString("seo_keywords")):(str_replace(" || ", ", ", $classified->getString("keywords"))));
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) 
            {
                $_GET["friendly_url"]   = string_substr($aux_array_url[$searchPos_3], 0,  string_strpos($aux_array_url[$searchPos_3], ".html"));
                $_GET["from"]           = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");
            }
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) 
            {
                if (CLASSIFIED_FEATURE != "on" || CUSTOM_CLASSIFIED_FEATURE != "on") { exit; }
                if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Classifide View All Locations";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
                } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Classified View All Categories";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                }
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "classified";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
            } 
            elseif (string_strpos($aux_array_url[$searchPos_2], "results.php") == false) 
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Classified Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "classified";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if($_GET['category_id']){
                    $categoryObjHeaderTag = new ListingCategory($_GET['category_id']);
                    if ($categoryObjHeaderTag->getString("seo_description")) {
                        $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                    }
                    if ($categoryObjHeaderTag->getString("seo_keywords")) {
                        $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                    }
                    unset($categoryObjHeaderTag);
                }
                if($_GET['location_3']){
                    $locationObjHeaderTag = new Location3($_GET['location_3']);
                    if ($locationObjHeaderTag) {
                        if ($locationObjHeaderTag->getString("seo_description")){
                            if(!empty($headertag_description))
                                $headertag_description .= $locationObjHeaderTag->getString("seo_description");
                            else
                                $headertag_description = $locationObjHeaderTag->getString("seo_description");
                        }
                        if ($locationObjHeaderTag->getString("seo_keywords")){
                            if(!empty($headertag_keywords))
                                $headertag_keywords .= ','.$locationObjHeaderTag->getString("seo_keywords");
                            else
                                $headertag_keywords = $locationObjHeaderTag->getString("seo_keywords");
                        }
                    }
                    unset($locationObjHeaderTag);
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            }
            else 
            {
                front_errorPage();
            }
        }
        elseif ($module_key == ARTICLE_FEATURE_FOLDER) 
        {
            if (string_strpos($aux_array_url[$searchPos_2], "results.php") !== false) 
            {
                if (ARTICLE_FEATURE != "on" || CUSTOM_ARTICLE_FEATURE != "on") { exit; }
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Article Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "article";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if ($browsebycategory || $category_id) {
                    if ($category_id)  {
                        $categoryObjHeaderTag = new ArticleCategory($category_id);
                        if ($categoryObjHeaderTag->getString("seo_description")) $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                        if ($categoryObjHeaderTag->getString("seo_keywords")) $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                        unset($categoryObjHeaderTag);
                    }
                } elseif ($browsebylocation) {
                    include(INCLUDES_DIR."/code/headertaglocation.php");
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            elseif((string_strpos($aux_array_url[$searchPos_2], "rss") !== false))
            {
                if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                    $_GET["qs"] = str_replace(ALIAS_ARTICLE_MODULE."_", "", $aux_array_url[$searchPos_3]);
                    $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
                exit;
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2],".html") !== false)) 
            {
                if (ARTICLE_FEATURE != "on" || CUSTOM_ARTICLE_FEATURE != "on") { exit; }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");
                $banner_section = "article";
                $headertag_title = (($article->getString("seo_title"))?($article->getString("seo_title")):($article->getString("title")));
                $headertag_description = (($article->getString("seo_abstract"))?($article->getString("seo_abstract")):($article->getString("abstract")));
                $headertag_keywords = (($article->getString("seo_keywords"))?($article->getString("seo_keywords")):(str_replace(" || ", ", ", $article->getString("keywords"))));
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");
            } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false)
                ) 
            {
                if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
                } else {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
                }
                $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");
            } 
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]))
            {
                if (ARTICLE_FEATURE != "on" || CUSTOM_ARTICLE_FEATURE != "on") { exit; }
                if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) {
                    $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                    include(EDIR_CONTROLER_FOLDER."/".$module_key."/review.php");
                    $banner_section = "article";
                    $headertag_title = system_showText(LANG_REVIEWSOF)." ".(($articleObj->getString("seo_title")) ? ($articleObj->getString("seo_title")) : ($articleObj->getString("title")));
                    $headertag_description = (($articleObj->getString("seo_description")) ? ($articleObj->getString("seo_description")) : ($articleObj->getString("description")));
                    $headertag_keywords = (($articleObj->getString("seo_keywords")) ? ($articleObj->getString("seo_keywords")) : (str_replace(" || ", ", ", $articleObj->getString("keywords"))));
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/comments.php");
                } 
                else
                {
                    $sitecontentSection = "Article View All Categories";
                    $array_HeaderContent = front_getSiteContent($sitecontentSection);
                    extract($array_HeaderContent);
                    $banner_section = "article";
                    $headertag_title = $headertagtitle;
                    $headertag_description = $headertagdescription;
                    $headertag_keywords = $headertagkeywords;
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
            }
            elseif (((string_strpos($aux_array_url[$searchPos_2], "results.php") == false))) 
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Article Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "article";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if($_GET['category_id']){
                    $categoryObjHeaderTag = new ArticleCategory($_GET['category_id']);

                    if ($categoryObjHeaderTag->getString("seo_description")) {
                        $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                    }
                    if ($categoryObjHeaderTag->getString("seo_keywords")) {
                        $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                    }
                    unset($categoryObjHeaderTag);
                }

                if($_GET['location_3']){
                    $locationObjHeaderTag = new Location3($_GET['location_3']);
                    if ($locationObjHeaderTag) {
                        if ($locationObjHeaderTag->getString("seo_description")){
                            if(!empty($headertag_description))
                                $headertag_description .= $locationObjHeaderTag->getString("seo_description");
                            else
                                $headertag_description = $locationObjHeaderTag->getString("seo_description");
                        }
                        if ($locationObjHeaderTag->getString("seo_keywords")){
                            if(!empty($headertag_keywords))
                                $headertag_keywords .= ','.$locationObjHeaderTag->getString("seo_keywords");
                            else
                                $headertag_keywords = $locationObjHeaderTag->getString("seo_keywords");
                        }
                    }
                    unset($locationObjHeaderTag);
                }

                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            }
            else
            {
                front_errorPage();
            }
        }
        elseif ($module_key == PROMOTION_FEATURE_FOLDER) 
        {
            if (string_strpos($aux_array_url[$searchPos_2], "results.php") !== false) 
            {
                if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") exit;
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Deal Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "promotion";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if ($browsebycategory || $category_id) {
                    if ($category_id) {
                        $categoryObjHeaderTag = new ListingCategory($category_id);
                        if ($categoryObjHeaderTag->getString("seo_description")){
                            $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                        }
                        if ($categoryObjHeaderTag->getString("seo_keywords")){
                            $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                        }
                        unset($categoryObjHeaderTag);
                    }
                } elseif ($browsebylocation) {
                    include(INCLUDES_DIR."/code/headertaglocation.php");
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2],"rss") !== false)) 
            {
                if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                    $_GET["qs"] = str_replace(ALIAS_PROMOTION_MODULE."_", "", $aux_array_url[$searchPos_3]);
                    $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
                exit;
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2],".html") !== false)) 
            {
                if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") exit;
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");
                $banner_section = "promotion";
                $headertag_title = (($promotion->getString("seo_name")) ? ($promotion->getString("seo_name")) : ($promotion->getString("title")));
                $headertag_description = (($promotion->getString("seo_description")) ? ($promotion->getString("seo_description")) : ($promotion->getString("description")));
                $headertag_keywords = (($promotion->getString("seo_keywords")) ? ($promotion->getString("seo_keywords")) : (str_replace(" || ", ", ", $promotion->getString("keywords"))));
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");
            } 
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false)
                )
            {
                if ((string_strpos($aux_array_url[$searchPos_2] ,ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
                } else {
                    $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
                }
                $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");
            } 
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3])) 
            {
                if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") exit;
                if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) {
                    $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                    include(EDIR_CONTROLER_FOLDER."/".$module_key."/review.php");
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/comments.php");
                    $banner_section = "promotion";
                    $headertag_title = system_showText(LANG_REVIEWSOF)." ".(($promotionObj->getString("seo_name")) ? ($promotionObj->getString("seo_name")) : ($promotionObj->getString("name")));
                    $headertag_description = (($promotionObj->getString("seo_description")) ? ($promotionObj->getString("seo_description")) : ($promotionObj->getString("description")));
                    $headertag_keywords = (($promotionObj->getString("seo_keywords")) ? ($promotionObj->getString("seo_keywords")) : (str_replace(" || ", ", ", $promotionObj->getString("keywords"))));
                }
                else
                {
                    if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                        $sitecontentSection = "Deal View All Locations";
                        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
                    } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                        $sitecontentSection = "Deal View All Categories";
                        define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                    }              
                    $array_HeaderContent = front_getSiteContent($sitecontentSection);
                    extract($array_HeaderContent);
                    $banner_section = "promotion";
                    $headertag_title = $headertagtitle;
                    $headertag_description = $headertagdescription;
                    $headertag_keywords = $headertagkeywords;
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
            }
            elseif(string_strpos($aux_array_url[$searchPos_2], "results.php") == false)
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Deal Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);            
                $banner_section = "promotion";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if($_GET['category_id']){
                    $categoryObjHeaderTag = new ListingCategory($_GET['category_id']);
                    if ($categoryObjHeaderTag->getString("seo_description")) {
                        $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                    }
                    if ($categoryObjHeaderTag->getString("seo_keywords")) {
                        $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                    }
                    unset($categoryObjHeaderTag);
                }
                if($_GET['location_3']){
                    $locationObjHeaderTag = new Location3($_GET['location_3']);
                    if ($locationObjHeaderTag) {
                        if ($locationObjHeaderTag->getString("seo_description")){
                            if(!empty($headertag_description))
                                $headertag_description .= $locationObjHeaderTag->getString("seo_description");
                            else
                                $headertag_description = $locationObjHeaderTag->getString("seo_description");
                        }
                        if ($locationObjHeaderTag->getString("seo_keywords")){
                            if(!empty($headertag_keywords))
                                $headertag_keywords .= ','.$locationObjHeaderTag->getString("seo_keywords");
                            else
                                $headertag_keywords = $locationObjHeaderTag->getString("seo_keywords");
                        }
                    }
                    unset($locationObjHeaderTag);
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            else
            {
                front_errorPage();
            }
        }
        elseif ($module_key == BLOG_FEATURE_FOLDER) 
        {
            if (string_strpos($aux_array_url[$searchPos_2], "results.php") !== false) 
            {
                if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                $blogHome = false;
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rewrite.php");
                include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
                include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
                setting_get("review_approve", $post_comment_approve);
                if ($category_id) {
                    $catObj = new BlogCategory($category_id);
                    if (!$catObj->getString("title")) {
                        header("Location: ".BLOG_DEFAULT_URL."/index.php");
                        exit;
                    }
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/prepare_results.php");            
                $sitecontentSection = "Blog Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);            
                $banner_section = "blog";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if ($browsebycategory || $category_id) {
                    if ($category_id) {
                        $categoryObjHeaderTag = new BlogCategory($category_id);
                        if ($categoryObjHeaderTag->getString("seo_description")) $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                        if ($categoryObjHeaderTag->getString("seo_keywords")) $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                        unset($categoryObjHeaderTag);
                    }
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) 
            {
                $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0,  string_strpos($aux_array_url[$searchPos_3], ".html"));
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");
            }
            elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) 
            {
                if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");
                $banner_section = "blog";
                $headertag_title = (($post->getString("seo_title")) ? ($post->getString("seo_title")) : ($post->getString("title")));
                $headertag_description = (($post->getString("seo_abstract")) ? ($post->getString("seo_abstract")) : (strip_tags($post->getString("content", false, 252))));
                $headertag_keywords = (($post->getString("seo_keywords")) ? ($post->getString("seo_keywords")) : (str_replace(" || ", ", ", $post->getString("keywords"))));
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");
            }
            elseif ((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) 
            {
                if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                    $_GET["qs"] = str_replace(ALIAS_BLOG_MODULE."_", "", $aux_array_url[$searchPos_3]);
                    $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
                }
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
                exit;          
            }
            elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) 
            {
                if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }
                $sitecontentSection = "Blog View All Categories";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
                $banner_section = "blog";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
            }
            elseif(string_strpos($aux_array_url[$searchPos_2], "results.php") == false)
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");
                $sitecontentSection = "Blog Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);            
                $banner_section = "blog";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
                if($_GET['category_id']){
                    $categoryObjHeaderTag = new BlogCategory($_GET['category_id']);
                    if ($categoryObjHeaderTag->getString("seo_description")) {
                        $headertag_description = $categoryObjHeaderTag->getString("seo_description");
                    }
                    if ($categoryObjHeaderTag->getString("seo_keywords")) {
                        $headertag_keywords = $categoryObjHeaderTag->getString("seo_keywords");
                    }
                    unset($categoryObjHeaderTag);
                }
                if($_GET['location_3']){
                    $locationObjHeaderTag = new Location3($_GET['location_3']);
                    if ($locationObjHeaderTag) {
                        if ($locationObjHeaderTag->getString("seo_description")){
                            if(!empty($headertag_description))
                                $headertag_description .= $locationObjHeaderTag->getString("seo_description");
                            else
                                $headertag_description = $locationObjHeaderTag->getString("seo_description");
                        }
                        if ($locationObjHeaderTag->getString("seo_keywords")){
                            if(!empty($headertag_keywords))
                                $headertag_keywords .= ','.$locationObjHeaderTag->getString("seo_keywords");
                            else
                                $headertag_keywords = $locationObjHeaderTag->getString("seo_keywords");
                        }
                    }
                    unset($locationObjHeaderTag);
                }
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");
            } 
            else
            {
                front_errorPage();
            }
        }
    }
    
    if ($theme_file && file_exists($theme_file)) 
    {
        
        # ----------------------------------------------------------------------------------------------------
        # AUX
        # ----------------------------------------------------------------------------------------------------
        require(EDIRECTORY_ROOT."/frontend/checkregbin.php");
    
        # ----------------------------------------------------------------------------------------------------
        # HEADER
        # ----------------------------------------------------------------------------------------------------
        include(system_getFrontendPath("header.php", "layout"));

        # ----------------------------------------------------------------------------------------------------
        # BODY
        # ----------------------------------------------------------------------------------------------------
       
        include($theme_file);

        # ----------------------------------------------------------------------------------------------------
        # FOOTER
        # ----------------------------------------------------------------------------------------------------
        include(system_getFrontendPath("footer.php", "layout"));
        
        if ($closeCacheFull) {
        # ----------------------------------------------------------------------------------------------------
        # CACHE
        # ----------------------------------------------------------------------------------------------------
        cachefull_footer();
        }
        
    } 
    else 
    {
        front_errorPage();
    }
?>