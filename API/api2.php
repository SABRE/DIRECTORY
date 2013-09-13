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
    # * FILE: /API/api2.php
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
    $aux_results_per_page = 25;
    
    extract($_GET);
    
    //Check if API is enabled
    if ($edirectory_api_enabled == "on"){
        
        unset($errors);
        //Validate API key
        if ($edirectory_api_key != $key){
            $errors .= system_showText(LANG_API_INVALIDKEY)."<br />";
        }

        
        // Validate parameters
        if($resource && !$errors){
            
              
            unset($aux_fields, $auxTable, $auxWhere, $aux_results, $aux_returnArray,$items);
            $_GET["module"] 			= $resource;
            $_GET["aux_results_per_page"] 	= $aux_results_per_page;
            
            $aux_returnArray = array();
            $aux_fields = array();
            $auxTable = "";
            $aux_Where = ""; 
            
            if($resource == "listing"){
                
                // Label = value (field on DB);
                $aux_fields["listing_ID"]   = "id";
                $aux_fields["name"]         = "title";
                $aux_fields["has_deal"]     = "promotion_id";
                $aux_fields["address"]      = "address";
                $aux_fields["address2"]     = "address2";
                $aux_fields["rate"]         = "avg_review";
                $aux_fields["imageurl"]     = "image_id";
                $aux_fields["phonenumber"]  = "phone";
                $aux_fields["latitude"]     = "latitude";
                $aux_fields["longitude"]    = "longitude";
                $aux_fields["longitude"]    = "longitude";
                //$aux_fields["distance"]     = "distance_score";
                
      //          $aux_fields["total_reviews"]= "(select count(0) from Review where item_type='listing' and item_id = Listing_Summary.id) as total_reviews";
                
                $aux_orderBy[] = "level";
                $aux_orderBy[] = "title";
                
                
                Listing::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
                
                
            }elseif($resource == "listing_category"){
                
                ListingCategory::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
                $aux_orderBy[] = "title";
                
            }elseif($resource == "review" && $type && $id){
                
                unset($reviewObj);
                $reviewObj = new Review();
                
                $reviewObj->item_id     = $id;
                $reviewObj->item_type   = $type;
                
                $reviewObj->GetInfoToApp($_GET, $aux_returnArray,$items);
                                
            }elseif($resource == "event"){
                
                $aux_fields["event_ID"]     = "id";
                $aux_fields["name"]         = "title";
                $aux_fields["address"]      = "address";
                if($searchBy == "calendarList"){
                    $aux_fields["image_id"]     = "image_id";
                }else{
                    $aux_fields["imageurl"]     = "image_id";
                }
                $aux_fields["phonenumber"]  = "phone";
                $aux_fields["latitude"]     = "latitude";
                $aux_fields["longitude"]    = "longitude";
                $aux_fields["longitude"]    = "longitude";
                $aux_fields["start_date"]   = "start_date";
                $aux_fields["end_date"]     = "end_date";
                $aux_fields["start_time"]   = "start_time";
                $aux_fields["end_time"]     = "end_time";
                
                $aux_orderBy[] = "level";
                $aux_orderBy[] = "title";
                
                if($searchBy == "calendar"){
                    
                    $arrayCalendar = Event::EventsDay($year,$month);
                    
                }else{
                    Event::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                }
                
                
            }elseif($resource == "event_category"){
                
                EventCategory::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
                $aux_orderBy[] = "title";
                
            }elseif($resource == "classified"){
                
                $aux_fields["classified_ID"]= "id";
                $aux_fields["name"]         = "title";
                $aux_fields["address"]      = "address";
                $aux_fields["address2"]     = "address2";
                $aux_fields["imageurl"]     = "image_id";
                $aux_fields["phonenumber"]  = "phone";
                $aux_fields["latitude"]     = "latitude";
                $aux_fields["longitude"]    = "longitude";
                $aux_fields["longitude"]    = "longitude";
                $aux_fields["price"]        = "classified_price";
                
                $aux_orderBy[] = "level";
                $aux_orderBy[] = "title";
                
                
                Classified::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
            }elseif($resource == "classified_category"){
                
                ClassifiedCategory::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
                $aux_orderBy[] = "title";
                
            }elseif($resource == "article"){
                
                $aux_fields["article_ID"]       = "id";
                $aux_fields["name"]             = "title";
                $aux_fields["author"]           = "author";
                $aux_fields["imageurl"]         = "image_id";
                $aux_fields["publication_date"] = "publication_date";
                $aux_fields["avg_review"]       = "avg_review";
                
                $aux_orderBy[] = "level";
                $aux_orderBy[] = "title";
                
                
                Article::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
            }elseif($resource == "article_category"){
                
                ArticleCategory::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
                $aux_orderBy[] = "title";
                
            }elseif($resource == "deal"){
                
                $aux_fields["deal_ID"]              = "id";
                $aux_fields["name"]                 = "name";
                $aux_fields["imageurl"]             = "image_id";
                if($searchBy && ($searchBy != "map")){
                    $aux_fields["listing_title"]    = "(select title from Listing_Summary where Listing_Summary.id=listing_id) as listing_title";
                }else{
                    $aux_fields["listing_title"]    = "(select title from Listing_Summary where Listing_Summary.id=listing_id)";
                }
                $aux_fields["listing_latitude"]     = "listing_latitude";
                $aux_fields["listing_longitude"]    = "listing_longitude";
                $aux_fields["avg_review"]           = "avg_review";
                $aux_fields["realvalue"]            = "realvalue";
                $aux_fields["dealvalue"]            = "dealvalue";
                $aux_fields["total_amount"]         = "amount";
                if($searchBy){
                    $aux_fields["amount"]       = "amount";
                }else{
                    $aux_fields["amount"]       = "(select count(id) from Promotion_Redeem where Promotion_Redeem.promotion_id=Promotion.id)";
                }
                
                $aux_orderBy[] = "listing_level";
                $aux_orderBy[] = "name";
                
                
                Promotion::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
            }elseif($resource == "deal_category"){
                
                ListingCategory::GetInfoToApp($_GET,$aux_returnArray,$aux_fields,$items,$auxTable,$aux_Where);
                
                $aux_orderBy[] = "title";
                
            }else{
                echo "Invalid Resource";
                die();
            }
            

            if(is_array($aux_fields) || $items || is_array($arrayCalendar)){


                if(!$items && !array_key_exists('error', $aux_returnArray) && (!$arrayCalendar && $searchBy != "calendar")){
                   /*
                    * Preparing SQL
                    */
                   $db = db_getDBObject();
                   
                   /**
                    * Counting results
                    */
                   $sql_count = "select 0 from ".$auxTable." where ".implode(" and ",$aux_Where);
                   $aux_total_results = $db->query($sql_count);
                   if(mysql_num_rows($aux_total_results)){
                        
                        $aux_returnArray["type"]            = $resource;
                        $aux_returnArray["total_results"]   = mysql_num_rows($aux_total_results); 
                        $aux_returnArray["total_pages"]     = ceil(mysql_num_rows($aux_total_results) / $aux_results_per_page); 
                        $aux_returnArray["results_per_page"]= $aux_results_per_page; 
                       
                       
                        /*
                         * Number fields
                         */
                        unset($number_fields);
                        $number_fields[] = "latitude";
                        $number_fields[] = "longitude";
                        $number_fields[] = "level";
                        $number_fields[] = "avg_review";
                        $number_fields[] = "id";
                        $number_fields[] = "promotion_id";
                        $number_fields[] = "category_id";
                        $number_fields[] = "count_sub";
                        $number_fields[] = "active_listing";
                        
                        $sql = "select ".implode(",",$aux_fields)." from ".$auxTable." where ".implode(" and ",$aux_Where)." ".(is_array($aux_orderBy) ? " order by ".implode(", ",$aux_orderBy) : "" )." limit ".($page ? (($page-1) * $aux_results_per_page)."," : "").$aux_results_per_page;                     
                        
                        //echo $sql;
                        //die();
                        
                        $aux_results = $db->query($sql);
                        if(mysql_num_rows($aux_results) > 0){

                            $i = 0;
                      
                            while($aux_row = mysql_fetch_assoc($aux_results)){

                                unset($array_results);
                                foreach($aux_row as $DB_field => $value){

                                    if($DB_field == "image_id"){
                                        unset($imageObj);
                                        $imageObj = new Image($value);
                                        if($imageObj->imageExists()){
                                            $value = $imageObj->getPath();
                                        }else{
                                            $value = NULL;
                                        }
                                    }

                                    $array_results[array_search($DB_field, $aux_fields)] = ((is_numeric($value) && in_array($DB_field,$number_fields))? (float)$value : $value);

                                }

                                $aux_returnArray["results"][$i] = $array_results;
                                $i++;

                            }
                            


                        }else{
                			$aux_returnArray["error"]			= "No results found.";
            	       		$aux_returnArray["type"]            = $resource;
        	        		$aux_returnArray["total_results"]   = 0; 
    	    		        $aux_returnArray["total_pages"]     = 0; 
			                $aux_returnArray["results_per_page"]= $aux_results_per_page;     
                        }
                       
                       
                   }else{
                   
                   		$aux_returnArray["error"]			= "No results found.";
                   		$aux_returnArray["type"]            = $resource;
                		$aux_returnArray["total_results"]   = 0; 
        		        $aux_returnArray["total_pages"]     = 0; 
		                $aux_returnArray["results_per_page"]= $aux_results_per_page; 

                   	
                   }
                    
                }else{
                    
                    if(is_array($items)){
                        
                        unset($aux_items);
                        for($i=0;$i<count($items);$i++){     
                            
                            $aux_array_items = $items[$i];
                            foreach($aux_array_items as $aux_key=>$aux_value){
                                
                                if($aux_key == "image_id" || ($aux_key == "imageurl" && is_numeric($aux_value))){
                                    unset($imageObj);
                                    $imageObj = new Image($aux_value);
                                    if($imageObj->imageExists()){
                                        $img_url = $imageObj->getPath();
                                    }else{
                                        $img_url = NULL;
                                    }
                                    if($id){
                                        $items[$i]["imageurl"] = $img_url;
                                    }else{
                                        $aux_items[$i]["imageurl"] = $img_url;
                                    }
                                    
                                    
                                }elseif($aux_key == "distance_score"){
                                    if($id){
                                        $items[$i]["distance_score"] = round($aux_value,2)." ".ZIPCODE_UNIT;
                                    }else{
                                        $aux_items[$i]["distance_score"] = round($aux_value,2)." ".ZIPCODE_UNIT;
                                    }
                                }else{
                                    if($id){
                                        $items[$i][array_search($aux_key, $aux_fields)] = (is_numeric($aux_value) ? (float)$aux_value : $aux_value);    
                                    }else{
                                        $aux_items[$i][array_search($aux_key, $aux_fields)] = (is_numeric($aux_value) ? (float)$aux_value : $aux_value);
                                    }
                                    
                                }
                                
                            }
                            if($id){
                                $aux_returnArray["results"][$i] = $items[$i];
                            }else{
                                $aux_returnArray["results"][$i] = $aux_items[$i];
                            }
                            
                            
                        }
                 
                    }else{
                    	$aux_returnArray["error"]			= "No results found.";
                   		$aux_returnArray["type"]            = $resource;
                		$aux_returnArray["total_results"]   = 0; 
        		        $aux_returnArray["total_pages"]     = 0; 
		                $aux_returnArray["results_per_page"]= $aux_results_per_page; 
					}
                    
                }
                
                if(is_array($aux_returnArray) || is_array($arrayCalendar)){
                    
                    header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
                    header("Cache-Control: no-store, no-cache, must-revalidate");
                    header("Cache-Control: post-check=0, pre-check=0", FALSE);
                    header("Pragma: no-cache");
                    header("Content-Type: application/json; charset=".EDIR_CHARSET, TRUE);
                    if(is_array($arrayCalendar)){
                        echo json_encode($arrayCalendar);    
                    }else{
                        echo json_encode($aux_returnArray);    
                    }
                    
                    
                }
                
            }

        }else{
            echo "Please check your parameters";
        }
        
        
    }else{
        echo "API disabled";
        die();
    }
    ?>