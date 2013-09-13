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
	# * FILE: /edir_core/blog/prepare_results.php
	# ----------------------------------------------------------------------------------------------------

    if (!$isDetail) {
        report_newRecord("post", $post->getString("id"), POST_REPORT_SUMMARY_VIEW);
    }
	
	setting_get("wp_enabled", $wp_enabled);
	
	if (BLOG_WITH_WORDPRESS == "on") {
		$force_blog_module = true;
	}
				
    $detailLink = "".BLOG_DEFAULT_URL."/".$post->getString("friendly_url").".html";
    
	$styleLink = "";
    $postStyle = "";
    
	if (!$user) {
		$detailLink = "javascript:void(0);";
		$styleLink = "style=\"cursor:default\"";
		$postStyle = "style=\"cursor:default\"";
	}

	$truncatedTitle = $post->getString("title", true, 40);
	$title = $post->getString("title");
    
    if ($isDetail) {
        $categories = $post->getCategories(false, false, $post->getNumber("id"), true);
		if ($categories) {
			$array_categories_obj = array();
			for ($i=0; $i<count($categories); $i++) {
				unset($categoryObj);
				$categoryObj = new BlogCategory($categories[$i]["id"]);
				$arr_full_path[] = $categoryObj->getFullPath();
				$array_categories_obj[] = $categoryObj;	
			}

			if ($arr_full_path) {
				$postCategoryTree = system_generateCategoryTree($array_categories_obj, $arr_full_path, "blog", $user);
			}	
		}
    } else {
    
        if (BLOG_SCALABILITY_OPTIMIZATION == "on") {
			$post_category = "<a href=\"javascript: void(0);\" ".($user ? "onclick=\"showCategory(".htmlspecialchars($post->getNumber("id")).", 'blog', ".($user ? true : false).", 0)\"" : "style=\"cursor: default;\"").">".system_showText(LANG_VIEWCATEGORY)."</a>";
		} else {
			$post_category = system_itemRelatedCategories($post->getNumber("id"), "blog", $user);
		}
        if ($post_category) {
            $postCategoryTree = $post_category;
        }

    }
	$postOn = LANG_BLOG_ON." ".format_date($post->getString("entered"),DEFAULT_DATE_FORMAT, "datetime")." - ".$post->getTimeString();

	$imageObj = new Image($post->getNumber("image_id"));
	if ($imageObj->imageExists()) {
		$thumbcaption = $post->getString("thumb_caption");
		$postImage = $imageObj->getTag(true, IMAGE_BLOG_THUMB_WIDTH_FULL, IMAGE_BLOG_THUMB_HEIGHT_FULL, ($thumbcaption ? $thumbcaption : $post->getString("title", false)), true);
	} else {
		if ($wp_enabled == "on" && $force_blog_module){
			$postImage = "";
		} else {
			$postImage = "<span class=\"no-image\"></span>";
		}
	}

	$more = false;
	if ($post->getString("content", false)) {
		if ($wp_enabled == "on") {
			$postContent = $post->getString("content", false);
		} else {
			$postContent = nl2br(blog_getContentbyCharacters($post->getString("content", false, $more), BLOG_MAX_CHARACTERS, $detailLink, $more));
		}
        $postContentPinterest = $post->getString("seo_abstract", true);
	} else {
		$postContent = "";
	}	
	
	if ($isDetail) {
        $imageTag = "";
		$imageObj = new Image($post->getNumber("image_id"));
		$thumbcaption = $post->getString("thumb_caption");
		$imagecaption = $post->getString("image_caption");
		if ($imageObj->imageExists()) {
			$imageTag .= "<div class=\"no-link\" ".(RESIZE_IMAGES_UPGRADE == "off" ? "style=\"text-align:center\"" : "").">";
			$imageTag .= $imageObj->getTag(true, IMAGE_BLOG_FULL_WIDTH, IMAGE_BLOG_FULL_HEIGHT, ($thumbcaption ? $thumbcaption : $post->getString("title", false)), true);
			$imageTag .= "</div>";
            $aux_thumbcaption = "";
            if (USE_GALLERY_PLUGIN) {
                $aux_thumbcaption = "<strong style=\"display:block\">$thumbcaption</strong>";
            }
            if ($imagecaption) {
                $imageTag .= "<p class=\"image-caption\">$aux_thumbcaption".$imagecaption."</p>";
            }
            $auxImgPath = $imageObj->getPath();
		} else {
			if ($wp_enabled == "on" && $force_blog_module) {
				$imageTag = "";
			} else {
				$imageTag = "<span class=\"no-image no-link\"></span>";
			}
		}
		
		include(INCLUDES_DIR."/views/icon_post.php");
        
        /*
        * Google+ Button
        */
        $post_googleplus_button = share_getGoogleButton($tPreview, $user);

        /*
        * Pinterest Button
        */
        $post_pinterest_button = share_getPinterestButton($auxImgPath, $post->getFriendlyURL(), $postContentPinterest, $title, $tPreview, $user);
        
        /*
        * Facebook Buttons
        */
        $post_facebook_buttons = share_getFacebookButton(false, $likeObj, $tPreview, $user);
        
		$content = $post->getString("content", false);
		
		if (!$user) {

			$dbMain = db_getDBObject(DEFAULT_DB, true);
			$dbObj = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
			$sql_comment = "SELECT * FROM Comments WHERE post_id = $id AND reply_id = 0 AND approved = 1 ORDER BY `added` DESC";
			$result = $dbObj->query($sql_comment);
			while ($row = mysql_fetch_assoc($result)) {
				$commentArr[] = $row;
			}
		}
        
		if ($commentArr) {
			for ($i = 0; $i < count($commentArr); $i++) {
				
				if ($i == 0) {
					$className = "first";
				} else {
					$className = "";
				}
				
				include(INCLUDES_DIR."/views/view_comment_detail.php");
				$detail_comment .= $item_reviewcomment;
			}
		}
        
        $post_id = $id;
        setting_get("commenting_fb", $commenting_fb);
		setting_get("commenting_edir", $commenting_edir);
		setting_get("review_blog_enabled", $review_blog_enabled);
		$showLabel = true;
        
        if ($commenting_fb) {
            $detailLink = BLOG_DEFAULT_URL."/".ALIAS_SHARE_URL_DIVISOR."/".$post->getString("friendly_url").".html";
			setting_get("commenting_fb_number_comments", $commenting_fb_number_comments);
        }
	}
?>