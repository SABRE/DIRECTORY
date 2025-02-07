<style>
    
    .sitemap ul
    {
        padding:0 0 0px;
    }
    .sitemap ul li
    {
        float: none !important;
        line-height: 23px !important;
        padding-left: 5px !important;
        width: 100% !important;
    }
    .sitemap ul li a
    {
        padding-left:5px !important;
        line-height:23px !important;
    }
    
    .sitemap span.plus
    {
        background:url("<?=DEFAULT_URL."/images/plus.png";?>") no-repeat;
        float:left;
        height:16px;
        width:8px;
        cursor:pointer;
    }
    
    .sitemap span.minus
    {
        background:url("<?=DEFAULT_URL."/images/minus.png";?>") no-repeat;
        float:left;
        height:16px;
        width:8px;
        cursor:pointer;
    }
    .sitemap div.categoryandstate
    {
        background:url("<?=DEFAULT_URL."/images/plus.png";?>") no-repeat;
        float:left;
        height:16px;
        width:8px;
        cursor:pointer;
    }
    .sitemap div.subcategoryandstate
    {
        background:url("<?=DEFAULT_URL."/images/plus.png";?>") no-repeat;
        float:left;
        height:16px;
        width:8px;
        cursor:pointer;
    }
    .sitemap div.subcategorylinks
    {
        background:url("<?=DEFAULT_URL."/images/plus.png";?>") no-repeat;
        float:left;
        height:16px;
        width:8px;
        cursor:pointer;
    }
    
    .content-main 
    {
        padding-left: 10px !important;
        padding-right: 10px !important;
        width: 690px !important;
    }
    
    .paginglinks ul li
    {
        float:left !important;
        cursor:pointer !important;
        padding-left:10px !important;
        width:0 !important;
    }
</style>
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
	# * FILE: /sitemapcontent.php
	# ----------------------------------------------------------------------------------------------------
        
        /*Code Made by Naresh on 11-09-2013*/
        $objLocationLabel = "Location3";
        ${"Location3"}= new $objLocationLabel;
        $retrieved_locations = ${"Location3"}->retrieveAllLocation();
        
        /*Code end on 11-09-2013*/
