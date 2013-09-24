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

        # ----------------------------------------------------------------------------------------------------
        # CACHE
        # ----------------------------------------------------------------------------------------------------
        cachefull_header();
        $closeCacheFull = true;

        # ----------------------------------------------------------------------------------------------------
        # MAINTENANCE MODE
        # ----------------------------------------------------------------------------------------------------
        verify_maintenanceMode();

        # ----------------------------------------------------------------------------------------------------
        # SESSION
        # ----------------------------------------------------------------------------------------------------
        sess_validateSessionFront();

        # ----------------------------------------------------------------------------------------------------
        # VALIDATE FEATURE
        # ----------------------------------------------------------------------------------------------------


        if (defined(strtoupper($module_key)."_FEATURE") && defined("CUSTOM_".strtoupper($module_key)."_FEATURE") && $module_key != PROMOTION_FEATURE_FOLDER) {
            if (constant(strtoupper($module_key)."_FEATURE") != "on" || constant("CUSTOM_".strtoupper($module_key)."_FEATURE") != "on") { exit; }
        } elseif ($module_key == PROMOTION_FEATURE_FOLDER) {
            if ( PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") { exit; }
        }

        # ----------------------------------------------------------------------------------------------------
        # VALIDATION
        # ----------------------------------------------------------------------------------------------------
        include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");

        # ----------------------------------------------------------------------------------------------------
        # SITE CONTENT
        # ----------------------------------------------------------------------------------------------------
        $sitecontentSection = ucfirst($module_key)." Home";
        $array_HeaderContent = front_getSiteContent($sitecontentSection);
        extract($array_HeaderContent);

        # ----------------------------------------------------------------------------------------------------
        # HEADER
        # ----------------------------------------------------------------------------------------------------
        $banner_section = ($module_key != PROMOTION_FEATURE_FOLDER ? $module_key : "promotion");
        $headertag_title = $headertagtitle;
        $headertag_description = $headertagdescription;
        $headertag_keywords = $headertagkeywords;

        if ($module_key == BLOG_FEATURE_FOLDER) 
        {

            # ----------------------------------------------------------------------------------------------------
            # PREPARE CONTENT
            # ----------------------------------------------------------------------------------------------------
            /*
            * Var to show results on the index page of blog
            */
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


        //Others pages
        define("LOAD_MODULE_CSS_HOME", "off");
        //Listing
        if ($module_key == LISTING_FEATURE_FOLDER) 
        {  


            //Listing Results
            if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3]) || (($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR) && $aux_array_url[$searchPos_3])) 
            {


                # ----------------------------------------------------------------------------------------------------
                # CACHE
                # ----------------------------------------------------------------------------------------------------
                cachefull_header();
                $closeCacheFull = true;

                # ----------------------------------------------------------------------------------------------------
                # MAINTENANCE MODE
                # ----------------------------------------------------------------------------------------------------
                verify_maintenanceMode();

                # ----------------------------------------------------------------------------------------------------
                # SESSION
                # ----------------------------------------------------------------------------------------------------
                sess_validateSessionFront();

                if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || ($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) || ($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR)) 
                {
                    $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                }

                include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

                # ----------------------------------------------------------------------------------------------------
                # SITE CONTENT
                # ----------------------------------------------------------------------------------------------------
                $sitecontentSection = "Listing Results";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
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

                //Listing Backlink return
        } 
        elseif (($aux_array_url[$searchPos_2] == ALIAS_BACKLINK_URL_DIVISOR) && $aux_array_url[$searchPos_3] && (string_strpos($aux_array_url[$searchPos_3], ".html") !== false)) 
        {

            $_GET["listing"] = $aux_array_url[$searchPos_3];
            $friendly_url = str_replace(".html", "", $_GET["listing"]);
            if ($friendly_url && BACKLINK_FEATURE == "on") 
            {
                $listingObj = db_getFromDB("listing", "friendly_url", db_formatString($friendly_url));
                $id = $listingObj->getNumber("id");
                $level = new ListingLevel();
                $listingHasBacklink = $level->getBacklink($listingObj->getNumber("level"));
                if ($listingObj->getString("backlink") == "y" && $listingObj->getString("backlink_url") && $listingHasBacklink == "y") {
                    $redirecLink = LISTING_DEFAULT_URL."/results.php?id=".$id;
                    header("Location: ".$redirecLink);
                    exit;
                } else {
                    header("Location: ".LISTING_DEFAULT_URL);
                    exit;
                }
            } else {
                header("Location: ".LISTING_DEFAULT_URL);
                exit;
            }

            //Listing RSS
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

            //Listing Detail
        } 
        elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) 
        {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "listing";
            $headertag_title = (($listing->getString("seo_title")) ? ($listing->getString("seo_title")) : ($listing->getString("title")));
            $headertag_description = (($listing->getString("seo_description")) ? ($listing->getString("seo_description")) : ($listing->getString("description")));
            $headertag_keywords = (($listing->getString("seo_keywords")) ? ($listing->getString("seo_keywords")) : (str_replace(" || ", ", ", $listing->getString("keywords"))));

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");

            //Listing Share
        } 
        elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false)
                ) 
        {
            //Share from reviews page
            if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
            //Share from checkins page
            } elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
            //Share from detail/results
            } else {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
            }

            $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");

        //Listing General Pages - reviews, checkins, all categories, all locations
        } 
        elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CHECKIN_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3])) 
        {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            //All categories and all locations page
            if (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
               ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) {

                if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Listing View All Locations";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
                } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Listing View All Categories";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                }

                # ----------------------------------------------------------------------------------------------------
                # SITE CONTENT
                # ----------------------------------------------------------------------------------------------------
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
                $banner_section = "listing";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;

            //Reviews and Checkins page
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

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
                $banner_section = "listing";
                $headertag_title = system_showText(LANG_REVIEWSOF)." ".(($listingObj->getString("seo_title")) ? ($listingObj->getString("seo_title")) : ($listingObj->getString("title")));
                $headertag_description = (($listingObj->getString("seo_description")) ? ($listingObj->getString("seo_description")) : ($listingObj->getString("description")));
                $headertag_keywords = (($listingObj->getString("seo_keywords")) ? ($listingObj->getString("seo_keywords")) : (str_replace(" || ", ", ", $listingObj->getString("keywords"))));

            }

          $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";

        //Listing Claim
        } 
        elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_CLAIM_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) 
        {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/claim.php");

            $theme_file = EDIRECTORY_ROOT."/".EDIR_CORE_FOLDER_NAME."/".$module_key."/claim.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/claim.php");

        }
        elseif (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || 
                (!empty($aux_array_url[$searchPos_2]))) 
        {

            /*This else case completed on the 20-09-2013 for friendly url*/
            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            if(!empty($aux_array_url[$searchPos_2]))
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Listing Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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

            //Listing Backlink return
        }
        else 
        {

           front_errorPage();
        }

    //Event
    } elseif ($module_key == EVENT_FEATURE_FOLDER) {

        //Event Results
        if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3]) || (($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }

            if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || ($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) || ($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR)) {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Event Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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
            } elseif ($browsebylocation) {
                include(INCLUDES_DIR."/code/headertaglocation.php");
            }

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/results.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/results.php");

        //Event RSS
        } elseif((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) {

            if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                $_GET["qs"] = str_replace(ALIAS_EVENT_MODULE."_", "", $aux_array_url[$searchPos_3]);
                $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
            exit;

        //Event Detail
        } elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "event";
            $headertag_title = (($event->getString("seo_title")) ? ($event->getString("seo_title")) :( $event->getString("title")));
            $headertag_description = (($event->getString("seo_description")) ? ($event->getString("seo_description")) : ($event->getString("description")));
            $headertag_keywords = (($event->getString("seo_keywords")) ? ($event->getString("seo_keywords")) : (str_replace(" || ", ", ", $event->getString("keywords"))));

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");

        //Event Share
        } elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) {

            $_GET["friendly_url"]   = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
            $_GET["from"]           = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");

        //Event General Pages - all categories, all locations
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (EVENT_FEATURE != "on" || CUSTOM_EVENT_FEATURE != "on") { exit; }

            if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                $sitecontentSection = "Event View All Locations";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
            } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                $sitecontentSection = "Event View All Categories";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
            }

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "event";
            $headertag_title = $headertagtitle;
            $headertag_description = $headertagdescription;
            $headertag_keywords = $headertagkeywords;
            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";

        } elseif (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || 
                (!empty($aux_array_url[$searchPos_2]))) {
            /*This else case completed on the 20-09-2013 for friendly url*/

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            if(!empty($aux_array_url[$searchPos_2]))
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Event Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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


        }else {
            front_errorPage();
        }

    //Classified
    } elseif ($module_key == CLASSIFIED_FEATURE_FOLDER) {

        //Classified Results
        if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3]) || (($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (CLASSIFIED_FEATURE != "on" || CUSTOM_CLASSIFIED_FEATURE != "on") { exit; }

            if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || ($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) || ($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR)) {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Classified Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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

        //Classified RSS
        } elseif ((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) {

            if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                $_GET["qs"] = str_replace(ALIAS_CLASSIFIED_MODULE."_", "", $aux_array_url[$searchPos_3]);
                $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
            exit;

        //Classified Detail
        } elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (CLASSIFIED_FEATURE != "on" || CUSTOM_CLASSIFIED_FEATURE != "on") { exit; }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "classified";
            $headertag_title = (($classified->getString("seo_title"))?($classified->getString("seo_title")):($classified->getString("title")));
            $headertag_description = (($classified->getString("seo_summarydesc"))?($classified->getString("seo_summarydesc")):($classified->getString("summarydesc")));
            $headertag_keywords = (($classified->getString("seo_keywords"))?($classified->getString("seo_keywords")):(str_replace(" || ", ", ", $classified->getString("keywords"))));

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");

        //Classified Share
        } elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) {

            $_GET["friendly_url"]   = string_substr($aux_array_url[$searchPos_3], 0,  string_strpos($aux_array_url[$searchPos_3], ".html"));
            $_GET["from"]           = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");

        //Classified General Pages - all categories, all locations
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (CLASSIFIED_FEATURE != "on" || CUSTOM_CLASSIFIED_FEATURE != "on") { exit; }

            if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                $sitecontentSection = "Classifide View All Locations";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
            } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                $sitecontentSection = "Classified View All Categories";
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
            }

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "classified";
            $headertag_title = $headertagtitle;
            $headertag_description = $headertagdescription;
            $headertag_keywords = $headertagkeywords;

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
        } elseif (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || 
                (!empty($aux_array_url[$searchPos_2]))) {
            /*This else case completed on the 20-09-2013 for friendly url*/

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            if(!empty($aux_array_url[$searchPos_2]))
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Classified Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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


        }else {
            front_errorPage();
        }

    //Article
    } elseif ($module_key == ARTICLE_FEATURE_FOLDER) {

        //Article Results
        if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (ARTICLE_FEATURE != "on" || CUSTOM_ARTICLE_FEATURE != "on") { exit; }

            if (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR)) {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Article Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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

        //Article RSS
        } elseif((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) {

            if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                $_GET["qs"] = str_replace(ALIAS_ARTICLE_MODULE."_", "", $aux_array_url[$searchPos_3]);
                $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
            exit;

        //Article Detail
        } elseif ((string_strpos($aux_array_url[$searchPos_2],".html") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (ARTICLE_FEATURE != "on" || CUSTOM_ARTICLE_FEATURE != "on") { exit; }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "article";
            $headertag_title = (($article->getString("seo_title"))?($article->getString("seo_title")):($article->getString("title")));
            $headertag_description = (($article->getString("seo_abstract"))?($article->getString("seo_abstract")):($article->getString("abstract")));
            $headertag_keywords = (($article->getString("seo_keywords"))?($article->getString("seo_keywords")):(str_replace(" || ", ", ", $article->getString("keywords"))));

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");

        //Article Share
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false)
                ) {

            //Share from reviews page
            if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
            //Share from detail/results    
            } else {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
            }

            $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");

        //Article General Pages - reviews, all categories
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (ARTICLE_FEATURE != "on" || CUSTOM_ARTICLE_FEATURE != "on") { exit; }

            //Reviews page
            if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) {

                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);

                include(EDIR_CONTROLER_FOLDER."/".$module_key."/review.php");

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
                $banner_section = "article";
                $headertag_title = system_showText(LANG_REVIEWSOF)." ".(($articleObj->getString("seo_title")) ? ($articleObj->getString("seo_title")) : ($articleObj->getString("title")));
                $headertag_description = (($articleObj->getString("seo_description")) ? ($articleObj->getString("seo_description")) : ($articleObj->getString("description")));
                $headertag_keywords = (($articleObj->getString("seo_keywords")) ? ($articleObj->getString("seo_keywords")) : (str_replace(" || ", ", ", $articleObj->getString("keywords"))));

                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/comments.php");

            //All categories page
            } else {

                # ----------------------------------------------------------------------------------------------------
                # SITE CONTENT
                # ----------------------------------------------------------------------------------------------------
                $sitecontentSection = "Article View All Categories";
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
                $banner_section = "article";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;

                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
            }

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
        } else {
            front_errorPage();
        }

    //Promotion
    } elseif ($module_key == PROMOTION_FEATURE_FOLDER) {

        //Promotion Results
        if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3]) || (($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") exit;

            if ( ((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || ($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) || ($aux_array_url[$searchPos_2] == ALIAS_LOCATION_URL_DIVISOR)) {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Deal Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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

        //Promotion RSS
        } elseif ((string_strpos($aux_array_url[$searchPos_2],"rss") !== false)) {

            if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                $_GET["qs"] = str_replace(ALIAS_PROMOTION_MODULE."_", "", $aux_array_url[$searchPos_3]);
                $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
            exit;

        //Promotion Detail
        } elseif ((string_strpos($aux_array_url[$searchPos_2],".html") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") exit;

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "promotion";
            $headertag_title = (($promotion->getString("seo_name")) ? ($promotion->getString("seo_name")) : ($promotion->getString("title")));
            $headertag_description = (($promotion->getString("seo_description")) ? ($promotion->getString("seo_description")) : ($promotion->getString("description")));
            $headertag_keywords = (($promotion->getString("seo_keywords")) ? ($promotion->getString("seo_keywords")) : (str_replace(" || ", ", ", $promotion->getString("keywords"))));

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");

        //Promotion Share
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false)
                ) {

            //Share from reviews page
            if ((string_strpos($aux_array_url[$searchPos_2] ,ALIAS_REVIEW_URL_DIVISOR) !== false) && (string_strpos($aux_array_url[$searchPos_3], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_4], ".html") !== false) {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_4], 0, string_strpos($aux_array_url[$searchPos_4], ".html"));
            //Share from detail/results
            } else {
                $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0, string_strpos($aux_array_url[$searchPos_3], ".html"));
            }

            $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");

        //Promotion General Pages - reviews, all categories, all locations
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR.".php") !== false) ||
                ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (PROMOTION_FEATURE != "on" || CUSTOM_PROMOTION_FEATURE != "on" || CUSTOM_HAS_PROMOTION != "on") exit;

            //Reviews page
            if ((string_strpos($aux_array_url[$searchPos_2], ALIAS_REVIEW_URL_DIVISOR) !== false) && $aux_array_url[$searchPos_3]) {

                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);

                include(EDIR_CONTROLER_FOLDER."/".$module_key."/review.php");
                define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/comments.php");

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
                $banner_section = "promotion";
                $headertag_title = system_showText(LANG_REVIEWSOF)." ".(($promotionObj->getString("seo_name")) ? ($promotionObj->getString("seo_name")) : ($promotionObj->getString("name")));
                $headertag_description = (($promotionObj->getString("seo_description")) ? ($promotionObj->getString("seo_description")) : ($promotionObj->getString("description")));
                $headertag_keywords = (($promotionObj->getString("seo_keywords")) ? ($promotionObj->getString("seo_keywords")) : (str_replace(" || ", ", ", $promotionObj->getString("keywords"))));

            //All categories and all locations page
            } else {

                if (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLLOCATIONS_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Deal View All Locations";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/alllocations.php");
                } elseif (string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) {
                    $sitecontentSection = "Deal View All Categories";
                    define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
                }

                # ----------------------------------------------------------------------------------------------------
                # SITE CONTENT
                # ----------------------------------------------------------------------------------------------------
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);

                # ----------------------------------------------------------------------------------------------------
                # HEADER
                # ----------------------------------------------------------------------------------------------------
                $banner_section = "promotion";
                $headertag_title = $headertagtitle;
                $headertag_description = $headertagdescription;
                $headertag_keywords = $headertagkeywords;
            }

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
        }elseif (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || 
                (!empty($aux_array_url[$searchPos_2]))) {
            /*This else case completed on the 20-09-2013 for friendly url*/

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            if(!empty($aux_array_url[$searchPos_2]))
            {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/results.php");

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Deal Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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

            //Listing Backlink return
        } else {
            front_errorPage();
        }

    //Blog
    } elseif ($module_key == BLOG_FEATURE_FOLDER) {

        //Blog Results
        if (((string_strpos($aux_array_url[$searchPos_2], "results.php") !== false)) || (($aux_array_url[$searchPos_2] == "page") && $aux_array_url[$searchPos_3]) || (($aux_array_url[$searchPos_2] == ALIAS_ARCHIVE_URL_DIVISOR) && $aux_array_url[$searchPos_3]) || (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3])) {

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            cachefull_header();
            $closeCacheFull = true;

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }

            $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
            $blogHome = false;

            # ----------------------------------------------------------------------------------------------------
            # MOD-REWRITE
            # ----------------------------------------------------------------------------------------------------
            include(EDIR_CONTROLER_FOLDER."/".$module_key."/rewrite.php");

            # ----------------------------------------------------------------------------------------------------
            # VALIDATION
            # ----------------------------------------------------------------------------------------------------
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

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Blog Results";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
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

        //Blog Share
        } elseif ((string_strpos($aux_array_url[$searchPos_2], ALIAS_SHARE_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_3], ".html") !== false) {

            $_GET["from"] = string_replace_once(EDIRECTORY_FOLDER."/".$alias_names[$module_key]."/", "", $_SERVER["REQUEST_URI"]);
            $_GET["friendly_url"] = string_substr($aux_array_url[$searchPos_3], 0,  string_strpos($aux_array_url[$searchPos_3], ".html"));

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/share.php");

        //Blog Detail
        } elseif ((string_strpos($aux_array_url[$searchPos_2], ".html") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/detail.php");

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "blog";
            $headertag_title = (($post->getString("seo_title")) ? ($post->getString("seo_title")) : ($post->getString("title")));
            $headertag_description = (($post->getString("seo_abstract")) ? ($post->getString("seo_abstract")) : (strip_tags($post->getString("content", false, 252))));
            $headertag_keywords = (($post->getString("seo_keywords")) ? ($post->getString("seo_keywords")) : (str_replace(" || ", ", ", $post->getString("keywords"))));

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/".$module_key."/detail.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/detail.php");

        //Blog RSS
        } elseif ((string_strpos($aux_array_url[$searchPos_2], "rss") !== false)) {

            if (string_strpos($aux_array_url[$searchPos_3], ".xml") !== false) {
                $_GET["qs"] = str_replace(ALIAS_BLOG_MODULE."_", "", $aux_array_url[$searchPos_3]);
                $_GET["qs"] = str_replace(".xml", "", $_GET["qs"]);
            }

            include(EDIR_CONTROLER_FOLDER."/".$module_key."/rss.php");
            exit;

        //Blog General Pages - all categories
        } elseif (((string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR) !== false) && string_strpos($aux_array_url[$searchPos_2], ALIAS_ALLCATEGORIES_URL_DIVISOR.".php") !== false)) {

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATE FEATURE
            # ----------------------------------------------------------------------------------------------------
            if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }

            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            $sitecontentSection = "Blog View All Categories";
            $array_HeaderContent = front_getSiteContent($sitecontentSection);
            extract($array_HeaderContent);

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $banner_section = "blog";
            $headertag_title = $headertagtitle;
            $headertag_description = $headertagdescription;
            $headertag_keywords = $headertagkeywords;

            $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";

            define("ACTUAL_PAGE_NAME", EDIRECTORY_FOLDER."/$module_key/allcategories.php");
        } else {
            front_errorPage();
        }

    } else {
        front_errorPage();
    }
    }

    if ($theme_file && file_exists($theme_file)) {
        
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
        
    } else {
        front_errorPage();
    }
?>