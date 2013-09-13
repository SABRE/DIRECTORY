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
	# * FILE: /includes/views/view_review.php
	# ----------------------------------------------------------------------------------------------------
	$item_review = "";

	if (!$tPreview) {
		if ($article) {
			$aux = $article->data_in_array;
		} else {
			if ((ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER) || string_strpos($_SERVER["PHP_SELF"], "".SITEMGR_ALIAS."/".PROMOTION_FEATURE_FOLDER) !== false || string_strpos($_SERVER["PHP_SELF"], "".MEMBERS_ALIAS."/".PROMOTION_FEATURE_FOLDER) !== false) {
				if (is_array($promotion)) {
					$aux = $promotion;
				} else if (is_object($promotion)) {
					$aux = $promotion->data_in_array;
				}
			} else {
				if (is_array($listing)) {
					$aux = $listing;
				} else if (is_object($listing)) {
					$aux = $listing->data_in_array;
				}
			}
		}

		$item_default_url = constant(string_strtoupper($item_type).'_DEFAULT_URL');
	}

	###################################################################
	######################     REVIEWS    #############################
	###################################################################

	if ($review_enabled == "on" && $commenting_edir) {

		if ($tPreview) {
			$rate_avg = 3;
		} else {
			$rate_avg = htmlspecialchars($aux["avg_review"]);
			$rate_avg = (isset($rate_avg) && $rate_avg != 0) ? round($rate_avg, 2) : system_showText(LANG_NA);
		}
		unset($rate_stars);

		if ($rate_avg) {
			if ($tPreview) {
				$review_amount = 10;
			} else {
				$dbMain = db_getDBObject(DEFAULT_DB, true);
				$db = db_getDBObjectByDomainID(SELECTED_DOMAIN_ID, $dbMain);
				$sql = "SELECT * FROM Review WHERE item_type = '$item_type' AND item_id = ".htmlspecialchars($aux["id"])." AND review IS NOT NULL AND review != '' AND approved=1";

				$r = $db->query($sql);
				$review_amount = mysql_num_rows($r);
			}

            //Link to open the Review Form
            $linkReviewFormPopup = DEFAULT_URL."/popup/popup.php?pop_type=reviewformpopup&amp;item_type=".$item_type."&amp;item_id=".htmlspecialchars($aux["id"]);
            $auxlinkReviewFormPopup = $linkReviewFormPopup;
            $class = "iframe fancy_window_review";
			if ($user){
				$linkReviewFormPopup = sess_validateSessionItens($item_type, "rate", false, $linkReviewFormPopup, htmlspecialchars($aux["id"]));
                if ($auxlinkReviewFormPopup != $linkReviewFormPopup){
                    $class = "fancy_window_login";
                } else {
                    $class = "iframe fancy_window_review";
                }
			}
			
			for ($x=0 ; $x < 5 ;$x++) {
                
                if (LISTING_REVIEW_SUMMARY){
                    if (round($rate_avg) > $x) $rate_stars .= "<a href=\"".($user ? $linkReviewFormPopup : "javascript:void(0);")."\" ".($user ? "class=\"$class star-rating\"" : "class=\"star-rating\"")." ".(!$user ? "style='cursor: default'" : "")."><img src='".DEFAULT_URL."/images/rated.png' alt='Star On' /></a>";
                    else $rate_stars .= "<a href=\"".($user ? $linkReviewFormPopup : "javascript:void(0);")."\" ".($user ? "class=\"$class star-rating\"" : "class=\"star-rating\"")." ".(!$user ? "style='cursor: default'" : "")."><img src='".DEFAULT_URL."/images/rate.png' alt='Star Off' /></a>";
                } else {
                    if (round($rate_avg) > $x) $rate_stars .= "<img src='".DEFAULT_URL."/images/rated.png' alt='Star On' />";
                    else $rate_stars .= "<img src='".DEFAULT_URL."/images/rate.png' alt='Star Off' />";
                }
			}

			if ($_SESSION["ITEM_ACTION"] == "rate" && $_SESSION["ITEM_TYPE"] && (is_numeric($_SESSION["ITEM_ID"]) && $_SESSION["ITEM_ID"] == htmlspecialchars($aux["id"])) && sess_isAccountLogged()) {
				?>
                <a href="<?=$linkReviewFormPopup?>" id="login_window" class="iframe fancy_window_review" style="display:none"></a>
                <script type="text/javascript">
                    $("a.fancy_window_review").fancybox({
                        'overlayShow'     : true,
                        'overlayOpacity'  : 0.75,
                        'width'           : <?=FANCYBOX_REVIEW_WIDTH?>,
                        'height'          : <?=FANCYBOX_REVIEW_HEIGHT?>,
                        'autoDimensions'  : false
                    });
                    
                    jQuery(document).ready(function() {
                        $("#login_window").trigger('click');
                    });
                </script>

				<?
				unset($_SESSION["ITEM_ACTION"], $_SESSION["ITEM_TYPE"], $_SESSION["ITEM_ID"]);
			}
			
			unset($aux_item_review);

			if ($user) {
				
				$aux_item_review .= "<div class=\"rate-stars\">";

				$review_str = $review_amount == 1 ? system_showText(LANG_REVIEWCOUNT) : system_showText(LANG_REVIEWCOUNT_PLURAL);

				if (mysql_num_rows($r) > 0) {

					$reviewsLink = $item_default_url."/".ALIAS_REVIEW_URL_DIVISOR."/".htmlspecialchars($aux["friendly_url"]);
					
					/** Logged User > (2 Reviews) */
					$aux_item_review .= $rate_stars."<a href='".$reviewsLink."' style='cursor:".($preview?'default':'pointer')."'>(<number>".$review_amount. "</number>)</a>";
					
					/** Logged User > See Commments */
					//$aux_item_review .= "<a href='".$reviewsLink."' style='cursor:".($preview?'default':'pointer')."'>".system_showText(LANG_REVIEWSEECOMMENTS)."</a>";

				} else {
					
					/** Logged User > (0 Comments ) */
					//$aux_item_review .= $rate_stars."<span>(".$review_amount." " . $review_str . ")</span>";
					$aux_item_review .= $rate_stars."<span>(<number>".$review_amount."</number>)</span>";
				}
				
				$aux_item_review .= "</div>";

			} else {
				$aux_item_review .= "<div class=\"rate-stars\">";
				$plural = $review_amount == 1 ? false : true;
				if ($review_amount > 0) {
					$aux_item_review .= $rate_stars."<a href='javascript:void(0);' style='cursor: default'>(<number>" . $review_amount. "</number>)</a>";
				} else {
					$aux_item_review .= $rate_stars."<span>(<number>".$review_amount."</number>)</span>";
				}
				
				$aux_item_review .= "</div>";

			}

            /*if (!LISTING_REVIEW_SUMMARY){
                if ($user) {

                    if (!$bt_rate_off){

                        $aux_item_review .= "<p>";

                        if ($review_amount > 0) {
                           
                            $aux_item_review .= "<a href=\"".$linkReviewFormPopup."\" class=\"$class rate-it\">" . system_showText(LANG_REVIEWRATEIT) . "</a>";
                        } else {
                           
                            $aux_item_review .= "<a href=\"".$linkReviewFormPopup."\" class=\"$class\">" . system_showText(LANG_REVIEWBETHEFIRST) . "</a>";
                        }
                        $aux_item_review .= "</p>";
                    }

                } else {

                    if (!$bt_rate_off){
                        $aux_item_review .= "<p>";
                        if ($review_amount > 0) {
                            $aux_item_review .= "<a href='javascript:void(0);'  style='cursor: default'>" . system_showText(LANG_REVIEWRATEIT) . "</a>";
                        } else {
                            $aux_item_review .= "<a href='javascript:void(0);' style='cursor: default'>" . system_showText(LANG_REVIEWBETHEFIRST) . "</a>";
                        }
                        $aux_item_review .= "</p>";
                    }
                }
            }*/

			if(string_strlen($aux_item_review) > 0){
				$item_review .= "<div class=\"rate\">".$aux_item_review."</div>";
			}
		}
	}

	###################################################################
	###################################################################
	###################################################################

	unset($aux);
?>