?>

	<h2><?=system_showText(LANG_MENU_SITEMAP);?></h2> 

	<div class="sitemap">

		<h3><a href="<?=DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_HOME);?></a></h3>		
	
		<h3><a href="<?=LISTING_DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_LISTING);?></a></h3>
		<?
		unset($categories);
		if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
			$sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_listing DESC LIMIT 20";
		} else {
			$sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		}
		$categories = db_getFromDBBySQL("listingcategory", $sql);
		if ($categories) {
			echo "<ul>";
			foreach ($categories as $category) {
                                unset($catLink);
				$catLink = LISTING_DEFAULT_URL."/".$category->getString("friendly_url");
                                echo "<li>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                $subcategories = subcategoriesFunction(1,$category->id);
                                
                                
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                                echo "<ul>";
                                echo "<li><div class=\"categoryandstate\"></div>Category/State combination";
                                if($retrieved_locations)
                                {
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,LISTING_DEFAULT_URL);
                                }
                                echo "</li>";
                                echo "<li><div class=\"subcategoryandstate\"></div>Category/Subcategory/State combination";
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\"><ul>";
                                foreach($subcategories as $sub){
                                    unset($subcatLink);
                                    $subcatLink = LISTING_DEFAULT_URL."/".$category->getString("friendly_url")."/".$sub->getString("friendly_url");
                                    echo "<li>";
                                    echo "<div class=\"subcategorylinks\"></div>";
                                    echo "<a href=\"".$subcatLink."\">".$sub->getString("title")."</a>";
                                    if($retrieved_locations)
                                    {
                                        echo $subcategoryStateLinks = subcategoryStateLinks($sub,$retrieved_locations,LISTING_DEFAULT_URL,$category);
                                    }
                                    echo "</li>";
                                }
                                echo "</ul></div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";    
                                
                                echo "</li>";  
			}
			if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
				echo "<li class=\"view-more\"><a href=\"".LISTING_DEFAULT_URL."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".system_showText(LANG_LISTING_VIEWALLCATEGORIES)." &raquo;</a></li>";
			}
			echo "</ul>";
		}
		unset($categories);
		?>
	
		<? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
	
		<h3><a href="<?=EVENT_DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_EVENT);?></a></h3>
		<?
		unset($categories);
		if (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
			$sql = "SELECT id, title, friendly_url FROM EventCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_event DESC LIMIT 20";
		} else {
			$sql = "SELECT id, title, friendly_url FROM EventCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		}
		$categories = db_getFromDBBySQL("eventcategory", $sql);
		if ($categories) {
			echo "<ul>";
			foreach ($categories as $category) {
                                unset($catLink);
				$catLink = EVENT_DEFAULT_URL."/".$category->getString("friendly_url");
                                echo "<li>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                $subcategories = subcategoriesFunction(2,$category->id);
                               
                               
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                                echo "<ul>";
                                echo "<li><div class=\"categoryandstate\"></div>Category/State combination";
                                if($retrieved_locations)
                                {
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,EVENT_DEFAULT_URL);
                                }
                                echo "</li>";
                                echo "<li><div class=\"subcategoryandstate\"></div>Category/Subcategory/State combination";
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\"><ul>";
                                foreach($subcategories as $sub){
                                    unset($subcatLink);
                                    $subcatLink = EVENT_DEFAULT_URL."/".$category->getString("friendly_url")."/".$sub->getString("friendly_url");
                                    echo "<li>";
                                    echo "<div class=\"subcategorylinks\"></div>";
                                    echo "<a href=\"".$subcatLink."\">".$sub->getString("title")."</a>";
                                    if($retrieved_locations)
                                    {
                                        echo $subcategoryStateLinks = subcategoryStateLinks($sub,$retrieved_locations,EVENT_DEFAULT_URL,$category);
                                    }
                                    echo "</li>";
                                }
                                echo "</ul></div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";    
                                
                                echo "</li>";  
			}
			if (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
				echo "<li class=\"view-more\"><a href=\"".EVENT_DEFAULT_URL."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".system_showText(LANG_EVENT_VIEWALLCATEGORIES)." &raquo;</a></li>";
			}
			echo "</ul>";
		}
		unset($categories);
		?>
	
		<? } ?>
	
		<? if (CLASSIFIED_FEATURE == "on" && CUSTOM_CLASSIFIED_FEATURE == "on") { ?>
	
		<h3><a href="<?=CLASSIFIED_DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_CLASSIFIED);?></a></h3>
		<?
		unset($categories);
		if (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
			$sql = "SELECT id, title, friendly_url FROM ClassifiedCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_classified DESC LIMIT 20";
		} else {
			$sql = "SELECT id, title, friendly_url FROM ClassifiedCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		}
		$categories = db_getFromDBBySQL("classifiedcategory", $sql);
		if ($categories) {
			echo "<ul>";
			foreach ($categories as $category) {
                                unset($catLink);
				$catLink = CLASSIFIED_DEFAULT_URL."/".$category->getString("friendly_url");
                                echo "<li>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                $subcategories = subcategoriesFunction(3,$category->id);
                                
                               
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                                echo "<ul>";
                                echo "<li><div class=\"categoryandstate\"></div>Category/State combination";
                                if($retrieved_locations)
                                {
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,CLASSIFIED_DEFAULT_URL);
                                }
                                echo "</li>";
                                echo "<li><div class=\"subcategoryandstate\"></div>Category/Subcategory/State combination";
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\"><ul>";
                                foreach($subcategories as $sub){
                                    unset($subcatLink);
                                    $subcatLink = CLASSIFIED_DEFAULT_URL."/".$category->getString("friendly_url")."/".$sub->getString("friendly_url");
                                    echo "<li>";
                                    echo "<div class=\"subcategorylinks\"></div>";
                                    echo "<a href=\"".$subcatLink."\">".$sub->getString("title")."</a>";
                                    if($retrieved_locations)
                                    {
                                        echo $subcategoryStateLinks = subcategoryStateLinks($sub,$retrieved_locations,CLASSIFIED_DEFAULT_URL,$category);
                                    }
                                    echo "</li>";
                                }
                                echo "</ul></div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";    
                                
                                echo "</li>";  
			}
			if (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
				echo "<li class=\"view-more\"><a href=\"".CLASSIFIED_DEFAULT_URL."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".system_showText(LANG_CLASSIFIED_VIEWALLCATEGORIES)." &raquo;</a></li>";
			}
			echo "</ul>";
		}
		unset($categories);
		?>
	
		<? } ?>
	
		<? if (ARTICLE_FEATURE == "on" && CUSTOM_ARTICLE_FEATURE == "on") { ?>
	
		<h3><a href="<?=ARTICLE_DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_ARTICLE);?></a></h3>
		<?
		unset($categories);
		if (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == "on") {
			$sql = "SELECT id, title, friendly_url FROM ArticleCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_article DESC LIMIT 20";
		} else {
			$sql = "SELECT id, title, friendly_url FROM ArticleCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		}
		$categories = db_getFromDBBySQL("articlecategory", $sql);
		if ($categories) {
			echo "<ul>";
			foreach ($categories as $category) {
                                unset($catLink);
				$catLink = ARTICLE_DEFAULT_URL."/".$category->getString("friendly_url");
                                echo "<li>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                $subcategories = subcategoriesFunction(4,$category->id);
                                
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                                echo "<ul>";
                                echo "<li><div class=\"categoryandstate\"></div>Category/State combination";
                                if($retrieved_locations)
                                {
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,ARTICLE_DEFAULT_URL);
                                }
                                echo "</li>";
                                echo "<li><div class=\"subcategoryandstate\"></div>Category/Subcategory/State combination";
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\"><ul>";
                                foreach($subcategories as $sub){
                                    unset($subcatLink);
                                    $subcatLink = ARTICLE_DEFAULT_URL."/".$category->getString("friendly_url")."/".$sub->getString("friendly_url");
                                    echo "<li>";
                                    echo "<div class=\"subcategorylinks\"></div>";
                                    echo "<a href=\"".$subcatLink."\">".$sub->getString("title")."</a>";
                                    if($retrieved_locations)
                                    {
                                        echo $subcategoryStateLinks = subcategoryStateLinks($sub,$retrieved_locations,ARTICLE_DEFAULT_URL,$category);
                                    }
                                    echo "</li>";
                                }
                                echo "</ul></div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";    
                                
                                echo "</li>";  
			}
			if (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == "on") {
				echo "<li class=\"view-more\"><a href=\"".ARTICLE_DEFAULT_URL."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".system_showText(LANG_ARTICLE_VIEWALLCATEGORIES)." &raquo;</a></li>";
			}
			echo "</ul>";
		}
		unset($categories);
		?>
	
	
		<? } ?>
	
		<? if ((PROMOTION_FEATURE == "on") && (CUSTOM_PROMOTION_FEATURE == "on") && (CUSTOM_HAS_PROMOTION == "on")) { ?>
			
		<h3><a href="<?=PROMOTION_DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_PROMOTION);?></a></h3>
		<?
		unset($categories);
		if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
			$sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_listing DESC LIMIT 20";
		} else {
			$sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		}
		$categories = db_getFromDBBySQL("listingcategory", $sql);
		if ($categories) {
			echo "<ul>";
			foreach ($categories as $category) {
                                unset($catLink);
				$catLink = PROMOTION_DEFAULT_URL."/".$category->getString("friendly_url");
                                echo "<li>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                $subcategories = subcategoriesFunction(5,$category->id);
                                
                                
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                                echo "<ul>";
                                echo "<li><div class=\"categoryandstate\"></div>Category/State combination";
                                if($retrieved_locations)
                                {
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,PROMOTION_DEFAULT_URL);
                                }
                                echo "</li>";
                                echo "<li><div class=\"subcategoryandstate\"></div>Category/Subcategory/State combination";
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\"><ul>";
                                foreach($subcategories as $sub){
                                    unset($subcatLink);
                                    $subcatLink = PROMOTION_DEFAULT_URL."/".$category->getString("friendly_url")."/".$sub->getString("friendly_url");
                                    echo "<li>";
                                    echo "<div class=\"subcategorylinks\"></div>";
                                    echo "<a href=\"".$subcatLink."\">".$sub->getString("title")."</a>";
                                    if($retrieved_locations)
                                    {
                                        echo $subcategoryStateLinks = subcategoryStateLinks($sub,$retrieved_locations,PROMOTION_DEFAULT_URL,$category);
                                    }
                                    echo "</li>";
                                }
                                echo "</ul></div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";    
                                
                                echo "</li>";  
			}
			if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
				echo "<li class=\"view-more\"><a href=\"".PROMOTION_DEFAULT_URL."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".system_showText(LANG_PROMOTION_VIEWALLCATEGORIES)." &raquo;</a></li>";
			}
			echo "</ul>";
		}
		unset($categories);
		?>
	
		<? } ?>
	
		<? if (BLOG_FEATURE == "on" && CUSTOM_BLOG_FEATURE == "on") { ?>
	
		<h3><a href="<?=BLOG_DEFAULT_URL?>/" class="sitemapSection"><?=system_showText(LANG_MENU_BLOG);?></a></h3>
		<?
        unset($categories);
		if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
			$sql = "SELECT id, title, friendly_url FROM BlogCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_post DESC LIMIT 20";
		} else {
			$sql = "SELECT id, title, friendly_url FROM BlogCategory WHERE category_id = 0 AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
		}
		$categories = db_getFromDBBySQL("blogcategory", $sql);
		if ($categories) {
			echo "<ul>";
			foreach ($categories as $category) {
                                unset($catLink);
				$catLink = BLOG_DEFAULT_URL."/".$category->getString("friendly_url");
                                echo "<li>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                $subcategories = subcategoriesFunction(6,$category->id);
                               
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                                echo "<ul>";
                                echo "<li><div class=\"categoryandstate\"></div>Category/State combination";
                                if($retrieved_locations)
                                {
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,BLOG_DEFAULT_URL);
                                }
                                echo "</li>";
                                echo "<li><div class=\"subcategoryandstate\"></div>Category/Subcategory/State combination";
                                echo "<div class=\"categoryStateLinks\" style=\"display:none;\"><ul>";
                                foreach($subcategories as $sub){
                                    unset($subcatLink);
                                    $subcatLink = BLOG_DEFAULT_URL."/".$category->getString("friendly_url")."/".$sub->getString("friendly_url");
                                    echo "<li>";
                                    echo "<div class=\"subcategorylinks\"></div>";
                                    echo "<a href=\"".$subcatLink."\">".$sub->getString("title")."</a>";
                                    if($retrieved_locations)
                                    {
                                        echo $subcategoryStateLinks = subcategoryStateLinks($sub,$retrieved_locations,BLOG_DEFAULT_URL,$category);
                                    }
                                    echo "</li>";
                                }
                                echo "</ul></div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";    
                               
                                echo "</li>";  
			}
                        if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
				echo "<li class=\"view-more\"><a href=\"".BLOG_DEFAULT_URL."/".ALIAS_ALLCATEGORIES_URL_DIVISOR.".php\">".system_showText(LANG_BLOG_VIEWALLCATEGORIES)." &raquo;</a></li>";
			}
			echo "</ul>";
		}
		unset($categories);
		?>
	
		<? } ?>
	
		<h3><a href="<?=DEFAULT_URL?>/<?=ALIAS_ADVERTISE_URL_DIVISOR?>.php" class="sitemapSection"><?=system_showText(LANG_MENU_ADVERTISE);?></a></h3>
	
		<h3><a href="<?=DEFAULT_URL?>/<?=ALIAS_FAQ_URL_DIVISOR?>.php" class="sitemapSection"><?=system_showText(LANG_MENU_FAQ);?></a></h3>
	
		<h3><a href="<?=DEFAULT_URL?>/<?=ALIAS_CONTACTUS_URL_DIVISOR?>.php" class="sitemapSection"><?=system_showText(LANG_MENU_CONTACT);?></a></h3>

	</div>
