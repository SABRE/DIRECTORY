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
	# * FILE: /includes/code/browsebycategory_listings.php
	# ----------------------------------------------------------------------------------------------------

        $module = "listing";
        $categTable = "ListingCategory";
        $moduleScalability = LISTINGCATEGORY_SCALABILITY_OPTIMIZATION;
        $module_default_url = LISTING_DEFAULT_URL;
        $viewAllLabel = system_showText(LANG_LISTING_VIEWALLCATEGORIES);
        $categoryCount = SHOW_CATEGORY_COUNT;
    
    
    unset($catObj);
    unset($categories);
    unset($featuredcategory);
    
	$catObj = new $categTable();
	
	if (FEATURED_CATEGORY == "on") {
		setting_get($module."_featuredcategory", $featuredcategory);
    }
    
    if ($allCategories){
        $moduleScalability = "off";
        $featuredcategory = "";
    }

	if ($moduleScalability == "on") {
		$sql = "SELECT id, title, friendly_url, active_".($module == "blog" ? "post" : $module)." FROM $categTable WHERE category_id = '0' ".($featuredcategory ? "AND featured = 'y'" : "")." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_".($module == "blog" ? "post" : $module)." DESC LIMIT 20";
		$categories = system_generateXML("categories", $sql, SELECTED_DOMAIN_ID);
	} else {
		$categories = system_retrieveAllCategoriesXML($categTable, $featuredcategory);
	}

	$total = 0;

	if (is_string($categories)) {
	
        if ($moduleScalability == "on") {
            $viewMoreLink = "<a class=\"view-more\" href=\"".$module_default_url."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".$viewAllLabel."</a>";
        } else {
            $viewMoreLink = "";
        }
        
		$xml_categories = simplexml_load_string($categories);
		if (count($xml_categories->info) > 0) {
			for ($i=0; $i < count($xml_categories->info); $i++) {
				unset($categories);
				foreach ($xml_categories->info[$i]->children() as $key => $value) {
					$categories[$key] = $value;
				}
				
				$total++;
				
				if ($categories) {

					$categoryLink = $module_default_url."/".$categories["friendly_url"];                
                    $array_item_categories[$i]["categoryLink"] = $categoryLink;
                    if ($total == 3){
                        $array_item_categories[$i]["liClass"] = "class=\"\"";
                        /* $array_item_categories[$i]["auxLi"] = "<li class=\"clear\">&nbsp;</li>"; */
                        $total = 0;
                    } else {
                        $array_item_categories[$i]["auxLi"] = "";
                    }

                    $array_item_categories[$i]["categoryLink"] = $categoryLink;
                    $array_item_categories[$i]["title"] = system_showTruncatedText($categories["title"], 25);
					$array_item_categories[$i]["active_".($module == "blog" ? "post" : $module)] = $categories["active_".($module == "blog" ? "post" : $module)];

                    unset($subcategories);
                    if ($moduleScalability != "on") {

                        $subcategories = system_getAllCategoriesHierarchyXML($categTable, $featuredcategory, $categories["id"], 0, SELECTED_DOMAIN_ID);

                        if ($subcategories) {
                            $xml_subcategories = simplexml_load_string($subcategories);
                            if(count($xml_subcategories->info) > 0) {
                                for($j = 0; $j < count($xml_subcategories->info); $j++){
                                    unset($subcategories);
                                    foreach($xml_subcategories->info[$j]->children() as $key => $value){
                                        $subcategories[$key] = $value;
                                    }
                                    if ($subcategories) {

                                        $subCategoryLink = $module_default_url."/".$categories["friendly_url"]."/".$subcategories["friendly_url"];

                                        $array_item_categories[$i]["subcategories"][$j]["subCategoryLink"] = $subCategoryLink;
                                        $array_item_categories[$i]["subcategories"][$j]["subCategoryTitle"] = system_showTruncatedText($subcategories["title"], 25);
                                        $array_item_categories[$i]["subcategories"][$j]["active_".($module == "blog" ? "post" : $module)] = $subcategories["active_".($module == "blog" ? "post" : $module)];
                                    }
                                }
                            }
                        }
                    }
				}
			}
		}
	}