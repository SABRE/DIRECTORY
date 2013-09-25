<?  
    flush();

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
    # * FILE: /index.php
    # ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # LOAD CONFIG
    # ----------------------------------------------------------------------------------------------------

    include("./conf/loadconfig.inc.php");

    # ----------------------------------------------------------------------------------------------------
    # VALIDATE URL TO OPEN
    # ----------------------------------------------------------------------------------------------------
    unset($aux_array_url, $alias_names);

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
     * Accept pages (home)
     */
    unset($acceptPages);
    $acceptPages[] = "index.php";
    $acceptPages[] = ALIAS_ADVERTISE_URL_DIVISOR.".php";
    $acceptPages[] = ALIAS_ADVERTISE_URL_DIVISOR.".php?listing";
    $acceptPages[] = ALIAS_ADVERTISE_URL_DIVISOR.".php?event";
    $acceptPages[] = ALIAS_ADVERTISE_URL_DIVISOR.".php?classified";
    $acceptPages[] = ALIAS_ADVERTISE_URL_DIVISOR.".php?article";
    $acceptPages[] = ALIAS_ADVERTISE_URL_DIVISOR.".php?banner";
    $acceptPages[] = ALIAS_CONTACTUS_URL_DIVISOR.".php";
    $acceptPages[] = ALIAS_SITEMAP_URL_DIVISOR.".php";
    $acceptPages[] = ALIAS_FAQ_URL_DIVISOR.".php";
    $acceptPages[] = "";

    $activeMenuHome = false;

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

    $module_key = array_search($aux_array_url[$searchPos], $alias_names);
       
    //Modules Pages
    if ($module_key) 
    {
        define("ACTUAL_MODULE_FOLDER", $module_key);
        /*Code is entered on the 24-09-2013 to redirect without the category sepaator*/
        $removeKey = '';
        if(array_search(ALIAS_CATEGORY_URL_DIVISOR,$aux_array_url)!== false)
        {
            $_SERVER["REQUEST_URI"] = '';
            foreach($aux_array_url as $key => $value)
            {
                if($value !== ALIAS_CATEGORY_URL_DIVISOR)
                    $_SERVER["REQUEST_URI"] .= $value.'/';
                else
                    $removeKey = $key;
            }
            unset($aux_array_url[$removeKey]);
            $url = DEFAULT_URL.str_replace(EDIRECTORY_FOLDER,'',$_SERVER['REQUEST_URI']);
            $url = rtrim($url,'/');
            
?>          
            <script>
                window.location = '<?=$url?>';
            </script>
<?      }
        /*Code is ended on the 24-09-2013*/
        include(EDIRECTORY_ROOT."/full_modrewrite.php");
        
    } 
    else 
    {
        //Front Pages (index, advertise, contact us, faq, sitemap)
        if (array_search($aux_array_url[$searchPos], $acceptPages) === false && string_strpos($aux_array_url[$searchPos], ALIAS_FAQ_URL_DIVISOR.".php") === false) 
        {
            front_errorPage();
        } 
        else 
        {
            //Advertise Page
            if (string_strpos($aux_array_url[$searchPos], ALIAS_ADVERTISE_URL_DIVISOR.".php") !== false) { 
				
                $loadCache = false;
                $loadValidation = false;
                $sitecontentSection = "Advertise with Us";
                $theme_file = "";
                $controllerFile = EDIR_CONTROLER_FOLDER."/advertise.php";
                $coreFile = EDIRECTORY_ROOT."/".EDIR_CORE_FOLDER_NAME."/advertise.php";
               
            //Contact US Page
            } elseif ($aux_array_url[$searchPos] == ALIAS_CONTACTUS_URL_DIVISOR.".php") {
                $loadCache = false;
                $loadValidation = false;
                $sitecontentSection = "Contact Us";
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
                $controllerFile = EDIR_CONTROLER_FOLDER."/contactus.php";
                $coreFile = "";
                $generalPage = "contactus";
                
            //FAQ Page
            } elseif (string_strpos($aux_array_url[$searchPos], ALIAS_FAQ_URL_DIVISOR.".php") !== false) {
                $loadCache = false;
                $loadValidation = true;
                $sitecontentSection = "";
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/faq.php";
                $controllerFile = EDIRECTORY_ROOT."/includes/code/faq.php";
                $coreFile = "";
                $generalPage = "faq";
                
            //Sitemap Page
            }elseif ($aux_array_url[$searchPos] == ALIAS_SITEMAP_URL_DIVISOR.".php") {
                $loadCache = false;
                $loadValidation = false;
                $sitecontentSection = "Sitemap";
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/general.php";
                $controllerFile = "";
                $coreFile = "";
                $generalPage = "sitemap";
                
            //Home Page
            } else {
                $loadCache = true;
                $loadValidation = false;
                $sitecontentSection = "Home Page";
                $theme_file = THEMEFILE_DIR."/".EDIR_THEME."/body/index.php";
                $loadSlider = true;
                $activeMenuHome = true;
            }
            
            define("ACTUAL_MODULE_FOLDER", "");

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            if ($loadCache) {
                cachefull_header();
            }

            # ----------------------------------------------------------------------------------------------------
            # MAINTENANCE MODE
            # ----------------------------------------------------------------------------------------------------
            verify_maintenanceMode();

            # ----------------------------------------------------------------------------------------------------
            # SESSION
            # ----------------------------------------------------------------------------------------------------
            sess_validateSessionFront();

            # ----------------------------------------------------------------------------------------------------
            # VALIDATION
            # ----------------------------------------------------------------------------------------------------
            if ($loadValidation) {
                include(EDIRECTORY_ROOT."/includes/code/validate_frontrequest.php");
            } else {
                include(EDIRECTORY_ROOT."/includes/code/validate_querystring.php");
            }
             
            # ----------------------------------------------------------------------------------------------------
            # CODE
            # ----------------------------------------------------------------------------------------------------
            if ($controllerFile && file_exists($controllerFile)) {
                include($controllerFile);
            }
           
            # ----------------------------------------------------------------------------------------------------
            # SITE CONTENT
            # ----------------------------------------------------------------------------------------------------
            if ($sitecontentSection) {
                $array_HeaderContent = front_getSiteContent($sitecontentSection);
                extract($array_HeaderContent);
            }

            # ----------------------------------------------------------------------------------------------------
            # HEADER
            # ----------------------------------------------------------------------------------------------------
            $headertag_title = $headertagtitle;
            $headertag_description = $headertagdescription;
            $headertag_keywords = $headertagkeywords;
            include(system_getFrontendPath("header.php", "layout"));

            # ----------------------------------------------------------------------------------------------------
            # AUX
            # ----------------------------------------------------------------------------------------------------
            require(EDIRECTORY_ROOT."/frontend/checkregbin.php");

            # ----------------------------------------------------------------------------------------------------
            # BODY
            # ----------------------------------------------------------------------------------------------------
            
            if ($theme_file && file_exists($theme_file)) {
                include($theme_file);
            }
            
            if ($coreFile && file_exists($coreFile)) {
                include($coreFile);
            }

            # ----------------------------------------------------------------------------------------------------
            # FOOTER
            # ----------------------------------------------------------------------------------------------------
            include(system_getFrontendPath("footer.php", "layout"));

            # ----------------------------------------------------------------------------------------------------
            # CACHE
            # ----------------------------------------------------------------------------------------------------
            if ($loadCache) {
                cachefull_footer();
            }
        }
    }
?>