<? 

// COmmented on the 23-09-2013//    
    /*Function is created For create the friendly url for category  and state combination on 11-09-2013*/
//    function categoryStateLinks($category,$retrieved_locations,$module)
//    {
//        $returnMessage = '';
//        if($category){
//                $returnMessage .= "<div class=\"categoryStateLinks\" style=\"display:none;\">";
//                $returnMessage .= "<ul>";
//                foreach($retrieved_locations as $each_location){
//                    $catStateLink = $module."/".$category->getString("friendly_url")."/".$each_location['friendly_url'];
//                    $returnMessage .= "<li><a href=\"".$catStateLink."\">".$category->getString("title")."/".$each_location['name']."</a></li>";
//                }
//                $returnMessage .= "</ul></div>";
//        }
//        
//        return $returnMessage;
//    }

    function categoryStateLinks($category,$retrieved_locations,$module)
    {
        $returnMessage = '';
        if($category){
                $returnMessage .= "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                $returnMessage .= "<ul>";
                foreach($retrieved_locations as $each_location){
                    $catStateLink = $module."/".$category->getString("friendly_url")."/".$each_location['friendly_url'];
                    $returnMessage .= "<li><a href=\"".$catStateLink."\">".$category->getString("title")."/".$each_location['name']."</a></li>";
                }
                $returnMessage .= "</ul>";
                $countLocations = count($retrieved_locations);
                if($countLocations > 10 ){
                    $returnMessage .= "<div class=\"paginglinks\">";
                    $returnMessage .= "<ul>";
                    for($i = 1,$j=1;$i <= $countLocations;$i = $i+10,$j++){
                       $returnMessage .= "<li class=\"link\" id=\"link_$j\">$j</li>";
                    }
                    $returnMessage .= "</ul>";
                    $returnMessage .= "</div>";
                }
                $returnMessage .= "</div>";
        }
        
        return $returnMessage;
    }
    
    function subcategoriesFunction($number , $id)
    {
        $subCategories = array();
        switch($number)
        {
            case 1:
            if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
                $sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_listing DESC LIMIT 20";
            }else {
                $sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            }
            $subCategories = db_getFromDBBySQL("listingcategory", $sql);
            break;
            case 2:
            if (EVENTCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
                $sql = "SELECT id, title, friendly_url FROM EventCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_event DESC LIMIT 20";
            } else {
                $sql = "SELECT id, title, friendly_url FROM EventCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            }
            $subCategories = db_getFromDBBySQL("eventcategory", $sql);
            break;
            case 3:
            if (CLASSIFIEDCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
                $sql = "SELECT id, title, friendly_url FROM ClassifiedCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_classified DESC LIMIT 20";
            } else {
                $sql = "SELECT id, title, friendly_url FROM ClassifiedCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            }
            
            $subCategories = db_getFromDBBySQL("classifiedcategory", $sql);
            break;
            case 4:
            if (ARTICLECATEGORY_SCALABILITY_OPTIMIZATION == "on") {
                $sql = "SELECT id, title, friendly_url FROM ArticleCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_article DESC LIMIT 20";
            } else {
                $sql = "SELECT id, title, friendly_url FROM ArticleCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            }
            $subCategories = db_getFromDBBySQL("articlecategory", $sql);
            break;
            case 5:
            if (LISTINGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
                $sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_listing DESC LIMIT 20";
            } else {
                $sql = "SELECT id, title, friendly_url FROM ListingCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            }
            $subCategories = db_getFromDBBySQL("listingcategory", $sql);
            break;
            case 6:
            if (BLOGCATEGORY_SCALABILITY_OPTIMIZATION == "on") {
                $sql = "SELECT id, title, friendly_url FROM BlogCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY active_post DESC LIMIT 20";
            } else {
                $sql = "SELECT id, title, friendly_url FROM BlogCategory WHERE category_id =".$id." AND title <> '' AND friendly_url <> '' AND enabled = 'y' ORDER BY title LIMIT ".MAX_SHOW_ALL_CATEGORIES;
            }
            $subCategories = db_getFromDBBySQL("blogcategory", $sql);
            break;
            default:
             //   
        }
        return $subCategories;
    }
    
// COmmented on the 23-09-2013//    
//    function subcategoryStateLinks($subcategory,$retrieved_locations,$module,$category)
//    {
//        $returnMessage = '';
//        if($category){
//                $returnMessage .= "<div class=\"categoryStateLinks\" style=\"display:none;\">";
//                $returnMessage .= "<ul>";
//                $subcatStateLink = $module."/".$category->getString("friendly_url")."/".$subcategory->getString("friendly_url")."/";
//                foreach($retrieved_locations as $each_location){
//                    $returnMessage .= "<li><a href=\"".$subcatStateLink.$each_location['friendly_url']."\">".$category->getString("title")."/".$subcategory->getString("friendly_url")."/".$each_location['name']."</a></li>";
//                }
//                $returnMessage .= "</ul></div>";
//        }
//        
//        return $returnMessage;
//    }
    
    function subcategoryStateLinks($subcategory,$retrieved_locations,$module,$category)
    {
        $returnMessage = '';
        if($category){
                $returnMessage .= "<div class=\"categoryStateLinks\" style=\"display:none;\">";
                $returnMessage .= "<ul>";
                $subcatStateLink = $module."/".$category->getString("friendly_url")."/".$subcategory->getString("friendly_url")."/";
                
                foreach($retrieved_locations as $each_location){
                    $returnMessage .= "<li><a href=\"".$subcatStateLink.$each_location['friendly_url']."\">".$category->getString("title")."/".$subcategory->getString("friendly_url")."/".$each_location['name']."</a></li>";
                }
                $returnMessage .= "</ul>";
                $countLocations = count($retrieved_locations);
                if($countLocations > 10 ){
                    $returnMessage .= "<div class=\"paginglinks\">";
                    $returnMessage .= "<ul>";
                    for($i = 1,$j=1;$i <= $countLocations;$i = $i+10,$j++){
                       $returnMessage .= "<li class=\"sublink\" id=\"sublink_$j\">$j</li>";
                    }
                    $returnMessage .= "</ul>";
                    $returnMessage .= "</div>";
                }
                $returnMessage .="</div>";
        }
        
        return $returnMessage;
    }


?>
<script>
$('span').click(function(){
   var className = $(this).attr('class');
    $('.subcategorylinks').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.subcategorylinks').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    $('.subcategoryandstate').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.subcategoryandstate').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    $('.categoryandstate').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.categoryandstate').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
   if(className == 'plus')
   {
       $('span').removeClass('minus');
       $('span').addClass('plus');
       $('span').parent('li').children('div .categoryStateLinks').slideUp('slow');
       $(this).parent('li').children('div .categoryStateLinks').slideDown('slow');
       $(this).removeClass('plus');
       $(this).addClass('minus');
   }
   else if(className == 'minus')
   {
       $(this).removeClass('minus');
       $(this).parent('li').children('div .categoryStateLinks').slideUp('slow');
       $(this).addClass('plus');
   }
});

$('.categoryandstate').click(function(){
    $('.subcategorylinks').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.subcategorylinks').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    $('.subcategoryandstate').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.subcategoryandstate').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    if($(this).parent('li').children('div .categoryStateLinks').is(':visible')){
        $(this).parent('li').children('div .categoryStateLinks').slideUp('slow');
        $(this).css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    }else{
        $(this).parent('li').children('div .categoryStateLinks').slideDown('slow');
        $(this).parent('li').children('div .categoryStateLinks').find('li').css('display','none');
        $(this).parent('li').children('div .categoryStateLinks').find('li').slice(0,10).css('display','block');
        $(this).parent('li').children('div .categoryStateLinks').find('.paginglinks').find('li').css('display','block');
        $(this).css('background-image','url(\"<?=DEFAULT_URL."/images/minus.png";?>\")');
    }
});

$('.subcategoryandstate').click(function(){
    $('.subcategorylinks').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.subcategorylinks').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    $('.categoryandstate').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.categoryandstate').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    if($(this).parent('li').children('div .categoryStateLinks').is(':visible')){
        $(this).parent('li').children('div .categoryStateLinks').slideUp('slow');
         $(this).css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    }else{
        $(this).parent('li').children('div .categoryStateLinks').slideDown('slow');
         $(this).css('background-image','url(\"<?=DEFAULT_URL."/images/minus.png";?>\")');
    }
   
});

