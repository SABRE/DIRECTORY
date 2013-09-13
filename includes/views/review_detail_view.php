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
	# * FILE: /includes/views/view_review_detail.php
	# ----------------------------------------------------------------------------------------------------
    $item_reviewcommentdetail = "";
    if (!$tPreview) {
        if (!$item_type) { 
            $item_type = 'listing';
        }

        if (!$itemObj) {
            if ($item_type == 'listing') {
                $itemObj = new Listing($item_id);
            } else if ($item_type == 'article') {
                $itemObj = new Article($item_id);
            } else if ($item_type == 'promotion') {
                $itemObj = new Promotion($item_id);
            }
        }

        if ($reviewArea != "profile"){
            $dbObj = db_getDBObject(DEFAULT_DB,true);
            $sql = "SELECT image_id, A.has_profile
                    FROM Profile
                    LEFT JOIN Account A ON (A.id = account_id)
                    WHERE account_id = $member_id";
            $result = $dbObj->query($sql);
            $rowProfile = mysql_fetch_assoc($result);

            if (SOCIALNETWORK_FEATURE == "on") {
                if ($member_id && $rowProfile["has_profile"] == "y") {
                        $imgTag = socialnetwork_writeLink($member_id, "profile", "general_see_profile", $rowProfile["image_id"], false, false, "",$user);
                } else {
                        $imgTag = "<span class=\"no-image no-link\"></span>";
                }
            }
        }
    } else {
        if (SOCIALNETWORK_FEATURE == "on") {
            $imgTag = "<span class=\"no-image\" style=\"cursor: default;\"></span>";
        } else {
            $imgTag = "<span class=\"no-image no-link\"></span>";
        }
    }
	
    $item_default_url = @constant(string_strtoupper($item_type).'_DEFAULT_URL');
	
    if (string_strpos($_SERVER['REQUEST_URI'], ALIAS_REVIEW_URL_DIVISOR."/") || $reviewArea == "profile"){
        $totalReview = $totalReviewsPage;
    } else {
        $totalReview = $numberOfReviews;
    }
	
    $lastItemStyle++;
					
    if ($lastItemStyle == 1) {
        $itemStyle = "first";
    } elseif ($lastItemStyle == $totalReview) {
        $itemStyle = "last";
    } else {
        $itemStyle = "";
    }

    if ($lastItemStyle == $totalReview && $lastItemStyle == 1) {
        $itemStyle .= " last";
    }

    if ($reviewArea == "profile" && $forceLast) {
        $itemStyle = "last";
    }

    if (string_strpos($url_base, "".MEMBERS_ALIAS."") !== false){
        $item_reviewcommentdetail .= "<div class=\"featured featured-review\">";
    } else {
        $item_reviewcommentdetail .= "<div class=\"featured-item ".$itemStyle."\">";
    }
    
    $item_reviewcommentdetail .= "<div class=\"left-image-name-box\">"; 

    if (SOCIALNETWORK_FEATURE == "on" && $reviewArea != "profile") {
        $item_reviewcommentdetail .= "<div class=\"image\">";
        $item_reviewcommentdetail .= $imgTag;
        $item_reviewcommentdetail .= "</div>";
    }
    
    $item_reviewcommentdetail .= "<div class=\"info\">";
    if ($reviewArea != "profile"){
        if (string_strpos($_SERVER['PHP_SELF'], "".SITEMGR_ALIAS."/review/view.php") || string_strpos($_SERVER['PHP_SELF'], "".MEMBERS_ALIAS."/review/view.php")) {
            if (string_strpos($_SERVER['PHP_SELF'], "".MEMBERS_ALIAS."/review/view.php")){
                $item_reviewcommentdetail .= ($reviewer_name) ? "<p>".$reviewer_name."</p>" : "<p>".system_showText(LANG_NA)."</p>";
            } else {
                $item_reviewcommentdetail .= ($reviewer_name) ? $reviewer_name : system_showText(LANG_NA);
            }
        } else {
            if ($member_id) {
                $membersStr = "";
                $membersStr = socialnetwork_writeLink($member_id, "profile", "general_see_profile", false, false, false, '', $user);
                if ($membersStr) {
                    $item_reviewcommentdetail .= ($reviewer_name) ? "<p>".$membersStr."</p>" : "<p>".system_showText(LANG_NA)."</p>";
                } else {
                    $item_reviewcommentdetail .=  ($reviewer_name) ? "<p>".$reviewer_name."</p>" : "<p>".system_showText(LANG_NA)."</p>";
                }
            } else {
                if ($tPreview) {
                    if (SOCIALNETWORK_FEATURE == "on") {
                        $item_reviewcommentdetail .= "<p>".system_showText(LANG_BY)." <a href=\"javascript:void(0);\" style=\"cursor: default;\">".$reviewer_name."</a></p>";
                    } else {
                        $item_reviewcommentdetail .= "<p>".system_showText(LANG_BY)." ".$reviewer_name."</p>";
                    }
                } else {
                    $item_reviewcommentdetail .= ($reviewer_name) ? "<p>".system_showText(LANG_BY)." ".$reviewer_name."</p>" : "<p>".system_showText(LANG_NA)."</p>";
                }
            }
        }
    }
    $item_reviewcommentdetail .= ($reviewer_location) ? "<p>".$reviewer_location."</p>" : "<p>".system_showText(LANG_NA)."</p>";
    if ($response) {
        $item_reviewcommentdetail .= "<div class=\"reply\">";
        $item_reviewcommentdetail .= "<p>". nl2br($response) . "</p>";
        $item_reviewcommentdetail .= "</div>";
    }
    $item_reviewcommentdetail .= "</div>";
    
    $item_reviewcommentdetail .= "</div>";
   	
    if ($rating) {
        unset($rate_stars);
        for ($x=0 ; $x < 5 ;$x++) {
            if ($rating > $x) $rate_stars .= "<img src=\"".DEFAULT_URL."/images/rated.png\" alt=\"Star On\" align=\"bottom\" />";
            else $rate_stars .= "<img src=\"".DEFAULT_URL."/images/rate.png\" alt=\"Star Off\" align=\"bottom\" />";
        }
    }
	
    if (!$tPreview) {
        if (!$itemObj) {
            if ($item_type == 'listing') {
                $itemObj = new Listing($item_id);
            } else if ($item_type == 'article') {
                $itemObj = new Article($item_id);
            }
            else if ($item_type == 'promotion') {
                $itemObj = new Promotion($item_id);
            }
        } 

        if ($show_item) {
            if (!$user) $linkstr = "javascript:void(0)";
            if (string_strpos($url_base, SITEMGR_ALIAS) || string_strpos($url_base, MEMBERS_ALIAS)) {
                $linkstr = $url_base."/".$item_type."/view.php?id=".$item_id;
            } else {
                $linkstr = $item_default_url."/".$itemObj->getString("friendly_url").".html";
            }
            $item_reviewcommentdetail .= "<h3><a href=\"".$linkstr."\">";
            $item_reviewcommentdetail .= $itemObj->getString("title");
            $item_reviewcommentdetail .= "</a></h3>";
        }
    }
    $item_reviewcommentdetail .= "<div class=\"right-section-description\">";
    $item_reviewcommentdetail .= "<h3>\"".$review_title."\"</h3>";
    $item_reviewcommentdetail .= "<div class=\"rate\">".$rate_stars."</div>";
    if (string_strpos($_SERVER['REQUEST_URI'], ALIAS_REVIEW_URL_DIVISOR."/") === false && $reviewArea != "profile" && string_strpos($_SERVER['PHP_SELF'], "".SITEMGR_ALIAS."/review") === false && string_strpos($_SERVER['PHP_SELF'], "".MEMBERS_ALIAS."/review") === false) {
        if (!$user) {
            $item_reviewcommentdetail .= "<a href=\"javascript:void(0);\" style=\"cursor: default;\">".system_showText(LANG_READMORE)."</a>";
        } else {
            $item_reviewcommentdetail .= "<a href=\"".$reviewsLink."\">".system_showText(LANG_READMORE)."</a>";
        }
    }
    $item_reviewcommentdetail .= "<p id=\"date-time\">Reviewed ".format_date($added, DEFAULT_DATE_FORMAT)." - ".format_getTimeString($added)."</p>";
    $item_reviewcommentdetail .= "<p>".((nl2br($review)) ? nl2br($review) : system_showText(LANG_NA))."</p>";
    $item_reviewcommentdetail .= "</div>";
    $item_reviewcommentdetail .= "</div>";
?>