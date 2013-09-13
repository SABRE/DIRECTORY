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
	# * FILE: /theme/realestate/frontend/featured_promotion_review.php
	# ----------------------------------------------------------------------------------------------------

	setting_get('commenting_edir', $commenting_edir);
    setting_get('review_promotion_enabled', $review_enabled);

    if ($review_enabled == 'on' && $commenting_edir) {
		# ----------------------------------------------------------------------------------------------------
		# LIMIT
		# ----------------------------------------------------------------------------------------------------

        $lastItemStyle = 0;
        $numberOfReviews = 3;
        $reviewMaxSize = 120;

		# ----------------------------------------------------------------------------------------------------
		# CODE
		# ----------------------------------------------------------------------------------------------------

		$sql = "SELECT item_id,
					member_id,
					added,
					reviewer_name,
					reviewer_location,
					review_title,
					review,
					rating,
					Account.image_id,
					Account.facebook_image,
					Account.has_profile,
					Promotion.id,
					Promotion.name,
					Promotion.friendly_url
				FROM Review
				INNER JOIN  Promotion ON Review.item_id = Promotion.id
				LEFT JOIN AccountProfileContact Account ON (Account.account_id = member_id)
				WHERE item_type = 'promotion' AND approved = 1 AND Promotion.listing_id > 0 AND Promotion.listing_status = 'A' ORDER BY added DESC LIMIT " . $numberOfReviews;
		$dbObj = db_getDBObject();
		$result = $dbObj->query($sql);

		if (mysql_numrows($result)) {

			echo "<h2><span>".system_showText(LANG_RECENT_REVIEWS)."</span></h2>";

			echo "<div class=\"featured featured-review\">";

			while($row = mysql_fetch_array($result)) {

				$lastItemStyle++;

				if($lastItemStyle==1){
					$itemStyle = "first";
				}elseif($lastItemStyle==3){
                        $itemStyle = "";
				}else{
					$itemStyle = "";
				}

				echo "<div class=\"featured-item ".$itemStyle."\">";

				if (SOCIALNETWORK_FEATURE == "on") {
					if ($row["member_id"] && $row["has_profile"] == "y") {
						$imgTag = socialnetwork_writeLink($row["member_id"], "profile", "general_see_profile", $row["image_id"], false, false);
						if (!$imgTag){
							$imgTag = "<span class=\"no-image no-link\"></span>";
						}
					} else {
						$imgTag = "<span class=\"no-image no-link\"></span>";
					}
				}

				$rate_stars = "";
				if ($row['rating']) {
					for ($x=0 ; $x < 5 ;$x++) {
						if ($row['rating'] > $x) $rate_stars .= "<img src=\"".DEFAULT_URL."/images/img_rateMiniStarOn.png\" alt=\"Star On\" align=\"bottom\" />";
						else $rate_stars .= "<img src=\"".DEFAULT_URL."/images/img_rateMiniStarOff.png\" alt=\"Star Off\" align=\"bottom\" />";
					}
				}

                $detailLink 	= "".PROMOTION_DEFAULT_URL."/".ALIAS_REVIEW_URL_DIVISOR."/".$row["friendly_url"];
                $detailItemLink = "".PROMOTION_DEFAULT_URL."/".$row["friendly_url"].".html";

				if (SOCIALNETWORK_FEATURE == "on") {
					echo "<div class=\"image\">";
					echo $imgTag;
					echo "</div>";
				}

				echo "<div class=\"featured-review-text\"><h3><a href=\"".$detailItemLink."\">".string_htmlentities($row["name"])."</a></h3>";

				echo "<div class=\"rate\">";
					echo $rate_stars;
				echo "</div>";

				echo "<a href=\"".$detailLink."\">".system_showText(LANG_READMORE)."</a>";

				echo "<div class=\"info\">";

					$str_time = format_getTimeString($row['added']);

					$publication_string = "";
					$membersStr = "";
                    $publication_string .= "<p class=\"date\">".format_date($row['added'], DEFAULT_DATE_FORMAT, "datetime")." - ".$str_time."</p>";
					if ($row['member_id']) {
						$membersStr = socialnetwork_writeLink($row['member_id'], "profile", "general_see_profile");
						if ($membersStr)
                            $publication_string .= "<p>".system_showText(LANG_BY)."&nbsp;".(($row['reviewer_name']) ? $membersStr : system_showText(LANG_NA)).", </p>";
						else
							$publication_string .= "<p>".system_showText(LANG_BY)."&nbsp;".(($row['reviewer_name']) ? string_htmlentities($row['reviewer_name']) : system_showText(LANG_NA)).", </p>";
					} else {
						$publication_string .= "<p>".system_showText(LANG_BY)."&nbsp;".(($row['reviewer_name']) ? string_htmlentities($row['reviewer_name']) : system_showText(LANG_NA)).", </p>";
					}
					$publication_string .= "<p>".(($row['reviewer_location']) ? string_htmlentities($row['reviewer_location']) : system_showText(LANG_NA))."</p>";

					echo $publication_string;
					$publication_string = "";

				echo "</div>";
                
                $review = "";
                if (string_strlen(trim($row['review'])) > 0) {
                    $review .= system_showTruncatedText($row['review'], $reviewMaxSize);
                }

                echo "<p class=\"review-text\">".$review."</p>";

                echo "</div></div>";				

			}
			echo "</div>";
		}
    }
?>