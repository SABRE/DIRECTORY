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
	# * FILE: /includes/code/featured_article.php
	# ----------------------------------------------------------------------------------------------------

    $numberOfArticles = FEATURED_ARTICLE_MAXITEMS;
	$lastItemStyle = 0;
	$specialItem = FEATURED_ARTICLE_MAXITEMS_SPECIAL;

	$level = implode(",", system_getLevelDetail("ArticleLevel"));

	if ($level) {
		unset($searchReturn);
		$searchReturn = search_frontArticleSearch($_GET, "random");
		$sql = "SELECT ".$searchReturn["select_columns"]." FROM ".$searchReturn["from_tables"]." WHERE ".(($searchReturn["where_clause"])?($searchReturn["where_clause"]." AND"):(""))." (Article.level IN (".$level.")) ".(($searchReturn["group_by"])?("GROUP BY ".$searchReturn["group_by"]):(""))." ORDER BY `publication_date` DESC, `random_number` LIMIT ".$numberOfArticles."";
        $random_articles = db_getFromDBBySQL("article", $sql);
	}

	if ($random_articles) {
        
        if (ARTICLE_SCALABILITY_OPTIMIZATION != "on"){
            $seeAllText = system_showText(LANG_LABEL_VIEW_ALL_ARTICLES);
			$seeAllTextLink = ARTICLE_DEFAULT_URL."/results.php"; 
        }
		
		$count = 0;
		$countSpecialItem = 0;
		$ids_report_lote = "";
        unset($array_show_articles);
        
		foreach ($random_articles as $article) {

			$ids_report_lote .= $article->getString("id").",";
            
            $lastItemStyle++;
            
			$array_show_articles[$count]["detailLink"] = ARTICLE_DEFAULT_URL."/".$article->getString("friendly_url").".html";
            
            unset($imageObj);
            
            if ($countSpecialItem < $specialItem) {
                
                $imageObj = new Image($article->getNumber("thumb_id"));
                if ($imageObj->imageExists()) {
                    $array_show_articles[$count]["image_tag"] = $imageObj->getTag(true, IMAGE_FRONT_ARTICLE_WIDTH, IMAGE_FRONT_ARTICLE_HEIGHT, $article->getString("title", false), true);                    
                } else {
                    $array_show_articles[$count]["image_tag"] = "";
                }
                
                $array_show_articles[$count]["abstract"] = $article->getString("abstract", true, 140);                
				$countSpecialItem++;
                
            }
            
            $array_show_articles[$count]["id"]                  = htmlspecialchars($article->getNumber("id"));
            $array_show_articles[$count]["account_id"]          = $article->getNumber("account_id");
            $array_show_articles[$count]["title"]               = $article->getString("title", true);
                      
            if (ARTICLE_SCALABILITY_OPTIMIZATION != "on") {
                if ($article->getString("publication_date", true)) {
                    $array_show_articles[$count]["publication_string"] = system_showText(LANG_ARTICLE_PUBLISHED).": ".$article->getDate("publication_date");
                } else {
                    $array_show_articles[$count]["publication_string"] = false;
                }
                $array_show_articles[$count]["author_string"] = "";
                if ($article->getString("author", true)) {
                    $array_show_articles[$count]["author_string"] .= system_showText(LANG_BY)." ";
                    if ($article->getString("author_url", true)) {
                        $array_show_articles[$count]["author_string"] .= "<a href=\"".$article->getString("author_url", true)."\" target=\"_blank\">\n";
                    }
                    $array_show_articles[$count]["author_string"] .= " ".$article->getString("author", true);
                    if ($article->getString("author_url", true)) {
                        $array_show_articles[$count]["author_string"] .= "</a>\n";
                    }
                } else {
                    $name = socialnetwork_writeLink($article->getNumber("account_id"), "profile", "general_see_profile");
                    if ($name) {
                        $array_show_articles[$count]["author_string"] .= " ".system_showText(LANG_BY)." ".$name;
                    }
                }

                $array_show_articles[$count]["categories"] = system_itemRelatedCategories($article->getNumber("id"), "article", true);
            }
            
            if ($lastItemStyle == $numberOfArticles) {
                $itemStyle = "last";
            } elseif ($lastItemStyle == ($specialItem+1)) {
                $itemStyle = "first";
            } else {
                $itemStyle = "";
            }
            $array_show_articles[$count]["itemStyle"] = $itemStyle;
            
            $count++;
        }
        
        $ids_report_lote = string_substr($ids_report_lote, 0, -1);
        report_newRecord("article", $ids_report_lote, ARTICLE_REPORT_SUMMARY_VIEW, true);			
	}
?>