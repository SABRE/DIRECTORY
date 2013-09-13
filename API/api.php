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
    # * FILE: /API/api.php
    # ----------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------
    # LOAD CONFIG
    # ----------------------------------------------------------------------------------------------------
    include("../conf/loadconfig.inc.php");
    
    # ----------------------------------------------------------------------------------------------------
	# VALIDATION
	# ----------------------------------------------------------------------------------------------------
    $errors = "";
    
    setting_get("edirectory_api_enabled", $edirectory_api_enabled);
    setting_get("edirectory_api_key", $edirectory_api_key);

    extract($_GET);

    //Check if API is enabled
    if ($edirectory_api_enabled == "on"){
        
        //Validate API key
        if ($edirectory_api_key != $key){
            $errors .= system_showText(LANG_API_INVALIDKEY)."<br />";
        }

        //Validate Module
        $arrayModules = array(0 => "LISTING", 1 => "EVENT", 2 => "CLASSIFIED", 3 => "ARTICLE", 4 => "DEAL", 5 => "BLOG");

        if (!$module){
            $errors .= system_showText(LANG_API_EMPTYMODULE)."<br />";
        } else {
            $module = string_strtoupper($module);

            if (!in_array($module, $arrayModules)){
                $errors .= system_showText(LANG_API_INVALIDMODULE)."<br />"; 
            } else {
                if ($module != "LISTING"){

                    if ($module == "DEAL") {
                        $module = "PROMOTION";
                        if (CUSTOM_HAS_PROMOTION != "on"){
                           $errors .= system_showText(LANG_API_MODULEOFF)."<br />"; 
                        }
                    }

                    $moduleFeature = @constant($module."_FEATURE");
                    $moduleFeatureCustom = @constant("CUSTOM_".$module."_FEATURE");
                    if ($moduleFeature != "on" || $moduleFeatureCustom != "on"){
                        $errors .= system_showText(LANG_API_MODULEOFF)."<br />";
                    }
                }
            }
        }
        
        //Validate keyword
        if (!$keyword){
            $errors .= system_showText(LANG_API_EMPTYKEYWORD)."<br />";
        }
        
    } else {
        $errors .= system_showText(LANG_API_DISABLED)."<br />";
    }

    # ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    
    if($_SERVER["REQUEST_METHOD"] == "GET" && !$errors){
        
        //These fields won't be shown in the xml structure
        $exclude_fields[] = "account_id";
        $exclude_fields[] = "discount_id";
        $exclude_fields[] = "package_id";
        $exclude_fields[] = "keyword_score";
        $exclude_fields[] = "keyword_score2";
        $exclude_fields[] = "random_number";
        $exclude_fields[] = "show_email";
        $exclude_fields[] = "zip5";
        $exclude_fields[] = "listing_zip5";
        $exclude_fields[] = "maptuning";
        $exclude_fields[] = "claim_disable";
        $exclude_fields[] = "backlink";
        $exclude_fields[] = "template_layout_id";
        $exclude_fields[] = "listingtemplate_id";
        $exclude_fields[] = "template_cat_id";
        $exclude_fields[] = "template_title";
        $exclude_fields[] = "template_status";
        $exclude_fields[] = "template_price";
        $exclude_fields[] = "location_1_title";
        $exclude_fields[] = "location_2_title";
        $exclude_fields[] = "location_3_title";
        $exclude_fields[] = "location_4_title";
        $exclude_fields[] = "location_5_title";
        $exclude_fields[] = "importID";
        $exclude_fields[] = "has_start_time";
        $exclude_fields[] = "has_end_time";
        $exclude_fields[] = "maptuning_date";
        $exclude_fields[] = "map_zoom";
        $exclude_fields[] = "recurring";
        $exclude_fields[] = "day";
        $exclude_fields[] = "dayofweek";
        $exclude_fields[] = "week";
        $exclude_fields[] = "month";
        $exclude_fields[] = "repeat_event";
        $exclude_fields[] = "fulltextsearch_keyword";
        $exclude_fields[] = "fulltextsearch_where";
        $exclude_fields[] = "clicktocall_extension";
        $exclude_fields[] = "package_id";
        $exclude_fields[] = "package_price";
        $exclude_fields[] = "reminder";
        $exclude_fields[] = "clicktocall_date";
        $exclude_fields[] = "last_traffic_sent";
        $exclude_fields[] = "visibility_start";
        $exclude_fields[] = "visibility_end";
        $exclude_fields[] = "deal_type";
        $exclude_fields[] = "suspended_sitemgr";

        $lowerModule = string_strtolower($module);
        $function = "search_front".ucfirst($lowerModule)."Search";
        if ($lowerModule == "listing"){
            $section = $lowerModule."_results_api"; //Listing_Summary does not have some fields that we need, so lets bring only the id and make an instance of a Listing obj later
        } else {
            $section = $lowerModule;
        }
        $tableModule = ucfirst($lowerModule);
        
        if ($lowerModule == "blog"){
            $xmlSection = "posts";  
        } else {
            $xmlSection = $lowerModule."s";            
        }
        
        if ($lowerModule == "listing"){
            $tableModule = "Listing_Summary";
            $letterField = "Listing_Summary.title";
        } elseif ($lowerModule == "promotion"){
            $letterField = "Promotion.name";
        } elseif ($lowerModule == "blog"){
            $letterField = "Post.title";
        } else {
            $letterField = ucfirst($tableModule).".title";
        }

        $searchReturn = $function($_GET, $section);
        $pageObj = new pageBrowsing($searchReturn["from_tables"], $screen, RESULTS_PER_PAGE, $searchReturn["order_by"], $letterField, $letter, $searchReturn["where_clause"], $searchReturn["select_columns"], $tableModule, $searchReturn["group_by"]);
        $itens = $pageObj->retrievePage("array");

        $xml_var .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xml_var .= "<$xmlSection>\n";

        $xml_var .= "<results_info>\n"; 
            $xml_var .= "<page>".$pageObj->getNumber("screen")."</page>\n"; 
            $xml_var .= "<total_pages>".$pageObj->getNumber("pages")."</total_pages>\n";
            $xml_var .= "<results_per_page>".RESULTS_PER_PAGE."</results_per_page>\n";
            $xml_var .= "<total_results>".$pageObj->getNumber("record_amount")."</total_results>\n";
        $xml_var .= "</results_info>\n";

        for($i=0;$i<count($itens);$i++){

            if ($lowerModule == "listing"){
                $listingObj = new Listing($itens[$i]["id"]); //Need this to get fields that do not exist on Listing_Summary table.
                $itens[$i] = $listingObj->data_in_array;
            }

            $xml_var .= "<".($lowerModule == "blog" ? "post" : $lowerModule)."_info>\n";

            foreach ($itens[$i] as $key => $value){

                $value = htmlspecialchars($value);

                //  The xml structure does not have the following fields:
                // - The ones existing in the array $exclude_fields
                // - The custom_* fields (Listing Types)
                if (!in_array($key, $exclude_fields) && (string_strpos($key, "custom_") === false) && (!is_numeric($key))){

                    //Change image_id and thumb_id to image path
                    if ($key == "image_id" || $key == "thumb_id"){
                        $imageObj = new Image($value);
                        if ($imageObj->imageExists()) {
                            $xml_var .= "<".str_replace("_id", "", $key).">".($imageObj->getPath())."</".str_replace("_id", "", $key).">\n";
                        } else {
                            $xml_var .= "<".str_replace("_id", "", $key)."></".str_replace("_id", "", $key).">\n";
                        }

                    //Change locations id to locations name    
                    } elseif ($key == "location_1" || $key == "location_2" || $key == "location_3" || $key == "location_4" || $key == "location_5" || $key == "listing_location1" || $key == "listing_location2" || $key == "listing_location3" || $key == "listing_location4" || $key == "listing_location5") {
                        if (string_strpos($key, "listing") !== false){
                            $locationField = explode("_", $key);
                            $locationLevel = ucfirst($locationField[1]);
                            $key = strtolower("listing_".constant(strtoupper($locationField[1]."_SYSTEM")));
                        } else {
                            $locationField = explode("_", $key);
                            $locationLevel = "Location".$locationField[1];
                            $key = strtolower(constant("LOCATION".strtoupper($locationField[1]."_SYSTEM")));
                        }
                        $locationObj = new $locationLevel($value);                   

                        $xml_var .= "<".$key.">".($locationObj->getString("name"))."</".$key.">\n";

                    //Change attachment id to attachment path    
                    }  elseif ($key == "attachment_file") {
                        $xml_var .= "<".$key.">".($value ? EXTRAFILE_URL."/".$value : "")."</".$key.">\n";

                    //Change status id to status name    
                    } elseif ($key == "status" || $key == "listing_status") {

                        $statusObj = new ItemStatus();
                        $xml_var .= "<".$key.">".($statusObj->getName($value))."</".$key.">\n";

                    //Change level id to level name    
                    } elseif ($key == "level" || $key == "listing_level") {
                        if ($lowerModule == "promotion"){
                            $strLevelObj = "ListingLevel"; 
                        } else {
                            $strLevelObj = ucfirst($lowerModule)."Level";
                        }
                        $levelObj = new $strLevelObj();
                        $xml_var .= "<".$key.">".($levelObj->getName($value))."</".$key.">\n";
                    }

                    //Change promotion id to promotion name
                    elseif ($key == "promotion_id") {
                        $promotionObj = new Promotion($value);
                        $xml_var .= "<promotion>".($promotionObj->getString("name"))."</promotion>\n";
                    }

                    //Change category id to category name
                    elseif ($key == "cat_1_id" || $key == "cat_2_id" || $key == "cat_3_id" || $key == "cat_4_id" || $key == "cat_5_id" || string_strpos($key, "parcat_") !== false) {
                        $strCategObj = ucfirst($lowerModule)."Category";
                        $categObj = new $strCategObj($value);
                        $xml_var .= "<".str_replace("_id", "", $key).">".($categObj->getString("title"))."</".str_replace("_id", "", $key).">\n";
                    }

                    //Add a new line for "deals done"
                    elseif ($lowerModule == "promotion" && $key == "amount"){
                        $xml_var .= "<".$key.">".($value)."</".$key.">\n";
                        unset($promotion);
                        $promotion = new Promotion($itens[$i]["id"]);
                        $promotionDeals = $promotion->getDealInfo();
                        $xml_var .= "<deals_done>".($promotionDeals["sold"])."</deals_done>\n";
                    }

                    else {
                        $xml_var .= "<".$key.">".($value)."</".$key.">\n";
                    }
                }

                //Get listing id to add info about listing categories later
                if (($lowerModule == "listing" && $key == "id") || ($lowerModule == "promotion" && $key == "listing_id")){
                    $listing_id = $value;
                }            

                //Get post id to add info about post categories later
                if ($lowerModule == "blog" && $key == "id"){
                    $post_id = $value;
                }
            }

            //Add info about listing categories
            if (($lowerModule == "listing" || $lowerModule == "promotion")  && $listing_id){
                unset($listingObj);
                $listingObj = new Listing($listing_id);
                $listCategs = $listingObj->getCategories(false, false, false, true, true);
                $categCount = 0;
                if ($lowerModule == "promotion"){
                    $fieldLabel = "listing_category_";
                } else {
                    $fieldLabel = "category_";
                }
                if ($listCategs) {
                    foreach ($listCategs as $listCateg) {
                        $categCount++;
                        $xml_var .= "<$fieldLabel".$categCount.">".($listCateg->getString("title"))."</$fieldLabel".$categCount.">\n";
                    }
                }
                $categCount++;
                for ($j = $categCount; $j<= LISTING_MAX_CATEGORY_ALLOWED; $j++){
                    $xml_var .= "<$fieldLabel".$j."></$fieldLabel".$j.">\n"; 
                }
            }

            //Add info about post categories
            if ($lowerModule == "blog" && $post_id){
                unset($postObj);
                $postObj = new Post($post_id);
                $postCategs = $postObj->getCategories(false, false, false, true, true);
                $categCount = 0;
                if ($postCategs) {
                    foreach ($postCategs as $postCateg) {
                        $categCount++;
                        $xml_var .= "<category_".$categCount.">".($postCateg->getString("title"))."</category".$categCount.">\n";
                    }
                }
                $categCount++;
                for ($j = $categCount; $j<= MAX_CATEGORY_ALLOWED; $j++){
                    $xml_var .= "<category_".$j."></category_".$j.">\n"; 
                }

            }

            $xml_var .= "</".($lowerModule == "blog" ? "post" : $lowerModule)."_info>\n";

        }

        $xml_var .= "</$xmlSection>\n";


        if (API_USE_JSON) {
            header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", FALSE);
            header("Pragma: no-cache");
            header("Content-Type: application/json; charset=".EDIR_CHARSET, TRUE);
            echo json_encode(simplexml_load_string($xml_var));    
        } else {
            echo $xml_var;
        }

    } else {
        echo $errors;
    }
    
?>