$('.subcategorylinks').click(function(){
    
    $('.subcategorylinks').parent('li').children('div .categoryStateLinks').slideUp('slow');
    $('.subcategorylinks').css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    if($(this).parent('li').children('div .categoryStateLinks').is(':visible')){
        $(this).parent('li').children('div .categoryStateLinks').slideUp('slow');
         $(this).css('background-image','url(\"<?=DEFAULT_URL."/images/plus.png";?>\")');
    }else{
         $(this).parent('li').children('div .categoryStateLinks').slideDown('slow');
         $(this).parent('li').children('div .categoryStateLinks').find('li').css('display','none');
         $(this).parent('li').children('div .categoryStateLinks').find('li').slice(0,10).css('display','block');
         $(this).parent('li').children('div .categoryStateLinks').find('.paginglinks').find('li').css('display','block');
         $(this).css('background-image','url(\"<?=DEFAULT_URL."/images/minus.png";?>\")');
    }
});

$('.sublink').click(function(){
    var id = $(this).attr('id').replace(/sublink_/,'');
    var start;
    var end;
    if(id == 1){
        start = 0;
        end = 10;
    }else{
        start = (id-1)*10;
        end = id*10;
    }
    $(this).parent('ul').parent('div .paginglinks').parent('div .categoryStateLinks').find('li').css('display','none');
    $(this).parent('ul').parent('div .paginglinks').parent('div .categoryStateLinks').find('li').slice(start,end).css('display','block');
    $(this).parent('ul').parent('div .paginglinks').find('li').css('display','block');
    
});


$('.link').click(function(){
    var id = $(this).attr('id').replace(/link_/,'');
    var start;
    var end;
    if(id == 1){
        start = 0;
        end = 10;
    }else{
        start = (id-1)*10;
        end = id*10;
    }
    
    $(this).parent('ul').parent('div .paginglinks').parent('div .categoryStateLinks').find('li').css('display','none');
    $(this).parent('ul').parent('div .paginglinks').parent('div .categoryStateLinks').find('li').slice(start,end).css('display','block');
    $(this).parent('ul').parent('div .paginglinks').find('li').css('display','block');
    
});

</script>