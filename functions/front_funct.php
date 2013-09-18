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
	# * FILE: /functions/front_function.php
	# ----------------------------------------------------------------------------------------------------

    function front_getHeaderTag(&$headertag_title, &$headertag_author, &$headertag_description, &$headertag_keywords) {
      
        if (string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false ||
            string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || 
            string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false ||
            string_strpos($_SERVER["REQUEST_URI"], ACTUAL_MODULE_FOLDER) !== false ) {

            $keyword = ($_GET["keyword"]) ? $_GET["keyword"] : $_POST["keyword"];
            if ($keyword) {
                $extra_headertag_title_keyword = string_strtoupper($keyword);
            }

            $where = ($_GET["where"]) ? $_GET["where"] : $_POST["where"];
            if ($where) {
                $extra_headertag_title_where = string_strtoupper($where);
            }

            $category_id = ($_GET["category_id"]) ? $_GET["category_id"] : $_POST["category_id"];
            if ($category_id) {
                if (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
                    $headertag_categoryObj = new ArticleCategory($category_id);
                } elseif (ACTUAL_MODULE_FOLDER == CLASSIFIED_FEATURE_FOLDER) {
                    $headertag_categoryObj = new ClassifiedCategory($category_id);
                } elseif (ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER) {
                    $headertag_categoryObj = new EventCategory($category_id);
                } elseif (ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER) {
                    $headertag_categoryObj = new ListingCategory($category_id);
                } elseif (ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) {
                    $headertag_categoryObj = new ListingCategory($category_id);
                } elseif (ACTUAL_MODULE_FOLDER == BLOG_FEATURE_FOLDER) {
                    $headertag_categoryObj = new BlogCategory($category_id);
                }
                if ($headertag_categoryObj && $headertag_categoryObj->getString("page_title")) {
                    $extra_headertag_title_category = $headertag_categoryObj->getString("page_title");
                }
            }

            $locationsTag = array();
            $db_main = db_getDBObject(DEFAULT_DB, true);

            $location_1 = ($_GET["location_1"]) ? $_GET["location_1"] : $_POST["location_1"];
            $location_2 = ($_GET["location_2"]) ? $_GET["location_2"] : $_POST["location_2"];
            $location_3 = ($_GET["location_3"]) ? $_GET["location_3"] : $_POST["location_3"];
            $location_4 = ($_GET["location_4"]) ? $_GET["location_4"] : $_POST["location_4"];
            $location_5 = ($_GET["location_5"]) ? $_GET["location_5"] : $_POST["location_5"];

            if ($location_1) {
                $sql = "SELECT name FROM Location_1 WHERE id = ".$location_1;
                $row = mysql_fetch_assoc($db_main->query($sql));
                if ($row['name']) $locationsTag[] = $row['name'];
            }
            if ($location_2) {
                $sql = "SELECT name FROM Location_2 WHERE id = ".$location_2;
                $row = mysql_fetch_assoc($db_main->query($sql));
                if ($row['name']) $locationsTag[] = $row['name'];
            }
            if ($location_3) {
                $sql = "SELECT name FROM Location_3 WHERE id = ".$location_3;
                $row = mysql_fetch_assoc($db_main->query($sql));
                if ($row['name']) $locationsTag[] = $row['name'];
            }
            if ($location_4) {
                $sql = "SELECT name FROM Location_4 WHERE id = ".$location_4;
                $row = mysql_fetch_assoc($db_main->query($sql));
                if ($row['name']) $locationsTag[] = $row['name'];
            }
            if ($location_5) {
                $sql = "SELECT name FROM Location_5 WHERE id = ".$location_5;
                $row = mysql_fetch_assoc($db_main->query($sql));
                if ($row['name']) $locationsTag[] = $row['name'];
            }

            if ($locationsTag) {
                $extra_headertag_title_location = implode (', ', $locationsTag);	
            }

            $zip = ($_GET["zip"]) ? $_GET["zip"] : $_POST["zip"];
            if ($zip) {
                $extra_headertag_title_zip .= ZIPCODE_LABEL." ".$zip.(($dist)?(" (".$dist." ".ZIPCODE_UNIT_LABEL_PLURAL.")"):(""));
            }

            $screen = ($_GET["screen"]) ? $_GET["screen"] : $_POST["screen"];
            if ($screen) {
                $extra_headertag_title_screen = $screen;
            }

            $page = ($_GET["page"]) ? $_GET["page"] : $_POST["page"];
            if ($page) {
                $extra_headertag_title_page = $page;
            }

            $extra_headertag_title = "";
            if ($extra_headertag_title_keyword) {
                $extra_headertag_title .= system_showText(LANG_SEARCHRESULTS_KEYWORD)." ".$extra_headertag_title_keyword;
            }
            if ($extra_headertag_title_where) {
                $extra_headertag_title .= ($extra_headertag_title_keyword ? " " : "").system_showText(LANG_SEARCHRESULTS_WHERE)." ".$extra_headertag_title_where;
            }
            if ($extra_headertag_title_category) {
                $extra_headertag_title .= ($extra_headertag_title_keyword || $extra_headertag_title_where ? " " : "").system_showText(LANG_SEARCHRESULTS_CATEGORY)." ".$extra_headertag_title_category;
            }
            if ($extra_headertag_title_location) {
                $extra_headertag_title .= ($extra_headertag_title_keyword || $extra_headertag_title_where || $extra_headertag_title_category ? " " : "").system_showText(LANG_SEARCHRESULTS_LOCATION)." ".$extra_headertag_title_location;
            }
            if ($extra_headertag_title_zip) {
                $extra_headertag_title .= ($extra_headertag_title_keyword || $extra_headertag_title_where || $extra_headertag_title_category || $extra_headertag_title_location ? " " : "").system_showText(LANG_SEARCHRESULTS_ZIP)." ".$extra_headertag_title_zip;
            }
            if ($extra_headertag_title_screen) {
                $extra_headertag_title .= " - ".system_showText(LANG_SEARCHRESULTS_PAGE)." ".$extra_headertag_title_screen;
            }
            if ($extra_headertag_title_page) {
                $extra_headertag_title .= " - ".system_showText(LANG_SEARCHRESULTS_PAGE)." ".$extra_headertag_title_page;
            }
            if ($extra_headertag_title) {
                $extra_headertag_title = system_showText(LANG_SEARCHRESULTS)." ".$extra_headertag_title." | ";
            } else {
                $extra_headertag_title = system_showText(LANG_SEARCHRESULTS)." | ";
            }

        }
         
        unset($aux_get_header_tag);
        $aux_get_header_tag = array();
        if (!$headertag_title) {
            $aux_get_header_tag[] = "name = 'header_title'";
        }

        if (!$headertag_author) {
            $aux_get_header_tag[] = "name = 'header_author'";
        }

        if (!$headertag_description) {
            $aux_get_header_tag[] = "name = 'header_description'";
        }

        if (!$headertag_keywords) {
            $aux_get_header_tag[] = "name = 'header_keywords'";
        }
       
        $return_headertag = customtext_getByArray($aux_get_header_tag);
        
        if(is_array($return_headertag)){

            extract($return_headertag);

            if(array_key_exists ("header_title", $return_headertag)){
                $headertag_title = (($header_title) ? ($header_title) : (EDIRECTORY_TITLE));
            }

            if(array_key_exists ("header_author", $return_headertag)){
                $headertag_author = (($header_author) ? ($header_author) : ("Arca Solutions"));
            }

            if(array_key_exists ("header_description", $return_headertag)){
                $headertag_description = (($header_description) ? ($header_description) : (EDIRECTORY_TITLE));
            }
            
            if(array_key_exists ("header_keywords", $return_headertag)){
                $headertag_keywords	= (($header_keywords) ? ($header_keywords) : EDIRECTORY_TITLE);
            }

        }

        if ($extra_headertag_title) {
            $headertag_title = $extra_headertag_title.$headertag_title ;
        }
        
        $headertag_title = (($headertag_title) ? ($headertag_title) : (EDIRECTORY_TITLE));
        $headertag_author = (($headertag_author) ? ($headertag_author) : ("Arca Solutions"));
        $headertag_description = (($headertag_description) ? ($headertag_description) : (EDIRECTORY_TITLE));
        $headertag_keywords = (($headertag_keywords) ? ($headertag_keywords) : (EDIRECTORY_TITLE));
        
    }
    
    function front_searchMetaTag() {
        $metaTags = "";
		unset($array_tags);
		$array_tags = array();
		$array_tags[] = "'google'";
		$array_tags[] = "'yahoo'";
		$array_tags[] = "'live'";
		$searchMetaObj = new SearchMetaTag();
		$aux_array_meta_tags = $searchMetaObj->isSetFieldByArray($array_tags);
		if(is_array($aux_array_meta_tags)){
			for($i=0;$i<count($aux_array_meta_tags);$i++){
				$metaTags .= $aux_array_meta_tags[$i];
			}
		}
        return $metaTags;
    }
    
    function front_themeFiles() {
        
        include(THEMEFILE_DIR."/".EDIR_THEME."/".EDIR_THEME.".php");
    }
    
    function front_navbarOptions($includeOptions = false) {
        $navbarOptions = "";
        if (DEMO_MODE) {
            
            if (!$includeOptions){
                $navbarOptions = "
                                        <div class=\"top-navbar\" id=\"topNavbar-options\" style=\"display:none\">
                                            <div class=\"top-wrapper\">
                                                <ul>
                                                    <li><a href=\"".((SSL_ENABLED == "on" && FORCE_MEMBERS_SSL == "on") ? SECURE_URL : NON_SECURE_URL)."/".MEMBERS_ALIAS."/\">".system_showText(LANG_SPONSOR_AREA)."</a></li>
                                                    <li id=\"demo_mode_sitemgr\"><a href=\"".((SSL_ENABLED == "on" && FORCE_SITEMGR_SSL == "on") ? SECURE_URL : NON_SECURE_URL)."/".SITEMGR_ALIAS."/\">".system_showText(LANG_SITEMGR_AREA)."</a></li>
                                                </ul>";

                include(EDIRECTORY_ROOT."/layout/themenavbar.php");
                
                $navbarOptions .= "         </div>
                                        </div>";
            }
            
            if ($includeOptions){
                $navbarOptions .= " 
                    <div class=\"top-button\">
                        <div class=\"top-open\">
                            <a href=\"javascript: void(0);\" onclick=\"controlTopnavbar();\">".system_showText(LANG_LABEL_OPTIONS)."</a>
                        </div>
                    </div>";
            }
        }
        return $navbarOptions;
    }
    
    function front_includeFile($file, $folder, &$js_fileLoader) {
        include(system_getFrontendPath($file, $folder));
    }
    
    function front_includeBanner($category_id, $banner_section) {
        include(EDIRECTORY_ROOT."/frontend/banner_top.php");
    }
    
    function front_includeSearch($hide_search, $browsebylocation, $browsebycategory, $keyword, $where, $screen, $letter, &$js_fileLoader) {
        if (string_strpos($_SERVER['REQUEST_URI'], ALIAS_FAQ_URL_DIVISOR.".php") === false && !$hide_search){
            include(EDIRECTORY_ROOT."/searchfront.php");
        }
    }
    
    function front_twitterFooter(&$twitterAccount, &$timeLine) {
        $twitterAccount = "";
        $timeLine = "";
        $twitterObj = new Twitter();
        if($twitterObj->getRandonAccount()){
            $twitterAccount = $twitterObj->account;
            $tweetInfo = $twitterObj->userInfo();
            if ($tweetInfo["protected"] != "true") {
                $timeLine = "   <ul id=\"twitter_update_list_footer\">
									<li id=\"twitter_loading_footer\" class=\"loading\"></li>
								</ul>";
            }
        } 
    }
    
    function front_getCopyright(&$footer) {
        customtext_get("footer_copyright", $footer_copyright);
        if (!$footer_copyright) {
            $footer = "Copyright &copy; ".date("Y")." Arca Solutions, Inc. <br />All Rights Reserved.";
        } else {
            $footer = $footer_copyright;
        }
        if (BRANDED_PRINT == "on") {
            echo "<h5 class=\"powered-by\">Powered by <a href=\"http://www.edirectory.com\" target=\"_blank\">eDirectory&trade;</a>.</h5>";
        }
    }

    function front_statisticReport($report_section) {
        
        if ($report_section == "blog") {
            $report_section = "post";
        } elseif ($report_section == "promotion") {
            $report_section = "deal";
        }
        # ----------------------------------------------------------------------------------------------------
        # statistic
        # ----------------------------------------------------------------------------------------------------
        $module         = ($report_section)         ? string_substr($report_section, 0, 1)    : "h";
        $keyword        = ($_GET["keyword"])        ? trim($_GET["keyword"])		   : "";
        $category_id    = ($_GET["category_id"])    ? $_GET["category_id"]			   : "";
        $location_1     = ($_GET["location_1"])     ? $_GET["location_1"]			   : "";
        $location_2     = ($_GET["location_2"])     ? $_GET["location_2"]			   : "";
        $location_3     = ($_GET["location_3"])     ? $_GET["location_3"]			   : "";
        $location_4     = ($_GET["location_4"])     ? $_GET["location_4"]			   : "";
        $location_5     = ($_GET["location_5"])     ? $_GET["location_5"]			   : "";
        $where          = ($_GET["where"])          ? trim(string_ucwords($_GET["where"]))    : "";
        # ----------------------------------------------------------------------------------------------------
        # validate
        # ----------------------------------------------------------------------------------------------------
        $save = false;
        if(!$save) $save = (string_strlen($keyword)      > 0);
        if(!$save) $save = (string_strlen($category_id)  > 0);
        if(!$save) $save = (string_strlen($location_1)   > 0);
        if(!$save) $save = (string_strlen($location_2)   > 0);
        if(!$save) $save = (string_strlen($location_3)   > 0);
        if(!$save) $save = (string_strlen($location_4)   > 0);
        if(!$save) $save = (string_strlen($location_5)   > 0);
        if(!$save) $save = (string_strlen($where)        > 0);

        # ----------------------------------------------------------------------------------------------------
        # insert
        # ----------------------------------------------------------------------------------------------------
        $sql = "";
        if($save) {
            $sql = "INSERT INTO Report_Statistic VALUES (NOW(), ".db_formatString($module).", ".db_formatString($keyword).", ".db_formatNumber($category_id).", ".db_formatNumber($location_1).", ".db_formatNumber($location_2).", ".db_formatNumber($location_3).", ".db_formatNumber($location_4).", ".db_formatNumber($location_5).", ".db_formatString($where).")";
            $db = db_getDBObject();
            $db->query($sql);
            unset($db);
        }
    }
    
    function front_googleMaps($itemRSSSection, $listings, $classifieds, $events, $promotions, $levelObj) {
       
        if (string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false ||
            string_strpos($_SERVER["REQUEST_URI"], ALIAS_CATEGORY_URL_DIVISOR."/") !== false || 
            string_strpos($_SERVER["REQUEST_URI"], ALIAS_LOCATION_URL_DIVISOR."/") !== false ||
            string_strpos($_SERVER["REQUEST_URI"], ACTUAL_MODULE_FOLDER) !== false ) {
            
            if($itemRSSSection == "listing") {
                $searchResults = $listings;
                $item_type = "listing";
            } elseif($itemRSSSection == "classified") {
                $searchResults = $classifieds;
                $item_type = "classified";
            } elseif($itemRSSSection == "event") {
                $searchResults = $events;
                $item_type = "event";
            } elseif($promotions) {
                $searchResults = array();
                $promotionTitle[] = array();
                $listingObj = new Listing();
                foreach($promotions as $promotion){
                    $listings = $listingObj->retrieveListingsbyPromotion_id($promotion->getNumber("id"));
                    foreach($listings as $listing){
                        $searchResults[] = $listing;
                        $promotionTitle[$listing->getNumber("id")] = $promotion->getString("name", true);
                    }
                }
                $item_type = "promotion";
            }

            $mapObj = new GoogleSettings(GOOGLE_MAPS_STATUS); 
            if (GOOGLE_MAPS_ENABLED == "on" && $mapObj->getString("value") == "on") {
                include(INCLUDES_DIR."/views/view_resultsmap.php");
            }
        }
    }
    
    function front_googleAnalytics() {
        if (!DEMO_DEV_MODE && (GOOGLE_ANALYTICS_ENABLED == "on")) {
            $google_analytics_page = "front";
			include(INCLUDES_DIR."/code/google_analytics.php");
        }
    }
    
    function front_pinterestButton() {
        if (PINTEREST_PLUGIN == "on" && string_strpos($_SERVER["REQUEST_URI"], ".html") !== false && defined("ACTUAL_MODULE_FOLDER") && ACTUAL_MODULE_FOLDER != "") { ?>
            <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
        <? }
    }

    function front_getSiteContent($sitecontentSection) {
        $array_HeaderContent = array();
        
        $contentObj = new Content();
        $sitecontentinfo = $contentObj->retrieveContentInfoByType($sitecontentSection);
        if ($sitecontentinfo) {
            $array_HeaderContent["headertagtitle"]         = $sitecontentinfo["title"];
            $array_HeaderContent["headertagdescription"]   = $sitecontentinfo["description"];
            $array_HeaderContent["headertagkeywords"]      = $sitecontentinfo["keywords"];
            $array_HeaderContent["sitecontent"]            = $sitecontentinfo["content"];
        } else {
            $array_HeaderContent["headertagtitle"]         = "";
            $array_HeaderContent["headertagdescription"]   = "";
            $array_HeaderContent["headertagkeywords"]      = "";
            $array_HeaderContent["sitecontent"]            = "";
        }
        return $array_HeaderContent;
    }

    function front_errorPage() {
        define("ACTUAL_MODULE_FOLDER", "errorpage");
        define("ACTUAL_PAGE_NAME", "errorpage.php");
        
        header("HTTP/1.0 404 Not Found");
        $notIncludeConfig = true;
        include(EDIRECTORY_ROOT."/errorpage.php");
        exit;
    }
    
    function front_validateIndex() {
        if ($_SERVER["REQUEST_URI"] == EDIRECTORY_FOLDER."/".ALIAS_LISTING_MODULE) {
            header( "HTTP/1.1 301 Moved Permanently");
            header( "Location: ".LISTING_DEFAULT_URL."/");
            exit;
        } elseif ($_SERVER["REQUEST_URI"] == EDIRECTORY_FOLDER."/".ALIAS_EVENT_MODULE) {
            header( "HTTP/1.1 301 Moved Permanently");
            header( "Location: ".EVENT_DEFAULT_URL."/");
            exit;
        } elseif ($_SERVER["REQUEST_URI"] == EDIRECTORY_FOLDER."/".ALIAS_CLASSIFIED_MODULE) {
            header( "HTTP/1.1 301 Moved Permanently");
            header( "Location: ".CLASSIFIED_DEFAULT_URL."/");
            exit;
        } elseif ($_SERVER["REQUEST_URI"] == EDIRECTORY_FOLDER."/".ALIAS_ARTICLE_MODULE) {
            header( "HTTP/1.1 301 Moved Permanently");
            header( "Location: ".ARTICLE_DEFAULT_URL."/");
            exit;
        } elseif ($_SERVER["REQUEST_URI"] == EDIRECTORY_FOLDER."/".ALIAS_PROMOTION_MODULE) {
            header( "HTTP/1.1 301 Moved Permanently");
            header( "Location: ".PROMOTION_DEFAULT_URL."/");
            exit;
        } elseif ($_SERVER["REQUEST_URI"] == EDIRECTORY_FOLDER."/".ALIAS_BLOG_MODULE) {
            header( "HTTP/1.1 301 Moved Permanently");
            header( "Location: ".BLOG_DEFAULT_URL."/");
            exit;
        }
    }
    
    function front_getBannerInfo(&$category_id, &$banner_section) {
        
        /**
        * Aux constants to alias for modules
        */
        $alias_names[LISTING_FEATURE_FOLDER]    = ALIAS_LISTING_MODULE;
        $alias_names[EVENT_FEATURE_FOLDER]      = ALIAS_EVENT_MODULE;
        $alias_names[ARTICLE_FEATURE_FOLDER]    = ALIAS_ARTICLE_MODULE;
        $alias_names[PROMOTION_FEATURE_FOLDER]  = ALIAS_PROMOTION_MODULE;
        $alias_names[CLASSIFIED_FEATURE_FOLDER] = ALIAS_CLASSIFIED_MODULE;
        $alias_names[BLOG_FEATURE_FOLDER]       = ALIAS_BLOG_MODULE;
        
        /**
        * Getting URL to do correct include
        */
        $aux_array_url = explode("/", $_SERVER["REQUEST_URI"]);
       
        if (EDIRECTORY_FOLDER) {
            $auxFolder = explode("/", EDIRECTORY_FOLDER);
            $searchPos = count($auxFolder);
        } else {
            $searchPos = 1;
        }
        
        $searchPos_2 = 2;
        $searchPos_3 = 3;
        $searchPos_4 = 4;

        if (EDIRECTORY_FOLDER) {
            $auxFolder = explode("/", EDIRECTORY_FOLDER);
            $searchPos = count($auxFolder) - 1;
            $searchPos_2 += $searchPos;
            $searchPos_3 += $searchPos;
            $searchPos_4 += $searchPos;
        }
        
        $module_key = array_search($aux_array_url[$searchPos], $alias_names);
        
        if ($module_key) {
            $banner_section = $module_key;
           
            if ($_GET["category_id"]) {
                $category_id = $_GET["category_id"];
            } elseif (($aux_array_url[$searchPos_2] == ALIAS_CATEGORY_URL_DIVISOR) && $aux_array_url[$searchPos_3]) {
                $_GET["url_full"] = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER, "", $_SERVER["REQUEST_URI"]);
                include(EDIR_CONTROLER_FOLDER."/".constant(strtoupper($module_key)."_FEATURE_FOLDER")."/rewrite.php");
                $category_id = $_GET["category_id"];
            }
        }
    }
    
    function front_shareContent($title, $description, $hasImage, $images, $fromURL) {
        
        header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", FALSE);
        header("Pragma: no-cache");
        header("Content-Type: text/html; charset=".EDIR_CHARSET, TRUE);
        
        $mainImage = "";
        $strImages = "";
        $randomImage = "";
        
        if ($hasImage) {
            if (is_array($images) && $images[0]) {
                foreach ($images as $image) {
                    $imgObj = new Image($image["image_id"]);
                    if ($imgObj->imageExists()) {
                        if ($image["image_default"] == "y") { //store the main image to use on meta tag og:image
                            $mainImage = $imgObj->getPath();
                        }
                        
                        $randomImage = $imgObj->getTag(false, 0, 0, $image["image_caption"] ? $image["image_caption"] : $title);
                        $strImages .= $randomImage;
                    }
                }
                if (!$mainImage) { //if there is no main image, use a random image
                    $mainImage = $randomImage;
                }
            }
        }
        
    ?>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en" lang="en">
            <head>
                <title><?=$title;?></title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <meta name="description" content="<?=$description;?>" />
                <meta property="og:title" content="<?=$title;?>"/>
                <meta property="og:site_name" content="<?=EDIRECTORY_TITLE?>"/>
                <meta property="og:description" content="<?=$description;?>"/>
                <?/*
                <meta property="og:image" content="<?=$mainImage?>"/>
                 */ ?>
                <meta property="og:url" content="<?=$fromURL?>"/>
                <?

                echo Facebook::getMetaTags("admins", FACEBOOK_USER_ID);
                echo Facebook::getMetaTags("app_id", FACEBOOK_API_ID);

                ?>
            </head>
            <body>
                <?=$strImages?>
            </body>
        </html>
    <?
        exit;
    }

?>