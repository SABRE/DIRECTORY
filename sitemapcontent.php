<style>
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
				$catLink = LISTING_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$category->getString("friendly_url");
                                echo "<li>";
                                //echo "<img src=\"".DEFAULT_URL."/images/plus.png\" height=\"16px\" width=\"8px\" style=\"float:left;cursor:pointer;\"/>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                if($retrieved_locations){
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,LISTING_DEFAULT_URL);
                                }
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
				$catLink = EVENT_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$category->getString("friendly_url");
				echo "<li>";
                                //echo "<img src=\"".DEFAULT_URL."/images/plus.png\" height=\"16px\" width=\"8px\" style=\"float:left;cursor:pointer;\"/>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                if($retrieved_locations){
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,EVENT_DEFAULT_URL);
                                }
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
				$catLink = CLASSIFIED_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$category->getString("friendly_url");
                                echo "<li>";
                                //echo "<img src=\"".DEFAULT_URL."/images/plus.png\" height=\"16px\" width=\"8px\" style=\"float:left;cursor:pointer;\"/>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                
                                if($retrieved_locations){
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,CLASSIFIED_DEFAULT_URL);
                                }
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
				$catLink = ARTICLE_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$category->getString("friendly_url");
				echo "<li>";
                                //echo "<img src=\"".DEFAULT_URL."/images/plus.png\" height=\"16px\" width=\"8px\" style=\"float:left;cursor:pointer;\"/>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                
                                if($retrieved_locations){
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,ARTICLE_DEFAULT_URL);
                                }
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
				$catLink = PROMOTION_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$category->getString("friendly_url");
				echo "<li>";
                                //echo "<img src=\"".DEFAULT_URL."/images/plus.png\" height=\"16px\" width=\"8px\" style=\"float:left;cursor:pointer;\"/>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                
                                if($retrieved_locations){
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,PROMOTION_DEFAULT_URL);
                                }
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
				$catLink = BLOG_DEFAULT_URL."/".ALIAS_CATEGORY_URL_DIVISOR."/".$category->getString("friendly_url");
				echo "<li>";
                                //echo "<img src=\"".DEFAULT_URL."/images/plus.png\" height=\"16px\" width=\"8px\" style=\"float:left;cursor:pointer;\"/>";
                                echo "<span class=\"plus\"></span><a href=\"".$catLink."\">".$category->getString("title")."</a>";
                                
                                if($retrieved_locations){
                                    echo $categoryStateLinks = categoryStateLinks($category,$retrieved_locations,BLOG_DEFAULT_URL);
                                }
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
    /*Function is created For create the friendly url for category  and state combination on 11-09-2013*/
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
                $returnMessage .= "</ul></div>";
        }
        
        return $returnMessage;
    }
?>
<script>
$('span').click(function(){
   var className = $(this).attr('class');
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
</script>