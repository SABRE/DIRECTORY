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
	# * FILE: /edir_core/article/featured.php
	# ----------------------------------------------------------------------------------------------------

	// Preparing markers to Full Cache
	?>
	<!--cachemarkerFeaturedArticle2-->
	<?

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
    include(EDIRECTORY_ROOT."/includes/code/featured_article.php");

	if (is_array($array_show_articles)) { ?>
		
		<h2>
			<span><?=system_showText(LANG_FEATURED_ARTICLE)?></span>
			<? if ($seeAllTextLink && $seeAllText) { ?>
				<a class="view-more" href="<?=$seeAllTextLink?>"><?=$seeAllText;?></a>
			<? } ?>
		</h2>
		
		<div class="featured featured-article">
            
		<?
		$countSpecialItem = 0;
        $lastItemStyle = 0;
        for ($i = 0; $i < count($array_show_articles); $i++) {
            
            $lastItemStyle++;
            
			if ($countSpecialItem < $specialItem) {
				if ($countSpecialItem == 0) { ?>
					<div class="left">
				<? }
				
				if (($lastItemStyle % 2) && ($lastItemStyle != 1)) { ?>
					<br class="clear" />
				<? } ?>
				
				<div class="featured-item featured-item-special">

                    <div class="image">
                        <a href="<?=$array_show_articles[$i]["detailLink"]?>" class="image">
                            <? if ($array_show_articles[$i]["image_tag"]) { ?>
                                <?=$array_show_articles[$i]["image_tag"]?>
                            <? } else { ?>
                                <span class="no-image"></span>
                            <? } ?>
                        </a>
                    </div>

                    <h3>
                        <a href="<?=$array_show_articles[$i]["detailLink"]?>">
                            <?=$array_show_articles[$i]["title"]?>
                        </a>
                    </h3>

                    <? if (ARTICLE_SCALABILITY_OPTIMIZATION == "on") { ?>
                    
                        <p id="showCategory_article<?=$array_show_articles[$i]["id"]?>">
                            <a href="javascript: void(0);" onclick="showCategory(<?=$array_show_articles[$i]["id"]?>, 'article', true, <?=$array_show_articles[$i]["account_id"]?>, true, 0)">
                                <?=system_showText(LANG_VIEWCATEGORY)?>
                            </a>
                        </p>

                    <? } else { ?>

                        <p>
                            <?=$array_show_articles[$i]["publication_string"];?> <?=$array_show_articles[$i]["author_string"];?> <?=$array_show_articles[$i]["categories"];?>                 
                        </p>
                    
                    <? } ?>
                
                    <p>
                        <?=$array_show_articles[$i]["abstract"]?>
                    </p>

				</div>
			
				<? if ($countSpecialItem == ($specialItem-1) || (count($array_show_articles) == $countSpecialItem +1)) { ?>
					</div>
				<? }
				
				$countSpecialItem++;

			} else { ?>

				<div class="featured-item <?=$array_show_articles[$i]["itemStyle"]?>">

                    <h3>
                        <a href="<?=$array_show_articles[$i]["detailLink"]?>">
                            <?=$array_show_articles[$i]["title"]?>
                        </a>
                    </h3>

                    <? if (ARTICLE_SCALABILITY_OPTIMIZATION == "on") { ?>
                    
                        <p id="showCategory_article<?=$array_show_articles[$i]["id"]?>">
                            <a href="javascript: void(0);" onclick="showCategory(<?=$array_show_articles[$i]["id"]?>, 'article', true, <?=$array_show_articles[$i]["account_id"]?>, true, 0)">
                                <?=system_showText(LANG_VIEWCATEGORY)?>
                            </a>
                        </p>

                    <? } else { ?>

                        <p>
                            <?=$array_show_articles[$i]["publication_string"];?> <?=$array_show_articles[$i]["author_string"];?> <?=$array_show_articles[$i]["categories"];?>                 
                        </p>
                    
                    <? } ?>
                </div>
			<? } 
		} ?>
		</div>
				
<? }
	// Preparing markers to Full Cache
?>
	<!--cachemarkerFeaturedArticle2-->