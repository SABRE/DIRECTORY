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
	# * FILE: /includes/views/view_comment.php
	# ----------------------------------------------------------------------------------------------------

	$item_review = "";

	$itemObj = new Post($post_id);

	###################################################################
	######################     COMMENTS    ############################
	###################################################################

	$rateObj = new Comments();

	$dbMain = db_getDBObject(DEFAULT_DB, true);
	$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
	$sql = "SELECT * FROM Comments WHERE post_id = $post_id AND approved = 1 ";
	$result = $db->query($sql);
	$review_amount = mysql_affected_rows();
    $reviewsLink = BLOG_DEFAULT_URL."/".$post->getString("friendly_url").".html#info"; 

	if (!$user) {
		$reviewsLink = "javascript:void(0);";
	}
	if ($review_amount == 1) {
		$commentsTag = "<a href=\"".$reviewsLink."\"".$postStyle."><strong>$review_amount</strong> ".system_showText(LANG_LABEL_COMMENT)."</a>";
	} else {
		$commentsTag = "<a href=\"".$reviewsLink."\"".$postStyle."><strong>$review_amount</strong> ".system_showText(LANG_BLOG_COMMENTS)."</a>";
	}
?>