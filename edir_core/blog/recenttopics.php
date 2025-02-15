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
	# * FILE: /edir_core/blog/recenttopics.php
	# ----------------------------------------------------------------------------------------------------
	
	# ----------------------------------------------------------------------------------------------------
	# VALIDATE FEATURE
	# ----------------------------------------------------------------------------------------------------
	if (BLOG_FEATURE != "on" || CUSTOM_BLOG_FEATURE != "on") { exit; }

	# ----------------------------------------------------------------------------------------------------
	# CODE
	# ----------------------------------------------------------------------------------------------------
	if ($aux_show_related_topics) {
		$postsIds = blog_retrieveIdRelatedPosts($_POST["category_id"], $_GET["id"]);
		
		if ($postsIds){
			unset($posts);
			for($i=0;$i<count($postsIds);$i++){
				$posts[] = new Post($postsIds[$i]);
			}

			$nPosts = count($postsIds);
		}
	} else {
		$numberOfPosts = 5;
		$sql = "SELECT Post.* FROM Post WHERE Post.entered <= NOW( ) AND Post.status = 'A' ORDER BY Post.entered DESC LIMIT ".($numberOfPosts+1);
		$posts = db_getFromDBBySQL("post", $sql);
		$nPosts = count($posts);
	}
	
	$featured_post = "";
	$show_post = 1;
	$user = true;

	if (is_array($posts) && $posts[0]) {
		foreach ($posts as $post) {
			if (($show_post <= $numberOfPosts) || $aux_show_related_topics) {
                $detailLink = "".BLOG_DEFAULT_URL."/".$post->getString("friendly_url").".html";
				$featured_post .= "<div class=\"item\">";

				$imageObj = new Image($post->getNumber("thumb_id"));
				$thumbcaption = $post->getString("thumb_caption");
				if ($imageObj->imageExists()) {
					$featured_post .= "<div class=\"image\">";
					$featured_post .= "<a href=\"".$detailLink."\" title=\"".$post->getString("title")."\">";
					$featured_post .= $imageObj->getTag(true, SIDEBAR_FEATURED_WIDTH, SIDEBAR_FEATURED_HEIGHT, ($thumbcaption ? $thumbcaption : $post->getString("title", false)), true);
					$featured_post .= "</a></div>";
				} else {
					$featured_post .= "<div class=\"image\">";
					$featured_post .= "<a href=\"".$detailLink."\" title=\"".$post->getString("title")."\"><span class=\"no-image\"></span></a>";
					$featured_post .= "</div>";
				}

				$featured_post .= "<h3><a href=\"".$detailLink."\" title=\"".$post->getString("title")."\">".$post->getString("title")."</a></h3>";
				$featured_post .= "<p>".LANG_BLOG_ON." ".format_date($post->getString("entered"),DEFAULT_DATE_FORMAT, "datetime")." - ".$post->getTimeString()."</p>";
				$featured_post .= "</div>";
				$show_post++;
			}
		}
	}
	
	if ($nPosts) { ?>
		<h2>
			<? if($aux_show_related_topics) {
				echo system_showText(LANG_BLOG_RELATEDPOSTS);
			} else {
				echo system_showText(LANG_BLOG_RECENTTOPICS);
			} ?>
		</h2>
		<div class="blog-item">
			<?=$featured_post?>
		</div>
    <? }
?>