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
	# * FILE: /functions/search_funct.php
	# ----------------------------------------------------------------------------------------------------

	function search_frontListingSearch($search_for, $section, $mobile = false) {
        
      
		$searchReturn["select_columns"] = false;
		$searchReturn["from_tables"]    = false;
		$searchReturn["where_clause"]   = false;
		$searchReturn["group_by"]       = false;
		$searchReturn["order_by"]       = false;

		$orderByConf =  array("characters",
							"lastupdate",
							"datecreated",
							"popular",
							"rating");
        
        if (LISTING_ORDERBY_PRICE) {
            array_unshift($orderByConf, "price");
        }
			
        $selecId = false;

        if ($section == "listing_results_api") {
            $section = "listing_results";
            $selecId = true;
        }
		
        $user_order_by = "";
		if (in_array($_GET["orderby"], $orderByConf)) {
			$user_order_by = $_GET["orderby"];
		}

		if (($section == "listing") || ($section == "mobile")) {
			$searchReturn["select_columns"] = (FORCE_SECOND ? "Listing_Summary.*" : "Listing.*");
		} else if ($section == "random") {
			if ($mobile) {
				$searchReturn["select_columns"] = (FORCE_SECOND ? "Listing_Summary.*" : "Listing.*");
			} else {
				$searchReturn["select_columns"] = (FORCE_SECOND ? "Listing_Summary.id, Listing_Summary.title, Listing_Summary.description, Listing_Summary.friendly_url, Listing_Summary.account_id, Listing_Summary.thumb_id " : "Listing.*");
			}
			
		} else if ($section == "listing_results") {

			/*
			 * Get fields of Listing Table
			 */
			$db = db_getDBObject();
			$sql_fields = "DESC Listing_Summary";
			$result = $db->query($sql_fields);
			if (mysql_num_rows($result) > 0) {
				$aux_fields_array = array();
				while ($row = mysql_fetch_assoc($result)) {
					$aux_fields_array[] = "Listing_Summary.".$row["Field"];
				}
				if (count($aux_fields_array) > 0) {
					$listing_fields = implode(",",$aux_fields_array);
				} else {
					$listing_fields = "Listing_Summary.*";
				}
			} else {
				$listing_fields = "Listing_Summary.*";
			}
			
            if ($selecId) {
                $listing_fields = "Listing_Summary.id";
            }
            
			$searchReturn["select_columns"] = $listing_fields;
			
            if (SEARCH_FORCE_BOOLEANMODE == "on") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Listing_Summary.title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
            }

		} elseif ($section == "count") {
			$searchReturn["select_columns"] = "COUNT(DISTINCT(Listing_Summary.id))";
		} elseif ($section == "rss") {
			$searchReturn["select_columns"] = (FORCE_SECOND ? "Listing_Summary.id" : "Listing.id");"Listing.id";
			$searchReturn["from_tables"] = (FORCE_SECOND ? "Listing_Summary" : "Listing");
		}

		if (($section == "listing") || ($section == "mobile")) {
			$searchReturn["from_tables"] = (FORCE_SECOND ? "Listing_Summary" : "Listing");
		} elseif ($section == "count") {
			$searchReturn["from_tables"] = "Listing_Summary";
		} else if ($section == "random") {
			$searchReturn["from_tables"] = "Listing_FeaturedTemp
											LEFT JOIN ".(FORCE_SECOND ? "Listing_Summary Listing_Summary" : "Listing Listing")." ON (".(FORCE_SECOND ? "Listing_Summary.id" : "Listing.id")." = Listing_FeaturedTemp.listing_id)";
		} else if ($section == "listing_results") {
			$searchReturn["from_tables"] = "Listing_Summary";
		}
		
		if (isset($search_for["id"]) && is_numeric($search_for["id"])) {
			if ($section == "listing_results") {
				$where_clause[] = "Listing_Summary.id = ".$search_for["id"]."";
			} else {
				$where_clause[] = (FORCE_SECOND ? "Listing_Summary" : "Listing").".id = ".$search_for["id"]."";
			}
		}

        if (isset($search_for["except_id"])) {
			if ($section == "listing_results") {
				$where_clause[] = "Listing_Summary.id NOT IN (".$search_for["except_id"].") ";
			} else {
				$where_clause[] = (FORCE_SECOND ? "Listing_Summary" : "Listing").".id NOT IN (".$search_for["except_id"].")";
			}
		}
      
		if (USING_THEME_TEMPLATE && THEME_TEMPLATE_ID > 0) {
			unset($aux_table_name);
			if ($section == "listing_results") {
				$aux_table_name = "Listing_Summary";
			} else {
				$aux_table_name = (FORCE_SECOND ? "Listing_Summary" : "Listing");
			}
            
			for ($i=0; $i<10; $i++) {
				if ($search_for["checkbox".$i] == "y") $where_clause[] = $aux_table_name.".custom_checkbox".$i." = ".db_formatString($search_for["checkbox".$i])."";
				if ($search_for["dropdown".$i]) $where_clause[] = $aux_table_name.".custom_dropdown".$i." = ".db_formatString($search_for["dropdown".$i])."";
				if ($search_for["text".$i]) $where_clause[] = $aux_table_name.".custom_text".$i." LIKE ".db_formatString("%".$search_for["text".$i]."%")."";
				if ($search_for["fromtext".$i]) $where_clause[] = "CAST(".$aux_table_name.".custom_text".$i." AS SIGNED INTEGER) >= CAST(".db_formatNumber($search_for["fromtext".$i])." AS SIGNED INTEGER)";
				if ($search_for["totext".$i]) $where_clause[] = "CAST(".$aux_table_name.".custom_text".$i." AS SIGNED INTEGER) <= CAST(".db_formatNumber($search_for["totext".$i])." AS SIGNED INTEGER)";
				if ($search_for["short_desc".$i]) $where_clause[] = $aux_table_name.".custom_short_desc".$i." LIKE ".db_formatString("%".$search_for["short_desc".$i]."%")."";
				if ($search_for["long_desc".$i]) $where_clause[] = $aux_table_name.".custom_long_desc".$i." LIKE ".db_formatString("%".$search_for["long_desc".$i]."%")."";
			}
		}

		if (!$search_for["category_id"]) {
			if ($section == "listing_results") {
				$where_clause[] = "Listing_Summary.status = 'A'";
			} elseif($section == "random") {
				$where_clause[] = (FORCE_SECOND ? "Listing_Summary" : "Listing").".status = 'A'";
			} else {
				$where_clause[] = "Listing_Summary.status = 'A'";
			}
		}
		
		if ($search_for["account"]) {
			if ($section == "listing_results") {
				$where_clause[] = "Listing_Summary.account_id = ".$search_for["account"];
			} else {
				$where_clause[] = (FORCE_SECOND ? "Listing_Summary" : "Listing").".account_id = ".$search_for["account"];
			}
		}

		if ($search_for["category_id"]) {
            //Create a category object to get hierarchy of categories
			unset($aux_categoryObj,$aux_cat_hierarchy);
			$aux_categoryObj = new ListingCategory($search_for["category_id"]);
			$aux_cat_hierarchy = $aux_categoryObj->getHierarchy($search_for["category_id"],false,true);
			if ($aux_cat_hierarchy) {
				$listing_ids = '0';
				if (($section == "listing_results") || ($section == "mobile")) {
					unset($listing_CategoryObj);
					$listing_CategoryObj = new Listing_Category();
					$listing_ids = $listing_CategoryObj->getListingsByCategoryHierarchy($aux_categoryObj->root_id,$aux_categoryObj->left,$aux_categoryObj->right,$search_for["letter"]);
					$total_listings_ids = $listing_CategoryObj->total_listings;
				}				
					
				$where_clause[] = ($section == "listing_results" ? "Listing_Summary" : "Listing_Summary").".id in (".$listing_ids.")";
				$searchReturn["total_listings"] = $total_listings_ids;	
			}
           
		}

		$_locations = explode(",", EDIR_LOCATIONS);
		$latLoc=false;
		$longLoc=false;
		
		foreach($_locations as $_location_level)
		{
			if (is_numeric($search_for["location_".$_location_level]))
			{
				$sql_location[] = ($section == "listing_results" ? "Listing_Summary" : "Listing").".location_".$_location_level." = ".$search_for["location_".$_location_level]."";
				if(($_location_level==3||$_location_level==4)&& $search_for["dist_loc"]!="")
				{
					unset($locLevel);
					unset($locObj);
					$locLevel = "Location".$_location_level;
					$locObj = new $locLevel($search_for["location_".$_location_level]);
					$latLoc = $locObj->getNumber('latitude'); 
					$longLoc = $locObj->getNumber('longitude'); 
				}
			}
		}
		

		if (($search_for["keyword"]) && ($section != "mobile")) {
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Listing_Summary.fulltextsearch_keyword";
			$where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
		}

		if (($search_for["keyword"]) && ($section == "mobile")) {
			$search_for["where"] = $search_for["keyword"];
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = (($section == "listing_results" || $section == "count" || FORCE_SECOND) ? "Listing_Summary" : "Listing").".fulltextsearch_keyword";
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = (($section == "listing_results" || $section == "count" || FORCE_SECOND) ? "Listing_Summary" : "Listing").".fulltextsearch_where";
			$where_clause[] = "(".search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2")." OR ".search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2").")";
		}

		if ($search_for["zip"]) {
			$search_for["zip"] = str_replace("\\", "", $search_for["zip"]);
			$search_for["dist"] = str_replace("\\", "", $search_for["dist"]);
			if (ZIPCODE_PROXIMITY == "on" && $search_for["dist"]) {
				if (zipproximity_getWhereZipCodeProximity($search_for["zip"], $search_for["dist"], $whereZipCodeProximity, $order_by_zipcode_score)) {
					$where_clause[] = $whereZipCodeProximity;
					if ($order_by_zipcode_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_zipcode_score;
					}
				} else {
					$where_clause[] = ($section == "listing_results"  || FORCE_SECOND ? "Listing_Summary" : "Listing").".zip_code = ".db_formatString($search_for["zip"])."";
				}
			} else {
				$where_clause[] = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".zip_code = ".db_formatString($search_for["zip"])."";
			}
		}
		
		if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc) {
			//whereProximitySearch...
			$search_for["dist_loc"] = str_replace("\\", "", $search_for["dist_loc"]);
			if ($search_for["dist_loc"]) {
				if (zipproximity_getWhereZipCodeProximity(false, $search_for["dist_loc"], $whereLocationProximity, $order_by_location_score,$latLoc,$longLoc,false,true)) {
					$where_clause[] = $whereLocationProximity;
					if ($order_by_location_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_location_score;
					}
				} 
			}
        }
        
		if ($sql_location && $latLoc==false && $longLoc==false) {
			$where_clause[] = "(".implode(" AND ", $sql_location).")";
        }
        
		if (($search_for["where"]) && ($section != "mobile") && $latLoc==false && $longLoc==false) {
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = (($section == "listing_results" || $section == "count" || FORCE_SECOND) ? "Listing_Summary" : "Listing").".fulltextsearch_where";
			$where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
		}

		if ($where_clause && (count($where_clause) > 0)) {
			$searchReturn["where_clause"] = implode(" AND ", $where_clause);
			if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc)
				$searchReturn["where_clause"] .= "OR (".implode(" AND ", $sql_location).")";
		}
    
		if ($user_order_by == "characters") {
			$user_order_by = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".title";
		} elseif ($user_order_by == "lastupdate") {
			$user_order_by = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".updated DESC";
		} elseif ($user_order_by == "datecreated") {
			$user_order_by = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".entered DESC";
		} elseif ($user_order_by == "popular") {
			$user_order_by = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".number_views DESC";
		} elseif ($user_order_by == "rating") {
			$user_order_by = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".avg_review DESC";
		} elseif ($user_order_by == "price") {
            $themeSummaryFields = unserialize(TEMPLATE_SUMMARY_FIELDS);
            $user_order_by = "CAST(".($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".".$themeSummaryFields["price_field"]." AS decimal(10,2))";
		}
        
        if (($section == "listing") || ($section == "mobile")) {
		  $searchReturn["order_by"] = ($order_by_keyword_score2 ? "keyword_score DESC, " : "").($section == "listing_results" || $section == "mobile" || FORCE_SECOND ? "Listing_Summary" : "Listing").".level, ".($section == "listing_results" || FORCE_SECOND  || $section == "mobile" ? "Listing_Summary" : "Listing").".random_number DESC, ".($section == "listing_results" || FORCE_SECOND  || $section == "mobile" ? "Listing_Summary" : "Listing").".title, ".($section == "listing_results" || FORCE_SECOND  || $section == "mobile" ? "Listing_Summary" : "Listing").".id";
		} elseif ($section == "rss") {
			$searchReturn["order_by"] = ($order_by_keyword_score2 ? "keyword_score DESC, " : "").($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".level, ".($section == "listing_results" || FORCE_SECOND  ? "Listing_Summary" : "Listing").".title, ".($section == "listing_results" || FORCE_SECOND  ? "Listing_Summary" : "Listing").".id";
		} elseif ($section == "listing_results") {
			if (LISTING_SCALABILITY_OPTIMIZATION == "on") {
				$searchReturn["order_by"] = ($user_order_by ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" ? "reg_exp_order DESC, " : "").($order_by_keyword_score2 ? "keyword_score DESC, " : "").(BACKLINK_FEATURE == "on" ? (FORCE_SECOND ? "Listing_Summary.backlink DESC, " : "Listing.backlink DESC, ") : "").($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".level, ".($section == "listing_results" || FORCE_SECOND  ? "Listing_Summary" : "Listing").".title";
			} else {
				$searchReturn["order_by"] = ($user_order_by ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" ? "reg_exp_order DESC, " : "").($order_by_keyword_score2 ? "keyword_score DESC, " : "").(BACKLINK_FEATURE == "on" ? (FORCE_SECOND ? "Listing_Summary.backlink DESC, " : "Listing.backlink DESC, ") : "").($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".level, ".($section == "listing_results" || FORCE_SECOND  ? "Listing_Summary" : "Listing").".random_number DESC, ".($section == "listing_results" || FORCE_SECOND  ? "Listing_Summary" : "Listing").".title, ".($section == "listing_results" || FORCE_SECOND  ? "Listing_Summary" : "Listing").".id";
			}
		} elseif ($section == "random") {
			$searchReturn["order_by"] = ((LISTING_SCALABILITY_OPTIMIZATION == "on")?("Listing_FeaturedTemp.random_number"):("RAND()"));
		} elseif ($section == "count") {
			$searchReturn["order_by"] = ($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing_Summary").".id";
		}

		if ($search_for["keyword"] && $order_by_keyword_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_keyword_score.($order_by_keyword_score2 ? ", ".$order_by_keyword_score2 : "" );
		}

		if ($search_for["where"] && $order_by_where_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_where_score;
		}

        if ((($search_for["keyword"] && $order_by_keyword_score) || ($search_for["where"] && $order_by_where_score) || ($search_for["zip"] && $order_by_zipcode_score) || ($search_for["dist_loc"] && $order_by_location_score && $longLoc && $latLoc)) && ($section != "count") && ($section != "random")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "listing_results" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "listing_results" ? "reg_exp_order DESC, " : "").($section == "listing_results" && BACKLINK_FEATURE == "on" ? (FORCE_SECOND ? "Listing_Summary.backlink DESC, " : "Listing.backlink DESC, ") : "").($section == "listing_results" || FORCE_SECOND ? "Listing_Summary" : "Listing").".level".($order_by_keyword_score2 ? ", keyword_score DESC" : "");
            if ($order_by_zipcode_score) {
                $searchReturn["order_by"] .= ", zipcode_score";
            }
            
        	if ($order_by_location_score) {
                $searchReturn["order_by"] .= ", location_score";
            }
            
            if ($order_by_keyword_score) {
                if ($order_by_keyword_score2){
                    $searchReturn["order_by"] .= ", keyword_score2 DESC";
                } else {
                    $searchReturn["order_by"] .= ", keyword_score DESC";
                }
            }
            if ($order_by_where_score) {
                $searchReturn["order_by"] .= ", where_score DESC";
            }
        }

		return $searchReturn;
	}

	function search_frontPromotionSearch($search_for, $section, $soldout = false) {

        $searchReturn["select_columns"] = false;
        $searchReturn["from_tables"] = false;
        $searchReturn["where_clause"] = false;
        $searchReturn["group_by"] = false;
        $searchReturn["order_by"] = false;

        $orderByConf =  array("characters",
                                "lastupdate",
                                "datecreated",
                                "popular",
                                "rating"
                            );

        $user_order_by = "";
        if (in_array($_GET["orderby"], $orderByConf)) {
            $user_order_by = $_GET["orderby"];
        }

        if (($section == "promotion") || ($section == "random") || $section == "promotion_results") {
            $searchReturn["select_columns"] = "Promotion.*";
            if (SEARCH_FORCE_BOOLEANMODE == "on" && $section == "promotion_results") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Promotion.name REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
            }
        } elseif ($section == "count") {
            $searchReturn["select_columns"] = "COUNT(Promotion.id)";
        }

        $forceJoinListing = false;
        $_locations = explode(",", EDIR_LOCATIONS);
        foreach($_locations as $_location_level) {
            if (is_numeric($search_for["location_".$_location_level])) {
                $forceJoinListing = true;
                $sql_location[] = (($section == "promotion_results" || $section == "count" || $section == "random") ? "Listing_Summary" : "Listing").".location_".$_location_level." = ".$search_for["location_".$_location_level]."";
            	if(($_location_level==3||$_location_level==4)&& $search_for["dist_loc"]!="")
				{
					unset($locLevel);
					unset($locObj);
					$locLevel = "Location".$_location_level;
					$locObj = new $locLevel($search_for["location_".$_location_level]);
					$latLoc = $locObj->getNumber('latitude'); 
					$longLoc = $locObj->getNumber('longitude'); 
				}
            }
        }

        if ($forceJoinListing) {
            $searchReturn["from_tables"] = "Promotion INNER JOIN Listing_Summary Listing_Summary ON Listing_Summary.promotion_id = Promotion.id ";
        } else {
            $searchReturn["from_tables"] = "Promotion ";
        }

        if (isset($search_for["id"]) && is_numeric($search_for["id"]) && $search_for["id"] > 0) {
            if (!$search_for["keyword"] || $section == "count") {
                $where_clause[] = "Promotion.id = ".$search_for["id"]."";
            } else {
                $having_clause[] = "Promotion.id = ".$search_for["id"]."";
            }
        }

        if (isset($search_for["except_ids"])) {
            if (!$search_for["keyword"] || $section == "count") {
                $where_clause[] = "Promotion.id  NOT IN (".$search_for["except_ids"].") ";
            } else {
                $having_clause[] = "Promotion.id  NOT IN (".$search_for["except_ids"].") ";
            }
        }

        if (!$search_for["keyword"] || $section == "count") {
            $where_clause[] = "Promotion.listing_status = 'A'";
        } else {
            $having_clause[] = "Promotion.listing_status = 'A'";
        }

        if (!$search_for["keyword"] || $section == "count") {
            $where_clause[] = "Promotion.end_date >= DATE_FORMAT(NOW(), '%Y-%m-%d')";
            $where_clause[] = "Promotion.start_date <= DATE_FORMAT(NOW(), '%Y-%m-%d')";
        } else {
            $having_clause[] = "Promotion.end_date >= DATE_FORMAT(NOW(), '%Y-%m-%d')";
            $having_clause[] = "Promotion.start_date <= DATE_FORMAT(NOW(), '%Y-%m-%d')";
        }

        if ($soldout) {
            if (!$search_for["keyword"] || $section == "count") {
                $where_clause[] = "Promotion.amount > 0";
            } else {
                $having_clause[] = "Promotion.amount > 0";
            }
        }

        // RANGE OF HOURS
        if (!$search_for["id"]) {
            $visibility_start = date('H')*60+date('i');
            $visibility_end = date('H')*60+date('i');
            if (!$search_for["keyword"] || $section == "count") {
                $where_clause[] = " ((Promotion.visibility_start <= $visibility_start AND Promotion.visibility_end >= $visibility_end ) OR (Promotion.visibility_start = 24 AND Promotion.visibility_end = 24)) ";
            } else {
                $having_clause[] = " ((Promotion.visibility_start <= $visibility_start AND Promotion.visibility_end >= $visibility_end ) OR (Promotion.visibility_start = 24 AND Promotion.visibility_end = 24)) ";
            }
        }

        if (!$search_for["keyword"] || $section == "count") {
            $where_clause[] = "Promotion.listing_id > 0";
        } else {
            $having_clause[] = "Promotion.listing_id > 0";
        }

        if ($search_for["account"]) {
            if (!$search_for["keyword"] || $section == "count") {
                $where_clause[] = "Promotion.account_id = ".$search_for["account"];
            } else {
                $having_clause[] = "Promotion.account_id = ".$search_for["account"];
            }
        }

        $levelObj = new ListingLevel(true);
        $levels = $levelObj->getLevelValues();

        unset($allowed_levels);
        foreach ($levels as $each_level) {
            if ( ($levelObj->getActive($each_level) == 'y') && ($levelObj->getHasPromotion($each_level) == 'y' ) ) {
                $allowed_levels[] = $each_level;
            }
        }

        $search_levels = ($allowed_levels ? implode(",", $allowed_levels) : "0");

        if (!$search_for["keyword"] || $section == "count") {
            $where_clause[] = "Promotion.listing_level IN ($search_levels)";
        } else {
            $having_clause[] = "Promotion.listing_level IN ($search_levels)";
        }

        if ($search_for["category_id"]) {
            //Create a category object to get hierarchy of categories
            unset($aux_categoryObj, $aux_cat_hierarchy);
            $aux_categoryObj = new ListingCategory($search_for["category_id"]);
            $aux_cat_hierarchy = $aux_categoryObj->getHierarchy($search_for["category_id"], false, true);
            if ($aux_cat_hierarchy) {
                $listing_ids = '0';
                unset($listing_CategoryObj);
                $listing_CategoryObj = new Listing_Category();
                $listing_ids = $listing_CategoryObj->getListingsByCategoryHierarchy($aux_categoryObj->root_id,  $aux_categoryObj->left, $aux_categoryObj->right, $search_for["letter"]);
                $where_clause[] = "Promotion.listing_id in (".$listing_ids.")";
            }
        }

        //if ($sql_location) {
        //    $where_clause[] = "(".implode(" AND ", $sql_location).")";
        //}

        if (($search_for["keyword"]) && ($section != "mobile")) {
            $search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
            $search_for_keyword_fields_promotion[] = "Promotion.fulltextsearch_keyword";
            $where_clause[] = "(".search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields_promotion, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2").")";
        }

        /*if (($search_for["where"]) && ($section != "mobile")) {
            $search_for["where"] = str_replace("\\", "", $search_for["where"]);
            if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
                $search_for["where"] = str_replace(",", "", $search_for["where"]);
            }
            $search_for_where_fields[] = "Promotion.fulltextsearch_where";
            $where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
        }*/

        if (($search_for["keyword"]) && ($section == "mobile")) {
            $search_for["where"] = $search_for["keyword"];
            $search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
            $search_for_keyword_fields_promotion[] = "Promotion.fulltextsearch_keyword";
            $search_for_keyword_fields_listing[] = (($section == "promotion_results" || $section == "count" || $section == "random") ? "Listing_Summary" : "Listing").".fulltextsearch_keyword";
            $search_for["where"] = str_replace("\\", "", $search_for["where"]);
            if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
                $search_for["where"] = str_replace(",", "", $search_for["where"]);
            }
            $search_for_where_fields[] = (($section == "promotion_results" || $section == "count" || $section == "random") ? "Listing_Summary" : "Listing").".fulltextsearch_where";
            $where_clause[] = "((".search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields_promotion, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2")." OR ".
            search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields_listing, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2").") OR ".
            search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2").")";
        }

        if ($search_for["zip"]) {
            $search_for["zip"] = str_replace("\\", "", $search_for["zip"]);
            $search_for["dist"] = str_replace("\\", "", $search_for["dist"]);
            if (ZIPCODE_PROXIMITY == "on" && $search_for["dist"]) {
                if (zipproximity_getWhereZipCodeProximity($search_for["zip"], $search_for["dist"], $whereZipCodeProximity, $order_by_zipcode_score, false, false, true)) {
                    $where_clause[] = $whereZipCodeProximity;
                    if ($order_by_zipcode_score && ($section != "count") && ($section != "random")) {
                            $searchReturn["select_columns"] .= ", ".$order_by_zipcode_score;
                    }
                } else {
                    if (!$search_for["keyword"] || $section == "count") {
                        $where_clause[]     = "Promotion.listing_zipcode = ".db_formatString($search_for["zip"])."";
                    } else {
                        $having_clause[]    = "Promotion.listing_zipcode = ".db_formatString($search_for["zip"])."";
                    }
                }
            } else {
                if (!$search_for["keyword"] || $section == "count") {
                    $where_clause[] = "Promotion.listing_zipcode = ".db_formatString($search_for["zip"])."";
                } else {
                    $having_clause[] = "Promotion.listing_zipcode = ".db_formatString($search_for["zip"])."";
                }
            }
        }
        
		if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc) {
			//whereProximitySearch...
			$search_for["dist_loc"] = str_replace("\\", "", $search_for["dist_loc"]);
			if ($search_for["dist_loc"]) {
				if (zipproximity_getWhereZipCodeProximity(false, $search_for["dist_loc"], $whereLocationProximity, $order_by_location_score,$latLoc,$longLoc,true,true)) {
					$where_clause[] = $whereLocationProximity;
					if ($order_by_location_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_location_score;
					}
				} 
			}
        }
        
		if ($sql_location && $latLoc==false && $longLoc==false) {
			$where_clause[] = "(".implode(" AND ", $sql_location).")";
        }
        
		if (($search_for["where"]) && ($section != "mobile") && $latLoc==false && $longLoc==false) {
		    $search_for["where"] = str_replace("\\", "", $search_for["where"]);
            if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
                $search_for["where"] = str_replace(",", "", $search_for["where"]);
            }
            $search_for_where_fields[] = "Promotion.fulltextsearch_where";
            $where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
        }

		if ($where_clause && (count($where_clause) > 0)) {
			$searchReturn["where_clause"] = implode(" AND ", $where_clause);
			if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc)
				$searchReturn["where_clause"] .= "OR (".implode(" AND ", $sql_location).")";
		}

        if ($having_clause && (count($having_clause) > 0)) {
            $searchReturn["having_clause"] = implode(" AND ", $having_clause);
        }

        if ($user_order_by == "characters") {
            $user_order_by = "Promotion.name";
        } elseif ($user_order_by == "lastupdate") {
            $user_order_by = "Promotion.updated DESC";
        } elseif ($user_order_by == "datecreated") {
            $user_order_by = "Promotion.entered DESC";
        } elseif ($user_order_by == "popular") {
            $user_order_by = "Promotion.number_views DESC";
        } elseif ($user_order_by == "rating") {
            $user_order_by = "Promotion.avg_review DESC";
        } 

        if (($section == "promotion")) {
            $searchReturn["order_by"] = "Promotion.random_number DESC, Promotion.end_date, Promotion.name, Promotion.id";
        } elseif (($section == "promotion_results")) {
            $searchReturn["order_by"] = ($user_order_by ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" ? "reg_exp_order DESC, " : "")."Promotion.end_date, Promotion.random_number DESC, Promotion.name, Promotion.id";
        } elseif ($section == "random") {
            $searchReturn["order_by"] = ((PROMOTION_SCALABILITY_OPTIMIZATION == "on") ? ("random_number DESC") : ("RAND()"));
        } elseif ($section == "count") {
            $searchReturn["order_by"] = "Promotion.id";
        }

        if ($search_for["keyword"] && $order_by_keyword_score && ($section != "count") && ($section != "random")) {
            $searchReturn["select_columns"] .= ", ".$order_by_keyword_score.($order_by_keyword_score2 ? ", ".$order_by_keyword_score2 : "");
        }

        if ($search_for["where"] && $order_by_where_score && ($section != "count") && ($section != "random")) {
            $searchReturn["select_columns"] .= ", ".$order_by_where_score;
        }

        if ((($search_for["keyword"] && $order_by_keyword_score) || ($search_for["where"] && $order_by_where_score) || ($search_for["zip"] && $order_by_zipcode_score)) && ($section != "count") && ($section != "random")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "promotion_results" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "promotion_results" ? "reg_exp_order DESC, " : "")."Promotion.end_date";

            if ($order_by_zipcode_score) {
                if ($searchReturn["order_by"]) {
                    $searchReturn["order_by"] .= ", zipcode_score";
                } else {
                    $searchReturn["order_by"] .= "zipcode_score";
                }
            }
            if ($order_by_keyword_score) {
                if ($order_by_keyword_score2) {
                    if ($searchReturn["order_by"]) $searchReturn["order_by"] .= ", keyword_score DESC, keyword_score2 DESC";
                    else $searchReturn["order_by"] .= "keyword_score DESC, keyword_score2 DESC";
                } else {
                    if ($searchReturn["order_by"]) $searchReturn["order_by"] .= ", keyword_score DESC";
                    else $searchReturn["order_by"] .= "keyword_score DESC";
                }
            }
            if ($order_by_where_score) {
                if ($searchReturn["order_by"]) {
                    $searchReturn["order_by"] .= ", where_score DESC";
                } else {
                    $searchReturn["order_by"] .= "where_score DESC";
                }
            }
        }

        return $searchReturn;

	}

	function search_frontEventSearch($search_for, $section) {

		$searchReturn["select_columns"] = false;
		$searchReturn["from_tables"] = false;
		$searchReturn["where_clause"] = false;
		$searchReturn["group_by"] = false;
		$searchReturn["order_by"] = false;

		$orderByConf =  array("startdate",
							"characters",
							"lastupdate",
							"datecreated",
							"popular");

        $user_order_by = "";
		if (in_array($_GET["orderby"], $orderByConf)) {
			$user_order_by = $_GET["orderby"];
		}

		if (($section == "event") || ($section == "random") || ($section == "mobile")) {
			$searchReturn["select_columns"] = "Event.*";
            if (SEARCH_FORCE_BOOLEANMODE == "on" && $section == "event") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Event.title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
            }
		} elseif ($section == "count") {
			$searchReturn["select_columns"] = "COUNT(DISTINCT(Event.id))";
		} elseif ($section == "rss") {
			$searchReturn["select_columns"] = "Event.id";
		}

		$searchReturn["from_tables"] = "Event";

		if (isset($search_for["id"]) && is_numeric($search_for["id"]) && $search_for["id"] > 0) {
			$where_clause[] = "Event.id = ".$search_for["id"]."";
		}

		$where_clause[] = "Event.status = 'A'";
		if ($search_for["account"]) {
			$where_clause[] = "Event.account_id = ".$search_for["account"];
		}

		$withoutDate = true;

		if($search_for["single_month"]){
            $aux_month1 = preg_replace('/[^0-9]/', '', string_substr($search_for["single_month"],0,4));
			$aux_month2 = preg_replace('/[^0-9]/', '', string_substr($search_for["single_month"], 4, 2));
            $datePickedFull = $aux_month1."-".$aux_month2;
            
            $withoutDate = false;
            
            $where_clause[] = "DATE_FORMAT(Event.start_date, '%Y-%m') = '{$datePickedFull}'";
            
            if($search_for["day"]){
                $where_clause[] = "DATE_FORMAT(Event.start_date, '%d') >= '{$search_for["day"]}'";
            }
            
        }elseif (($search_for["this_date"]) && (!$search_for["month"])) {

			$aux_month1 = preg_replace('/[^0-9]/', '', string_substr($search_for["this_date"],0,4));
			$aux_month2 = preg_replace('/[^0-9]/', '', string_substr($search_for["this_date"], 4, 2));
			$aux_month3 = preg_replace('/[^0-9]/', '', string_substr($search_for["this_date"], 6));

			$datePickedFull = $aux_month1."-".$aux_month2."-".$aux_month3;
			if ($aux_month1 && $aux_month2 && $aux_month3) {
				$datePickedTimestamp = mktime(0,0,0,$aux_month2, $aux_month3, $aux_month1);
            }

			if ($datePickedTimestamp) {
				$datePickedDay = date('d', $datePickedTimestamp);
				$datePickedDayOfTheWeek = date('w', $datePickedTimestamp) + 1; //database does not follow ISO or numeric standard -- sunday=1, monday=2, tuesday=3, (...), saturday=7
			} else {
				$datePickedDay = date('d');
				$datePickedDayOfTheWeek = date('w') + 1; //database does not follow ISO or numeric standard -- sunday=1, monday=2, tuesday=3, (...), saturday=7
			}
			if ($aux_month1 && $aux_month2) {
				$datePickedWeekofMonth = ceil(($aux_month3 + date("w",mktime(0,0,0,$aux_month2,1,$aux_month1)))/7);
            }
			
			if ($datePickedTimestamp) {
				$datePickedMonth = date('m', $datePickedTimestamp);
			} else {
				$datePickedWeekofMonth = "''";
				$datePickedMonth = date('m');
			}

			$where_clause[] = "
								DATE_FORMAT(Event.start_date, '%Y-%m-%d') <= '{$datePickedFull}'
								AND
								(
									(
										Event.recurring = 'Y'
										AND (DATE_FORMAT(Event.until_date, '%Y-%m-%d') >= '{$datePickedFull}' OR DATE_FORMAT(Event.until_date, '%Y-%m-%d') = '0000-00-00')
										AND
										(
											(
												(
													Event.day = '$datePickedDay'
													AND (Event.week = 0 OR Event.week = '' OR Event.week IS NULL )
													AND (Event.dayofweek = 0 OR Event.dayofweek = '' OR Event.dayofweek IS NULL )
													AND (Event.month = 0 OR Event.month = '' OR Event.month IS NULL)
												)

												OR
												(
													Event.day = '$datePickedDay'
													AND (Event.week = 0 OR Event.week = '' OR Event.week IS NULL )
													AND (Event.dayofweek = 0 OR Event.dayofweek = '' OR Event.dayofweek IS NULL )
													AND (Event.month = '$datePickedMonth')
												)


												OR
												(
													LOCATE($datePickedDayOfTheWeek, Event.dayofweek) > 0
													AND (Event.day = 0 OR Event.day IS NULL OR Event.day = '')
													AND (Event.week = 0 OR Event.week = '' OR Event.week IS NULL )
													AND (Event.month = 0 OR Event.month = '' OR Event.month IS NULL )
												)

												OR
												(
                                                    LOCATE($datePickedWeekofMonth, Event.week) > 0
                                                    AND LOCATE($datePickedDayOfTheWeek,Event.dayofweek) > 0
                                                    AND (Event.month = 0 OR Event.month = '' OR Event.month IS NULL OR Event.month = '$datePickedMonth')
												)

												OR
												(
													Event.month = $datePickedMonth
													AND LOCATE($datePickedWeekofMonth, Event.week) > 0
													AND LOCATE($datePickedDayOfTheWeek, Event.dayofweek) > 0
												)
											)
											OR
											(
												(Event.day = 0 OR Event.day IS NULL OR Event.day = '')
												AND (Event.week = 0 OR Event.week = '' OR Event.week IS NULL )
												AND (Event.dayofweek = 0 OR Event.dayofweek = '' OR Event.dayofweek IS NULL )
												AND (Event.month = 0 OR Event.month = '' OR Event.month IS NULL )
											)
										)
									)
									OR (Event.recurring = 'N' AND DATE_FORMAT(Event.end_date, '%Y-%m-%d') >= '{$datePickedFull}')
								)";
			$withoutDate = false;
		}

		if ($search_for["month"] && !$search_for["single_month"]) {
			$aux_month1 = preg_replace('/[^0-9]/', '', string_substr($search_for["month"],4));
			$aux_month2 = preg_replace('/[^0-9]/', '', string_substr($search_for["month"],0,4));
			if ($aux_month1 && $aux_month2) {
				$monthPickedStartTimestamp = mktime(0, 0, 0, $aux_month1, 1, $aux_month2);
            }

			if ($aux_month1 && $aux_month2) {
				$monthPickedEndTimestamp = mktime(23, 59, 59, $aux_month1, date('t', $monthPickedStartTimestamp), $aux_month2);
            }

			if ($monthPickedStartTimestamp) {
				$monthPicked = date('m', $monthPickedStartTimestamp);
				$monthPickedStartFull = date('Y-m-d', $monthPickedStartTimestamp);
			} else {
				$monthPicked = date('m');
				$monthPickedStartFull = date('Y-m-d');
			}

			if ($monthPickedEndTimestamp) {
				$monthPickedEndFull = date('Y-m-d', $monthPickedEndTimestamp);
			} else {
				$monthPickedEndFull = date('Y-m-d');
			}

			$where_clause[] = "
								(

									DATE_FORMAT(Event.start_date, '%Y-%m-%d') <= '{$monthPickedEndFull}'
									AND
									(
										(
											(DATE_FORMAT(Event.until_date, '%Y-%m-%d') >= '{$monthPickedStartFull}' AND DATE_FORMAT(Event.until_date, '%Y-%m-%d') <> '0000-00-00')
											OR (DATE_FORMAT(Event.end_date, '%Y-%m-%d') >= '{$monthPickedStartFull}' AND DATE_FORMAT(Event.end_date, '%Y-%m-%d') <> '0000-00-00')
										)
										OR (DATE_FORMAT(Event.end_date, '%Y-%m-%d') = '0000-00-00' AND DATE_FORMAT(Event.until_date, '%Y-%m-%d') = '0000-00-00')
									)
									AND
									(
										(Event.recurring = 'N')
										OR (Event.month = 0 OR Event.month= ' ' OR Event.month IS NULL OR Event.month = '$monthPicked')
									)
								)";
			$withoutDate = false;
		}

		if ($withoutDate) {
			$where_clause[] = "((Event.end_date >= DATE_FORMAT(NOW(), '%Y-%m-%d') OR Event.until_date >= DATE_FORMAT(NOW(), '%Y-%m-%d') AND repeat_event = 'N') OR (repeat_event = 'Y'))";
		}

		if ($search_for["category_id"]) {
			$where_clause[] = "(Event.cat_1_id = ".$search_for["category_id"]." OR Event.parcat_1_level1_id = ".$search_for["category_id"]." OR Event.parcat_1_level2_id = ".$search_for["category_id"]." OR Event.parcat_1_level3_id = ".$search_for["category_id"]." OR Event.parcat_1_level4_id = ".$search_for["category_id"]." OR Event.cat_2_id = ".$search_for["category_id"]." OR Event.parcat_2_level1_id = ".$search_for["category_id"]." OR Event.parcat_2_level2_id = ".$search_for["category_id"]." OR Event.parcat_2_level3_id = ".$search_for["category_id"]." OR Event.parcat_2_level4_id = ".$search_for["category_id"]." OR Event.cat_3_id = ".$search_for["category_id"]." OR Event.parcat_3_level1_id = ".$search_for["category_id"]." OR Event.parcat_3_level2_id = ".$search_for["category_id"]." OR Event.parcat_3_level3_id = ".$search_for["category_id"]." OR Event.parcat_3_level4_id = ".$search_for["category_id"]." OR Event.cat_4_id = ".$search_for["category_id"]." OR Event.parcat_4_level1_id = ".$search_for["category_id"]." OR Event.parcat_4_level2_id = ".$search_for["category_id"]." OR Event.parcat_4_level3_id = ".$search_for["category_id"]." OR Event.parcat_4_level4_id = ".$search_for["category_id"]." OR Event.cat_5_id = ".$search_for["category_id"]." OR Event.parcat_5_level1_id = ".$search_for["category_id"]." OR Event.parcat_5_level2_id = ".$search_for["category_id"]." OR Event.parcat_5_level3_id = ".$search_for["category_id"]." OR Event.parcat_5_level4_id = ".$search_for["category_id"].")";
		}

		$_locations = explode(",", EDIR_LOCATIONS);
		foreach($_locations as $_location_level)
		{
			if (is_numeric($search_for["location_".$_location_level]))
			{
				$sql_location[] = " Event.location_".$_location_level." = ".$search_for["location_".$_location_level]."";
				if(($_location_level==3||$_location_level==4)&& $search_for["dist_loc"]!="")
				{
					unset($locLevel);
					unset($locObj);
					$locLevel = "Location".$_location_level;
					$locObj = new $locLevel($search_for["location_".$_location_level]);
					$latLoc = $locObj->getNumber('latitude'); 
					$longLoc = $locObj->getNumber('longitude'); 
				}
			}
		}
		//if ($sql_location) {
		//	$where_clause[] = "(".implode(" AND ", $sql_location).")";
        //}

		if (($search_for["keyword"]) && ($section != "mobile")) {
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Event.fulltextsearch_keyword";
			$where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
		}

		/*if (($search_for["where"]) && ($section != "mobile")) {
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Event.fulltextsearch_where";
			$where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
		}*/
		

		if (($search_for["keyword"]) && ($section == "mobile")) {
			$search_for["where"] = $search_for["keyword"];
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Event.fulltextsearch_keyword";
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Event.fulltextsearch_where";
			$where_clause[] = "(".search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2")." OR ".search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2").")";
		}

		if ($search_for["zip"]) {
			$search_for["zip"] = str_replace("\\", "", $search_for["zip"]);
			$search_for["dist"] = str_replace("\\", "", $search_for["dist"]);
			if (ZIPCODE_PROXIMITY == "on" && $search_for["dist"]) {
				if (zipproximity_getWhereZipCodeProximity($search_for["zip"], $search_for["dist"], $whereZipCodeProximity, $order_by_zipcode_score)) {
					$where_clause[] = $whereZipCodeProximity;
					if ($order_by_zipcode_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_zipcode_score;
					}
				} else {
					$where_clause[] = "Event.zip_code = ".db_formatString($search_for["zip"])."";
				}
			} else {
				$where_clause[] = "Event.zip_code = ".db_formatString($search_for["zip"])."";
			}
		}

		if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc) {
			//whereProximitySearch...
			$search_for["dist_loc"] = str_replace("\\", "", $search_for["dist_loc"]);
			if ($search_for["dist_loc"]) {
				if (zipproximity_getWhereZipCodeProximity(false, $search_for["dist_loc"], $whereLocationProximity, $order_by_location_score,$latLoc,$longLoc,false,true)) {
					$where_clause[] = $whereLocationProximity;
					if ($order_by_location_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_location_score;
					}
				} 
			}
        }
        
		if ($sql_location && $latLoc==false && $longLoc==false) {
			$where_clause[] = "(".implode(" AND ", $sql_location).")";
        }
        
		if (($search_for["where"]) && ($section != "mobile") && $latLoc==false && $longLoc==false) {
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Event.fulltextsearch_where";
			$where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
		}

		if ($where_clause && (count($where_clause) > 0)) {
			$searchReturn["where_clause"] = implode(" AND ", $where_clause);
			if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc)
				$searchReturn["where_clause"] .= "OR (".implode(" AND ", $sql_location).")";
		}

        if ($user_order_by == "startdate") {
			$user_order_by = "Event.start_date";
		} elseif ($user_order_by == "characters") {
			$user_order_by = "Event.title";
		} elseif ($user_order_by == "lastupdate") {
			$user_order_by = "Event.updated DESC";
		} elseif ($user_order_by == "datecreated") {
			$user_order_by = "Event.entered DESC";
		} elseif ($user_order_by == "popular") {
			$user_order_by = "Event.number_views DESC";
		} 
        
        if (($section == "event") || ($section == "mobile") || ($section == "rss")) {
			$searchReturn["order_by"] = ($user_order_by && $section == "event" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "event" ? "reg_exp_order DESC, " : "")."Event.level, Event.random_number DESC, Event.end_date, Event.until_date, Event.title, Event.id";
		} elseif ($section == "random") {
			$searchReturn["order_by"] = ((EVENT_SCALABILITY_OPTIMIZATION == "on") ? ("random_number DESC") : ("RAND()"));
		} elseif ($section == "count") {
			$searchReturn["order_by"] = "Event.id";
		}

		if ($search_for["keyword"] && $order_by_keyword_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_keyword_score.($order_by_keyword_score2 ? ", ".$order_by_keyword_score2 : "");
		}

		if ($search_for["where"] && $order_by_where_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_where_score;
		}

        if ((($search_for["keyword"] && $order_by_keyword_score) || ($search_for["where"] && $order_by_where_score) || ($search_for["zip"] && $order_by_zipcode_score)) && ($section != "count") && ($section != "random")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "event" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "event" ? "reg_exp_order DESC, " : "")."Event.level";

            if ($order_by_zipcode_score) {
                $searchReturn["order_by"] .= ", zipcode_score";
            }
            if ($order_by_keyword_score) {
                if ($order_by_keyword_score2){
                    $searchReturn["order_by"] .= ", keyword_score2 DESC";
                } else {
                    $searchReturn["order_by"] .= ", keyword_score DESC";
                }
            }
            if ($order_by_where_score) {
                $searchReturn["order_by"] .= ", where_score DESC";
            }
        }

		return $searchReturn;

	}

	function search_frontClassifiedSearch($search_for, $section) {

		$searchReturn["select_columns"] = false;
		$searchReturn["from_tables"] = false;
		$searchReturn["where_clause"] = false;
		$searchReturn["group_by"] = false;
		$searchReturn["order_by"] = false;

		$orderByConf =  array("characters",
							"lastupdate",
							"datecreated",
							"popular");
			
        $user_order_by = "";
		if (in_array($_GET["orderby"], $orderByConf)) {
			$user_order_by = $_GET["orderby"];
		}

		if (($section == "classified") || ($section == "random") || ($section == "mobile")) {
			$searchReturn["select_columns"] = "Classified.*";
            if (SEARCH_FORCE_BOOLEANMODE == "on" && $section == "classified") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Classified.title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
            }
		} elseif ($section == "count") {
			$searchReturn["select_columns"] = "COUNT(DISTINCT(Classified.id))";
		} elseif ($section == "rss") {
			$searchReturn["select_columns"] = "Classified.id";
		}

		$searchReturn["from_tables"] = "Classified";

		if (isset($search_for["id"]) && is_numeric($search_for["id"]) && $search_for["id"] > 0) {
			$where_clause[] = "Classified.id = ".$search_for["id"]."";
		}

		$where_clause[] = "Classified.status = 'A'";

		if ($search_for["account"]) {
			$where_clause[] = "Classified.account_id = ".$search_for["account"];
		}

		if ($search_for["category_id"]) {
			$where_clause[] = "(Classified.cat_1_id = ".$search_for["category_id"]." OR Classified.parcat_1_level1_id = ".$search_for["category_id"]." OR Classified.parcat_1_level2_id = ".$search_for["category_id"]." OR Classified.parcat_1_level3_id = ".$search_for["category_id"]." OR Classified.parcat_1_level4_id = ".$search_for["category_id"]." OR Classified.cat_2_id = ".$search_for["category_id"]." OR Classified.parcat_2_level1_id = ".$search_for["category_id"]." OR Classified.parcat_2_level2_id = ".$search_for["category_id"]." OR Classified.parcat_2_level3_id = ".$search_for["category_id"]." OR Classified.parcat_2_level4_id = ".$search_for["category_id"]." OR Classified.cat_3_id = ".$search_for["category_id"]." OR Classified.parcat_3_level1_id = ".$search_for["category_id"]." OR Classified.parcat_3_level2_id = ".$search_for["category_id"]." OR Classified.parcat_3_level3_id = ".$search_for["category_id"]." OR Classified.parcat_3_level4_id = ".$search_for["category_id"]." OR Classified.cat_4_id = ".$search_for["category_id"]." OR Classified.parcat_4_level1_id = ".$search_for["category_id"]." OR Classified.parcat_4_level2_id = ".$search_for["category_id"]." OR Classified.parcat_4_level3_id = ".$search_for["category_id"]." OR Classified.parcat_4_level4_id = ".$search_for["category_id"]." OR Classified.cat_5_id = ".$search_for["category_id"]." OR Classified.parcat_5_level1_id = ".$search_for["category_id"]." OR Classified.parcat_5_level2_id = ".$search_for["category_id"]." OR Classified.parcat_5_level3_id = ".$search_for["category_id"]." OR Classified.parcat_5_level4_id = ".$search_for["category_id"].")";
		}

		$_locations = explode(",", EDIR_LOCATIONS);
		foreach($_locations as $_location_level)
		{
			if (is_numeric($search_for["location_".$_location_level]))
			{
				$sql_location[] = " Classified.location_".$_location_level." = ".$search_for["location_".$_location_level]."";
				if(($_location_level==3||$_location_level==4)&& $search_for["dist_loc"]!="")
				{
					unset($locLevel);
					unset($locObj);
					$locLevel = "Location".$_location_level;
					$locObj = new $locLevel($search_for["location_".$_location_level]);
					$latLoc = $locObj->getNumber('latitude'); 
					$longLoc = $locObj->getNumber('longitude'); 
				}
			}
		}
		
		/*if ($sql_location) {
			$where_clause[] = "(".implode(" AND ", $sql_location).")";
        }*/

		if (($search_for["keyword"]) && ($section != "mobile")) {
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Classified.fulltextsearch_keyword";
			$where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
		}

		/*if (($search_for["where"]) && ($section != "mobile")) {
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Classified.fulltextsearch_where";
			$where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
		}*/		

		if (($search_for["keyword"]) && ($section == "mobile")) {
			$search_for["where"] = $search_for["keyword"];
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Classified.fulltextsearch_keyword";
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Classified.fulltextsearch_where";
			$where_clause[] = "(".search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2")." OR ".search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2").")";
		}

		if ($search_for["zip"]) {
			$search_for["zip"] = str_replace("\\", "", $search_for["zip"]);
			$search_for["dist"] = str_replace("\\", "", $search_for["dist"]);
			if (ZIPCODE_PROXIMITY == "on" && $search_for["dist"]) {
				if (zipproximity_getWhereZipCodeProximity($search_for["zip"], $search_for["dist"], $whereZipCodeProximity, $order_by_zipcode_score)) {
					$where_clause[] = $whereZipCodeProximity;
					if ($order_by_zipcode_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_zipcode_score;
					}
				} else {
					$where_clause[] = "Classified.zip_code = ".db_formatString($search_for["zip"])."";
				}
			} else {
				$where_clause[] = "Classified.zip_code = ".db_formatString($search_for["zip"])."";
			}
		}

	if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc) {
			//whereProximitySearch...
			$search_for["dist_loc"] = str_replace("\\", "", $search_for["dist_loc"]);
			if ($search_for["dist_loc"]) {
				if (zipproximity_getWhereZipCodeProximity(false, $search_for["dist_loc"], $whereLocationProximity, $order_by_location_score,$latLoc,$longLoc,false,true)) {
					$where_clause[] = $whereLocationProximity;
					if ($order_by_location_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_location_score;
					}
				} 
			}
        }
        
		if ($sql_location && $latLoc==false && $longLoc==false) {
			$where_clause[] = "(".implode(" AND ", $sql_location).")";
        }
        
		if (($search_for["where"]) && ($section != "mobile") && $latLoc==false && $longLoc==false) {
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Classified.fulltextsearch_where";
			$where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
		}

		if ($where_clause && (count($where_clause) > 0)) {
			$searchReturn["where_clause"] = implode(" AND ", $where_clause);
			if ($sql_location && $search_for["dist_loc"]!="" && $latLoc && $longLoc)
				$searchReturn["where_clause"] .= "OR (".implode(" AND ", $sql_location).")";
		}

		if ($user_order_by == "characters") {
			$user_order_by = "Classified.title";
		} elseif ($user_order_by == "lastupdate") {
			$user_order_by = "Classified.updated DESC";
		} elseif ($user_order_by == "datecreated") {
			$user_order_by = "Classified.entered DESC";
		} elseif ($user_order_by == "popular") {
			$user_order_by = "Classified.number_views DESC";
		}
        
        if (($section == "classified") || ($section == "mobile") || ($section == "rss")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "classified" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "classified" ? "reg_exp_order DESC, " : "")."Classified.level, Classified.random_number DESC, Classified.title, Classified.id";
		} elseif ($section == "random") {
			$searchReturn["order_by"] = ((CLASSIFIED_SCALABILITY_OPTIMIZATION == "on") ? ("random_number DESC") : ("RAND()"));
		} elseif ($section == "count") {
			$searchReturn["order_by"] = "Classified.id";
		}

		if ($search_for["keyword"] && $order_by_keyword_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_keyword_score.($order_by_keyword_score2 ? ", ".$order_by_keyword_score2 : "");
		}

		if ($search_for["where"] && $order_by_where_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_where_score;
		}

        if ((($search_for["keyword"] && $order_by_keyword_score) || ($search_for["where"] && $order_by_where_score) || ($search_for["zip"] && $order_by_zipcode_score)) && ($section != "count") && ($section != "random")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "classified" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "classified" ? "reg_exp_order DESC, " : "")."Classified.level";
            
            if ($order_by_zipcode_score) {
                $searchReturn["order_by"] .= ", zipcode_score";
            }
            if ($order_by_keyword_score) {
                if ($order_by_keyword_score2){
                    $searchReturn["order_by"] .= ", keyword_score2 DESC";
                } else {
                    $searchReturn["order_by"] .= ", keyword_score DESC";
                }

            }
            if ($order_by_where_score) {
                $searchReturn["order_by"] .= ", where_score DESC";
            }
        }

		return $searchReturn;

	}

	function search_frontArticleSearch($search_for, $section) {

		$searchReturn["select_columns"] = false;
		$searchReturn["from_tables"] = false;
		$searchReturn["where_clause"] = false;
		$searchReturn["group_by"] = false;
		$searchReturn["order_by"] = false;

		$orderByConf =  array("characters",
							"lastupdate",
							"datecreated",
							"popular",
							"rating");		
		
        $user_order_by = "";
		if (in_array($_GET["orderby"], $orderByConf)) {
			$user_order_by = $_GET["orderby"];			
		}		

		if (($section == "article") || ($section == "random") || ($section == "mobile")) {
			$searchReturn["select_columns"] = "Article.*";
            if (SEARCH_FORCE_BOOLEANMODE == "on" && $section == "article") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Article.title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
            }
		} elseif ($section == "count") {
			$searchReturn["select_columns"] = "COUNT(DISTINCT(Article.id))";
		} elseif ($section == "rss") {
			$searchReturn["select_columns"] = "Article.id";
		}

		$searchReturn["from_tables"] = "Article";

		if (isset($search_for["id"]) && is_numeric($search_for["id"]) && $search_for["id"] > 0) {
			$where_clause[] = "Article.id = ".$search_for["id"]."";
		}

		$where_clause[] = "Article.status = 'A'";

		if ($search_for["account"]) {
			$where_clause[] = "Article.account_id = ".$search_for["account"];
		}

		$where_clause[] = "Article.publication_date <= DATE_FORMAT(NOW(), '%Y-%m-%d')";

		if ($search_for["category_id"]) {
			$where_clause[] = "(Article.cat_1_id = ".$search_for["category_id"]." OR Article.parcat_1_level1_id = ".$search_for["category_id"]." OR Article.parcat_1_level2_id = ".$search_for["category_id"]." OR Article.parcat_1_level3_id = ".$search_for["category_id"]." OR Article.parcat_1_level4_id = ".$search_for["category_id"]." OR Article.cat_2_id = ".$search_for["category_id"]." OR Article.parcat_2_level1_id = ".$search_for["category_id"]." OR Article.parcat_2_level2_id = ".$search_for["category_id"]." OR Article.parcat_2_level3_id = ".$search_for["category_id"]." OR Article.parcat_2_level4_id = ".$search_for["category_id"]." OR Article.cat_3_id = ".$search_for["category_id"]." OR Article.parcat_3_level1_id = ".$search_for["category_id"]." OR Article.parcat_3_level2_id = ".$search_for["category_id"]." OR Article.parcat_3_level3_id = ".$search_for["category_id"]." OR Article.parcat_3_level4_id = ".$search_for["category_id"]." OR Article.cat_4_id = ".$search_for["category_id"]." OR Article.parcat_4_level1_id = ".$search_for["category_id"]." OR Article.parcat_4_level2_id = ".$search_for["category_id"]." OR Article.parcat_4_level3_id = ".$search_for["category_id"]." OR Article.parcat_4_level4_id = ".$search_for["category_id"]." OR Article.cat_5_id = ".$search_for["category_id"]." OR Article.parcat_5_level1_id = ".$search_for["category_id"]." OR Article.parcat_5_level2_id = ".$search_for["category_id"]." OR Article.parcat_5_level3_id = ".$search_for["category_id"]." OR Article.parcat_5_level4_id = ".$search_for["category_id"].")";
		}

		if (($search_for["keyword"]) && ($section != "mobile")) {
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Article.fulltextsearch_keyword";
			$where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
		}

		if (($search_for["where"]) && ($section != "mobile")) {
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Article.fulltextsearch_where";
			$where_clause[] = search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2");
		}

		if (($search_for["keyword"]) && ($section == "mobile")) {
			$search_for["where"] = $search_for["keyword"];
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Article.fulltextsearch_keyword";
			$search_for["where"] = str_replace("\\", "", $search_for["where"]);
			if (($locpos = string_strpos($search_for["where"], ",")) !== false) {
				$search_for["where"] = str_replace(",", "", $search_for["where"]);
			}
			$search_for_where_fields[] = "Article.fulltextsearch_where";
			$where_clause[] = "(".search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2")." OR ".search_getSQLFullTextSearch($search_for["where"], $search_for_where_fields, "where_score", $order_by_where_score, "allwords", $order_by_where_score2, "where_score2").")";
		}

		if ($search_for["zip"]) {
			$search_for["zip"] = str_replace("\\", "", $search_for["zip"]);
			$search_for["dist"] = str_replace("\\", "", $search_for["dist"]);
			if (ZIPCODE_PROXIMITY == "on" && $search_for["dist"]) {
				if (zipproximity_getWhereZipCodeProximity($search_for["zip"], $search_for["dist"], $whereZipCodeProximity, $order_by_zipcode_score)) {
					$where_clause[] = $whereZipCodeProximity;
					if ($order_by_zipcode_score && ($section != "count") && ($section != "random")) {
						$searchReturn["select_columns"] .= ", ".$order_by_zipcode_score;
					}
				} else {
					$where_clause[] = "Article.zip_code = ".db_formatString($search_for["zip"])."";
				}
			} else {
				$where_clause[] = "Article.zip_code = ".db_formatString($search_for["zip"])."";
			}
		}

		if ($where_clause && (count($where_clause) > 0)) {
			$searchReturn["where_clause"] = implode(" AND ", $where_clause);
		}		
		
		if ($user_order_by == "characters") {
			$user_order_by = "Article.title";
		} elseif ($user_order_by == "lastupdate") {
			$user_order_by = "Article.updated DESC";
		} elseif ($user_order_by == "datecreated") {
			$user_order_by = "Article.entered DESC";
		} elseif ($user_order_by == "popular") {
			$user_order_by = "Article.number_views DESC";
		} elseif ($user_order_by == "rating") {
			$user_order_by = "Article.avg_review DESC";
		}
        
        if (($section == "article") || ($section == "mobile") || ($section == "rss")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "article" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "article" ? "reg_exp_order DESC, " : "")."Article.level, Article.publication_date DESC, Article.random_number DESC,  Article.updated DESC, Article.entered DESC, Article.title, Article.id";
        } elseif ($section == "random") {
			$searchReturn["order_by"] = ((ARTICLE_SCALABILITY_OPTIMIZATION == "on")?("random_number DESC"):("RAND()"));
		} elseif ($section == "count") {
			$searchReturn["order_by"] = "Article.id";
		}

		if ($search_for["keyword"] && $order_by_keyword_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_keyword_score.($order_by_keyword_score2 ? ", ".$order_by_keyword_score2 : "");
		}

		if ($search_for["where"] && $order_by_where_score && ($section != "count") && ($section != "random")) {
			$searchReturn["select_columns"] .= ", ".$order_by_where_score;
		}

        if ((($search_for["keyword"] && $order_by_keyword_score) || ($search_for["where"] && $order_by_where_score) || ($search_for["zip"] && $order_by_zipcode_score)) && ($section != "count") && ($section != "random")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "article" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "article" ? "reg_exp_order DESC, " : "")."Article.level";

            if ($order_by_zipcode_score) {
                $searchReturn["order_by"] .= ", zipcode_score";
            }
            if ($order_by_keyword_score) {
                if ($order_by_keyword_score2){
                    $searchReturn["order_by"] .= ", keyword_score2 DESC";
                } else {
                    $searchReturn["order_by"] .= ", keyword_score DESC";
                }

            }
            if ($order_by_where_score) {
                $searchReturn["order_by"] .= ", where_score DESC";
            }
        }

		return $searchReturn;

	}
    
    function search_frontBlogSearch($search_for, $section) {

		$searchReturn["select_columns"] = false;
		$searchReturn["from_tables"] = false;
		$searchReturn["where_clause"] = false;
		$searchReturn["group_by"] = false;
		$searchReturn["order_by"] = false;

		$orderByConf =  array("characters",
							"lastupdate",
							"datecreated",
							"popular");

		if (in_array($_GET["orderby"], $orderByConf)) {
			$user_order_by = $_GET["orderby"];
		}

		if (($section == "blog") || ($section == "random")) {
			$searchReturn["select_columns"] = "Post.*";
            if (SEARCH_FORCE_BOOLEANMODE == "on" && $section == "blog") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Post.title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
            }
		} elseif ($section == "count") {
			$searchReturn["select_columns"] = "COUNT(DISTINCT(Post.id))";
		} elseif ($section == "rss") {
			$searchReturn["select_columns"] = "Post.id";
		}

		$searchReturn["from_tables"] = "Post";

		if (isset($search_for["id"]) && is_numeric($search_for["id"])) {
			$where_clause[] = "Post.id = ".$search_for["id"]."";
		}

		$where_clause[] = "Post.status = 'A'";

		if ($search_for["category_id"]) {
            //Create a category object to get hierarchy of categories
			unset($aux_categoryObj,$aux_cat_hierarchy);
			$aux_categoryObj = new BlogCategory($search_for["category_id"]);
			$aux_cat_hierarchy = $aux_categoryObj->getHierarchy($search_for["category_id"],false,true);
			if ($aux_cat_hierarchy) {
				$post_ids = '0';
                unset($post_CategoryObj);
                $post_CategoryObj = new Blog_Category();
                $post_ids = $post_CategoryObj->getPostsByCategoryHierarchy($aux_categoryObj->root_id,$aux_categoryObj->left,$aux_categoryObj->right,$search_for["letter"]);
                $total_posts_ids = $post_CategoryObj->total_posts;			
					
				$where_clause[] = "Post.id in (".$post_ids.")";
				$searchReturn["total_posts"] = $total_posts_ids;	
			}
		}

		if ($search_for["archive_year"]) {
			$where_clause[] = "YEAR(entered) = ".$search_for["archive_year"];
		}

		if ($search_for["archive_month"]) {
			$where_clause[] = "MONTH(entered) = ".$search_for["archive_month"];
		}

		if (($search_for["keyword"]) && ($section != "mobile")) {
			$search_for["keyword"] = str_replace("\\", "", $search_for["keyword"]);
			$search_for_keyword_fields[] = "Post.fulltextsearch_keyword";
			$where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, "keyword_score", $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, "keyword_score2");
		}

		if ($where_clause && (count($where_clause) > 0)) {
			$searchReturn["where_clause"] = implode(" AND ", $where_clause);
		}

		if ($user_order_by == "characters") {
			$user_order_by = "Post.title";
		} elseif ($user_order_by == "lastupdate") {
			$user_order_by = "Post.updated DESC";
		} elseif ($user_order_by == "datecreated") {
			$user_order_by = "Post.entered DESC";
		} elseif ($user_order_by == "popular") {
			$user_order_by = "Post.number_views DESC";
		}
        
        if (($section == "blog") || ($section == "rss")) {
            $searchReturn["order_by"] = ($user_order_by && $section == "blog" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "blog" ? "reg_exp_order DESC, " : "")."Post.entered DESC, Post.updated DESC, Post.title, Post.id";
        } elseif ($section == "count") {
			$searchReturn["order_by"] = "Post.id";
		}

		if ($search_for["keyword"] && $order_by_keyword_score && ($section != "count") && ($section != "random")) {
            if ($order_by_keyword_score2) {
                $searchReturn["select_columns"] .= ", ".$order_by_keyword_score.", ".$order_by_keyword_score2;
            } else {
                $searchReturn["select_columns"] .= ", ".$order_by_keyword_score;
            }
		}

		if (($search_for["keyword"] && $order_by_keyword_score) && ($section != "count") && ($section != "random")) {
			$searchReturn["order_by"] = ($user_order_by && $section == "blog" ? $user_order_by.", " : "").(SEARCH_FORCE_BOOLEANMODE == "on" && $section == "blog" ? "reg_exp_order DESC, " : "")."Post.entered DESC";
           
            if ($order_by_keyword_score) {
                if ($order_by_keyword_score2) {
                    $searchReturn["order_by"] .= ", keyword_score2 DESC";
                } else {
                    $searchReturn["order_by"] .= ", keyword_score DESC";
                }
			}
		}
        
		return $searchReturn;

	}

	function search_getSQLFullTextSearch($searchfor, $fields, $order_by_fieldname, &$order_by_score, $force_specific_search="", &$order_by_score2, $order_by_fieldname2 = "") {

		$order_by_score = "";
		$order_by_score2 = "";
		unset($sql_aux);
		unset($searchfor_aux);
		unset($searchfor_array);
		if (($force_specific_search != "exactmatch") && ($force_specific_search != "anyword") && ($force_specific_search != "allwords")) {
			$force_specific_search = "";
		}
        if (!$force_specific_search) {
            setting_get("default_search_option", $force_specific_search);
        }

		$searchfor = trim($searchfor);

		$words_array = explode(" ", $searchfor);

		/*
		 * Remove wrong spaces
		 */
		unset($aux_words_array);
		for($i=0;$i<count($words_array);$i++){
			if(strlen($words_array[$i]) > 0){
				$aux_words_array[] = trim($words_array[$i]);
			}
		}
		
		if(count($aux_words_array) > 0){
			unset($words_array);
			$words_array = $aux_words_array;
			$searchfor = implode(" ",$words_array);
		}
		
		
		$thesaurus = false;
		if (count($words_array) == 2) {
			$thesaurus = str_replace(" ", "", $searchfor);
		}

		$force_text_search = false;
		if (count($words_array) >= 2) {
			foreach ($words_array as $each_word) {
				if (string_strlen($each_word) <= 3) {
					$force_text_search = true;
					break;
				}
			}
		}

		$force_like = false;
		if(LISTING_SCALABILITY_OPTIMIZATION != "on"){
			foreach ($words_array as $each_searchfor) {
				if (string_strlen(Inflector::singularize($each_searchfor)) < (int)FT_MIN_WORD_LEN) {
					$force_like = true;
					break;
				}
			}
		}
		
        $auxWordsArray = explode("-", $searchfor);
        if (is_array($auxWordsArray) && $auxWordsArray[0]){
            foreach ($auxWordsArray as $auxWord){
                if (string_strlen(Inflector::singularize($auxWord)) < (int)FT_MIN_WORD_LEN) {
                    $force_like = true;
                    break;
                }
            }
        }
                
		if ($force_specific_search == "exactmatch") {

			$searchfor = db_formatString($searchfor);
			$searchfor = string_substr($searchfor, 1, string_strlen($searchfor)-2);

			if (string_strlen($searchfor) < (int)FT_MIN_WORD_LEN) {
				if ($searchfor == "'") $searchfor = "\'";
				foreach ($fields as $field) {
					$sql_aux[] = "(".$field." = '$searchfor' OR ".$field." LIKE '$searchfor %' OR ".$field." LIKE '% $searchfor' OR ".$field." LIKE '% $searchfor %')";
				}

				return "(".(implode(" OR ", $sql_aux)).")";
			} else {
				foreach ($words_array as $each_searchfor) {
					$searchfor_array[] = $each_searchfor;
				}

				$searchfor_array = array_unique($searchfor_array);
				$formated_searchfor = implode(" ", $searchfor_array);

                if (SEARCH_FORCE_BOOLEANMODE == "on"){
                    $auxFields = implode(", ", $fields);
                    if (string_strpos($auxFields, "Promotion") !== false){
                        $auxFields = str_replace("fulltextsearch_keyword", "name", $auxFields);
                    } else {
                        $auxFields = str_replace("fulltextsearch_keyword", "title", $auxFields);
                    }
                    $order_by_score = "MATCH (".$auxFields.") AGAINST ('\"".addslashes($formated_searchfor)."\"' IN BOOLEAN MODE) as ".$order_by_fieldname;
                    $order_by_score2 = "MATCH (".implode(", ", $fields).") AGAINST ('\"".addslashes($formated_searchfor)."\"') as ".$order_by_fieldname2;
                } else {
                    $order_by_score = "MATCH (".implode(", ", $fields).") AGAINST ('\"".addslashes($formated_searchfor)."\"') as ".$order_by_fieldname;
                }
				
				return "MATCH (".implode(", ", $fields).") AGAINST ('\"".addslashes($formated_searchfor)."\"' IN BOOLEAN MODE)";
			}

		} elseif ((string_strlen($searchfor) < (int)FT_MIN_WORD_LEN || $force_like) && ($force_specific_search == "anyword" || !$force_specific_search)) {

			unset($searchfor_aux_array);
			foreach ($words_array as $each_searchfor) {
				$searchfor_aux = $each_searchfor;
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = $searchfor_aux;
			}

			unset($searchfor_aux_array);
			foreach ($words_array as $each_searchfor) {
				$searchfor_aux = Inflector::singularize($each_searchfor);
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = $searchfor_aux;
			}

			unset($searchfor_aux_array);
			foreach ($words_array as $each_searchfor) {
				$searchfor_aux = Inflector::pluralize($each_searchfor);
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = $searchfor_aux;
			}

			if ($thesaurus) {
				$searchfor_aux = $thesaurus;
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = $searchfor_aux;
			}

			$searchfor_array = array_unique($searchfor_array);

			$keyCheck = array_search("'",$searchfor_array);
			if ($keyCheck !== false){
				$searchfor_array[$keyCheck] = "\'";
			}

			foreach ($searchfor_array as $each_searchfor) {
				foreach ($fields as $field) {
					$sql_aux[] = $field." = '$each_searchfor'";
					$sql_aux[] = $field." LIKE '$each_searchfor %'";
					$sql_aux[] = $field." LIKE '% $each_searchfor'";
					$sql_aux[] = $field." LIKE '% $each_searchfor %'";
				}
			}

			return "(".(implode(" OR ", $sql_aux)).")";

		} elseif ($force_specific_search == "anyword" || !$force_specific_search) {

    		unset($searchfor_aux_array);
			foreach ($words_array as $each_searchfor) {
				$searchfor_aux_array[] = $each_searchfor;
			}
			$searchfor_aux = implode(" ", $searchfor_aux_array);
            
            $searchfor_aux_booleanMode = $searchfor_aux;
            
			$searchfor_aux = db_formatString($searchfor_aux);
			$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
			$searchfor_array[] = $searchfor_aux;

			unset($searchfor_aux_array);
			foreach ($words_array as $each_searchfor) {
				$searchfor_aux_array[] = Inflector::singularize($each_searchfor);
			}
			$searchfor_aux = implode(" ", $searchfor_aux_array);
			$searchfor_aux = db_formatString($searchfor_aux);
			$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
			$searchfor_array[] = $searchfor_aux;

			unset($searchfor_aux_array);
			foreach ($words_array as $each_searchfor) {
				$searchfor_aux_array[] = Inflector::pluralize($each_searchfor);
			}
			$searchfor_aux = implode(" ", $searchfor_aux_array);
			$searchfor_aux = db_formatString($searchfor_aux);
			$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
			$searchfor_array[] = $searchfor_aux;

			if ($thesaurus) {
				$searchfor_aux = $thesaurus;
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = $searchfor_aux;
			}

			$searchfor_array = array_unique($searchfor_array);

			foreach ($searchfor_array as $each_searchfor) {
				$sql_aux[] = $each_searchfor;
			}

			$searchfor_array = array_unique($sql_aux);

			$formated_searchfor = db_formatString(implode(" ", $searchfor_array));
            if (SEARCH_FORCE_BOOLEANMODE == "on"){
                $auxFields = implode(", ", $fields);
                if (string_strpos($auxFields, "Promotion") !== false){
                    $auxFields = str_replace("fulltextsearch_keyword", "name", $auxFields);
                } else {
                    $auxFields = str_replace("fulltextsearch_keyword", "title", $auxFields);
                }
                $order_by_score = "MATCH (".$auxFields.") AGAINST ('\"".addslashes($searchfor_aux_booleanMode)."\"' IN BOOLEAN MODE) as ".$order_by_fieldname;
                $order_by_score2 = "MATCH (".implode(", ", $fields).") AGAINST (".$formated_searchfor.") as ".$order_by_fieldname2;
            } else {
                $order_by_score = "MATCH (".implode(", ", $fields).") AGAINST (".$formated_searchfor.") as ".$order_by_fieldname;
            }

			return "MATCH (".implode(", ", $fields).") AGAINST (".$formated_searchfor." IN BOOLEAN MODE)";

		} elseif (($force_specific_search == "allwords")) {

			if ((string_strlen($searchfor) < (int)FT_MIN_WORD_LEN) || ($force_text_search) || $force_like) {

				foreach ($words_array as $each_searchfor) {
					$searchfor_aux = $each_searchfor;
					$searchfor_aux = db_formatString($searchfor_aux);
					$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
					$searchfor_words[] = $searchfor_aux;
				}

				foreach ($words_array as $each_searchfor) {
					$searchfor_aux = Inflector::singularize($each_searchfor);
					$searchfor_aux = db_formatString($searchfor_aux);
					$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
					$searchfor_singular[] = $searchfor_aux;
				}

				foreach ($words_array as $each_searchfor) {
					$searchfor_aux = Inflector::pluralize($each_searchfor);
					$searchfor_aux = db_formatString($searchfor_aux);
					$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
					$searchfor_plural[] = $searchfor_aux;
				}

				unset($searchfor_aux_array);
				$searchfor_aux_array[] = implode(" ", $searchfor_words);
				$searchfor_aux_array[] = implode(" ", $searchfor_singular);
				$searchfor_aux_array[] = implode(" ", $searchfor_plural);

				$searchfor_aux_array = array_unique($searchfor_aux_array);

				foreach ($searchfor_aux_array as $searchword) {
					$searchfor_array = array_merge((array)$searchfor_array, explode(" ", $searchword));
				}
				
				$keyCheck = array_search("'",$searchfor_array);
				if ($keyCheck !== false){
					$searchfor_array[$keyCheck] = "\'";
				}
				$count = count($words_array);

				foreach ($fields as $field) {
					unset($sqlaux);
					$i = 1;
					$j = 0;
					foreach ($searchfor_array as $each_searchfor) {
						$sqlaux[$j][] = "(".$field." = '$each_searchfor' OR ".$field." LIKE '$each_searchfor %' OR ".$field." LIKE '% $each_searchfor' OR ".$field." LIKE '% $each_searchfor %')";

						if ($i >= $count) {
							$j++;
							$i = 1;
						} else {
							$i++;
						}
					}

					foreach ($sqlaux as $sql) {
						$sql_aux[] = "(".(implode(" AND ", $sql)).")";
					}
				}
			
				return "(".(implode(" OR ", $sql_aux)).")";

			} else {

				unset($searchfor_aux_array);
				foreach ($words_array as $each_searchfor) {
					$searchfor_aux_array[] = "+".$each_searchfor;
				}
				$searchfor_aux = implode(" ", $searchfor_aux_array);
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = "(".$searchfor_aux.")";

				unset($searchfor_aux_array);
				foreach ($words_array as $each_searchfor) {
					$searchfor_aux_array[] = "+".Inflector::singularize($each_searchfor);
				}
				$searchfor_aux = implode(" ", $searchfor_aux_array);
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = "(".$searchfor_aux.")";

				unset($searchfor_aux_array);
				foreach ($words_array as $each_searchfor) {
					$searchfor_aux_array[] = "+".Inflector::pluralize($each_searchfor);
				}
				$searchfor_aux = implode(" ", $searchfor_aux_array);
				$searchfor_aux = db_formatString($searchfor_aux);
				$searchfor_aux = string_substr($searchfor_aux, 1, string_strlen($searchfor_aux)-2);
				$searchfor_array[] = "(".$searchfor_aux.")";

				$searchfor_array = array_unique($searchfor_array);

                $formated_searchfor_aux = implode(" ", $searchfor_array);

				$formated_searchfor = db_formatString(implode(" ", $searchfor_array));

                if (SEARCH_FORCE_BOOLEANMODE == "on"){
                    $auxFields = implode(", ", $fields);
                    if (string_strpos($auxFields, "Promotion") !== false){
                        $auxFields = str_replace("fulltextsearch_keyword", "name", $auxFields);
                    } else {
                        $auxFields = str_replace("fulltextsearch_keyword", "title", $auxFields);
                    }
                    $order_by_score = "MATCH (".$auxFields.") AGAINST ('\"".addslashes($formated_searchfor_aux)."\"' IN BOOLEAN MODE) as ".$order_by_fieldname;
                    $order_by_score2 = "MATCH (".implode(", ", $fields).") AGAINST (".$formated_searchfor.") as ".$order_by_fieldname2;
                } else {
                    $order_by_score = "MATCH (".implode(", ", $fields).") AGAINST (".$formated_searchfor.") as ".$order_by_fieldname;
                    }

				return "MATCH (".implode(", ", $fields).") AGAINST (".$formated_searchfor." IN BOOLEAN MODE)";

			}
		}

		return "";
	}

	function search_getOrderbyDropDown ($getData, $pagingUrl, $orderBy, $defaultText = "Order by: ", $defaultOnChange = "this.form.submit();", $array_params = false, $deal = false, $blog = false) {

		$url_base = $_SERVER["PHP_SELF"];

		$use_friendly_url = true;

		$method = "get";

		if (string_strpos($url_base, SITEMGR_ALIAS) !== false || string_strpos($url_base, MEMBERS_ALIAS) !== false || string_strpos($_SERVER["REQUEST_URI"], "results.php") !== false){
			$use_friendly_url = false;
		}

		$id = "";

		if ($use_friendly_url){
			for($i=0; $i < count($array_params); $i++ ){
				if ($array_params[$i]){
					if ($array_params[$i] != "screen" && $array_params[$i] != "letter" && $array_params[$i] != "orderby"){
						$array_search_params[] = "/".urlencode($array_params[$i]);
					} else {
						if ($array_params[$i] != "orderby"){
							$array_search_params[] = "/".$array_params[$i]."/".$array_params[$i+1];
							$i++;
						} else {
							$i++;
						}
					}
				}
			}

			if (is_array($array_search_params)){
				$url_search_params = implode("/", $array_search_params);
				if ($url_search_params == "orderby/") $url_search_params = "";
			} else {
				$url_search_params = "";
			}
			$url_search_params = str_replace("//", "/", $url_search_params);
			if (string_substr($url_search_params, -1) == "/")
					$url_search_params = string_substr($url_search_params, 0, -1);

			$id = "dropDownOrder";
			$method = "post";
			$defaultOnChange = "changePageOrder(\"".$pagingUrl."\", this.value, \"".$url_search_params."\")";
		}

		if ($id) $id = "id=\"".$id."\"";

		$orderbyDropDown = "<form name=\"pages\" ".$id." method=\"".$method."\" action=\"$pagingUrl\" class=\"form\">";

		foreach ($getData as $name => $value) {
			if ((is_string($name) || is_numeric($name)) && (is_string($value) || is_numeric($value)) && $name != "url_full") {
				if (($name != "orderby") && ($name != "acct_search_company") && ($name != "acct_search_username")) {
					$orderbyDropDown .= "<input type=\"hidden\" name=\"".$name."\" value=\"".string_htmlentities($value)."\" />\n";
				}
			}
		}

		$orderbyDropDown .= "<label>".$defaultText . "</label>" ;
		$orderbyDropDown .= "<select name=\"orderby\" onchange='".$defaultOnChange."' class='select'>\n";
		
		if (!$blog && !$deal){
            if (ACTUAL_MODULE_FOLDER == ARTICLE_FEATURE_FOLDER) {
                $orderbyDropDown .= "<option value=\"\">".LANG_LABEL_PUBLICATION_DATE."</option>\n";
            } else {
                $orderbyDropDown .= "<option value=\"\">".LANG_LABEL_LEVEL."</option>\n";
            }
		} elseif ($deal) {
            $orderbyDropDown .= "<option value=\"\">".LANG_LABEL_ENDDATE."</option>\n";
        }
        
		for ($i = 0; $i < count($orderBy); $i++) {
			if ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_CHARACTERS) { $option_value = "characters"; }
			elseif ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_LASTUPDATE) { $option_value = "lastupdate"; }
			elseif ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_DATECREATED) { $option_value = "datecreated"; }
			elseif ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_POPULAR) { $option_value = "popular"; }
			elseif ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_RATING) { $option_value = "rating"; }
			elseif ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_PRICE) { $option_value = "price"; }
			elseif ($orderBy[$i] == LANG_PAGING_ORDERBYPAGE_STARTDATE) { $option_value = "startdate"; }
			
			if (($getData["orderby"] == $option_value) || ($blog && !$getData["orderby"] && $option_value == "datecreated")) {
				$orderbyDropDown .= "<option value=\"".$option_value."\" selected=\"selected\">".$orderBy[$i]."</option>\n";
			} else
				$orderbyDropDown .= "<option value=\"".$option_value."\">".$orderBy[$i]."</option>\n";
		}

		$orderbyDropDown .= "</select>\n";
		$orderbyDropDown .= "</form>\n";

		return $orderbyDropDown;

	}    
    
    function search_frontListingAppKeyword($array, &$searchReturn) {
        
        if ($array["keyword"]) {
            $search_for["keyword"] = str_replace("\\", "", $array["keyword"]);
            $search_for_keyword_fields[] = "Listing_Summary.fulltextsearch_keyword";

            $aux_label_keyword_score    = "keyword_score";
            $aux_label_keyword_score2   = "keyword_score2";
            $where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, $aux_label_keyword_score, $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, $aux_label_keyword_score2);

            unset($aux_order_by);
            
            if (SEARCH_FORCE_BOOLEANMODE == "on") {
                $searchReturn["select_columns"] = $searchReturn["select_columns"].", Listing_Summary.title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
                $aux_order_by[] = "reg_exp_order desc";
            }

            if ($order_by_keyword_score) {
                $searchReturn["select_columns"] .= ", ".$order_by_keyword_score;
                $aux_order_by[] = $aux_label_keyword_score;
            }

            if ($order_by_keyword_score2) {
                $searchReturn["select_columns"] .= ", ".$order_by_keyword_score2;
                $aux_order_by[] = $aux_label_keyword_score2;
            }

            if (is_array($aux_order_by)) {
                $searchReturn["order_by"] = implode(", ",$aux_order_by).", ".$searchReturn["order_by"];
            }
        }
        
        /**
         * Searching inside a category         
         */
        if ($array["category_id"]) {
            //Create a category object to get hierarchy of categories
			unset($aux_categoryObj,$aux_cat_hierarchy);
			$aux_categoryObj = new ListingCategory($array["category_id"]);
			$aux_cat_hierarchy = $aux_categoryObj->getHierarchy($array["category_id"],false,true);
			if ($aux_cat_hierarchy) {
				$listing_ids = '0';
                unset($listing_CategoryObj);
                $listing_CategoryObj = new Listing_Category();
                $listing_ids = $listing_CategoryObj->getListingsByCategoryHierarchy($aux_categoryObj->root_id,$aux_categoryObj->left,$aux_categoryObj->right,$array["letter"]);
				$where_clause[] = $searchReturn["from_tables"].".id in (".$listing_ids.")";
			}
		}
        
        if (is_array($where_clause)) {
            $searchReturn["where_clause"] .= " and (".implode(" and ",$where_clause).") ";
        }
    }
    
    function search_frontListingDrawMap($array, $section, $aux_select_columns = false) {
        
        if (($array["module"] == "promotion") || ($array["module"] == "deal")) {
            $latitudeField = "listing_latitude";
            $longitudeField = "listing_longitude";
            
            if($array["myLat"] && $array["myLong"]){
                if (ZIPCODE_UNIT == "mile") {
                    $order_by_zipcode_score = "SQRT(POW((69.1 * (".$array["myLat"]." - $latitudeField)), 2) + POW((53.0 * (".$array["myLong"]." - $longitudeField)), 2)) AS distance_score";
                } elseif (ZIPCODE_UNIT == "km") {
                    $order_by_zipcode_score = "SQRT(POW((69.1 * (".$array["myLat"]." - $latitudeField)), 2) + POW((53.0 * (".$array["myLong"]." - $longitudeField)), 2)) * 1.609344 AS distance_score";
                }
            }else{
                $order_by_zipcode_score = false;
            }
            
            $searchReturn["from_tables"]    = "Promotion";
            $searchReturn["order_by"]       = "Promotion.listing_level, Promotion.name";
            $searchReturn["select_columns"] = (is_array($aux_select_columns) ? implode(", ",$aux_select_columns) : "Promotion.name, Promotion.id, Promotion.listing_latitude, Promotion.listing_longitude").($order_by_zipcode_score ? ",".$order_by_zipcode_score : "");
        } else {
            $latitudeField                  = "latitude";
            $longitudeField                 = "longitude";
            
            if($array["myLat"] && $array["myLong"]){
                if (ZIPCODE_UNIT == "mile") {
                    $order_by_zipcode_score = "SQRT(POW((69.1 * (".$array["myLat"]." - $latitudeField)), 2) + POW((53.0 * (".$array["myLong"]." - $longitudeField)), 2)) AS distance_score";
                } elseif (ZIPCODE_UNIT == "km") {
                    $order_by_zipcode_score = "SQRT(POW((69.1 * (".$array["myLat"]." - $latitudeField)), 2) + POW((53.0 * (".$array["myLong"]." - $longitudeField)), 2)) * 1.609344 AS distance_score";
                }
            }else{
                $order_by_zipcode_score = false;
            }
            
            $searchReturn["from_tables"]    = "Listing_Summary";
            $searchReturn["order_by"]       = "Listing_Summary.level, Listing_Summary.title";
            $searchReturn["select_columns"] = (is_array($aux_select_columns) ? implode(", ",$aux_select_columns) : "Listing_Summary.title, Listing_Summary.id, Listing_Summary.latitude, Listing_Summary.longitude").($order_by_zipcode_score ? ",".$order_by_zipcode_score : "");
        }
        
        $where = "";
        $where .= "(";
        $where .= "$latitudeField <= ".$array["drawLat1"];
        $where .= " AND ";
        $where .= "$latitudeField >= ".$array["drawLat0"];
        $where .= " AND ";
        $where .= "$longitudeField <= ".$array["drawLong1"];
        $where .= " AND ";
        $where .= "$longitudeField >= ".$array["drawLong0"];        
        $where .= ")";
        if(($array["module"] == "promotion") || ($array["module"] == "deal")){
            $where .= " AND listing_status = 'A'";
        }else{
            $where .= " AND status = 'A'";
        }
        
        $searchReturn["where_clause"]   = $where;
        $searchReturn["group_by"]       = false;

        if ($array["keyword"] || $array["category_id"]) {
            if(($array["module"] == "promotion") || ($array["module"] == "deal")){
                search_frontAppKeyword($array, $searchReturn,"Promotion");
            }else{
                search_frontListingAppKeyword($array, $searchReturn);
            }
            
        }
           
        if (is_array($searchReturn)) {
            return $searchReturn;
        } else {
            return false;
        }
    }
    
    
    function search_frontAppKeyword($array, &$searchReturn,$tableName) {
        
        if ($array["keyword"]) {
            $search_for["keyword"] = str_replace("\\", "", $array["keyword"]);
            $search_for_keyword_fields[] = $tableName.".fulltextsearch_keyword";

            $aux_label_keyword_score    = "keyword_score";
            $aux_label_keyword_score2   = "keyword_score2";
            $where_clause[] = search_getSQLFullTextSearch($search_for["keyword"], $search_for_keyword_fields, $aux_label_keyword_score, $order_by_keyword_score, $search_for["match"], $order_by_keyword_score2, $aux_label_keyword_score2);

            unset($aux_order_by);
            
            if (SEARCH_FORCE_BOOLEANMODE == "on") {
                if($tableName == "Promotion"){
                    $searchReturn["select_columns"] = $searchReturn["select_columns"].", ".$tableName.".name REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
                }else{
                    $searchReturn["select_columns"] = $searchReturn["select_columns"].", ".$tableName.".title REGEXP '^".db_formatString($search_for["keyword"], "", false, false)."$' AS reg_exp_order";
                }
                
                $aux_order_by[] = "reg_exp_order desc";
            }

            if ($order_by_keyword_score) {
                $searchReturn["select_columns"] .= ", ".$order_by_keyword_score;
                $aux_order_by[] = $aux_label_keyword_score;
            }

            if ($order_by_keyword_score2) {
                $searchReturn["select_columns"] .= ", ".$order_by_keyword_score2;
                $aux_order_by[] = $aux_label_keyword_score2;
            }

            if (is_array($aux_order_by)) {
                $searchReturn["order_by"] = implode(", ",$aux_order_by).", ".$searchReturn["order_by"];
            }
        }
        
        if (is_array($where_clause)) {
            $searchReturn["where_clause"] .= " and (".implode(" and ",$where_clause).") ";
        }
    }
    
    function search_frontDrawMap($array, $aux_select_columns = false,$tableName) {
        
        $latitudeField = "latitude";
        $longitudeField = "longitude";
        
        $where = "";
        $where .= "(";
        $where .= "$latitudeField <= ".$array["drawLat1"];
        $where .= " AND ";
        $where .= "$latitudeField >= ".$array["drawLat0"];
        $where .= " AND ";
        $where .= "$longitudeField <= ".$array["drawLong1"];
        $where .= " AND ";
        $where .= "$longitudeField >= ".$array["drawLong0"];        
        $where .= ")";
        $where .= " AND status = 'A'";
        
        $searchReturn["from_tables"]    = $tableName;
        $searchReturn["order_by"]       = $tableName.".level,".$tableName.".title";
        $searchReturn["where_clause"]   = $where;
        $searchReturn["select_columns"] = (is_array($aux_select_columns) ? implode(", ",$aux_select_columns) : $tableName.".title, ".$tableName.".id, ".$tableName.".latitude, ".$tableName.".longitude");
        $searchReturn["group_by"]       = false;

        if ($array["keyword"] || $array["category_id"]) {
            search_frontAppKeyword($array, $searchReturn,$tableName);
        }
           
        if (is_array($searchReturn)) {
            return $searchReturn;
        } else {
            return false;
        }
    }
    
    
    
    
?>